const db = require('../backend/config/db');
async function checkColumns() {
    try {
        const [rows] = await db.query('DESCRIBE users');
        console.log(JSON.stringify(rows, null, 2));
    } catch (e) {
        console.error(e);
    }
    process.exit();
}
checkColumns();
