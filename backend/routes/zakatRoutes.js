const express = require('express');
const router = express.Router();
const zakatController = require('../controllers/zakatController');
const auth = require('../middleware/auth');

// User actions (must login)
router.post('/', auth, zakatController.createZakat);
router.get('/my', auth, zakatController.getUserZakats);

// Admin actions
router.get('/pending', auth, auth.isAdmin, zakatController.getPendingZakats);
router.post('/verify/:id', auth, auth.isAdmin, zakatController.verifyZakat);

module.exports = router;
