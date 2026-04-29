const db = require('../config/db');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const sendEmail = require('../utils/email');
const crypto = require('crypto');

/**
 * REGISTRASI PUBLIK (Hanya untuk Donatur)
 * Role dipaksa 'user' di level query untuk keamanan
 */
const register = async (req, res) => {
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
const login = async (req, res) => {
    const { email, password } = req.body;
    try {
        if (!email || !password) {
            return res.status(400).json({ message: 'Email dan password wajib diisi' });
        }

        const cleanEmail = email.trim();

        // Cari user berdasarkan email
        const [users] = await db.query(
            'SELECT * FROM users WHERE email = ?', 
            [cleanEmail]
        );

        if (users.length === 0) {
            return res.status(401).json({ message: 'Identitas atau password salah' });
        }
        
        const user = users[0];
        const isMatch = await bcrypt.compare(password, user.password);

        if (!isMatch) {
            return res.status(401).json({ message: 'Identitas atau password salah' });
        }
        
        const token = jwt.sign(
            { id: user.id, role: user.role, name: user.name },
            process.env.JWT_SECRET,
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
        console.error('Login Error Full:', error);
        res.status(500).json({ message: 'Terjadi kesalahan pada server' });
    }
};

/**
 * FORGOT PASSWORD
 */
const forgotPassword = async (req, res) => {
    const { email } = req.body;
    try {
        if (!email) {
            return res.status(400).json({ message: 'Email wajib diisi' });
        }

        const [users] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
        if (users.length === 0) {
            return res.status(404).json({ message: 'User dengan email tersebut tidak ditemukan' });
        }

        const user = users[0];
        // Generate 6-digit token
        const token = Math.floor(100000 + Math.random() * 900000).toString();
        const expires = new Date(Date.now() + 10 * 60 * 1000); // 10 menit

        await db.query(
            'UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?',
            [token, expires, user.id]
        );

        const message = `Token reset password Anda adalah: ${token}. Berlaku selama 10 menit.`;
        const html = `
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
                <h2 style="color: #4CAF50; text-align: center;">Reset Password</h2>
                <p>Halo <strong>${user.name}</strong>,</p>
                <p>Anda menerima email ini karena kami menerima permintaan untuk mereset password akun Anda.</p>
                <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
                    <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #333;">${token}</span>
                </div>
                <p>Token ini berlaku selama <strong>10 menit</strong>.</p>
                <p>Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>
                <hr style="border: 0; border-top: 1px solid #eee;" />
                <p style="font-size: 12px; color: #888;">Ini adalah email otomatis, mohon tidak membalas.</p>
            </div>
        `;

        await sendEmail({
            email: user.email,
            subject: 'Token Reset Password - Humanity Donation',
            message,
            html
        });

        res.json({ message: 'Token reset password telah dikirim ke email Anda' });
    } catch (error) {
        console.error('Forgot Password Error:', error);
        res.status(500).json({ message: 'Gagal mengirim email reset password' });
    }
};

/**
 * RESET PASSWORD
 */
const resetPassword = async (req, res) => {
    const { email, token, newPassword } = req.body;
    try {
        if (!email || !token || !newPassword) {
            return res.status(400).json({ message: 'Semua data wajib diisi' });
        }

        const [users] = await db.query(
            'SELECT * FROM users WHERE email = ? AND reset_token = ? AND reset_token_expires > NOW()',
            [email, token]
        );

        if (users.length === 0) {
            return res.status(400).json({ message: 'Token tidak valid atau sudah kedaluwarsa' });
        }

        const user = users[0];
        const hashedPassword = await bcrypt.hash(newPassword, 10);

        await db.query(
            'UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?',
            [hashedPassword, user.id]
        );

        res.json({ message: 'Password berhasil diperbarui. Silakan login kembali.' });
    } catch (error) {
        console.error('Reset Password Error:', error);
        res.status(500).json({ message: 'Gagal mereset password' });
    }
};

module.exports = {
    register,
    login,
    forgotPassword,
    resetPassword
};