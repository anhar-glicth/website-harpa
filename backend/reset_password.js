const db = require('./config/db');
const bcrypt = require('bcryptjs');

async function reset() {
  const hashedPassword = await bcrypt.hash('password123', 10);
  await db.query('UPDATE users SET password = ? WHERE email = ?', [hashedPassword, 'admin@humanity.org']);
  console.log('Password updated for admin@humanity.org');
  process.exit();
}
reset();
