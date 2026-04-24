const express = require('express');
const router = express.Router();
const prayerController = require('../controllers/prayerController');
const authMiddleware = require('../middleware/authMiddleware');

router.get('/', prayerController.getPrayers);
router.post('/', authMiddleware, prayerController.submitPrayer);

module.exports = router;
