const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const { auth, adminAuth, adminPinAuth } = require('../middleware/auth');

// User routes
router.get('/profile', auth, userController.getProfile);
router.put('/profile', auth, userController.updateProfile);
router.put('/password', auth, userController.changePassword);

// Admin routes
router.get('/', auth, adminAuth, userController.getAllUsers);
router.put('/:id', auth, adminAuth, adminPinAuth, userController.updateUser);
router.delete('/:id', auth, adminAuth, adminPinAuth, userController.deleteUser);
router.post('/deposit', auth, adminAuth, adminPinAuth, userController.addBalance);

module.exports = router;
