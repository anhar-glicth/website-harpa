const express = require('express');
const router = express.Router();

// Import Middleware
const auth = require('../middleware/auth');

// Import Controllers
const adminController = require('../controllers/adminController');
const masterController = require('../controllers/adminMasterController');

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

// 5. Generic Master Data Management
router.get('/master/:module', auth, auth.isAdmin, masterController.getData);
router.get('/master/stats/:module', auth, auth.isAdmin, masterController.getStats);
router.post('/master/:module', auth, auth.isAdmin, masterController.createData);
router.put('/master/:module/:id', auth, auth.isAdmin, masterController.updateData);
router.delete('/master/:module/:id', auth, auth.isAdmin, masterController.deleteData);

// 6. Monitoring & Tugas Pegawai
router.get('/attendances', auth, auth.isAdmin, adminController.getAllAttendances);
router.get('/worker-performance', auth, auth.isAdmin, adminController.getWorkerPerformance);
router.get('/tasks', auth, auth.isAdmin, adminController.getAllTasks);
router.post('/tasks', auth, auth.isAdmin, adminController.assignTask);

module.exports = router;