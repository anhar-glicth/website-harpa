const db = require('../config/db');

/**
 * 1. Mengambil semua data kampanye (Public)
 */
const getAllCampaigns = async (req, res, next) => {
    try {
        const [rows] = await db.query('SELECT * FROM campaigns WHERE status = "approved"');
        res.json(rows);
    } catch (err) { 
        next(err); 
    }
};

/**
 * 1.5 Mengambil kampanye milik user tertentu (Staff)
 */
const getMyCampaigns = async (req, res, next) => {
    try {
        const user_id = req.user.id;
        const [rows] = await db.query('SELECT * FROM campaigns WHERE user_id = ?', [user_id]);
        res.json(rows);
    } catch (err) { 
        next(err); 
    }
};

/**
 * 2. Mengambil satu kampanye berdasarkan ID
 * Fungsi ini WAJIB ada karena dipanggil di routes/api.js baris 28
 */
const getCampaignById = async (req, res, next) => {
    try {
        const { id } = req.params;
        const [rows] = await db.query('SELECT * FROM campaigns WHERE id = ?', [id]);
        
        if (rows.length === 0) {
            return res.status(404).json({ 
                status: 'error',
                message: 'Kampanye tidak ditemukan' 
            });
        }
        
        res.json(rows[0]);
    } catch (err) { 
        next(err); 
    }
};

/**
 * 3. Mengambil statistik ringkasan (Total Donasi, Kampanye Sukses, Kategori)
 */
const getStats = async (req, res, next) => {
    try {
        // Menghitung total donasi dari semua kampanye
        const [[{ total_donation }]] = await db.query('SELECT SUM(current_amount) as total_donation FROM campaigns');
        
        // Menghitung kampanye yang sudah mencapai target
        const [[{ total_success }]] = await db.query('SELECT COUNT(*) as total_success FROM campaigns WHERE current_amount >= target_amount');
        
        // Menghitung jumlah kategori unik
        const [[{ total_reach }]] = await db.query('SELECT COUNT(DISTINCT category) as total_reach FROM campaigns');

        res.json({
            totalDonation: total_donation || 0,
            campaignsSuccess: total_success || 0,
            reach: total_reach || 0
        });
    } catch (err) { 
        next(err); 
    }
};

/**
 * 4. Membuat kampanye baru
 */
const createCampaign = async (req, res, next) => {
    try {
        const { title, description, target_amount, start_date, end_date, category, foto, bank_name, account_number, account_holder } = req.body;
        const user_id = req.user.id; // Diambil dari middleware auth

        const sql = `INSERT INTO campaigns 
                     (title, description, target_amount, current_amount, start_date, end_date, category, foto, bank_name, account_number, account_holder, user_id, status) 
                     VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')`;
        
        const [result] = await db.query(sql, [
            title, description, target_amount, 
            start_date || new Date(), end_date || null, 
            category || 'Bantuan', foto || null, 
            bank_name || null, account_number || null, account_holder || null, 
            user_id
        ]);
        
        res.status(201).json({ 
            status: 'success',
            message: 'Kampanye berhasil dibuat', 
            id: result.insertId 
        });
    } catch (err) { 
        next(err); 
    }
};

/**
 * 5. Memperbarui data kampanye
 */
const updateCampaign = async (req, res, next) => {
    try {
        const { id } = req.params;
        const { title, description, target_amount, end_date, category, foto, bank_name, account_number, account_holder } = req.body;
        
        await db.query(
            `UPDATE campaigns SET 
                title = ?, description = ?, target_amount = ?, 
                end_date = ?, category = ?, foto = ?, 
                bank_name = ?, account_number = ?, account_holder = ? 
             WHERE id = ?`, 
            [title, description, target_amount, end_date, category, foto, bank_name, account_number, account_holder, id]
        );
        
        res.json({ 
            status: 'success',
            message: 'Kampanye berhasil diperbarui' 
        });
    } catch (err) { 
        next(err); 
    }
};

/**
 * 6. Menghapus kampanye
 */
const deleteCampaign = async (req, res, next) => {
    try {
        const { id } = req.params;
        await db.query('DELETE FROM campaigns WHERE id = ?', [id]);
        
        res.json({ 
            status: 'success',
            message: 'Kampanye berhasil dihapus' 
        });
    } catch (err) { 
        next(err); 
    }
};

// EKSPOR SEMUA FUNGSI
module.exports = { 
    getAllCampaigns, 
    getMyCampaigns,
    getCampaignById, 
    getStats, 
    createCampaign, 
    updateCampaign, 
    deleteCampaign 
};