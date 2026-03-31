const db = require('../config/db');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

/**
 * REGISTRASI PUBLIK (Hanya untuk Donatur)
 * Role dipaksa 'user' di level query untuk keamanan
 */
exports.register = async (req, res) => {
    const { name, email, password } = req.body;
    try {
        if (!name || !email || !password) {
            return res.status(400).json({ message: 'Semua data wajib diisi' });
        }
        
        const [existingUser] = await db.query('SELECT id FROM users WHERE email = ?', [email]);
        if (existingUser.length > 0) {
            return res.status(400).json({ message: 'Email sudah terdaftar' });
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        // "user" ditulis langsung di query untuk memastikan pendaftaran publik tidak bisa jadi admin
        const query = 'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, "user")';
        await db.query(query, [name, email, hashedPassword]);

        res.status(201).json({ message: 'Registrasi Donatur berhasil! Silakan login.' });
    } catch (error) {
        console.error('Register Error:', error.message);
        res.status(500).json({ message: 'Terjadi kesalahan server saat pendaftaran' });
    }
};

/**
 * LOGIN TUNGGAL
 */
exports.login = async (req, res) => {
    const { email, password } = req.body;
    try {
        const [users] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
        if (users.length === 0) {
            return res.status(401).json({ message: 'Email atau password salah' });
        }
        
        const user = users[0];
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ message: 'Email atau password salah' });
        }
        
        const token = jwt.sign(
            { id: user.id, role: user.role, name: user.name },
            process.env.JWT_SECRET || 'supersecret_key_123',
            { expiresIn: '1d' }
        );

        res.json({
            token,
            role: user.role,
            name: user.name,
            photo: user.profile_photo,
            message: `Selamat datang kembali, ${user.name}!`
        });
    } catch (error) {
        console.error('Login Error:', error.message);
        res.status(500).json({ message: 'Terjadi kesalahan pada server' });
    }
};