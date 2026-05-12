const db = require('../config/db');
const xlsx = require('xlsx');

/**
 * --- TO DO LIST ---
 */

exports.getTodos = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM todo_list WHERE user_id = ? ORDER BY created_at DESC', [req.user.id]);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil data tugas' });
    }
};

exports.addTodo = async (req, res) => {
    const { task, deadline, description } = req.body;
    try {
        await db.query(
            'INSERT INTO todo_list (user_id, task, deadline, description) VALUES (?, ?, ?, ?)',
            [req.user.id, task, deadline || null, description || null]
        );
        res.status(201).json({ message: 'Tugas berhasil ditambahkan' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menambah tugas', error: error.message });
    }
};

exports.toggleTodo = async (req, res) => {
    const { id } = req.params;
    try {
        const [[todo]] = await db.query('SELECT status FROM todo_list WHERE id = ? AND user_id = ?', [id, req.user.id]);
        if (!todo) return res.status(404).json({ message: 'Tugas tidak ditemukan' });

        const newStatus = todo.status === 'pending' ? 'completed' : 'pending';
        await db.query('UPDATE todo_list SET status = ? WHERE id = ?', [newStatus, id]);
        res.json({ message: 'Status tugas diperbarui', status: newStatus });
    } catch (error) {
        res.status(500).json({ message: 'Gagal memperbarui tugas' });
    }
};

exports.deleteTodo = async (req, res) => {
    const { id } = req.params;
    try {
        await db.query('DELETE FROM todo_list WHERE id = ? AND user_id = ?', [id, req.user.id]);
        res.json({ message: 'Tugas dihapus' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menghapus tugas' });
    }
};

/**
 * --- ATTENDANCE ---
 */

// DEBUG: Endpoint untuk cek timezone dan range tanggal server
exports.debugTime = async (req, res) => {
    const now = new Date();
    const WITA_OFFSET = 8 * 60 * 60 * 1000;
    const nowWITA = new Date(now.getTime() + WITA_OFFSET);
    const startOfDayWITA = new Date(Date.UTC(
        nowWITA.getUTCFullYear(),
        nowWITA.getUTCMonth(),
        nowWITA.getUTCDate(),
        0, 0, 0
    ));
    const startOfDayUTC = new Date(startOfDayWITA.getTime() - WITA_OFFSET);
    const endOfDayUTC = new Date(startOfDayUTC.getTime() + 24 * 60 * 60 * 1000 - 1);

    // Ambil juga data attendance langsung dari DB untuk user tertentu
    const [[dbTime]] = await req.app?.locals?.db?.query?.('SELECT NOW() as db_now') || [[{ db_now: 'N/A (no db access)' }]];

    res.json({
        server_now_utc: now.toISOString(),
        server_now_wita: nowWITA.toISOString(),
        process_timezone: process.env.TZ || 'NOT SET',
        startOfDayUTC: startOfDayUTC.toISOString(),
        endOfDayUTC: endOfDayUTC.toISOString(),
        query_range: `created_at >= '${startOfDayUTC.toISOString()}' AND created_at <= '${endOfDayUTC.toISOString()}'`
    });
};

exports.getAttendanceStatus = async (req, res) => {
    try {
        // Set timezone MySQL ke WITA (+08:00) lalu gunakan CURDATE() agar
        // seluruh perbandingan tanggal dilakukan di sisi MySQL — menghindari
        // timezone mismatch antara JavaScript dan MySQL.
        await db.query("SET time_zone = '+08:00'");
        const [rows] = await db.query(`
            SELECT *, 
                   DATE_FORMAT(created_at, '%H:%i') as jam_menit,
                   DATE_FORMAT(created_at, '%e %M %Y') as tgl_indo 
            FROM attendance 
            WHERE user_id = ? AND DATE(created_at) = CURDATE()
            ORDER BY created_at DESC
        `, [req.user.id]);
        res.json(rows);
    } catch (error) {
        console.error('Attendance Status Error:', error);
        res.status(500).json({ message: 'Gagal mengecek status absensi' });
    }
};

exports.submitAttendance = async (req, res) => {
    const { type, location, latitude, longitude, attachment } = req.body;
    try {
        await db.query(
            'INSERT INTO attendance (user_id, type, location, latitude, longitude, attachment) VALUES (?, ?, ?, ?, ?, ?)',
            [req.user.id, type, location, latitude, longitude, attachment]
        );
        let msg = `Berhasil ${type === 'check_in' ? 'Masuk' : type === 'check_out' ? 'Pulang' : 'Kirim Keterangan'}`;
        res.status(201).json({ message: msg });
    } catch (error) {
        console.error('Attendance Submission Error:', error);
        res.status(500).json({
            message: 'Gagal melakukan absensi',
            error: error.message,
            stack: process.env.NODE_ENV === 'development' ? error.stack : undefined
        });
    }
};

exports.getAttendanceRecap = async (req, res) => {
    try {
        await db.query("SET time_zone = '+08:00'");
        const [rows] = await db.query(`
            SELECT *, 
                   DATE_FORMAT(created_at, '%H:%i') as jam_menit,
                   DATE_FORMAT(created_at, '%e %M %Y') as tgl_indo 
            FROM attendance 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 50
        `, [req.user.id]);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil riwayat absensi' });
    }
};

/**
 * --- FIELD WORKER PERFORMANCE STATS ---
 */
exports.getFieldStats = async (req, res) => {
    try {
        const userId = req.user.id;

        // 1. Total Campaigns Created
        const [[{ totalCampaigns }]] = await db.query('SELECT COUNT(*) as totalCampaigns FROM campaigns WHERE author_id = ?', [userId]);

        // 2. Total News/Updates Published
        const [[{ totalNews }]] = await db.query('SELECT COUNT(*) as totalNews FROM news WHERE author_id = ?', [userId]);
        const [[{ totalUpdates }]] = await db.query('SELECT COUNT(*) as totalUpdates FROM campaign_updates WHERE user_id = ?', [userId]);

        // 3. Total Funds Collected via their campaigns
        const [[{ totalFunds }]] = await db.query(
            'SELECT SUM(current_amount) as totalFunds FROM campaigns WHERE author_id = ?',
            [userId]
        );

        // 4. Attendance Stats (last 30 days)
        const [[{ presentDays }]] = await db.query(
            'SELECT COUNT(DISTINCT DATE(created_at)) as presentDays FROM attendance WHERE user_id = ? AND type = "check_in"',
            [userId]
        );

        // 5. Work Effectiveness (Task Completion Rate)
        const [[{ totalTasks }]] = await db.query('SELECT COUNT(*) as total FROM todo_list WHERE user_id = ?', [userId]);
        const [[{ completedTasks }]] = await db.query('SELECT COUNT(*) as completed FROM todo_list WHERE user_id = ? AND status = "completed"', [userId]);

        const effectiveness = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

        res.json({
            totalCampaigns,
            totalContent: totalNews + totalUpdates,
            totalFunds: totalFunds || 0,
            presentDays,
            effectiveness
        });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal mengambil statistik kinerja' });
    }
};

/**
 * --- CAMPAIGN UPDATES (IMPACT STORIES) ---
 */
exports.addCampaignUpdate = async (req, res) => {
    const { campaign_id, title, content, image } = req.body;
    try {
        await db.query(
            'INSERT INTO campaign_updates (campaign_id, user_id, title, content, image) VALUES (?, ?, ?, ?, ?)',
            [campaign_id, req.user.id, title, content, image]
        );
        res.status(201).json({ message: 'Kabar terbaru berhasil dikirim!' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengirim kabar terbaru' });
    }
};

exports.getCampaignUpdates = async (req, res) => {
    const { campaignId } = req.params;
    try {
        const [rows] = await db.query(
            'SELECT cu.*, u.name as author_name FROM campaign_updates cu JOIN users u ON cu.user_id = u.id WHERE cu.campaign_id = ? ORDER BY cu.created_at DESC',
            [campaignId]
        );
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil kabar terbaru' });
    }
};

/**
 * --- TASK COMPLETION (UPLOAD RESULT) ---
 */
exports.completeTask = async (req, res) => {
    const { id } = req.params;
    const { result_attachment, result_note } = req.body;
    try {
        await db.query(
            'UPDATE todo_list SET status = "completed", result_attachment = ?, result_note = ?, completed_at = NOW() WHERE id = ? AND user_id = ?',
            [result_attachment, result_note || null, id, req.user.id]
        );
        res.json({ message: 'Tugas berhasil diselesaikan dan bukti telah diunggah!' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menyelesaikan tugas' });
    }
};

/**
 * --- DISTRIBUTION MAPPING ---
 */
exports.addDistribution = async (req, res) => {
    const {
        title, description, latitude, longitude, location_name,
        photo, maps_link, campaign_id, amount,
        recipient_name, items_distributed, distribution_date
    } = req.body;

    try {
        await db.query(
            `INSERT INTO distributions (
                worker_id, title, description, latitude, longitude, 
                location_name, photo_url, maps_link, program_id, 
                amount, recipient_name, items_distributed, distribution_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                req.user.id, title, description, latitude, longitude,
                location_name, photo, maps_link || null, campaign_id || null,
                amount || 0, recipient_name || null, items_distributed || null,
                distribution_date || null
            ]
        );
        res.status(201).json({ message: 'Laporan penyaluran berhasil disimpan!' });
    } catch (error) {
        console.error('SQL Error in addDistribution:', error);
        res.status(500).json({
            message: 'Gagal menyimpan laporan penyaluran',
            error: error.message
        });
    }
};

exports.getAllDistributions = async (req, res) => {
    try {
        const [rows] = await db.query(`
            SELECT d.*, u.name as worker_name, c.title as campaign_title 
            FROM distributions d 
            JOIN users u ON d.worker_id = u.id 
            LEFT JOIN campaigns c ON d.program_id = c.id
            ORDER BY d.created_at DESC
        `);
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Gagal mengambil data penyaluran' });
    }
};

// Petugas melihat laporan miliknya sendiri
exports.getMyDistributions = async (req, res) => {
    try {
        const [rows] = await db.query(
            `SELECT d.*, c.title as campaign_title 
             FROM distributions d 
             LEFT JOIN campaigns c ON d.program_id = c.id 
             WHERE d.worker_id = ? 
             ORDER BY d.created_at DESC`,
            [req.user.id]
        );
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil riwayat laporan Anda' });
    }
};

exports.updateDistribution = async (req, res) => {
    const { id } = req.params;
    const { title, description, location_name, maps_link } = req.body;
    try {
        // Pastikan laporan milik petugas yang sedang login
        const [check] = await db.query('SELECT id FROM distributions WHERE id = ? AND worker_id = ?', [id, req.user.id]);
        if (check.length === 0) return res.status(403).json({ message: 'Anda tidak memiliki akses ke laporan ini' });

        await db.query(
            'UPDATE distributions SET title = ?, description = ?, location_name = ?, maps_link = ? WHERE id = ?',
            [title, description, location_name, maps_link, id]
        );
        res.json({ message: 'Laporan berhasil diperbarui!' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal memperbarui laporan' });
    }
};

exports.deleteDistribution = async (req, res) => {
    const { id } = req.params;
    try {
        // Pastikan laporan milik petugas yang sedang login
        const [check] = await db.query('SELECT id FROM distributions WHERE id = ? AND worker_id = ?', [id, req.user.id]);
        if (check.length === 0) return res.status(403).json({ message: 'Anda tidak memiliki akses ke laporan ini' });

        await db.query('DELETE FROM distributions WHERE id = ?', [id]);
        res.json({ message: 'Laporan berhasil dihapus' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menghapus laporan' });
    }
};

/**
 * --- BULK IMPORT DISTRIBUTIONS (Staff) ---
 */
exports.bulkImportDistributions = async (req, res) => {
    if (!req.file) {
        return res.status(400).json({ message: 'File tidak ditemukan' });
    }
    try {
        const workbook = xlsx.read(req.file.buffer, { type: 'buffer' });
        const sheetName = workbook.SheetNames[0];
        const data = xlsx.utils.sheet_to_json(workbook.Sheets[sheetName]);

        if (data.length === 0) {
            return res.status(400).json({ message: 'File Excel kosong' });
        }

        const allowedFields = [
            'title', 'description', 'location_name', 'maps_link',
            'program_id', 'amount', 'recipient_name',
            'items_distributed', 'distribution_date'
        ];
        const labelMap = {
            'Judul': 'title', 'Deskripsi': 'description', 'Nama Lokasi': 'location_name',
            'Link Maps': 'maps_link', 'ID Program': 'program_id', 'Nominal': 'amount',
            'Nama Penerima': 'recipient_name', 'Barang Disalurkan': 'items_distributed',
            'Tanggal': 'distribution_date'
        };

        let successCount = 0;
        const errors = [];

        for (let i = 0; i < data.length; i++) {
            const row = data[i];
            const insertData = { worker_id: req.user.id };
            allowedFields.forEach(field => {
                const label = Object.keys(labelMap).find(l => labelMap[l] === field);
                insertData[field] = row[field] !== undefined ? row[field] : (label && row[label] !== undefined ? row[label] : null);
            });
            if (!insertData.distribution_date) {
                insertData.distribution_date = new Date().toISOString().split('T')[0];
            }
            try {
                await db.query('INSERT INTO distributions SET ?', insertData);
                successCount++;
            } catch (err) {
                errors.push(`Baris ${i + 2}: ${err.message}`);
            }
        }

        res.json({
            message: `Impor selesai. ${successCount} berhasil, ${errors.length} gagal.`,
            successCount,
            failureCount: errors.length,
            errors: errors.slice(0, 10)
        });
    } catch (error) {
        console.error('Bulk Import Distribution Error:', error);
        res.status(500).json({ message: 'Gagal memproses file Excel', error: error.message });
    }
};

/**
 * --- MASTER DATA FOR STAFF ---
 */
exports.getProgramGroups = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT id, name FROM program_groups ORDER BY name ASC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil data rumpun program' });
    }
};
