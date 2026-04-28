const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const db = require('../config/db'); 

router.post('/register', authController.register);
router.post('/login', authController.login);

// Jalur tes koneksi database
router.get('/test-db', async (req, res) => {
    try {
        const [rows] = await db.query('SELECT 1 + 1 AS result');
        res.json({ 
            success: true, 
            message: 'Database Berhasil Terkoneksi!', 
            data: rows 
        });
    } catch (err) {
        res.status(500).json({ 
            success: false, 
            message: 'Database GAGAL Terkoneksi', 
            error: err.message 
        });
    }
});

module.exports = router;