const db = require('../config/db');
const bcrypt = require('bcryptjs');

const getProfile = async (req, res) => {
    try {
        const userId = req.user.id;
        const [rows] = await db.query(
            'SELECT id, name, username, email, role, profile_photo, created_at FROM users WHERE id = ?',
            [userId]
        );
        if (rows.length === 0) return res.status(404).json({ message: 'User tidak ditemukan' });
        res.json(rows[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

const updateProfile = async (req, res) => {
    const userId = req.user.id;
    const { name, username, email, password, profile_photo } = req.body;

    try {
        // 1. Get current data
        const [[user]] = await db.query('SELECT * FROM users WHERE id = ?', [userId]);

        // 2. Prepare values
        let newName = name || user.name;
        let newUsername = username || user.username;
        let newEmail = email || user.email;
        let newPhoto = profile_photo || user.profile_photo;
        let newPassword = user.password;

        // Check if username/email already taken by someone else
        if (username && username !== user.username) {
            const [existing] = await db.query('SELECT id FROM users WHERE username = ? AND id != ?', [username, userId]);
            if (existing.length > 0) return res.status(400).json({ message: 'Username sudah digunakan' });
        }
        if (email && email !== user.email) {
            const [existing] = await db.query('SELECT id FROM users WHERE email = ? AND id != ?', [email, userId]);
            if (existing.length > 0) return res.status(400).json({ message: 'Email sudah digunakan' });
        }

        if (password) {
            newPassword = await bcrypt.hash(password, 10);
        }

        // 3. Update query
        await db.query(
            'UPDATE users SET name = ?, username = ?, email = ?, password = ?, profile_photo = ? WHERE id = ?',
            [newName, newUsername, newEmail, newPassword, newPhoto, userId]
        );

        res.json({ 
            message: 'Profil Anda telah berhasil diperbarui!',
            user: { name: newName, username: newUsername, email: newEmail, photo: newPhoto }
        });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getProfile,
    updateProfile
};
