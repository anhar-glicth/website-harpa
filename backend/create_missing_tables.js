const db = require('./config/db');

async function createMissingTables() {
    console.log('--- CREATING MISSING TABLES FROM database.sql ---');
    
    const queries = [
        `CREATE TABLE IF NOT EXISTS offices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            address TEXT,
            city VARCHAR(100),
            phone VARCHAR(20),
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )`,
        `CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            office_id INT NULL,
            name VARCHAR(255) NOT NULL,
            position VARCHAR(100),
            department VARCHAR(100),
            phone VARCHAR(20),
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL
        )`,
        `CREATE TABLE IF NOT EXISTS donor_channels (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(50),
            account VARCHAR(100),
            code VARCHAR(50) UNIQUE,
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
        )`,
        `CREATE TABLE IF NOT EXISTS program_groups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50) UNIQUE,
            description TEXT
        )`,
        `CREATE TABLE IF NOT EXISTS payment_via (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            provider VARCHAR(255),
            fee DECIMAL(5,2) DEFAULT 0,
            account_no VARCHAR(100),
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
        )`,
        `CREATE TABLE IF NOT EXISTS prospect_media (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(50),
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
        )`,
        `CREATE TABLE IF NOT EXISTS prospect_methods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT
        )`,
        `CREATE TABLE IF NOT EXISTS prospect_types (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            priority ENUM('Tinggi', 'Sedang', 'Rendah') DEFAULT 'Sedang'
        )`,
        `CREATE TABLE IF NOT EXISTS fundraisers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255),
            type ENUM('Internal', 'Mitra', 'Volunteer') DEFAULT 'Internal',
            target DECIMAL(15, 2) DEFAULT 0,
            achievement DECIMAL(15, 2) DEFAULT 0,
            status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )`,
        `CREATE TABLE IF NOT EXISTS prospect_responses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            prospect_id INT NULL,
            response TEXT,
            status ENUM('Tertarik', 'Tidak Tertarik', 'Follow Up') DEFAULT 'Follow Up',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )`,
        `CREATE TABLE IF NOT EXISTS targets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            period VARCHAR(50) NOT NULL,
            program_id INT NULL,
            amount DECIMAL(15, 2) NOT NULL,
            achievement DECIMAL(15, 2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
        )`,
        `CREATE TABLE IF NOT EXISTS distributions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            recipient_name VARCHAR(255) NOT NULL,
            program_id INT NULL,
            amount DECIMAL(15, 2) NOT NULL,
            distribution_date DATE,
            status ENUM('Tersalur', 'Proses') DEFAULT 'Proses',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
        )`
    ];

    for (const q of queries) {
        try {
            await db.query(q);
            console.log('Successfully executed query part.');
        } catch (e) {
            console.error('Error executing query:', e.message);
        }
    }

    console.log('--- ALL TABLES INITIALIZED ---');
    process.exit(0);
}

createMissingTables();
