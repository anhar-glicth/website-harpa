const db = require('../config/db');

const getDonorDashboardData = async (req, res) => {
    try {
        // 1. Distribusi Segmen
        const [segmentRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN LOWER(segment) LIKE '%retail%' OR LOWER(segment) LIKE '%wa%' THEN 'Retail WA'
                    WHEN LOWER(segment) LIKE '%community%' OR LOWER(segment) LIKE '%komunitas%' THEN 'Community'
                    WHEN LOWER(segment) LIKE '%perusahaan%' OR LOWER(segment) LIKE '%corporate%' THEN 'Perusahaan'
                    WHEN LOWER(segment) LIKE '%sekolah%' OR LOWER(segment) LIKE '%school%' THEN 'Sekolah'
                    WHEN LOWER(segment) LIKE '%cash%' THEN 'Cash'
                    WHEN segment IS NULL OR segment = '' THEN 'Tidak Tersegmen'
                    ELSE segment
                END as label,
                COUNT(*) as value
            FROM users 
            WHERE role = 'user'
            GROUP BY label
            ORDER BY value DESC
        `);

        // 2. Retensi (berdasarkan frekuensi donasi)
        const [retentionRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN don_count = 0 THEN 'Belum Donasi'
                    WHEN don_count = 1 THEN 'Satu Kali'
                    WHEN don_count BETWEEN 2 AND 4 THEN 'Sesekali'
                    WHEN don_count >= 5 THEN 'Loyal'
                END as label,
                COUNT(*) as value
            FROM (
                SELECT u.id, COUNT(d.id) as don_count
                FROM users u
                LEFT JOIN donations d ON d.user_id = u.id AND d.payment_status = 'Success'
                WHERE u.role = 'user'
                GROUP BY u.id
            ) t
            GROUP BY label
            ORDER BY value DESC
        `);

        // 3. Distribusi Jenis Kelamin
        const [genderRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN gender = 'L' THEN 'Laki-laki'
                    WHEN gender = 'P' THEN 'Perempuan'
                    ELSE 'Tidak Diketahui'
                END as label,
                COUNT(*) as value
            FROM users
            WHERE role = 'user'
            GROUP BY label
        `);

        // 4. Distribusi Kontak
        const [contactRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN (phone IS NOT NULL AND phone != '') AND (email IS NOT NULL AND email != '' AND email NOT LIKE '%@internal.com') THEN 'HP & Email'
                    WHEN (phone IS NOT NULL AND phone != '') AND (email IS NULL OR email = '' OR email LIKE '%@internal.com') THEN 'HP Saja'
                    WHEN (phone IS NULL OR phone = '') AND (email IS NOT NULL AND email != '' AND email NOT LIKE '%@internal.com') THEN 'Email Saja'
                    ELSE 'Tanpa Kontak'
                END as label,
                COUNT(*) as value
            FROM users
            WHERE role = 'user'
            GROUP BY label
            ORDER BY value DESC
        `);

        // 5. Distribusi Donasi
        const [donationRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN don_count = 0 THEN 'Tidak Pernah'
                    WHEN don_count BETWEEN 1 AND 3 THEN 'Pernah Donasi'
                    WHEN don_count > 3 THEN 'Sering Donasi'
                END as label,
                COUNT(*) as value
            FROM (
                SELECT u.id, COUNT(d.id) as don_count
                FROM users u
                LEFT JOIN donations d ON d.user_id = u.id AND d.payment_status = 'Success'
                WHERE u.role = 'user'
                GROUP BY u.id
            ) t
            GROUP BY label
            ORDER BY value DESC
        `);

        // 6. Distribusi Prospek
        const [prospectRows] = await db.query(`
            SELECT 
                CASE 
                    WHEN prospect_date IS NOT NULL AND pic_prospect_id IS NOT NULL THEN 'Dalam Prospek'
                    WHEN pic_prospect_id IS NOT NULL AND prospect_date IS NULL THEN 'Assign PIC'
                    WHEN source_info IS NOT NULL AND source_info != '' THEN 'Ada Sumber Info'
                    ELSE 'Belum Diprospek'
                END as label,
                COUNT(*) as value
            FROM users
            WHERE role = 'user'
            GROUP BY label
            ORDER BY value DESC
        `);

        // 7. Total data per Kantor
        const [officeRows] = await db.query(`
            SELECT 
                COALESCE(o.name, 'Tidak Ada Kantor') as label,
                COUNT(u.id) as value
            FROM users u
            LEFT JOIN offices o ON u.office_id = o.id
            WHERE u.role = 'user'
            GROUP BY o.id, o.name
            ORDER BY value DESC
            LIMIT 15
        `);

        // 8. Total data per PIC Prospek
        const [picRows] = await db.query(`
            SELECT 
                COALESCE(pic.name, 'Belum Ada PIC') as label,
                COUNT(u.id) as value
            FROM users u
            LEFT JOIN users pic ON u.pic_prospect_id = pic.id
            WHERE u.role = 'user'
            GROUP BY u.pic_prospect_id, pic.name
            ORDER BY value DESC
            LIMIT 15
        `);

        // Summary KPI
        const [[kpi]] = await db.query(`
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN id IN (SELECT user_id FROM donations WHERE payment_status = 'Success') THEN 1 ELSE 0 END) as active_donors,
                SUM(CASE WHEN created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_this_month,
                SUM(CASE WHEN phone IS NOT NULL AND phone != '' THEN 1 ELSE 0 END) as has_phone
            FROM users WHERE role = 'user'
        `);

        res.json({
            kpi,
            charts: {
                segment: segmentRows,
                retention: retentionRows,
                gender: genderRows,
                contact: contactRows,
                donation: donationRows,
                prospect: prospectRows,
                byOffice: officeRows,
                byPIC: picRows
            }
        });

    } catch (error) {
        console.error('Donor Dashboard Error:', error);
        res.status(500).json({ message: 'Gagal memuat data dashboard donatur', error: error.message });
    }
};

module.exports = { getDonorDashboardData };
