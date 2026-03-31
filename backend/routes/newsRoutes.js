const express = require('express');
const router = express.Router();
const newsController = require('../controllers/newsController');
const auth = require('../middleware/auth');

/**
 * --- Rute Berita (News Routes) ---
 */

// 1. Publik (Ambil semua berita)
router.get('/', newsController.getAllNews);

// 2. Dashboard Petugas/Admin (Ambil berita milik sendiri)
router.get('/my-news', auth, auth.isStaff, newsController.getMyNews);

// 3. Tambah Berita Baru (Staff & Admin)
router.post('/', auth, auth.isStaff, newsController.createNews);

// 4. Hapus Berita
router.post('/delete/:id', auth, auth.isStaff, newsController.deleteNews);
router.delete('/:id', auth, auth.isStaff, newsController.deleteNews);

module.exports = router;
