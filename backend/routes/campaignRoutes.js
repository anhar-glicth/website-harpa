const express = require('express');
const router = express.Router();

// Import Controller
const campaignController = require('../controllers/campaignController');

// Import Middleware
// Karena kita sudah mengubah auth.js dan validate.js menggunakan module.exports,
// maka variabel 'auth' dan 'validate' di sini sudah langsung berupa FUNGSI.
const auth = require('../middleware/auth');
const validate = require('../middleware/validate');

/**
 * CAMPAIGNS ROUTES
 */

// Staff: Melihat kampanye milik sendiri
router.get('/my-campaigns', auth, auth.isStaff, campaignController.getMyCampaigns);

// Public: Melihat daftar kampanye
router.get('/', campaignController.getAllCampaigns);
router.get('/:id', campaignController.getCampaignById);

// Private: Staff Only (Admin & Field Worker)
router.post('/', auth, auth.isStaff, validate, campaignController.createCampaign);
router.put('/:id', auth, auth.isStaff, validate, campaignController.updateCampaign);
router.delete('/:id', auth, auth.isStaff, campaignController.deleteCampaign);

module.exports = router;