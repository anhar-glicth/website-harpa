const db = require('./config/db');

async function setup() {
    try {
        // Add admin_note to mualaf_requests
        await db.query('ALTER TABLE mualaf_requests ADD COLUMN IF NOT EXISTS admin_note TEXT AFTER status');
        
        // Add admin_note to donations
        await db.query('ALTER TABLE donations ADD COLUMN IF NOT EXISTS admin_note TEXT AFTER payment_status');

        console.log('Database updated with admin_note columns.');
        process.exit();
    } catch (e) {
        console.error(e.message);
        process.exit(1);
    }
}

setup();
