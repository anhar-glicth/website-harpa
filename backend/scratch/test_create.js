const db = require('../config/db');

async function testCreate() {
    try {
        console.log('Testing create table in humanity_db...');
        await db.query('CREATE TABLE IF NOT EXISTS test_write (id INT PRIMARY KEY)');
        console.log('Table created successfully.');
        await db.query('INSERT INTO test_write (id) VALUES (1)');
        console.log('Insert successful.');
        await db.query('DROP TABLE test_write');
        console.log('Drop successful. Database is fully WRITABLE.');
    } catch (error) {
        console.error('Database write error:', error.message);
    } finally {
        process.exit();
    }
}

testCreate();
