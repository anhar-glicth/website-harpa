const db = require('../config/db');

async function checkInnoDB() {
    try {
        const [[row]] = await db.query('SHOW VARIABLES LIKE "innodb_read_only"');
        console.log('innodb_read_only:', row ? row.Value : 'N/A');
    } catch (error) {
        console.error('Error checking InnoDB:', error.message);
    } finally {
        process.exit();
    }
}

checkInnoDB();
