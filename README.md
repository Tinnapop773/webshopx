# WebShopX - E-Commerce Platform

ระบบจำหน่ายสินค้าออนไลน์แบบครบถ้วน พร้อมระบบจัดการสมาชิก การชำระเงิน และ Dashboard สำหรับผู้บริหาร

## 🚀 Features

### Frontend
- ✅ หน้าแสดงสินค้า
- ✅ ระบบสมัครสมาชิก/เข้าสู่ระบบ
- ✅ ตระกร้าสินค้า
- ✅ ชำระเงินผ่าน Bank Slip & True Wallet
- ✅ Dashboard ผู้ใช้

### Backend
- ✅ Authentication & Authorization (JWT + PIN Admin)
- ✅ User Management (CRUD, Password Change, Deposit)
- ✅ Product Management
- ✅ Payment Integration (Bank API, True Wallet)
- ✅ Order Management
- ✅ Admin Dashboard (Sales, Monthly/Yearly Reports)
- ✅ Discord Webhook Notifications
- ✅ Configuration Management (Site Name, Colors, API Keys)

## 📦 Tech Stack

### Backend
- Node.js + Express.js
- MongoDB
- JWT Authentication
- Discord.js Webhooks

### Frontend
- Next.js 14
- React 18
- TailwindCSS
- Axios

## 📁 Project Structure

```
webshopx/
├── backend/               # Node.js Backend
│   ├── src/
│   │   ├── models/       # MongoDB Schemas
│   │   ├── routes/       # API Routes
│   │   ├── controllers/  # Business Logic
│   │   ├── middleware/   # Auth, Validation
│   │   ├── utils/        # Helper Functions
│   │   ├── config/       # Configuration
│   │   └── app.js        # Express App
│   ├── .env.example
│   └── package.json
│
├── frontend/             # Next.js Frontend
│   ├── app/
│   │   ├── page.tsx      # Home Page
│   │   ├── products/     # Products Pages
│   │   ├── auth/         # Auth Pages
│   │   ├── admin/        # Admin Pages
│   │   └── api/          # API Routes (if needed)
│   ├── components/
│   ├── lib/
│   ├── public/
│   └── package.json
│
└── docs/                 # Documentation
```

## 🔧 Installation & Setup

ดูรายละเอียดใน `backend/README.md` และ `frontend/README.md`

## 📝 License

MIT
