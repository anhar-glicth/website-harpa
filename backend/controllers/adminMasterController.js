const db = require('../config/db');

/**
 * Generic Controller for Administrative Master Data
 * Handles CRUD for various tables in a modular way
 */

// Mapping of module names to table names and special configurations
const moduleConfigs = {
    'profile': { table: 'settings', pk: 'id', orderBy: 'id DESC' },
    'users_mgt': { table: 'users', orderBy: 'role ASC, created_at DESC' },
    'donors': { table: 'users', filter: 'role = "user"', orderBy: 'created_at DESC' },
    'distribution_list': { 
        table: 'distributions', 
        select: 'distributions.*, campaigns.title as campaign_title',
        join: 'LEFT JOIN campaigns ON distributions.campaign_id = campaigns.id',
        orderBy: 'distributions.id DESC' 
    },
    'transactions': { 
        table: 'donations', 
        select: 'donations.*, users.name as donor_name, campaigns.title as campaign_title',
        join: 'LEFT JOIN users ON donations.user_id = users.id LEFT JOIN campaigns ON donations.campaign_id = campaigns.id',
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
        
        const [result] = await db.query(`INSERT INTO ${config.table} SET ?`, [data]);
        await logAudit(req.user.id, 'CREATE', module, { id: result.insertId, ...data });

        res.status(201).json({ message: 'Data berhasil ditambahkan', id: result.insertId });
    } catch (error) {
        console.error(`Error creating ${module}:`, error);
        res.status(500).json({ message: `Gagal menambah data ${module}` });
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
