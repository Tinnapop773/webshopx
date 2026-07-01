const jwt = require('jsonwebtoken');
const User = require('../models/User');

const auth = async (req, res, next) => {
  try {
    const token = req.headers.authorization?.split(' ')[1];
    
    if (!token) {
      return res.status(401).json({
        success: false,
        message: 'No authorization token provided'
      });
    }

    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'your_jwt_secret_key');
    const user = await User.findById(decoded.id);

    if (!user) {
      return res.status(401).json({
        success: false,
        message: 'User not found'
      });
    }

    req.user = user;
    next();
  } catch (error) {
    res.status(401).json({
      success: false,
      message: 'Invalid token',
      error: error.message
    });
  }
};

const adminAuth = async (req, res, next) => {
  try {
    await auth(req, res, () => {});
    
    if (!req.user || req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Admin access required'
      });
    }

    next();
  } catch (error) {
    res.status(403).json({
      success: false,
      message: 'Forbidden',
      error: error.message
    });
  }
};

const adminPinAuth = async (req, res, next) => {
  try {
    const { pin } = req.body;
    
    if (!pin) {
      return res.status(400).json({
        success: false,
        message: 'PIN is required'
      });
    }

    const user = await User.findById(req.user._id).select('+adminPin');
    
    if (user.role !== 'admin' || !user.verifyAdminPin(pin)) {
      return res.status(403).json({
        success: false,
        message: 'Invalid admin PIN'
      });
    }

    next();
  } catch (error) {
    res.status(403).json({
      success: false,
      message: 'PIN verification failed',
      error: error.message
    });
  }
};

module.exports = { auth, adminAuth, adminPinAuth };
