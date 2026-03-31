const db = require('./config/db');

async function setup() {
    try {
        await db.query(`
            CREATE TABLE IF NOT EXISTS mualaf_requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                address TEXT,
                desired_date DATE,
                location VARCHAR(100),
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                author_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        console.log('Tables mualaf_requests and news ensured.');
        process.exit();
    } catch (e) {
        console.error(e.message);
        process.exit(1);
    }
}

setup();
