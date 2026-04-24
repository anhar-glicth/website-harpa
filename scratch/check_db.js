const db = require('../backend/config/db');
async function checkTable() {
    try {
        const [rows] = await db.query('DESCRIBE users');
        console.log(JSON.stringify(rows, null, 2));
        process.exit(0);
    } catch (e) {
        console.error(e);
        process.exit(1);
    }
}
checkTable();
