const axios = require('axios');

const sendDiscordNotification = async (type, data) => {
  const webhookUrl = process.env.DISCORD_WEBHOOK_URL;
  
  if (!webhookUrl) {
    console.log('Discord webhook not configured');
    return;
  }

  let color = 3498db;
  let message = '';

  switch(type) {
    case 'register':
      color = 3447003; // Blue
      message = `🎉 **สมัครสมาชิกใหม่**\n**Username:** ${data.username}\n**Email:** ${data.email}\n**เวลา:** ${new Date().toLocaleString('th-TH')}`;
      break;
    
    case 'deposit':
      color = 65280; // Green
      message = `💰 **เติมเงินเข้ากระเป๋า**\n**ผู้ใช้:** ${data.username}\n**จำนวนเงิน:** ฿${data.amount}\n**ยอดคงเหลือ:** ฿${data.balance}\n**เวลา:** ${new Date().toLocaleString('th-TH')}`;
      break;
    
    case 'order':
      color = 15105570; // Yellow
      message = `📦 **มีการซื้อสินค้า**\n**ผู้ใช้:** ${data.username}\n**เลขคำสั่ง:** ${data.orderNumber}\n**จำนวนเงิน:** ฿${data.totalAmount}\n**สินค้า:** ${data.itemCount} รายการ\n**เวลา:** ${new Date().toLocaleString('th-TH')}`;
      break;
    
    case 'payment':
      color = 3066993; // Purple
      message = `✅ **ยืนยันการชำระเงิน**\n**ผู้ใช้:** ${data.username}\n**เลขคำสั่ง:** ${data.orderNumber}\n**จำนวนเงิน:** ฿${data.amount}\n**วิธีชำระ:** ${data.paymentMethod}\n**เวลา:** ${new Date().toLocaleString('th-TH')}`;
      break;
  }

  try {
    await axios.post(webhookUrl, {
      embeds: [{
        color: color,
        description: message,
        timestamp: new Date().toISOString()
      }]
    });
    console.log(`✅ Discord notification sent for ${type}`);
  } catch (error) {
    console.error(`❌ Failed to send Discord notification: ${error.message}`);
  }
};

module.exports = { sendDiscordNotification };
