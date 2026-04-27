const db = require('./config/db');

async function setup() {
    console.log('--- MEMULAI PROSES PEMBANGUNAN DATABASE TERSTRUKTUR (TOTAL) ---');
    
    try {
        await db.query('SET FOREIGN_KEY_CHECKS = 0');
        
        const dropTables = [
            'zakats', 'todo_list', 'attendance', 'donations', 'campaign_updates', 'campaigns', 'users',
            'volunteers', 'contact_messages', 'muallaf_requests', 'potential_donors',
            'news', 'newsletters', 'audit_logs', 'settings', 'prayers', 'distributions'
        ];
        for (const table of dropTables) {
            await db.query(`DROP TABLE IF EXISTS ${table}`);
        }
        console.log('✔ Seluruh tabel lama dibersihkan.');

        const tables = [
            `CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'field_worker', 'user') DEFAULT 'user',
                phone VARCHAR(20),
                address TEXT,
                profile_photo LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE campaigns (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                category VARCHAR(100),
                target_amount DECIMAL(15, 2) NOT NULL,
                current_amount DECIMAL(15, 2) DEFAULT 0,
                foto LONGTEXT,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
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
                donor_name VARCHAR(255) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                payment_method VARCHAR(100),
                payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
                tracking_status ENUM('Diterima', 'Logistik', 'Dikirim', 'Tersalur') DEFAULT 'Diterima',
                proof_image LONGTEXT,
                admin_note TEXT,
                donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )`,
            `CREATE TABLE distributions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT NOT NULL,
                recipient_name VARCHAR(255) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                distribution_date DATE,
                status ENUM('Proses', 'Tersalur') DEFAULT 'Proses',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
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
            `CREATE TABLE potential_donors (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                phone VARCHAR(20),
                status ENUM('Prospek', 'Calon Donatur', 'Konversi', 'Lost') DEFAULT 'Prospek',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )`,
            `CREATE TABLE news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT,
                title VARCHAR(255),
                tag VARCHAR(100),
                content TEXT,
                image_url LONGTEXT,
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
                name VARCHAR(255),
                message TEXT,
                is_anonymous BOOLEAN DEFAULT FALSE,
                aamiin_count INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
