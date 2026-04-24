const db = require('./config/db');

async function updateDB() {
    try {
        console.log('Synchronizing Master Data Schema...');

        // 1. Create Prospects Table
        await db.query(`
            CREATE TABLE IF NOT EXISTS prospects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                email VARCHAR(255),
                address TEXT,
                media_id INT,
                fundraiser_id INT,
                type_id INT,
                status ENUM('Prospek', 'Calon Donatur', 'Konversi', 'Lost') DEFAULT 'Prospek',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);
        console.log('Table prospects ready.');

        // 2. Align Offices
        try { await db.query(`ALTER TABLE offices ADD COLUMN city VARCHAR(100) AFTER address`); } catch(e) {}
        
        // 3. Align Employees
        try { await db.query(`ALTER TABLE employees ADD COLUMN department VARCHAR(100) AFTER position`); } catch(e) {}

        // 4. Align Donor Channels
        try { await db.query(`ALTER TABLE donor_channels ADD COLUMN type VARCHAR(50) AFTER name`); } catch(e) {}
        try { await db.query(`ALTER TABLE donor_channels ADD COLUMN account VARCHAR(100) AFTER type`); } catch(e) {}

        // 5. Align Payment Via
        try { await db.query(`ALTER TABLE payment_via ADD COLUMN fee DECIMAL(5,2) DEFAULT 0 AFTER provider`); } catch(e) {}

        // 6. Align Prospect Media
        try { await db.query(`ALTER TABLE prospect_media ADD COLUMN type VARCHAR(50) AFTER name`); } catch(e) {}

        // 7. Align Fundraisers
        try { await db.query(`ALTER TABLE fundraisers ADD COLUMN email VARCHAR(255) AFTER name`); } catch(e) {}
        try { await db.query(`ALTER TABLE fundraisers ADD COLUMN achievement DECIMAL(15,2) DEFAULT 0 AFTER target`); } catch(e) {}

        console.log('Master Data Schema synchronized successfully!');
    } catch (err) {
        console.error('Error synchronizing DB:', err.message);
    } finally {
        process.exit();
    }
}

updateDB();
