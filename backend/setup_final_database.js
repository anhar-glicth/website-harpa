const db = require('./config/db');

async function setup() {
    console.log('--- MEMULAI PROSES PEMBANGUNAN DATABASE TERSTRUKTUR (FINAL MASTER) ---');
    
    try {
        await db.query('SET FOREIGN_KEY_CHECKS = 0');
        
        const dropTables = [
            'zakats', 'todo_list', 'attendance', 'donations', 'campaign_updates', 'campaigns', 'users',
            'volunteers', 'contact_messages', 'muallaf_requests', 'potential_donors',
            'news', 'newsletters', 'audit_logs', 'settings', 'prayers', 'distributions',
            'offices', 'employees', 'donor_channels', 'program_groups', 'payment_via',
            'prospect_media', 'prospect_methods', 'prospect_types', 'fundraisers',
            'prospect_responses', 'targets', 'prospects', 'pic_targets'
        ];
        for (const table of dropTables) {
            await db.query(`DROP TABLE IF EXISTS ${table}`);
        }
        console.log('✔ Seluruh tabel lama dibersihkan.');

        const tables = [
            `CREATE TABLE offices (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                address TEXT,
                phone VARCHAR(20),
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE donor_channels (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(50) UNIQUE,
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
            )`,
            `CREATE TABLE program_groups (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(50) UNIQUE,
                description TEXT
            )`,
            `CREATE TABLE payment_via (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                provider VARCHAR(255),
                account_no VARCHAR(100),
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
            )`,
            `CREATE TABLE prospect_media (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
            )`,
            `CREATE TABLE prospect_methods (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT
            )`,
            `CREATE TABLE prospect_types (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                priority ENUM('Tinggi', 'Sedang', 'Rendah') DEFAULT 'Sedang'
            )`,
            `CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                username VARCHAR(100) UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'field_worker', 'user') DEFAULT 'user',
                gender ENUM('L', 'P', 'Other'),
                phone VARCHAR(20),
                address TEXT,
                profile_photo LONGTEXT,
                office_id INT NULL,
                pic_prospect_id INT NULL,
                last_prospect_media_id INT NULL,
                segment VARCHAR(100),
                rfm_alltime VARCHAR(100),
                prospect_date DATE,
                source_info TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL,
                FOREIGN KEY (pic_prospect_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (last_prospect_media_id) REFERENCES prospect_media(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                office_id INT NULL,
                name VARCHAR(255) NOT NULL,
                position VARCHAR(100),
                phone VARCHAR(20),
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE campaigns (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT NOT NULL,
                group_id INT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                category VARCHAR(100),
                target_amount DECIMAL(15, 2) NOT NULL,
                current_amount DECIMAL(15, 2) DEFAULT 0,
                foto LONGTEXT,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (group_id) REFERENCES program_groups(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE campaign_updates (
                id INT AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT NOT NULL,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )`,
            `CREATE TABLE donations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT NOT NULL,
                user_id INT NULL,
                payment_via_id INT NULL,
                donor_name VARCHAR(255) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                message TEXT,
                payment_method VARCHAR(100),
                payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
                tracking_status ENUM('Diterima', 'Logistik', 'Dikirim', 'Tersalur') DEFAULT 'Diterima',
                proof_image LONGTEXT,
                admin_note TEXT,
                donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (payment_via_id) REFERENCES payment_via(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE distributions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                program_id INT NOT NULL,
                recipient_name VARCHAR(255) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                distribution_date DATE,
                status ENUM('Proses', 'Tersalur') DEFAULT 'Proses',
                notes TEXT,
                asnaf VARCHAR(100),
                city VARCHAR(100),
                province VARCHAR(100),
                source_of_fund VARCHAR(100),
                pm_count INT DEFAULT 1,
                category_name VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE CASCADE
            )`,
            `CREATE TABLE attendance (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                type ENUM('check_in', 'check_out', 'sakit', 'izin') NOT NULL,
                location VARCHAR(255),
                latitude DECIMAL(10, 8),
                longitude DECIMAL(11, 8),
                attachment LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )`,
            `CREATE TABLE todo_list (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                assigned_by INT NULL,
                task VARCHAR(255) NOT NULL,
                description TEXT,
                deadline DATETIME,
                status ENUM('pending', 'completed') DEFAULT 'pending',
                result_attachment LONGTEXT,
                completed_at DATETIME,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE zakats (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                zakat_type VARCHAR(100) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
                admin_note TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )`,
            `CREATE TABLE volunteers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                phone VARCHAR(20),
                address TEXT,
                skills TEXT,
                reason TEXT,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                subject VARCHAR(255),
                message TEXT,
                status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE muallaf_requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                address TEXT,
                desired_date DATE,
                location VARCHAR(255),
                status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT,
                title VARCHAR(255),
                tag VARCHAR(100),
                content TEXT,
                image LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE newsletters (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE,
                status ENUM('active', 'unsubscribed') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE audit_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                action VARCHAR(255),
                module VARCHAR(100),
                details TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                hero_title TEXT,
                hero_subtitle TEXT,
                hero_image LONGTEXT,
                about_mission TEXT,
                about_image LONGTEXT,
                impact_title TEXT,
                impact_image LONGTEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE prayers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                name VARCHAR(255),
                message TEXT,
                is_anonymous BOOLEAN DEFAULT FALSE,
                aamiin_count INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE fundraisers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                name VARCHAR(255) NOT NULL,
                type ENUM('Internal', 'Mitra', 'Volunteer') DEFAULT 'Internal',
                target DECIMAL(15, 2) DEFAULT 0,
                status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE prospects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                email VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE prospect_responses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                prospect_id INT NULL,
                response TEXT,
                status ENUM('Tertarik', 'Tidak Tertarik', 'Follow Up') DEFAULT 'Follow Up',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (prospect_id) REFERENCES prospects(id) ON DELETE CASCADE
            )`,
            `CREATE TABLE targets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                period VARCHAR(50) NOT NULL,
                program_id INT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                achievement DECIMAL(15, 2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE pic_targets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                pic_id INT NOT NULL,
                month_period DATE NOT NULL,
                target_amount DECIMAL(15, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (pic_id) REFERENCES users(id) ON DELETE CASCADE
            )`
        ];

        for (const sql of tables) {
            await db.query(sql);
        }

        // Insert default settings
        await db.query(`INSERT INTO settings (hero_title, hero_subtitle) VALUES (?, ?)`, 
            ['Uluran Tangan Anda, Harapan Mereka', 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan.']);

        await db.query('SET FOREIGN_KEY_CHECKS = 1');
        console.log('--- SELURUH TABEL BERHASIL DISINKRONISASI ---');

    } catch (error) {
        console.error('❌ GAGAL MEMBANGUN DATABASE:', error.message);
    } finally {
        process.exit();
    }
}

setup();
