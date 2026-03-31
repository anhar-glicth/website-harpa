const db = require('../config/db');

exports.createZakat = async (req, res) => {
    const { zakat_type, amount, payment_method } = req.body;
    const userId = req.user.id;

    if (!zakat_type || !amount) {
        return res.status(400).json({ message: 'Jenis zakat dan jumlah harus diisi' });
    }

    try {
        await db.query(
            'INSERT INTO zakats (user_id, zakat_type, amount, payment_method) VALUES (?, ?, ?, ?)',
            [userId, zakat_type, amount, payment_method || 'Transfer Bank']
        );
        res.status(201).json({ message: 'Pembayaran zakat berhasil diajukan, menunggu verifikasi admin.' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getUserZakats = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM zakats WHERE user_id = ? ORDER BY created_at DESC', [req.user.id]);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getPendingZakats = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT z.*, u.name as user_name 
            FROM zakats z 
            JOIN users u ON z.user_id = u.id 
            WHERE z.payment_status = 'Pending' 
            ORDER BY z.created_at DESC
        `);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.verifyZakat = async (req, res) => {
    const { id } = req.params;
    const { status, admin_note } = req.body;

    try {
        await db.query('UPDATE zakats SET payment_status = ?, admin_note = ? WHERE id = ?', [status, admin_note, id]);
        res.json({ message: `Status zakat telah diperbarui menjadi ${status}` });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
