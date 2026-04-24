const express = require('express');
const router = express.Router();
const prayerController = require('../controllers/prayerController');
const auth = require('../middleware/auth');

router.get('/', prayerController.getPrayers);
router.post('/', auth, prayerController.submitPrayer);
router.post('/:id/aamiin', prayerController.likePrayer);

module.exports = router;
