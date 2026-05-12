const express = require('express');
const router = express.Router();
const multer = require('multer');
const upload = multer({ storage: multer.memoryStorage() });

// Import Middleware
const auth = require('../middleware/auth');

// Import Controllers
const adminController = require('../controllers/adminController');
const masterController = require('../controllers/adminMasterController');
const donorDashboardController = require('../controllers/donorDashboardController');
const rfmDashboardController = require('../controllers/rfmDashboardController');
const targetDashboardController = require('../controllers/targetDashboardController');
const prospectDashboardController = require('../controllers/prospectDashboardController');
const transactionDashboardController = require('../controllers/transactionDashboardController');
const distributionDashboardController = require('../controllers/distributionDashboardController');

/**
 * Rute khusus Admin Dashboard & Management
 */

// 1. Statistik Dashboard
router.get('/stats', auth, adminController.getStats);
router.get('/all-dashboard-data', auth, adminController.getAllDashboardData);
router.get('/dashboard-stats', auth, adminController.getStats); // Alias tambahan

// 2. Manajemen User
router.get('/users', auth, auth.isAdmin, adminController.getTeamList);
router.post('/users', auth, auth.isAdmin, adminController.addAccount);
router.put('/users/:id', auth, auth.isAdmin, adminController.updateAccount);
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
router.post('/master/:module/bulk', auth, upload.single('file'), masterController.bulkImport);
router.put('/master/:module/:id', auth, auth.isAdmin, masterController.updateData);
router.delete('/master/:module/:id', auth, auth.isAdmin, masterController.deleteData);

// 6. Donor Dashboard Analytics
router.get('/donor-dashboard', auth, auth.isAdmin, donorDashboardController.getDonorDashboardData);
router.get('/rfm-dashboard', auth, auth.isAdmin, rfmDashboardController.getRFMDashboardData);
router.get('/target-dashboard', auth, auth.isAdmin, targetDashboardController.getTargetDashboardData);
router.post('/save-target', auth, auth.isAdmin, targetDashboardController.saveTarget);
router.get('/prospect-dashboard', auth, auth.isAdmin, prospectDashboardController.getProspectDashboardData);
router.get('/transaction-dashboard', auth, auth.isAdmin, transactionDashboardController.getTransactionDashboardData);
router.get('/distribution-dashboard', auth, auth.isAdmin, distributionDashboardController.getDistributionDashboardData);

// 6. Monitoring & Tugas Pegawai
router.get('/attendances', auth, auth.isAdmin, adminController.getAllAttendances);
router.get('/worker-performance', auth, auth.isAdmin, adminController.getWorkerPerformance);
router.get('/tasks', auth, auth.isAdmin, adminController.getAllTasks);
router.post('/tasks', auth, auth.isAdmin, adminController.assignTask);
router.put('/tasks/:id', auth, auth.isAdmin, adminController.updateTask);
router.delete('/tasks/:id', auth, auth.isAdmin, adminController.deleteTask);

// 7. Manajemen Penyaluran (Distributions)
router.get('/distributions', auth, auth.isAdmin, masterController.getAllDistributions);
router.delete('/distributions/:id', auth, auth.isAdmin, masterController.deleteDistribution);

module.exports = router;