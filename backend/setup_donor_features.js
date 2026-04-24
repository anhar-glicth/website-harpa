const db = require('./config/db');

async function setup() {
    try {
        console.log('Building donor features database schema...');

        // 1. Create Prayers/Messages Table
        await db.query(`
            CREATE TABLE IF NOT EXISTS prayers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                name VARCHAR(255),
                message TEXT NOT NULL,
                is_anonymous BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);
        console.log('Table "prayers" created.');

        // 2. Add Tracking Status to Donations if not exists
        try {
            await db.query(`
                ALTER TABLE donations 
                ADD COLUMN tracking_status ENUM('Received', 'Processing', 'Shipping', 'Delivered') 
                DEFAULT 'Received' AFTER payment_status
            `);
            console.log('Column "tracking_status" added to donations.');
        } catch (e) {
            console.log('Column "tracking_status" might already exist.');
        }

        console.log('Database schema for donor features is ready!');
        process.exit();
    } catch (err) {
        console.error('Migration failed:', err.message);
        process.exit(1);
    }
}

setup();
