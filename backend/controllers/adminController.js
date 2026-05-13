const db = require('../config/db');
const bcrypt = require('bcryptjs');

/**
 * Mengambil Statistik Dashboard (Total Donasi, Donatur, dan Kampanye)
 * Digunakan untuk Admin Dashboard
 */
exports.getStats = async (req, res) => {
    try {
        // Run all queries in parallel for maximum speed
        const [
            [[donations]],
            [[donors]],
            [[campaigns]],
            [[successCampaigns]],
            [recentDonations],
            [recentCampaigns],
            [monthlyDonations],
            [categoryDistribution]
        ] = await Promise.all([
            db.query('SELECT SUM(amount) as total FROM donations WHERE payment_status = "Success"'),
            db.query('SELECT COUNT(DISTINCT donor_name) as total FROM donations'),
            db.query('SELECT COUNT(*) as total FROM campaigns'),
            db.query('SELECT COUNT(*) as total FROM campaigns WHERE current_amount >= target_amount'),
            db.query('SELECT donor_name as name, "Donasi" as type, amount as detail, donation_date as date FROM donations ORDER BY donation_date DESC LIMIT 3'),
            db.query('SELECT title as name, "Kampanye" as type, category as detail, created_at as date FROM campaigns ORDER BY created_at DESC LIMIT 2'),
            db.query(`SELECT DATE_FORMAT(donation_date, '%b') as month, SUM(amount) as total FROM donations WHERE payment_status = 'Success' AND donation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(donation_date, '%Y-%m') ORDER BY donation_date ASC`),
            db.query(`SELECT c.category as name, SUM(d.amount) as value FROM donations d JOIN campaigns c ON d.campaign_id = c.id WHERE d.payment_status = 'Success' GROUP BY c.category`)
        ]);

        const recent_activities = [...recentDonations, ...recentCampaigns]
            .sort((a, b) => new Date(b.date) - new Date(a.date))
            .slice(0, 5);

        res.json({
            totalDonations: donations?.total || 0,
            totalDonors: donors?.total || 0,
            activeCampaigns: campaigns?.total || 0,
            successCampaigns: successCampaigns?.total || 0,
            recent_activities,
            monthlyDonations,
            categoryDistribution
        });
    } catch (error) {
        console.error('Error getStats Full:', error);
        res.status(500).json({ message: 'Gagal memuat statistik platform' });
    }
};

/**
 * Consolidated Dashboard Data to reduce request count and prevent 429 errors
 */
