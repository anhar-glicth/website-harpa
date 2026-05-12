const db = require('../config/db');
const xlsx = require('xlsx');

/**
 * Generic Controller for Administrative Master Data
 * Handles CRUD for various tables in a modular way
 */

// Mapping of module names to table names and special configurations
const moduleConfigs = {
    'profile': { table: 'site_settings', pk: 'setting_key', orderBy: 'setting_key ASC' },
    'users_mgt': { table: 'users', orderBy: 'role ASC, created_at DESC' },
    'donors': { 
        table: 'users', 
        // OPTIMASI: Gunakan JOIN agregat tunggal daripada 5 subquery per baris
        select: `users.id, users.name, users.email, users.phone, users.gender, users.profile_type,
                 users.category, users.badge, users.segment, users.source_info, users.created_at,
                 users.office_id, users.pic_prospect_id,
                 offices.name as office_name, 
                 pic.name as pic_name,
                 COALESCE(don_agg.total_transactions, 0) as total_transactions,
                 COALESCE(don_agg.total_nominal, 0) as total_nominal,
                 don_agg.last_transaction_date,
                 don_agg.last_amount`,
        join: `LEFT JOIN offices ON users.office_id = offices.id 
               LEFT JOIN users pic ON users.pic_prospect_id = pic.id
               LEFT JOIN (
                   SELECT user_id,
                          COUNT(*) as total_transactions,
                          SUM(amount) as total_nominal,
                          MAX(donation_date) as last_transaction_date,
                          (SELECT amount FROM donations d2 WHERE d2.user_id = donations.user_id AND d2.payment_status = 'Success' ORDER BY d2.donation_date DESC LIMIT 1) as last_amount
                   FROM donations
                   WHERE payment_status = 'Success'
                   GROUP BY user_id
               ) don_agg ON users.id = don_agg.user_id`,
        filter: 'users.role = "user" AND don_agg.user_id IS NOT NULL',
        orderBy: 'don_agg.total_nominal DESC' 
    },
    'office': { table: 'offices', orderBy: 'name ASC' },
    'employees': { 
        table: 'employees', 
        select: 'employees.*, offices.name as office_name',
        join: 'LEFT JOIN offices ON employees.office_id = offices.id',
        orderBy: 'employees.name ASC' 
    },
    'donor_channel': { table: 'donor_channels', orderBy: 'name ASC' },
    'program_group': { table: 'program_groups', orderBy: 'name ASC' },
    'programs': { 
        table: 'campaigns', 
        select: 'campaigns.*, program_groups.name as group_name',
        join: 'LEFT JOIN program_groups ON campaigns.group_id = program_groups.id',
        orderBy: 'campaigns.created_at DESC' 
    },
    'payment_via': { table: 'payment_via', orderBy: 'name ASC' },
    'prospect_media': { table: 'prospect_media', orderBy: 'name ASC' },
    'prospect_method': { table: 'prospect_methods', orderBy: 'name ASC' },
    'prospect_type': { table: 'prospect_types', orderBy: 'name ASC' },
    'fundraisers': { table: 'fundraisers', orderBy: 'name ASC' },
    'prospect_responses': { 
        table: 'prospect_responses', 
        select: 'prospect_responses.*, prospects.name as prospect_name',
        join: 'LEFT JOIN prospects ON prospect_responses.prospect_id = prospects.id',
        orderBy: 'prospect_responses.created_at DESC' 
    },
    'targets': { table: 'targets', orderBy: 'period DESC' },
    'prospects': { table: 'prospects', orderBy: 'created_at DESC' },
    'potential_donors': { 
        table: 'users', 
        select: 'users.*, pic.name as pic_name',
        join: 'LEFT JOIN users pic ON users.pic_prospect_id = pic.id',
        filter: 'users.role = "user" AND users.id NOT IN (SELECT user_id FROM donations WHERE payment_status = "Success")',
        orderBy: 'users.created_at DESC' 
    },
    'distribution_list': { 
        table: 'distributions', 
        select: 'distributions.id, distributions.worker_id, distributions.program_id, distributions.title, distributions.amount, distributions.recipient_name, distributions.items_distributed, distributions.distribution_date, distributions.status, distributions.created_at, campaigns.title as campaign_title',
        join: 'LEFT JOIN campaigns ON distributions.program_id = campaigns.id',
        orderBy: 'distributions.id DESC' 
    },
    'field_reports': { 
        table: 'distributions', 
        // OPTIMASI: Jangan tarik photo_url di daftar agar tidak lelet (Base64 sangat berat)
        // Pastikan kolom yang ditarik ada di DB dan sesuai dengan UI
        select: 'distributions.id, distributions.title, distributions.worker_id, distributions.program_id, distributions.location_name, distributions.created_at, users.name as worker_name, campaigns.title as campaign_title',
        join: 'LEFT JOIN users ON distributions.worker_id = users.id LEFT JOIN campaigns ON distributions.program_id = campaigns.id',
        orderBy: 'distributions.created_at DESC' 
    },
    'transactions': { 
        table: 'donations', 
        // OPTIMASI: Kecualikan proof_image dari list utama
        select: 'donations.id, donations.user_id, donations.campaign_id, donations.amount, donations.payment_via_id, donations.payment_status, donations.donation_date, donations.created_at, users.name as donor_name, users.phone as donor_phone, campaigns.title as campaign_title, payment_via.name as payment_via_name',
        join: 'LEFT JOIN users ON donations.user_id = users.id LEFT JOIN campaigns ON donations.campaign_id = campaigns.id LEFT JOIN payment_via ON donations.payment_via_id = payment_via.id',
        orderBy: 'donations.donation_date DESC' 
    },
    'news_mgt': { table: 'news', orderBy: 'created_at DESC' },
    'mualaf_mgt': { table: 'muallaf_requests', orderBy: 'created_at DESC' },
    'recruitment': { table: 'volunteers', orderBy: 'created_at DESC' },
    'inbox': { table: 'contact_messages', orderBy: 'created_at DESC' },
    'newsletter_mgt': { table: 'newsletters', orderBy: 'created_at DESC' },
    'audit_logs': { 
        table: 'audit_logs', 
        select: 'audit_logs.*, users.name as user_name',
        join: 'LEFT JOIN users ON audit_logs.user_id = users.id',
        orderBy: 'audit_logs.created_at DESC' 
    }
};

