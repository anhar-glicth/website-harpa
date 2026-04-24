const express = require('express');
const router = express.Router();
const volunteerController = require('../controllers/volunteerController');
const { auth, adminOnly } = require('../middleware/auth');

router.post('/register', volunteerController.registerVolunteer);
router.get('/', auth, adminOnly, volunteerController.getVolunteers);

module.exports = router;
