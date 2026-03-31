const db = require('./config/db');

async function setup() {
    try {
        // Table for Zakat payments
        await db.query(`
            CREATE TABLE IF NOT EXISTS zakats (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                zakat_type VARCHAR(50) NOT NULL,
                amount DECIMAL(15, 2) NOT NULL,
                payment_method VARCHAR(50) DEFAULT 'Transfer Bank',
                payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
                admin_note TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        `);

        console.log('Zakats table ensured.');
        process.exit();
    } catch (e) {
        console.error(e.message);
        process.exit(1);
    }
}

setup();
