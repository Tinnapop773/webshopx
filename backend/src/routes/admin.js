const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const { auth, adminAuth, adminPinAuth } = require('../middleware/auth');

router.get('/dashboard', auth, adminAuth, adminPinAuth, adminController.getDashboard);
router.get('/sales/monthly', auth, adminAuth, adminPinAuth, adminController.getMonthlySales);
router.get('/sales/yearly', auth, adminAuth, adminPinAuth, adminController.getYearlySales);
router.get('/config', auth, adminAuth, adminController.getConfig);
router.put('/config', auth, adminAuth, adminPinAuth, adminController.updateConfig);

module.exports = router;
