const db = require('../config/db');

async function testMyISAM() {
    try {
        console.log('Creating MyISAM table in test_db_3...');
        await db.query('CREATE DATABASE test_db_3');
        await db.query('USE test_db_3');
        await db.query('CREATE TABLE t1 (id INT) ENGINE=MyISAM');
        console.log('Table created.');
        await db.query('INSERT INTO t1 VALUES (1)');
        console.log('Insert SUCCESS.');
        await db.query('DROP DATABASE test_db_3');
        console.log('MyISAM is WRITABLE.');
    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        process.exit();
    }
}

testMyISAM();
