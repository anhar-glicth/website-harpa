const db = require('./config/db');

async function extend() {
    try {
        console.log('Extending DB for Mualaf & News...');

        // 1. Tabel Mualaf Requests
        await db.query(`
            CREATE TABLE IF NOT EXISTS mualaf_requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                address TEXT NOT NULL,
                desired_date DATE NOT NULL,
                location VARCHAR(100) NOT NULL,
                status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
                admin_notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        `);
        console.log('Tabel mualaf_requests siap.');

        // 2. Tabel News
        await db.query(`
            CREATE TABLE IF NOT EXISTS news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image LONGTEXT,
                category VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
            )
        `);
        console.log('Tabel news siap.');

        console.log('DB Extension Success!');
    } catch (err) {
        console.error('Error:', err.message);
    } finally {
        process.exit();
    }
}

extend();
