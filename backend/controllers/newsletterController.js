const db = require('../config/db');

exports.subscribe = async (req, res) => {
    const { email } = req.body;
    try {
        await db.query(
            'INSERT INTO newsletters (email) VALUES (?) ON DUPLICATE KEY UPDATE status = "active"',
            [email]
        );
        res.status(201).json({ message: 'Terima kasih telah berlangganan warta kami!' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal memproses langganan.' });
    }
};

exports.getSubscribers = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM newsletters ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil data pelanggan.' });
    }
};
