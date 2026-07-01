# WebShopX - PHP Version

## 🚀 ติดตั้งและใช้งาน

### ขั้นตอนที่ 1: ติดตั้ง XAMPP
1. ดาวน์โหลด XAMPP จาก https://www.apachefriends.org/
2. ติดตั้งและเลือก MySQL + Apache
3. เปิด XAMPP Control Panel
4. คลิก Start ทั้ง Apache และ MySQL

### ขั้นตอนที่ 2: สร้าง Database
1. เปิด http://localhost/phpmyadmin
2. คลิก "Databases" → "Create database"
3. ตั้งชื่อ `webshopx` → Create
4. นำ SQL Code ด้านล่างไปวาง

### ขั้นตอนที่ 3: นำโค้ดไปไว้ใน htdocs
```
C:\xampp\htdocs\webshopx\
```

### ขั้นตอนที่ 4: เข้าใช้งาน
- Website: http://localhost/webshopx/
- phpMyAdmin: http://localhost/phpmyadmin

---

## 📊 SQL Database Schema

```sql
CREATE DATABASE webshopx CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE webshopx;

-- Users Table
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  balance DECIMAL(10,2) DEFAULT 0,
  role ENUM('user', 'admin') DEFAULT 'user',
  admin_pin VARCHAR(10),
  full_name VARCHAR(255),
  phone VARCHAR(20),
  address TEXT,
  status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  min_price DECIMAL(10,2) DEFAULT 0,
  max_price DECIMAL(10,2),
  image VARCHAR(500) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  stock_type ENUM('daily', 'permanent') DEFAULT 'permanent',
  category VARCHAR(100),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_number VARCHAR(100) UNIQUE NOT NULL,
  user_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'paid', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
  payment_method ENUM('bank_slip', 'truewallet', 'wallet') NOT NULL,
  payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  transaction_id VARCHAR(255),
  shipping_address TEXT,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  product_name VARCHAR(255),
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Payments Table
CREATE TABLE payments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  order_id INT,
  amount DECIMAL(10,2) NOT NULL,
  payment_method ENUM('bank_slip', 'truewallet', 'wallet') NOT NULL,
  status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  transaction_id VARCHAR(255),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
);

-- Site Config Table
CREATE TABLE site_config (
  id INT PRIMARY KEY AUTO_INCREMENT,
  site_name VARCHAR(255) DEFAULT 'WebShopX',
  primary_color VARCHAR(7) DEFAULT '#3498db',
  secondary_color VARCHAR(7) DEFAULT '#2ecc71',
  bank_api_key TEXT,
  bank_merchant_id VARCHAR(255),
  truewallet_api_key TEXT,
  discord_webhook_url TEXT,
  maintenance_mode BOOLEAN DEFAULT FALSE,
  maintenance_message TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default config
INSERT INTO site_config (site_name, primary_color, secondary_color) VALUES ('WebShopX', '#3498db', '#2ecc71');
```

---

## 🎨 UI Features

### 🏠 Home Page
- Header พร้อม Search Bar
- Hero Banner
- Product Grid 4 columns
- Footer
- Responsive Design

### 🔐 Login Page
- Email input
- Password input
- Login button
- Link to Register

### 📝 Register Page
- Username input
- Full Name input
- Email input
- Password input
- Register button
- Link to Login

### 👤 Admin Features (จะเพิ่มในส่วนถัดไป)
- Dashboard
- User Management
- Product Management
- Order Management
- Site Configuration
- Discord Webhook

---

## 📁 Project Structure

```
webshopx/
├── config/
│   └── db.php              # Database connection
├── classes/
│   ├── Auth.php            # Authentication
│   ├── Product.php         # Product operations
│   ├── Order.php           # Order operations
│   ├── User.php            # User operations
│   └── Admin.php           # Admin operations
├── api/
│   ├── auth.php            # Auth endpoints
│   └── product.php         # Product endpoints
├── pages/
│   ├── login.php           # Login page
│   ├── register.php        # Register page
│   ├── profile.php         # User profile
│   ├── orders.php          # User orders
│   └── cart.php            # Shopping cart
├── admin/
│   ├── dashboard.php       # Admin dashboard
│   ├── users.php           # User management
│   ├── products.php        # Product management
│   ├── orders.php          # Order management
│   └── settings.php        # Site configuration
└── index.php               # Home page
```

---

## 🔑 Default Admin Account

ยังไม่มี Admin Account ให้สมัครสมาชิกแล้วไปแก้ Database ให้เป็น admin ผ่าน phpMyAdmin:

1. เข้า phpMyAdmin
2. ไปที่ Database `webshopx` → Table `users`
3. หา user ที่ต้องการให้เป็น admin
4. แก้ Column `role` เป็น `admin`

---

## 🚀 ใช้งาน

1. **สมัครสมาชิก** → ไปที่ Register
2. **เข้าสู่ระบบ** → ไปที่ Login
3. **ดูสินค้า** → Home page
4. **ซื้อสินค้า** → คลิก "ซื้อเลย"
5. **จัดการเก็บ** → Admin Panel (จะเพิ่มเร็ว ๆ นี้)

---

## 📞 Support

หากมีปัญหา:
1. ตรวจสอบว่า XAMPP กำลังรัน
2. ตรวจสอบว่า Database ถูกสร้างแล้ว
3. ตรวจสอบ Connection String ใน `config/db.php`

✨ **พร้อมใช้งานแล้ว!**
