const db = require('./config/db');

async function setup() {
    try {
        // Add profile_photo to users table
        await db.query('ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_photo TEXT AFTER role');
        
        console.log('Users table updated with profile_photo column.');
        process.exit();
    } catch (e) {
        console.error(e.message);
        process.exit(1);
    }
}

setup();
