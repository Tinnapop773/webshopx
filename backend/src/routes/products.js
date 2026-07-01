const express = require('express');
const router = express.Router();
const productController = require('../controllers/productController');
const { auth, adminAuth, adminPinAuth } = require('../middleware/auth');
const { validateProduct } = require('../middleware/validation');

// Public routes
router.get('/', productController.getAllProducts);
router.get('/:id', productController.getProductById);

// Admin routes
router.post('/', auth, adminAuth, adminPinAuth, validateProduct, productController.createProduct);
router.put('/:id', auth, adminAuth, adminPinAuth, productController.updateProduct);
router.put('/:id/stock', auth, adminAuth, adminPinAuth, productController.updateStock);
router.delete('/:id', auth, adminAuth, adminPinAuth, productController.deleteProduct);

module.exports = router;
