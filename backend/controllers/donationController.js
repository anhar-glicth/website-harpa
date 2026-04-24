const db = require('../config/db');

/**
 * Mencatat donasi baru
 * Public: Bisa diakses siapa saja
 */
const createDonation = async (req, res, next) => {
    const { campaign_id, amount, donor_name, message, proof_image } = req.body;
    const user_id = req.user ? req.user.id : null;

    if (!amount || amount <= 0) {
        return res.status(400).json({ message: 'Nominal donasi tidak valid' });
    }

    try {
        // 1. Catat transaksi donasi sebagai PENDING
        const sql = `INSERT INTO donations (campaign_id, donor_name, amount, message, donation_date, payment_status, user_id, proof_image) 
                     VALUES (?, ?, ?, ?, NOW(), 'Pending', ?, ?)`;
        
        const [result] = await db.query(sql, [campaign_id, donor_name, amount, message, user_id, proof_image || null]);

        res.status(201).json({ 
            status: 'success',
            message: 'Donasi dikirim. Menunggu verifikasi admin.',
            id: result.insertId
        });
    } catch (error) {
        next(error);
    }
};

/**
 * Mengambil riwayat donasi milik user yang login
 */
const getMyDonations = async (req, res, next) => {
    try {
        const user_id = req.user.id;
        const query = `
            SELECT 
                d.*, 
                c.title AS campaign_title,
                c.foto as foto
            FROM donations d
            JOIN campaigns c ON d.campaign_id = c.id
            WHERE d.user_id = ?
            ORDER BY d.donation_date DESC
        `;
        const [rows] = await db.query(query, [user_id]);
        res.json(rows);
    } catch (error) {
        next(error);
    }
};

const getDonationById = async (req, res, next) => {
    try {
        const { id } = req.params;
        const query = `
            SELECT d.*, c.title as campaign_title 
            FROM donations d
            JOIN campaigns c ON d.campaign_id = c.id
            WHERE d.id = ?
        `;
        const [rows] = await db.query(query, [id]);
        if (rows.length === 0) return res.status(404).json({ message: 'Donasi tidak ditemukan' });
        
        // Cek kepemilikan (Opsional: Jika bukan admin, pastikan milik user ybs)
        if (req.user.role !== 'admin' && rows[0].user_id !== req.user.id) {
            return res.status(403).json({ message: 'Akses ditolak' });
        }

        res.json(rows[0]);
    } catch (error) {
        next(error);
    }
};

module.exports = {
    createDonation,
    getMyDonations,
    getAllDonations,
    getDonationById
};