const logAudit = async (userId, action, module, details) => {
    try {
        await db.query('INSERT INTO audit_logs (user_id, action, module, details) VALUES (?, ?, ?, ?)', [userId, action, module, JSON.stringify(details)]);
    } catch (e) {
        console.error('Audit Log Error:', e);
    }
};

const getData = async (req, res) => {
    const { module } = req.params;
    const config = moduleConfigs[module];

    if (!config) {
        return res.status(400).json({ message: 'Modul tidak valid' });
    }

    try {
        const selectFields = config.select || '*';
        const joinClause = config.join || '';
        const whereClause = config.filter ? `WHERE ${config.filter}` : '';
        const orderBy = config.orderBy || 'id DESC';
        
        // LIMIT 500 untuk mencegah overload memori jika data sangat besar
        const query = `SELECT ${selectFields} FROM ${config.table} ${joinClause} ${whereClause} ORDER BY ${orderBy} LIMIT 500`;
        
        const [rows] = await db.query(query);
        res.json(rows);
    } catch (error) {
        console.error(`Error fetching ${module}:`, error);
        res.status(500).json({ message: `Gagal memuat data ${module}` });
    }
};

const createData = async (req, res) => {
    const { module } = req.params;
    const config = moduleConfigs[module];
    const data = req.body;

    if (!config) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        if (module === 'news_mgt') {
            data.author_id = req.user.id;
        }

        if (module === 'potential_donors' || module === 'donors') {
            data.role = 'user';
            // Generate default password
            if (!data.password) {
                const bcrypt = require('bcryptjs');
                data.password = await bcrypt.hash('donatur123', 10);
            }
            // Ensure username exists
            if (!data.username) {
                data.username = data.phone || (data.email ? data.email.split('@')[0] : 'calon_' + Math.random().toString(36).substring(7));
            }
            // Ensure email exists (Database might require it)
            if (!data.email) {
                data.email = data.username + '@internal.com';
            }
        }
        
        const [result] = await db.query(`INSERT INTO ${config.table} SET ?`, [data]);
        await logAudit(req.user.id, 'CREATE', module, { id: result.insertId, ...data });

        res.status(201).json({ message: 'Data berhasil ditambahkan', id: result.insertId });
    } catch (error) {
        console.error(`Error creating ${module}:`, error);
        res.status(500).json({ message: `Gagal menambah data ${module}: ${error.message}` });
    }
};

