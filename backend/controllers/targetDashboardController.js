const db = require('../config/db');

const getTargetDashboardData = async (req, res) => {
    try {
        const { startDate, endDate } = req.query;

        // If no dates provided, use current month
        const start = startDate || new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        const end = endDate || new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).toISOString().split('T')[0];

        // 1. Get Targets per PIC
        const [targetRows] = await db.query(`
            SELECT 
                pic_id, 
                SUM(target_amount) as total_target
            FROM pic_targets
            WHERE month_period BETWEEN ? AND ?
            GROUP BY pic_id
        `, [start, end]);

        // 2. Get Achievements per PIC (from donations)
        const [achievementRows] = await db.query(`
            SELECT 
                u.pic_prospect_id as pic_id,
                pic.name as pic_name,
                SUM(d.amount) as total_achievement
            FROM donations d
            JOIN users u ON d.user_id = u.id
            JOIN users pic ON u.pic_prospect_id = pic.id
            WHERE d.payment_status = 'Success' 
              AND d.donation_date BETWEEN ? AND ?
            GROUP BY u.pic_prospect_id, pic.name
        `, [start, end]);

        // 3. Merge Data
        const allPics = {};

        // Process targets
        for (const row of targetRows) {
            const picId = row.pic_id;
            if (!allPics[picId]) allPics[picId] = { name: 'Unknown PIC', target: 0, achievement: 0 };
            allPics[picId].target = Number(row.total_target);
        }

        // Process achievements & get names
        for (const row of achievementRows) {
            const picId = row.pic_id;
            if (!allPics[picId]) allPics[picId] = { name: row.pic_name, target: 0, achievement: 0 };
            allPics[picId].name = row.pic_name;
            allPics[picId].achievement = Number(row.total_achievement);
        }

        const resumeData = Object.entries(allPics).map(([id, data]) => {
            const sisa = data.target - data.achievement;
            const percent = data.target > 0 ? (data.achievement / data.target * 100).toFixed(2) : 0;
            return {
                id,
                name: data.name,
                target: data.target,
                achievement: data.achievement,
                sisa: sisa,
                percent: percent
            };
        });

        // 4. Global Summary
        const summary = {
            totalTarget: resumeData.reduce((s, r) => s + r.target, 0),
            totalAchievement: resumeData.reduce((s, r) => s + r.achievement, 0)
        };
        summary.percent = summary.totalTarget > 0 ? (summary.totalAchievement / summary.totalTarget * 100).toFixed(2) : 0;
        summary.sisa = summary.totalTarget - summary.totalAchievement;

        res.json({
            summary,
            resume: resumeData
        });

    } catch (error) {
        console.error('Target Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data target vs capaian', error: error.message });
    }
};

const saveTarget = async (req, res) => {
    try {
        const { pic_id, month_period, target_amount } = req.body;
        
        // Upsert logic: If target for this PIC and month exists, update it. Otherwise, insert.
        const [existing] = await db.query(
            "SELECT id FROM pic_targets WHERE pic_id = ? AND month_period = ?",
            [pic_id, month_period]
        );

        if (existing.length > 0) {
            await db.query(
                "UPDATE pic_targets SET target_amount = ? WHERE id = ?",
                [target_amount, existing[0].id]
            );
        } else {
            await db.query(
                "INSERT INTO pic_targets (pic_id, month_period, target_amount) VALUES (?, ?, ?)",
                [pic_id, month_period, target_amount]
            );
        }

        res.json({ message: 'Target berhasil disimpan' });
    } catch (error) {
        console.error('Save Target Error:', error);
        res.status(500).json({ message: 'Gagal menyimpan target', error: error.message });
    }
};

module.exports = { getTargetDashboardData, saveTarget };
