const express = require('express');
const router = express.Router();

// Import Controller
const donationController = require('../controllers/donationController');

// Import Middleware
// Karena auth.js dan validate.js menggunakan module.exports = fungsi,
// maka variabel di bawah ini sudah langsung berupa FUNGSI.
const auth = require('../middleware/auth');
const validate = require('../middleware/validate');

/**
 * DONATIONS ROUTES
 */

// Public: Orang bisa mengirim donasi (Opsional: simpan user_id jika login)
router.post('/', auth.optional, validate, donationController.createDonation);

// Private: User melihat riwayat donasi mereka sendiri
router.get('/my-donations', auth, donationController.getMyDonations);

// Private: Admin bisa melihat riwayat semua donasi
router.get('/', auth, auth.isAdmin, donationController.getAllDonations);

// Private: Detail donasi untuk Kwitansi
router.get('/:id', auth, donationController.getDonationById);

module.exports = router;