const updateData = async (req, res) => {
    const { module, id } = req.params;
    const config = moduleConfigs[module];
    const data = req.body;
    const pk = config?.pk || 'id';

    if (!config) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        await db.query(`UPDATE ${config.table} SET ? WHERE ${pk} = ?`, [data, id]);
        await logAudit(req.user.id, 'UPDATE', module, { id, ...data });

        res.json({ message: 'Data berhasil diperbarui' });
    } catch (error) {
        console.error(`Error updating ${module}:`, error);
        res.status(500).json({ message: `Gagal memperbarui data ${module}` });
    }
};

const deleteData = async (req, res) => {
    const { module, id } = req.params;
    const config = moduleConfigs[module];
    const pk = config?.pk || 'id';

    if (!config) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        await db.query(`DELETE FROM ${config.table} WHERE ${pk} = ?`, [id]);
        await logAudit(req.user.id, 'DELETE', module, { id });

        res.json({ message: 'Data berhasil dihapus' });
    } catch (error) {
        console.error(`Error deleting ${module}:`, error);
        res.status(500).json({ message: `Gagal menghapus data ${module}` });
    }
};

const getStats = async (req, res) => {
    const { module } = req.params;
    
    try {
        let stats = {};
        
        if (module === 'transaction_dashboard') {
            const [[{ total_amount }]] = await db.query('SELECT SUM(amount) as total_amount FROM donations WHERE payment_status = "Success"');
            const [[{ count }]] = await db.query('SELECT COUNT(*) as count FROM donations');
            const [[{ pending }]] = await db.query('SELECT COUNT(*) as pending FROM donations WHERE payment_status = "Pending"');
            stats = {
                kpis: [
                    { label: 'Total Dana Terkumpul', value: `Rp ${Number(total_amount||0).toLocaleString()}`, percentage: 100 },
                    { label: 'Total Transaksi', value: count, percentage: 85 },
                    { label: 'Transaksi Pending', value: pending, percentage: (pending/count * 100) || 0 }
                ]
            };
        } else if (module === 'distribution_dashboard') {
            const [[{ total_dist }]] = await db.query('SELECT SUM(amount) as total_dist FROM distributions WHERE status = "Tersalur"');
            const [[{ count }]] = await db.query('SELECT COUNT(*) as count FROM distributions');
            stats = {
                kpis: [
                    { label: 'Total Penyaluran', value: `Rp ${Number(total_dist||0).toLocaleString()}`, percentage: 70 },
                    { label: 'Titik Distribusi', value: count, percentage: 40 },
                    { label: 'Efisiensi Dana', value: '92%', percentage: 92 }
                ]
            };
        } else if (module === 'donor_dashboard') {
            const [[{ total_donors }]] = await db.query('SELECT COUNT(*) as total_donors FROM users WHERE role = "user"');
            const [[{ new_donors }]] = await db.query('SELECT COUNT(*) as new_donors FROM users WHERE role = "user" AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)');
            stats = {
                kpis: [
                    { label: 'Total Donatur', value: total_donors, percentage: 100 },
                    { label: 'Donatur Baru (30hr)', value: new_donors, percentage: (new_donors/total_donors * 100) || 0 },
                    { label: 'Retensi Donatur', value: '78%', percentage: 78 }
                ]
            };
        } else if (module === 'muallaf_mgt') {
            const [[{ total }]] = await db.query('SELECT COUNT(*) as total FROM muallaf_requests');
            const [[{ pending }]] = await db.query('SELECT COUNT(*) as pending FROM muallaf_requests WHERE status = "pending"');
            stats = {
                kpis: [
                    { label: 'Total Pengajuan', value: total, percentage: 100 },
                    { label: 'Menunggu Jadwal', value: pending, percentage: (pending/total * 100) || 0 },
                    { label: 'Tingkat Respon', value: '100%', percentage: 100 }
                ]
            };
        } else if (module === 'recruitment') {
            const [[{ total }]] = await db.query('SELECT COUNT(*) as total FROM volunteers');
            const [[{ pending }]] = await db.query('SELECT COUNT(*) as pending FROM volunteers WHERE status = "pending"');
            stats = {
                kpis: [
                    { label: 'Total Pendaftar', value: total, percentage: 100 },
                    { label: 'Belum Direview', value: pending, percentage: (pending/total * 100) || 0 },
                    { label: 'Target Relawan', value: '150', percentage: (total/150 * 100) }
                ]
            };
        } else if (module === 'inbox') {
            const [[{ total }]] = await db.query('SELECT COUNT(*) as total FROM contact_messages');
            const [[{ unread }]] = await db.query('SELECT COUNT(*) as unread FROM contact_messages WHERE status = "unread"');
            stats = {
                kpis: [
                    { label: 'Total Pesan Masuk', value: total, percentage: 100 },
                    { label: 'Pesan Belum Dibaca', value: unread, percentage: (unread/total * 100) || 0 },
                    { label: 'Kepuasan Publik', value: '98%', percentage: 98 }
                ]
            };
        } else {
            const [[{ count }]] = await db.query(`SELECT COUNT(*) as count FROM ${config.table}`);
            stats = { kpis: [{ label: `Total Data ${config.table}`, value: count, percentage: 100 }] };
        }
        
        res.json(stats);
    } catch (error) {
        console.error(`Error fetching stats for ${module}:`, error);
        res.status(500).json({ message: 'Gagal memuat statistik' });
    }
};

