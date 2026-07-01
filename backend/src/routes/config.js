const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');

// Public route to get site config
router.get('/', adminController.getConfig);

module.exports = router;
