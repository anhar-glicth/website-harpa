const db = require('../config/db');

const getProspectDashboardData = async (req, res) => {
    try {
        const { 
            startDate, endDate, picId, officeId, mediaId, 
            status, response, potentialRange, methodId, typeId, campaignId 
        } = req.query;

        let whereClause = "WHERE 1=1 ";
        const params = [];

        if (startDate) { whereClause += "AND p.created_at >= ? "; params.push(startDate); }
        if (endDate) { whereClause += "AND p.created_at <= ? "; params.push(endDate); }
        if (picId) { whereClause += "AND p.fundraiser_id = ? "; params.push(picId); }
        if (officeId) { whereClause += "AND p.office_id = ? "; params.push(officeId); }
        if (mediaId) { whereClause += "AND p.media_id = ? "; params.push(mediaId); }
        if (status) { whereClause += "AND p.status = ? "; params.push(status); }
        if (methodId) { whereClause += "AND p.method_id = ? "; params.push(methodId); }
        if (typeId) { whereClause += "AND p.type_id = ? "; params.push(typeId); }
        if (campaignId) { whereClause += "AND p.campaign_id = ? "; params.push(campaignId); }
        
        if (potentialRange === 'low') { whereClause += "AND p.potential_fund BETWEEN 0 AND 1000000 "; }
        else if (potentialRange === 'mid') { whereClause += "AND p.potential_fund BETWEEN 1000001 AND 10000000 "; }
        else if (potentialRange === 'high') { whereClause += "AND p.potential_fund > 10000000 "; }

        if (response) {
            whereClause += "AND EXISTS (SELECT 1 FROM prospect_responses pr WHERE pr.prospect_id = p.id AND pr.status = ?) ";
            params.push(response);
        }

        // Helper for distributions
        const getDist = async (sql) => {
            const [rows] = await db.query(sql, params);
            return rows;
        };

        // 1. KPI Summaries
        const [[kpi]] = await db.query(`
            SELECT 
                COUNT(*) as total_prospects,
                SUM(CASE WHEN p.status = 'Konversi' THEN 1 ELSE 0 END) as total_converted,
                SUM(CASE WHEN p.status = 'Calon Donatur' THEN 1 ELSE 0 END) as total_leads,
                COALESCE(SUM(p.potential_fund), 0) as total_potential_fund
            FROM prospects p
            ${whereClause}
        `, params);

        // 8 Distributions for Pie Charts
        const charts = {
            target: await getDist(`SELECT p.status as label, COUNT(*) as value FROM prospects p ${whereClause} GROUP BY p.status`),
            
            response: await getDist(`
                SELECT COALESCE(pr.status, 'No Response') as label, COUNT(DISTINCT p.id) as value 
                FROM prospects p 
                LEFT JOIN (
                    SELECT prospect_id, status FROM prospect_responses 
                    WHERE id IN (SELECT MAX(id) FROM prospect_responses GROUP BY prospect_id)
                ) pr ON p.id = pr.prospect_id 
                ${whereClause} 
                GROUP BY pr.status
            `),
            
            media: await getDist(`
                SELECT COALESCE(pm.name, 'Unknown') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN prospect_media pm ON p.media_id = pm.id 
                ${whereClause} 
                GROUP BY pm.name
            `),
            
            method: await getDist(`
                SELECT COALESCE(pmt.name, 'Unknown') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN prospect_methods pmt ON p.method_id = pmt.id 
                ${whereClause} 
                GROUP BY pmt.name
            `),
            
            type: await getDist(`
                SELECT COALESCE(pt.name, 'Unknown') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN prospect_types pt ON p.type_id = pt.id 
                ${whereClause} 
                GROUP BY pt.name
            `),
            
            fundraiser: await getDist(`
                SELECT COALESCE(pic.name, 'No PIC') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN users pic ON p.fundraiser_id = pic.id 
                ${whereClause} 
                GROUP BY pic.id, pic.name
            `),
            
            program_group: await getDist(`
                SELECT COALESCE(pg.name, 'No Group') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN program_groups pg ON p.program_group_id = pg.id 
                ${whereClause} 
                GROUP BY pg.name
            `),
            
            campaign: await getDist(`
                SELECT COALESCE(c.title, 'No Campaign') as label, COUNT(p.id) as value 
                FROM prospects p 
                LEFT JOIN campaigns c ON p.campaign_id = c.id 
                ${whereClause} 
                GROUP BY c.id, c.title
            `)
        };

        // Resume Tables
        const officeResume = await getDist(`
            SELECT COALESCE(o.name, 'No Office') as label, COUNT(p.id) as value, SUM(p.potential_fund) as potential 
            FROM prospects p 
            LEFT JOIN offices o ON p.office_id = o.id 
            ${whereClause} 
            GROUP BY o.id, o.name 
            ORDER BY value DESC
        `);

        const [top20] = await db.query(`
            SELECT 
                p.name, p.id, COALESCE(o.name, '-') as office_name, p.status, 
                COALESCE(pic.name, '-') as pic_name, p.potential_fund, p.phone, p.email
            FROM prospects p
            LEFT JOIN offices o ON p.office_id = o.id
            LEFT JOIN users pic ON p.fundraiser_id = pic.id
            ${whereClause}
            ORDER BY p.potential_fund DESC
            LIMIT 20
        `, params);

        res.json({ kpi, charts, officeResume, top20 });

    } catch (error) {
        console.error('Prospect Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data dashboard prospek', error: error.message });
    }
};

module.exports = { getProspectDashboardData };
