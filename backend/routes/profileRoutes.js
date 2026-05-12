const express = require('express');
const router = express.Router();
const profileController = require('../controllers/profileController');
const auth = require('../middleware/auth');

// Protected Profile Routes
router.get('/', auth, profileController.getProfile);
router.get('/stats', auth, profileController.getUserStats);
router.post('/update', auth, profileController.updateProfile);

module.exports = router;
