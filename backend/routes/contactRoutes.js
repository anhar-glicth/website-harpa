const express = require('express');
const router = express.Router();
const contactController = require('../controllers/contactController');
const auth = require('../middleware/auth');

router.post('/', contactController.sendMessage);
router.get('/', auth, auth.isAdmin, contactController.getMessages);

module.exports = router;