exports.getAllDashboardData = async (req, res) => {
    try {
        // Run all queries in parallel for maximum performance
        const [
            [[donations]],
            [[donors]],
            [[campaigns]],
            [[successCampaigns]],
            [recentDonations],
            [recentCampaigns],
            [monthlyDonations],
            [categoryDistribution],
            [users],
            [wp],
            [tasks],
            [att],
            [pc],
            [pd],
            [settings],
            [zakats]
        ] = await Promise.all([
            db.query('SELECT SUM(amount) as total FROM donations WHERE payment_status = "Success"'),
            db.query('SELECT COUNT(DISTINCT donor_name) as total FROM donations'),
            db.query('SELECT COUNT(*) as total FROM campaigns'),
            db.query('SELECT COUNT(*) as total FROM campaigns WHERE current_amount >= target_amount'),
            db.query('SELECT donor_name as name, "Donasi" as type, amount as detail, donation_date as date FROM donations ORDER BY donation_date DESC LIMIT 3'),
            db.query('SELECT title as name, "Kampanye" as type, category as detail, created_at as date FROM campaigns ORDER BY created_at DESC LIMIT 2'),
            db.query(`SELECT DATE_FORMAT(donation_date, '%b') as month, SUM(amount) as total FROM donations WHERE payment_status = 'Success' AND donation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(donation_date, '%Y-%m') ORDER BY donation_date ASC`),
            db.query(`SELECT c.category as name, SUM(d.amount) as value FROM donations d JOIN campaigns c ON d.campaign_id = c.id WHERE d.payment_status = 'Success' GROUP BY c.category`),
            db.query('SELECT id, name, email, role, created_at FROM users ORDER BY role ASC, created_at DESC'),
            db.query(`
                SELECT u.id, u.name, u.role,
                    COALESCE(att_c.attendance_count, 0) as attendance_count,
                    COALESCE(task_c.tasks_completed, 0) as tasks_completed,
                    COALESCE(task_c.total_tasks, 0) as total_tasks
                FROM users u
                LEFT JOIN (
                    SELECT user_id, COUNT(*) as attendance_count
                    FROM attendance WHERE type = 'check_in'
                    GROUP BY user_id
                ) att_c ON att_c.user_id = u.id
                LEFT JOIN (
                    SELECT user_id,
                        COUNT(*) as total_tasks,
                        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as tasks_completed
                    FROM todo_list
                    GROUP BY user_id
                ) task_c ON task_c.user_id = u.id
                WHERE u.role IN ('field_worker', 'admin')
            `),
            db.query(`
                SELECT t.id, t.user_id, t.assigned_by, t.task, t.description, t.deadline,
                       t.status, t.result_note, t.created_at, t.completed_at,
                       u.name as employee_name, a.name as assigner_name
                FROM todo_list t
                LEFT JOIN users u ON t.user_id = u.id
                LEFT JOIN users a ON t.assigned_by = a.id
                ORDER BY t.created_at DESC
                LIMIT 200
            `),
            db.query(`
                SELECT a.id, a.user_id, a.type, a.location, a.latitude, a.longitude, a.created_at,
                    u.name as employee_name, 
                    u.email as employee_email
                FROM attendance a
                JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC LIMIT 100
            `),
            db.query('SELECT * FROM campaigns WHERE status = "pending" ORDER BY created_at DESC'),
            db.query('SELECT d.*, c.title as campaign_title FROM donations d JOIN campaigns c ON d.campaign_id = c.id WHERE d.payment_status = "Pending" ORDER BY d.donation_date DESC'),
            db.query('SELECT * FROM site_settings'),
            db.query('SELECT z.*, u.name as user_name FROM zakats z JOIN users u ON z.user_id = u.id WHERE z.payment_status = "Pending" ORDER BY z.created_at DESC')
        ]);

        const recent_activities = [...recentDonations, ...recentCampaigns]
            .sort((a, b) => new Date(b.date) - new Date(a.date))
            .slice(0, 5);

        res.json({
            stats: {
                totalDonations: donations?.total || 0,
                totalDonors: donors?.total || 0,
                activeCampaigns: campaigns?.total || 0,
                successCampaigns: successCampaigns?.total || 0,
                recent_activities,
                monthlyDonations,
                categoryDistribution
            },
            users: users || [],
            workerPerformance: (wp || []).map(w => ({
                ...w,
                percentage: w.total_tasks > 0 ? Math.round((w.tasks_completed / w.total_tasks) * 100) : 0
            })),
            tasks: tasks || [],
            attendances: att || [],
            pendingCampaigns: pc || [],
            pendingDonations: pd || [],
            pendingZakats: zakats || [],
            settings: (settings || []).reduce((acc, row) => { acc[row.setting_key] = row.setting_value; return acc; }, {})
        });

    } catch (error) {
        console.error('Error getAllDashboardData:', error);
        res.status(500).json({ message: 'Gagal memuat data dashboard' });
    }
};

/**
 * Manajemen Pengguna: List Seluruh Akun Terdaftar
 */
