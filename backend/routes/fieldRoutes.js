const express = require('express');
const router = express.Router();
const fieldController = require('../controllers/fieldController');
const auth = require('../middleware/auth');

// To Do List
router.get('/todos', auth, fieldController.getTodos);
router.post('/todos', auth, fieldController.addTodo);
router.put('/todos/:id', auth, fieldController.toggleTodo);
router.delete('/todos/:id', auth, fieldController.deleteTodo);

// Attendance
router.get('/attendance', auth, fieldController.getAttendanceStatus);
router.get('/attendance/recap', auth, fieldController.getAttendanceRecap);
router.post('/attendance', auth, fieldController.submitAttendance);

// Performance Stats
router.get('/stats', auth, fieldController.getFieldStats);

// DEBUG: Cek timezone server
router.get('/debug-time', fieldController.debugTime);

// Campaign Updates (Impact Stories)
router.get('/updates/:campaignId', fieldController.getCampaignUpdates);
router.post('/updates', auth, fieldController.addCampaignUpdate);

// Task Completion with results
router.post('/todos/:id/complete', auth, fieldController.completeTask);

module.exports = router;
