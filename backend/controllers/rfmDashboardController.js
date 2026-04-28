const db = require('../config/db');

const getRFMDashboardData = async (req, res) => {
    try {
        const { 
            startDate, endDate, segment, gender, 
            rfm90, rfmAll, picId, officeId 
        } = req.query;

        let whereClause = "WHERE u.role = 'user' ";
        const params = [];

        if (startDate) { whereClause += "AND u.created_at >= ? "; params.push(startDate); }
        if (endDate) { whereClause += "AND u.created_at <= ? "; params.push(endDate); }
        if (segment) { whereClause += "AND u.segment = ? "; params.push(segment); }
        if (gender && gender !== 'All') { whereClause += "AND u.gender = ? "; params.push(gender); }
        if (picId) { whereClause += "AND u.pic_prospect_id = ? "; params.push(picId); }
        if (officeId) { whereClause += "AND u.office_id = ? "; params.push(officeId); }

        // Core Query: Get stats for all users first
        const baseQuery = `
            SELECT 
                u.id, u.name, u.gender, u.office_id, u.pic_prospect_id, u.segment,
                o.name as office_name, pic.name as pic_name,
                
                -- All Time Stats
                COUNT(d_all.id) as total_trx_all,
                COALESCE(SUM(d_all.amount), 0) as total_amount_all,
                MAX(d_all.donation_date) as last_donation_all,
                DATEDIFF(NOW(), MAX(d_all.donation_date)) as days_since_last_all,

                -- 90 Days Stats
                COUNT(d_90.id) as total_trx_90,
                COALESCE(SUM(d_90.amount), 0) as total_amount_90
            FROM users u
            LEFT JOIN offices o ON u.office_id = o.id
            LEFT JOIN users pic ON u.pic_prospect_id = pic.id
            LEFT JOIN donations d_all ON u.id = d_all.user_id AND d_all.payment_status = 'Success'
            LEFT JOIN donations d_90 ON u.id = d_90.user_id AND d_90.payment_status = 'Success' AND d_90.donation_date >= DATE_SUB(NOW(), INTERVAL 90 DAY)
            ${whereClause}
            GROUP BY u.id
        `;

        const [users] = await db.query(baseQuery, params);

        // Process Profiling on JS side for flexibility
        const processedUsers = users.map(u => {
            // RFM 90 Days Logic
            let rfm90_profile = 'DORMANT';
            if (u.total_trx_90 >= 3 && u.total_amount_90 >= 500000) rfm90_profile = 'CHAMPION';
            else if (u.total_trx_90 === 2) rfm90_profile = 'LOYAL';
            else if (u.total_trx_90 === 1) rfm90_profile = 'POTENTIAL';
            else if (u.total_trx_all > 0 && u.total_trx_90 === 0) rfm90_profile = 'DORMANT';
            else rfm90_profile = 'LOW_ENGAGEMENT';

            // RFM All Time Logic
            let rfmAll_profile = 'NEW';
            if (u.total_trx_all === 0) rfmAll_profile = 'NEW';
            else if (u.days_since_last_all <= 90 && u.total_trx_all >= 3 && u.total_amount_all >= 500000) rfmAll_profile = 'CHAMPION';
            else if (u.days_since_last_all <= 120 && u.total_trx_all >= 2) rfmAll_profile = 'LOYAL';
            else if (u.days_since_last_all <= 180) rfmAll_profile = 'POTENTIAL';
            else if (u.days_since_last_all > 180) rfmAll_profile = 'DORMANT';
            else rfmAll_profile = 'LOW_ENGAGEMENT';

            return { ...u, rfm90_profile, rfmAll_profile };
        });

        // Apply secondary RFM filters if requested
        let filteredUsers = processedUsers;
        if (rfm90 && rfm90 !== 'All') filteredUsers = filteredUsers.filter(u => u.rfm90_profile === rfm90);
        if (rfmAll && rfmAll !== 'All') filteredUsers = filteredUsers.filter(u => u.rfmAll_profile === rfmAll);

        // --- AGGREGATIONS ---

        // 1. KPI Summaries
        const summary = {
            total: filteredUsers.length,
            male: filteredUsers.filter(u => u.gender === 'L').length,
            female: filteredUsers.filter(u => u.gender === 'P').length,
            unknown: filteredUsers.filter(u => u.gender !== 'L' && u.gender !== 'P').length
        };

        // 2. Aggregate by PIC (90 Days)
        const pic90 = {};
        filteredUsers.forEach(u => {
            const name = u.pic_name || 'Unassigned';
            if (!pic90[name]) pic90[name] = { Champion:0, Loyal:0, Potential:0, 'Need Attention':0, 'Low Engagement':0, Dormant:0 };
            const profileMap = { 'CHAMPION':'Champion', 'LOYAL':'Loyal', 'POTENTIAL':'Potential', 'LOW_ENGAGEMENT':'Low Engagement', 'DORMANT':'Dormant' };
            const key = profileMap[u.rfm90_profile] || 'Dormant';
            pic90[name][key]++;
        });

        // 3. Aggregate by PIC (All Time)
        const picAll = {};
        filteredUsers.forEach(u => {
            const name = u.pic_name || 'Unassigned';
            if (!picAll[name]) picAll[name] = { Champion:0, Loyal:0, Potential:0, 'Need Attention':0, 'Low Engagement':0, Dormant:0, New:0 };
            const profileMap = { 'CHAMPION':'Champion', 'LOYAL':'Loyal', 'POTENTIAL':'Potential', 'LOW_ENGAGEMENT':'Low Engagement', 'DORMANT':'Dormant', 'NEW':'New' };
            const key = profileMap[u.rfmAll_profile] || 'New';
            picAll[name][key]++;
        });

        // 4. Top 20 Donors
        const totalGlobalDonation = filteredUsers.reduce((s, u) => s + Number(u.total_amount_all), 0);
        const top20 = [...filteredUsers]
            .sort((a, b) => b.total_amount_all - a.total_amount_all)
            .slice(0, 20)
            .map(u => ({
                name: u.name,
                id: u.id,
                office: u.office_name,
                rfm90: u.rfm90_profile,
                rfmAll: u.rfmAll_profile,
                pic: u.pic_name,
                totalTrx: u.total_trx_all,
                totalDonation: u.total_amount_all,
                contribution: totalGlobalDonation > 0 ? (u.total_amount_all / totalGlobalDonation * 100).toFixed(2) : 0
            }));

        res.json({
            summary,
            pic90: Object.entries(pic90).map(([name, stats]) => ({ name, ...stats })),
            picAll: Object.entries(picAll).map(([name, stats]) => ({ name, ...stats })),
            top20
        });

    } catch (error) {
        console.error('RFM Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data RFM', error: error.message });
    }
};

module.exports = { getRFMDashboardData };