exports.getTeamList = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT id, name, email, role, created_at FROM users ORDER BY role ASC, created_at DESC'
        );
        res.json(rows);
    } catch (error) {
        console.error('Error getTeamList:', error.message);
        res.status(500).json({ message: 'Gagal memuat database pengguna' });
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
 * Update Akun Petugas/User
 */
exports.updateAccount = async (req, res) => {
    const { id } = req.params;
    const { name, email, role, password } = req.body;

    try {
        // Cek email duplikat (kecuali email milik user ini sendiri)
        const [existing] = await db.query('SELECT id FROM users WHERE email = ? AND id != ?', [email, id]);
        if (existing.length > 0) {
            return res.status(400).json({ message: 'Alamat email sudah digunakan akun lain' });
        }

        let query = 'UPDATE users SET name = ?, email = ?, role = ?';
        let params = [name, email, role];

        // Update password hanya jika diisi
        if (password && password.trim() !== '') {
            const hashedPassword = await bcrypt.hash(password, 10);
            query += ', password = ?';
            params.push(hashedPassword);
        }

        query += ' WHERE id = ?';
        params.push(id);

        const [result] = await db.query(query, params);
        
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Akun tidak ditemukan' });
        }

        res.json({ message: 'Informasi akun berhasil diperbarui' });
    } catch (error) {
        console.error('Error updateAccount:', error);
        res.status(500).json({ message: 'Gagal memperbarui informasi akun' });
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
    const { status, admin_note } = req.body; // 'Success' atau 'Failed' + Note

    try {
        const [[donation]] = await db.query('SELECT * FROM donations WHERE id = ?', [id]);
        if (!donation) return res.status(404).json({ message: 'Donasi tidak ditemukan' });

        if (donation.payment_status !== 'Pending') {
            return res.status(400).json({ message: 'Donasi ini sudah diproses sebelumnya' });
        }

        if (status === 'Success') {
            await db.query('UPDATE donations SET payment_status = "Success", admin_note = ? WHERE id = ?', [admin_note, id]);
            await db.query('UPDATE campaigns SET current_amount = current_amount + ? WHERE id = ?', [donation.amount, donation.campaign_id]);
            res.json({ message: 'Donasi berhasil diverifikasi' });
        } else {
            await db.query('UPDATE donations SET payment_status = "Failed", admin_note = ? WHERE id = ?', [admin_note, id]);
            res.json({ message: 'Donasi ditolak' });
        }
    } catch (error) {
        console.error('Verify Error:', error);
        res.status(500).json({ message: 'Gagal memproses verifikasi' });
    }
};

/**
 * --- MONITORING PEGAWAI (ATTENDANCE & TASKS) ---
 */

// 1. Ambil seluruh absensi pegawai
exports.getAllAttendances = async (req, res) => {
    try {
        // time_zone sudah diset otomatis oleh pool.on('connection') di db.js
        const [rows] = await db.query(`
            SELECT a.id, a.user_id, a.type, a.location, a.latitude, a.longitude, a.created_at,
                   DATE_FORMAT(a.created_at, '%H:%i') as jam_menit,
                   DATE_FORMAT(a.created_at, '%e %M %Y') as tgl_indo,
                   u.name as employee_name, 
                   u.email as employee_email 
            FROM attendance a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC
            LIMIT 500
        `);
        res.json(rows);
    } catch (error) {
        console.error('getAllAttendances Error:', error.message);
        res.status(500).json({ message: 'Gagal memuat data absensi' });
    }
};

// 2. Ambil Statistik Tugas Pegawai (Completion %)
exports.getWorkerPerformance = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT 
                u.id, 
                u.name, 
                u.role,
                COUNT(t.id) as total_tasks,
                SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
            FROM users u
            LEFT JOIN todo_list t ON u.id = t.user_id
            WHERE u.role IN ('field_worker', 'admin')
            GROUP BY u.id
        `);

        const performance = rows.map(r => ({
            ...r,
            percentage: r.total_tasks > 0 ? Math.round((r.completed_tasks / r.total_tasks) * 100) : 0
        }));

        res.json(performance);
    } catch (error) {
        res.status(500).json({ message: 'Gagal memuat performa pegawai' });
    }
};

// 3. Admin memberikan tugas ke pegawai
exports.assignTask = async (req, res) => {
    const { user_id, task, description, deadline } = req.body;
    try {
        await db.query(
            'INSERT INTO todo_list (user_id, assigned_by, task, description, deadline, status) VALUES (?, ?, ?, ?, ?, "pending")',
            [user_id, req.user.id, task, description, deadline]
        );
        res.status(201).json({ message: 'Tugas berhasil diberikan ke pegawai' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal memberikan tugas' });
    }
};

// 4. Lihat detail tugas (termasuk hasil upload pegawai)
exports.getAllTasks = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT t.*, u.name as employee_name, a.name as assigner_name
            FROM todo_list t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN users a ON t.assigned_by = a.id
            ORDER BY t.created_at DESC
        `);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal memuat daftar tugas' });
    }
};

