const db = require('../config/db');
const bcrypt = require('bcryptjs');

/**
 * Mengambil Statistik Dashboard (Total Donasi, Donatur, dan Kampanye)
 * Digunakan untuk Admin Dashboard
 */
exports.getStats = async (req, res) => {
    try {
        const [[donations]] = await db.query('SELECT SUM(amount) as total FROM donations WHERE payment_status = "Success"');
        const [[donors]] = await db.query('SELECT COUNT(DISTINCT user_id) as total FROM donations');
        const [[campaigns]] = await db.query('SELECT COUNT(*) as total FROM campaigns');
        const [[successCampaigns]] = await db.query('SELECT COUNT(*) as total FROM campaigns WHERE current_amount >= target_amount');

        res.json({
            totalDonations: donations.total || 0,
            totalDonors: donors.total || 0,
            activeCampaigns: campaigns.total || 0,
            successCampaigns: successCampaigns.total || 0
        });
    } catch (error) {
        console.error('Error getStats:', error.message);
        res.status(500).json({ message: 'Gagal memuat statistik platform' });
    }
};

/**
 * Manajemen Tim: List Admin dan Petugas Lapangan
 */
exports.getTeamList = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT id, name, email, role, created_at FROM users WHERE role IN ("admin", "field_worker") ORDER BY role ASC, created_at DESC'
        );
        res.json(rows);
    } catch (error) {
        console.error('Error getTeamList:', error.message);
        res.status(500).json({ message: 'Gagal memuat daftar tim operasional' });
    }
};

/**
 * Daftar Donatur: List user dengan role 'user'
 */
exports.getDonorList = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT id, name, email, role, created_at FROM users WHERE role = "user" ORDER BY created_at DESC'
        );
        res.json(rows);
    } catch (error) {
        console.error('Error getDonorList:', error.message);
        res.status(500).json({ message: 'Gagal memuat database donatur' });
    }
};

/**
 * Menambah Anggota Tim Baru (Admin atau Petugas Lapangan)
 */
exports.addAccount = async (req, res) => {
    const { name, email, password, role } = req.body;

    if (!name || !email || !password || !role) {
        return res.status(400).json({ message: 'Semua kolom wajib diisi' });
    }

    try {
        const [existing] = await db.query('SELECT id FROM users WHERE email = ?', [email]);
        if (existing.length > 0) {
            return res.status(400).json({ message: 'Alamat email sudah terdaftar' });
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        await db.query(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            [name, email, hashedPassword, role]
        );

        res.status(201).json({ message: `Akun ${role} berhasil dibuat` });
    } catch (error) {
        console.error('Error addAccount:', error);
        res.status(500).json({ message: 'Terjadi kesalahan sistem saat membuat akun' });
    }
};

/**
 * Hapus Akun secara permanen
 */
exports.deleteAccount = async (req, res) => {
    const { id } = req.params;
    const currentUserId = req.user.id;

    try {
        if (parseInt(id) === currentUserId) {
            return res.status(400).json({ message: 'Anda tidak bisa menghapus akun Anda sendiri' });
        }

        const [result] = await db.query('DELETE FROM users WHERE id = ?', [id]);
        
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Akun tidak ditemukan' });
        }

        res.json({ message: 'Akun telah dihapus secara permanen' });
    } catch (error) {
        console.error('Error deleteAccount:', error.message);
        res.status(500).json({ message: 'Gagal menghapus akun' });
    }
};

/**
 * List Kampanye yang Menunggu Persetujuan
 */
exports.getPendingCampaigns = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM campaigns WHERE status = "pending" ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal memuat kampanye pending' });
    }
};

/**
 * Menyetujui Kampanye agar Muncul di Public
 */
exports.approveCampaign = async (req, res) => {
    const { id } = req.params;
    try {
        await db.query('UPDATE campaigns SET status = "approved" WHERE id = ?', [id]);
        res.json({ message: 'Kampanye telah disetujui dan kini aktif!' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menyetujui kampanye' });
    }
};

/**
 * List Donasi yang Menunggu Verifikasi Bukti Transfer
 */
exports.getPendingDonations = async (req, res) => {
    try {
        const query = `
            SELECT d.*, c.title as campaign_title 
            FROM donations d 
            JOIN campaigns c ON d.campaign_id = c.id 
            WHERE d.payment_status = "Pending" 
            ORDER BY d.donation_date DESC
        `;
        const [rows] = await db.query(query);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal memuat donasi pending' });
    }
};

/**
 * Verifikasi Donasi (Mengubah status menjadi Success dan update Saldo Kampanye)
 */
exports.verifyDonation = async (req, res) => {
    const { id } = req.params;
    const { status } = req.body; // 'Success' atau 'Failed'

    try {
        // 1. Ambil data donasi
        const [[donation]] = await db.query('SELECT * FROM donations WHERE id = ?', [id]);
        if (!donation) return res.status(404).json({ message: 'Donasi tidak ditemukan' });

        if (donation.payment_status !== 'Pending') {
            return res.status(400).json({ message: 'Donasi ini sudah diproses sebelumnya' });
        }

        if (status === 'Success') {
            // 2. Update status donasi
            await db.query('UPDATE donations SET payment_status = "Success" WHERE id = ?', [id]);
            
            // 3. Update saldo real-time di tabel kampanye
            await db.query(
                'UPDATE campaigns SET current_amount = current_amount + ? WHERE id = ?',
                [donation.amount, donation.campaign_id]
            );
            
            res.json({ message: 'Donasi berhasil diverifikasi dan saldo kampanye diperbarui' });
        } else {
            await db.query('UPDATE donations SET payment_status = "Failed" WHERE id = ?', [id]);
            res.json({ message: 'Donasi ditolak' });
        }
    } catch (error) {
        console.error('Verify Error:', error);
        res.status(500).json({ message: 'Gagal memproses verifikasi' });
    }
};