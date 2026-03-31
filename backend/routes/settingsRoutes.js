const express = require('express');
const router = express.Router();
const settingsController = require('../controllers/settingsController');
const auth = require('../middleware/auth');

// Public
router.get('/', settingsController.getSettings);

// Admin
router.post('/', auth, auth.isAdmin, settingsController.updateSettings);

module.exports = router;
