const db = require('./config/db');

async function updateUsers() {
    try {
        console.log('Updating users table...');
        try { await db.query(`ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email`); } catch(e) {}
        console.log('Users table updated.');
    } catch (err) {
        console.error('Error:', err.message);
    } finally {
        process.exit();
    }
}

updateUsers();
