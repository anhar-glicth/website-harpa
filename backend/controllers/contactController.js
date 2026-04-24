const db = require('../config/db');

exports.sendMessage = async (req, res) => {
    const { name, email, message } = req.body;
    try {
        await db.query(
            'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)',
            [name, email, message]
        );
        res.status(201).json({ message: 'Pesan Anda telah terkirim! Tim kami akan segera merespons.' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal mengirim pesan.' });
    }
};

exports.getMessages = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM contact_messages ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil pesan.' });
    }
};
