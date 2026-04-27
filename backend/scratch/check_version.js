const db = require('../config/db');

async function checkVersion() {
    try {
        const [[version]] = await db.query('SELECT VERSION() as v');
        console.log('MySQL Version:', version.v);

        const [dbs] = await db.query('SHOW DATABASES');
        console.log('Available Databases:', dbs.map(d => d.Database).join(', '));

        // Try creating a new database
        console.log('Testing create database...');
        await db.query('CREATE DATABASE IF NOT EXISTS test_write_db');
        console.log('Database creation SUCCESS.');
        await db.query('DROP DATABASE test_write_db');
        console.log('Database drop SUCCESS.');
    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        process.exit();
    }
}

checkVersion();
