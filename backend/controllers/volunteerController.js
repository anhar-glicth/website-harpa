const db = require('../config/db');

const registerVolunteer = async (req, res) => {
    const { name, email, phone, address, skills, reason } = req.body;
    try {
        await db.query(
            'INSERT INTO volunteers (name, email, phone, address, skills, reason) VALUES (?, ?, ?, ?, ?, ?)',
            [name, email, phone, address, skills, reason]
        );
        res.status(201).json({ message: 'Pendaftaran relawan berhasil dikirim! Kami akan menghubungi Anda segera.' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal mengirim pendaftaran relawan.' });
    }
};

const getVolunteers = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM volunteers ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil data relawan.' });
    }
};

module.exports = {
    registerVolunteer,
    getVolunteers
};
