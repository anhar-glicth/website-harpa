const db = require('../config/db');

async function testNewDb() {
    try {
        console.log('Creating new database test_db_2...');
        await db.query('CREATE DATABASE test_db_2');
        await db.query('USE test_db_2');
        console.log('Creating InnoDB table...');
        await db.query('CREATE TABLE t1 (id INT) ENGINE=InnoDB');
        console.log('Table created.');
        await db.query('INSERT INTO t1 VALUES (1)');
        console.log('Insert SUCCESS.');
        await db.query('DROP DATABASE test_db_2');
        console.log('Test finished.');
    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        process.exit();
    }
}

testNewDb();
