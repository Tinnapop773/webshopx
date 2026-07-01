const mongoose = require('mongoose');

const siteConfigSchema = new mongoose.Schema({
  siteName: {
    type: String,
    default: 'WebShopX'
  },
  primaryColor: {
    type: String,
    default: '#3498db'
  },
  secondaryColor: {
    type: String,
    default: '#2ecc71'
  },
  bankApiKey: String,
  bankMerchantId: String,
  trueWalletApiKey: String,
  discordWebhookUrl: String,
  maintenanceMode: {
    type: Boolean,
    default: false
  },
  maintenanceMessage: String,
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model('SiteConfig', siteConfigSchema);
