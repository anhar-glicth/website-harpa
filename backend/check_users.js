const db = require('./config/db');
async function check() {
  const [rows] = await db.query('SELECT id, email, role FROM users');
  console.log(JSON.stringify(rows, null, 2));
  process.exit();
}
check();