// --- BULK IMPORT ---
exports.bulkImport = async (req, res) => {
    const { module } = req.params;
    const config = moduleConfigs[module];
    
    if (!config) {
        return res.status(400).json({ message: 'Modul tidak valid untuk impor' });
    }

    if (!req.file) {
        return res.status(400).json({ message: 'File tidak ditemukan' });
    }

    try {
        const workbook = xlsx.read(req.file.buffer, { type: 'buffer' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const data = xlsx.utils.sheet_to_json(worksheet);

        if (data.length === 0) {
            return res.status(400).json({ message: 'File Excel kosong' });
        }

        const table = config.table;

        let successCount = 0;
        let errors = [];

        for (let i = 0; i < data.length; i++) {
            const row = data[i];
            const insertData = { ...row };
            
            // Remove id field if present (auto-increment)
            delete insertData.id;

            // Special handling for some modules
            if (module === 'transactions' && !insertData.donation_date) {
                insertData.donation_date = new Date();
            }
            if ((module === 'distributions' || module === 'field_reports') && !insertData.worker_id) {
                insertData.worker_id = req.user.id;
            }
            if (module === 'news_mgt') {
                insertData.author_id = req.user.id;
            }

            try {
                await db.query(`INSERT INTO ${table} SET ?`, insertData);
                successCount++;
            } catch (err) {
                errors.push(`Baris ${i + 2}: ${err.message}`);
            }
        }

        res.json({ 
            message: `Impor selesai. ${successCount} berhasil, ${errors.length} gagal.`,
            successCount,
            failureCount: errors.length,
            errors: errors.slice(0, 10) // Limit error list
        });

    } catch (error) {
        console.error('Bulk Import Error:', error);
        res.status(500).json({ message: 'Gagal memproses file Excel' });
    }
};

// --- DISTRIBUTIONS MANAGEMENT ---
exports.getAllDistributions = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT d.*, u.name as worker_name 
            FROM distributions d
            JOIN users u ON d.worker_id = u.id
            ORDER BY d.created_at DESC
        `);
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal mengambil data penyaluran' });
    }
};

exports.deleteDistribution = async (req, res) => {
    try {
        await db.query('DELETE FROM distributions WHERE id = ?', [req.params.id]);
        res.json({ message: 'Laporan penyaluran berhasil dihapus' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal menghapus laporan' });
    }
};

module.exports = { 
    getData, 
    createData, 
    updateData, 
    deleteData, 
    getStats, 
    bulkImport: exports.bulkImport,
    getAllDistributions: exports.getAllDistributions, 
    deleteDistribution: exports.deleteDistribution 
};
