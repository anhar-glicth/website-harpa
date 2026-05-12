const db = require('../config/db');

const getDistributionDashboardData = async (req, res) => {
    try {
        const { startDate, endDate, category, asnaf, city, province, source } = req.query;

        let whereClause = "WHERE d.status = 'Tersalur' ";
        const params = [];

        if (startDate) { whereClause += "AND d.distribution_date >= ? "; params.push(startDate); }
        if (endDate) { whereClause += "AND d.distribution_date <= ? "; params.push(endDate); }
        if (category) { whereClause += "AND d.category_name = ? "; params.push(category); }
        if (asnaf) { whereClause += "AND d.asnaf = ? "; params.push(asnaf); }
        if (city) { whereClause += "AND d.city = ? "; params.push(city); }
        if (province) { whereClause += "AND d.province = ? "; params.push(province); }
        if (source) { whereClause += "AND d.source_of_fund = ? "; params.push(source); }

        // Helper for grouped data
        const getGroupedData = async (selectClause, groupBy) => {
            const sql = `
                SELECT 
                    ${selectClause},
                    COUNT(*) as value,
                    SUM(d.pm_count) as total_pm,
                    SUM(d.amount) as total_amount
                FROM distributions d
                LEFT JOIN campaigns c ON d.program_id = c.id
                ${whereClause}
                GROUP BY ${groupBy}
                ORDER BY total_amount DESC
            `;
            const [rows] = await db.query(sql, params);
            return rows;
        };

        // 1. Overall Summary
        const [[summary]] = await db.query(`
            SELECT 
                SUM(amount) as total_amount,
                SUM(pm_count) as total_pm,
                COUNT(*) as total_distributions
            FROM distributions d
            ${whereClause}
        `, params);

        // 2. Monthly Trend
        const [monthlyTrend] = await db.query(`
            SELECT 
                DATE_FORMAT(distribution_date, '%Y-%m') as month,
                SUM(amount) as nominal,
                SUM(pm_count) as pm
            FROM distributions d
            ${whereClause}
            GROUP BY month
            ORDER BY month ASC
        `, params);

        // Distributions for Pie Charts
        const charts = {
            asnaf: await getGroupedData("COALESCE(d.asnaf, 'Lainnya') as label", "d.asnaf"),
            source: await getGroupedData("COALESCE(d.source_of_fund, 'Zakat') as label", "d.source_of_fund"),
            category: await getGroupedData("COALESCE(d.category_name, 'Umum') as label", "d.category_name"),
            program: await getGroupedData("c.title as label", "c.id, c.title")
        };

        // Geographic Table
        const byCity = await getGroupedData("d.city as label, d.province", "d.city, d.province");

        // Calculate % for each list
        const total = summary.total_amount || 0;
        const addPercent = (list) => list.map(item => ({
            ...item,
            percent: total > 0 ? ((item.total_amount / total) * 100).toFixed(2) : 0
        }));

        res.json({
            summary,
            monthlyTrend,
            charts: {
                asnaf: addPercent(charts.asnaf),
                source: addPercent(charts.source),
                category: addPercent(charts.category),
                program: addPercent(charts.program)
            },
            byCity: addPercent(byCity)
        });

    } catch (error) {
        console.error('Distribution Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data dashboard penyaluran', error: error.message });
    }
};

module.exports = { getDistributionDashboardData };
