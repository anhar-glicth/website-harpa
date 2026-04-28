const db = require('../config/db');

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

exports.getAttendanceStatus = async (req, res) => {
    try {
        const [rows] = await db.query(
            'SELECT * FROM attendance WHERE user_id = ? AND DATE(created_at) = CURDATE() ORDER BY created_at DESC', 
            [req.user.id]
        );
        res.json(rows);
    } catch (error) {
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
        const [rows] = await db.query(
            'SELECT * FROM attendance WHERE user_id = ? ORDER BY created_at DESC LIMIT 50', 
            [req.user.id]
        );
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
