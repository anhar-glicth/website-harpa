const db = require('../config/db');

const getTransactionDashboardData = async (req, res) => {
    try {
        const { 
            startDate, endDate, programGroupId, programId, 
            donorId, picId, officeId 
        } = req.query;

        let whereClause = "WHERE d.payment_status = 'Success' ";
        const params = [];

        if (startDate) { whereClause += "AND d.donation_date >= ? "; params.push(startDate); }
        if (endDate) { whereClause += "AND d.donation_date <= ? "; params.push(endDate); }
        if (programId) { whereClause += "AND d.campaign_id = ? "; params.push(programId); }
        if (donorId) { whereClause += "AND d.user_id = ? "; params.push(donorId); }
        
        // Joins needed for PIC and Office filtering
        let joinClause = "LEFT JOIN users u ON d.user_id = u.id ";
        
        if (picId) { whereClause += "AND u.pic_prospect_id = ? "; params.push(picId); }
        if (officeId) { whereClause += "AND u.office_id = ? "; params.push(officeId); }

        // 1. KPI Summary
        const [[kpi]] = await db.query(`
            SELECT 
                COUNT(*) as total_transactions,
                COUNT(DISTINCT d.user_id) as total_donors,
                SUM(d.amount) as total_amount
            FROM donations d
            ${joinClause}
            ${whereClause}
        `, params);

        // 2. Resume per PIC
        const [picResume] = await db.query(`
            SELECT 
                COALESCE(pic.name, 'No PIC') as label, 
                SUM(d.amount) as achievement
            FROM donations d
            LEFT JOIN users u ON d.user_id = u.id
            LEFT JOIN users pic ON u.pic_prospect_id = pic.id
            ${whereClause}
            GROUP BY pic.id, pic.name
            ORDER BY achievement DESC
        `, params);

        // 3. Resume per Office
        const [officeResume] = await db.query(`
            SELECT 
                COALESCE(o.name, 'No Office') as label, 
                SUM(d.amount) as achievement
            FROM donations d
            LEFT JOIN users u ON d.user_id = u.id
            LEFT JOIN offices o ON u.office_id = o.id
            ${whereClause}
            GROUP BY o.id, o.name
            ORDER BY achievement DESC
        `, params);

        // 4. Top 20 Donors
        const [top20] = await db.query(`
            SELECT 
                u.name, u.id, COALESCE(o.name, '-') as office_name, 
                u.segment, u.rfm_alltime as profile,
                COALESCE(pic.name, '-') as pic_name,
                SUM(d.amount) as total_donation
            FROM donations d
            LEFT JOIN users u ON d.user_id = u.id
            LEFT JOIN offices o ON u.office_id = o.id
            LEFT JOIN users pic ON u.pic_prospect_id = pic.id
            ${whereClause}
            GROUP BY u.id
            ORDER BY total_donation DESC
            LIMIT 20
        `, params);

        // Calculate % contributions
        const total = kpi.total_amount || 0;
        const processList = (list) => list.map(item => ({
            ...item,
            percent: total > 0 ? ((item.achievement || item.total_donation) / total * 100).toFixed(2) : 0
        }));

        res.json({
            summary: kpi,
            picResume: processList(picResume),
            officeResume: processList(officeResume),
            top20: processList(top20)
        });

    } catch (error) {
        console.error('Transaction Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data dashboard transaksi', error: error.message });
    }
};

module.exports = { getTransactionDashboardData };
