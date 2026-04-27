const db = require('../config/db');

async function testWrite() {
    try {
        console.log('Testing write access to humanity_db...');
        const [result] = await db.query('UPDATE users SET name = name WHERE id = 1');
        console.log('Update result:', result);
        console.log('Database is WRITABLE.');
    } catch (error) {
        console.error('Database write error:', error.message);
        if (error.message.includes('read-only')) {
            console.error('CONFIRMED: Database is in read-only mode.');
        }
    } finally {
        process.exit();
    }
}

testWrite();
