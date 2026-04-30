const mysql = require('mysql2');
require('dotenv').config();

// Membuat pool koneksi
const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,

});

// Pastikan zona waktu benar-benar terpasang di sesi setiap kali ada koneksi baru
pool.on('connection', function (connection) {
    connection.query("SET time_zone = '+08:00'");
});

const db = pool.promise();

// Tes koneksi database
pool.getConnection((err, connection) => {
    if (err) {
        console.error('❌ DATABASE CONNECTION FAILED:', err.message);
    } else {
        console.log('✅ DATABASE CONNECTED SUCCESSFULLY (WITA MODE)');
        connection.release();
    }
});

module.exports = db;