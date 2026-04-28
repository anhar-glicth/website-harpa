const mysql = require('mysql2');
require('dotenv').config();

// Membuat pool koneksi
const db = mysql.createPool({
    host: process.env.DB_HOST || '127.0.0.1',
    user: process.env.DB_USER || 'u314775488_abadi',
    password: process.env.DB_PASSWORD || 'Abadi_1234',
    database: process.env.DB_NAME || 'u314775488_abadi',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    family: 4  // Paksa IPv4 agar tidak terhubung via ::1 (IPv6)
});

// Tes koneksi database saat start
db.getConnection((err, connection) => {
    if (err) {
        console.error('❌ DATABASE CONNECTION FAILED:', err.message);
        console.error('Check your .env credentials!');
    } else {
        console.log('✅ DATABASE CONNECTED SUCCESSFULLY');
        connection.release();
    }
});

module.exports = db.promise();