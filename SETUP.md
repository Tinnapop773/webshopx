# WebShopX - ขั้นตอนการติดตั้ง

## 📋 ความต้องการ

- Node.js v16+
- MongoDB หรือ MySQL
- npm หรือ yarn

## 🚀 การติดตั้ง

### 1. Clone Repository
```bash
git clone https://github.com/Tinnapop773/webshopx.git
cd webshopx
```

### 2. Backend Setup
```bash
cd backend
npm install
cp .env.example .env
```

แก้ไข `.env`:
```env
MONGODB_URI=mongodb://localhost:27017/webshopx
JWT_SECRET=your_super_secret_key
ADMIN_PIN=1234
PORT=5000
DISCORD_WEBHOOK_URL=your_discord_webhook_url
```

รัน Backend:
```bash
npm run dev
```

### 3. Frontend Setup
```bash
cd ../frontend
npm install
cp .env.example .env.local
```

แก้ไข `.env.local`:
```env
NEXT_PUBLIC_API_URL=http://localhost:5000/api
NEXT_PUBLIC_SITE_NAME=WebShopX
```

รัน Frontend:
```bash
npm run dev
```

### 4. เข้าใช้งาน

- Frontend: http://localhost:3000
- Backend API: http://localhost:5000/api
- phpMyAdmin: http://localhost/phpmyadmin (ถ้าใช้ XAMPP)

## 📊 Default Admin Account

- Email: `admin@webshopx.com`
- Password: `admin123`
- Admin PIN: `1234`

## 🔧 Configuration

หลังเข้า Admin Panel สามารถเปลี่ยนได้:
- ชื่อเว็บไซต์
- สีหลัก และสีรอง
- API Keys สำหรับ Payment Gateway
- Discord Webhook

## 📚 API Endpoints

ดูรายละเอียดใน `backend/README.md`

## 🎨 UI Preview

### หน้าหลัก
- Header ที่แสดงชื่อเว็บ สี และการค้นหา
- Banner ตามสีที่ตั้ง
- Grid สินค้า 4 คอลัมน์
- Footer ที่มีลิงค์ต่างๆ

### หน้าเข้าสู่ระบบ
- Gradient Background ตามสี Primary
- Form ตรงกลาง
- Link ไป Register

### หน้า Register
- Gradient Background สีเขียว
- Form สมัครสมาชิก
- Link ไป Login

### Admin Dashboard (จะเพิ่มเติมในภายหลัง)
- Dashboard ยอดขาย
- ตารางจัดการสมาชิก
- ตารางจัดการสินค้า
- การตั้งค่าเว็บ
- Chart รายประมาณเดือน/ปี

## 📝 License

MIT
