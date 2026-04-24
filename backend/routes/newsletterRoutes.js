const express = require('express');
const router = express.Router();
const newsletterController = require('../controllers/newsletterController');
const { auth, adminOnly } = require('../middleware/auth');

router.post('/subscribe', newsletterController.subscribe);
router.get('/subscribers', auth, adminOnly, newsletterController.getSubscribers);

module.exports = router;
