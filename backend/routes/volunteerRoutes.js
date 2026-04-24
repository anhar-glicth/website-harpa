const express = require('express');
const router = express.Router();
const volunteerController = require('../controllers/volunteerController');
const auth = require('../middleware/auth');

router.post('/register', volunteerController.registerVolunteer);
router.get('/', auth, auth.isAdmin, volunteerController.getVolunteers);

module.exports = router;
