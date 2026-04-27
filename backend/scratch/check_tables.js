const db = require('../config/db');

async function checkTables() {
    try {
        const [rows] = await db.query('SHOW TABLE STATUS');
        console.log('--- Table Status ---');
        rows.forEach(table => {
            console.log(`Table: ${table.Name}, Engine: ${table.Engine}, Row_format: ${table.Row_format}, Comment: ${table.Comment}`);
        });
    } catch (error) {
        console.error('Error checking tables:', error.message);
    } finally {
        process.exit();
    }
}

checkTables();
