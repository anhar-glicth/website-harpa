const db = require('../config/db');
const bcrypt = require('bcryptjs');

exports.getProfile = async (req, res) => {
    try {
        const userId = req.user.id;
        const [rows] = await db.query(
            'SELECT id, name, email, role, profile_photo, created_at FROM users WHERE id = ?',
            [userId]
        );
        if (rows.length === 0) return res.status(404).json({ message: 'User tidak ditemukan' });
        res.json(rows[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.updateProfile = async (req, res) => {
    const userId = req.user.id;
    const { name, email, password, profile_photo } = req.body;

    try {
        // 1. Get current data
        const [[user]] = await db.query('SELECT * FROM users WHERE id = ?', [userId]);

        // 2. Prepare values
        let newName = name || user.name;
        let newEmail = email || user.email;
        let newPhoto = profile_photo || user.profile_photo;
        let newPassword = user.password;

        if (password) {
            newPassword = await bcrypt.hash(password, 10);
        }

        // 3. Update query
        await db.query(
            'UPDATE users SET name = ?, email = ?, password = ?, profile_photo = ? WHERE id = ?',
            [newName, newEmail, newPassword, newPhoto, userId]
        );

        res.json({ message: 'Profil Anda telah berhasil diperbarui!' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
