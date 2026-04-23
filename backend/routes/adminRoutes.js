const express = require('express');
const router = express.Router();

// Import Middleware
const auth = require('../middleware/auth');

// Import Controllers
const adminController = require('../controllers/adminController');

/**
 * Rute khusus Admin Dashboard & Management
 */

// 1. Statistik Dashboard
router.get('/stats', auth, auth.isAdmin, adminController.getStats);

// 2. Manajemen User
router.get('/users', auth, auth.isAdmin, adminController.getTeamList);
router.post('/users', auth, auth.isAdmin, adminController.addAccount);
router.delete('/users/:id', auth, auth.isAdmin, adminController.deleteAccount);

// 3. Verifikasi Kampanye
router.get('/pending-campaigns', auth, auth.isAdmin, adminController.getPendingCampaigns);
router.post('/approve-campaign/:id', auth, auth.isAdmin, adminController.approveCampaign);

// 4. Verifikasi Donasi
router.get('/pending-donations', auth, auth.isAdmin, adminController.getPendingDonations);
router.post('/verify-donation/:id', auth, auth.isAdmin, adminController.verifyDonation);



module.exports = router;