// 5. Admin mengupdate tugas
exports.updateTask = async (req, res) => {
    const { id } = req.params;
    const { task, description, deadline, status } = req.body;
    try {
        await db.query(
            'UPDATE todo_list SET task = ?, description = ?, deadline = ?, status = ? WHERE id = ?',
            [task, description, deadline, status || 'pending', id]
        );
        res.json({ message: 'Tugas berhasil diperbarui' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal memperbarui tugas' });
    }
};

// 6. Admin menghapus tugas
exports.deleteTask = async (req, res) => {
    const { id } = req.params;
    try {
        await db.query('DELETE FROM todo_list WHERE id = ?', [id]);
        res.json({ message: 'Tugas berhasil dihapus' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menghapus tugas' });
    }
};

/**
 * Mengambil detail performa dan tugas pegawai spesifik dengan filter waktu
 */
exports.getWorkerDetail = async (req, res) => {
    const { id } = req.params;
    const { start_date, end_date } = req.query;
    try {
        // 1. Ambil info user
        const [[worker]] = await db.query('SELECT id, name, email, role FROM users WHERE id = ?', [id]);
        if (!worker) return res.status(404).json({ message: 'Pegawai tidak ditemukan' });

        // 2. Build filter query untuk todo_list
        let filterSql = ' WHERE user_id = ?';
        let params = [id];

        if (start_date && end_date) {
            filterSql += ' AND DATE(created_at) BETWEEN ? AND ?';
            params.push(start_date, end_date);
        } else if (start_date) {
            filterSql += ' AND DATE(created_at) >= ?';
            params.push(start_date);
        } else if (end_date) {
            filterSql += ' AND DATE(created_at) <= ?';
            params.push(end_date);
        }

        // 3. Ambil statistik terfilter (Simple query on todo_list only, no ambiguity)
        const [[stats]] = await db.query(`
            SELECT 
                COUNT(*) as total_tasks,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                SUM(CASE WHEN status != 'completed' THEN 1 ELSE 0 END) as pending_tasks
            FROM todo_list
            ${filterSql}
        `, params);

        const total = stats.total_tasks || 0;
        const completed = stats.completed_tasks || 0;
        const pending = stats.pending_tasks || 0;
        const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

        // 4. Ambil daftar tugas terfilter (With JOIN, must use prefixes)
        // Manually prefix columns in filterSql for the second query
        const prefixedFilterSql = filterSql
            .replace(/user_id/g, 't.user_id')
            .replace(/created_at/g, 't.created_at');

        const [tasks] = await db.query(`
            SELECT t.*, a.name as assigner_name
            FROM todo_list t
            LEFT JOIN users a ON t.assigned_by = a.id
            ${prefixedFilterSql}
            ORDER BY t.created_at DESC
        `, params);

        res.json({
            worker,
            stats: {
                total,
                completed,
                pending,
                percentage
            },
            tasks
        });
    } catch (error) {
        console.error('getWorkerDetail Error:', error);
        res.status(500).json({ message: 'Gagal memuat detail pegawai' });
    }
};