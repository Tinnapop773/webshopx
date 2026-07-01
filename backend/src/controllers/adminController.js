const User = require('../models/User');
const Order = require('../models/Order');
const SiteConfig = require('../models/SiteConfig');

// Get dashboard data
exports.getDashboard = async (req, res) => {
  try {
    const totalUsers = await User.countDocuments();
    const totalOrders = await Order.countDocuments();
    const totalRevenue = await Order.aggregate([
      { $match: { status: 'completed' } },
      { $group: { _id: null, total: { $sum: '$totalAmount' } } }
    ]);

    const recentOrders = await Order.find()
      .populate('userId', 'username email')
      .sort({ createdAt: -1 })
      .limit(10);

    res.json({
      success: true,
      dashboard: {
        totalUsers,
        totalOrders,
        totalRevenue: totalRevenue[0]?.total || 0,
        recentOrders
      }
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: error.message
    });
  }
};

// Get monthly sales
exports.getMonthlySales = async (req, res) => {
  try {
    const monthlySales = await Order.aggregate([
      { $match: { status: 'completed' } },
      {
        $group: {
          _id: {
            year: { $year: '$createdAt' },
            month: { $month: '$createdAt' }
          },
          total: { $sum: '$totalAmount' },
          count: { $sum: 1 }
        }
      },
      { $sort: { '_id.year': -1, '_id.month': -1 } }
    ]);

    res.json({
      success: true,
      monthlySales
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: error.message
    });
  }
};

// Get yearly sales
exports.getYearlySales = async (req, res) => {
  try {
    const yearlySales = await Order.aggregate([
      { $match: { status: 'completed' } },
      {
        $group: {
          _id: { $year: '$createdAt' },
          total: { $sum: '$totalAmount' },
          count: { $sum: 1 }
        }
      },
      { $sort: { _id: -1 } }
    ]);

    res.json({
      success: true,
      yearlySales
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: error.message
    });
  }
};

// Get site config
exports.getConfig = async (req, res) => {
  try {
    let config = await SiteConfig.findOne();
    
    if (!config) {
      config = new SiteConfig();
      await config.save();
    }

    res.json({
      success: true,
      config
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: error.message
    });
  }
};

// Update site config
exports.updateConfig = async (req, res) => {
  try {
    const { siteName, primaryColor, secondaryColor, bankApiKey, bankMerchantId, trueWalletApiKey, discordWebhookUrl } = req.body;

    let config = await SiteConfig.findOne();
    
    if (!config) {
      config = new SiteConfig();
    }

    if (siteName) config.siteName = siteName;
    if (primaryColor) config.primaryColor = primaryColor;
    if (secondaryColor) config.secondaryColor = secondaryColor;
    if (bankApiKey) config.bankApiKey = bankApiKey;
    if (bankMerchantId) config.bankMerchantId = bankMerchantId;
    if (trueWalletApiKey) config.trueWalletApiKey = trueWalletApiKey;
    if (discordWebhookUrl) config.discordWebhookUrl = discordWebhookUrl;
    
    config.updatedAt = Date.now();
    await config.save();

    res.json({
      success: true,
      message: 'Configuration updated successfully',
      config
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: error.message
    });
  }
};
