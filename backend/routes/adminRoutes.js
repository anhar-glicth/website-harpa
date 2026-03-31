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
router.get('/dashboard-stats', auth, auth.isAdmin, adminController.getStats);

// 2. Senarai Pengguna (Team & Donatur)
router.get('/management-list', auth, auth.isAdmin, adminController.getTeamList);
router.get('/donors-list', auth, auth.isAdmin, adminController.getDonorList);

// 3. Pengurusan Akaun (Add & Delete)
router.post('/add-account', auth, auth.isAdmin, adminController.addAccount);
router.delete('/delete-account/:id', auth, auth.isAdmin, adminController.deleteAccount);

// 4. Verifikasi Kampanye & Donasi (Fitur Baru)
router.get('/pending-campaigns', auth, auth.isAdmin, adminController.getPendingCampaigns);
router.put('/approve-campaign/:id', auth, auth.isAdmin, adminController.approveCampaign);
router.get('/pending-donations', auth, auth.isAdmin, adminController.getPendingDonations);
router.put('/verify-donation/:id', auth, auth.isAdmin, adminController.verifyDonation);

module.exports = router;