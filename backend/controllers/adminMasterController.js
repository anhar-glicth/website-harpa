const db = require('../config/db');

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
        filter: 'users.role = "user"',
        select: `users.*, 
                 offices.name as office_name, 
                 pic.name as pic_name, 
                 media.name as last_media_name,
                 (SELECT COUNT(*) FROM donations WHERE donations.user_id = users.id AND payment_status = 'Success') as total_transactions,
                 (SELECT COALESCE(SUM(amount), 0) FROM donations WHERE donations.user_id = users.id AND payment_status = 'Success') as total_nominal,
                 (SELECT donation_date FROM donations WHERE donations.user_id = users.id AND payment_status = 'Success' ORDER BY donation_date DESC LIMIT 1) as last_transaction_date,
                 (SELECT campaigns.title FROM donations JOIN campaigns ON donations.campaign_id = campaigns.id WHERE donations.user_id = users.id AND donations.payment_status = 'Success' ORDER BY donations.donation_date DESC LIMIT 1) as last_program_title,
                 (SELECT amount FROM donations WHERE donations.user_id = users.id AND payment_status = 'Success' ORDER BY donation_date DESC LIMIT 1) as last_amount,
                 (SELECT payment_via.name FROM donations JOIN payment_via ON donations.payment_via_id = payment_via.id WHERE donations.user_id = users.id AND donations.payment_status = 'Success' ORDER BY donations.donation_date DESC LIMIT 1) as last_payment_via_name,
                 (SELECT proof_image FROM donations WHERE donations.user_id = users.id AND payment_status = 'Success' ORDER BY donation_date DESC LIMIT 1) as last_donation_proof`,
        join: 'LEFT JOIN offices ON users.office_id = offices.id LEFT JOIN users pic ON users.pic_prospect_id = pic.id LEFT JOIN prospect_media media ON users.last_prospect_media_id = media.id',
        filter: 'users.role = "user" AND users.id IN (SELECT user_id FROM donations WHERE payment_status = "Success")',
        orderBy: 'users.created_at DESC' 
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
        select: 'distributions.*, campaigns.title as campaign_title',
        join: 'LEFT JOIN campaigns ON distributions.program_id = campaigns.id',
        orderBy: 'distributions.id DESC' 
    },
    'transactions': { 
        table: 'donations', 
        select: 'donations.*, users.name as donor_name, users.phone as donor_phone, campaigns.title as campaign_title, payment_via.name as payment_via_name',
        join: 'LEFT JOIN users ON donations.user_id = users.id LEFT JOIN campaigns ON donations.campaign_id = campaigns.id LEFT JOIN payment_via ON donations.payment_via_id = payment_via.id',
        orderBy: 'donations.donation_date DESC' 
    },
    'programs': { table: 'campaigns', orderBy: 'created_at DESC' },
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
        
        const query = `SELECT ${selectFields} FROM ${config.table} ${joinClause} ${whereClause} ORDER BY ${orderBy}`;
        
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
        } else {
            stats = { kpis: [{ label: 'Data', value: '0', percentage: 0 }] };
        }
        
        res.json(stats);
    } catch (error) {
        console.error(`Error fetching stats for ${module}:`, error);
        res.status(500).json({ message: 'Gagal memuat statistik' });
    }
};

module.exports = { getData, createData, updateData, deleteData, getStats };
