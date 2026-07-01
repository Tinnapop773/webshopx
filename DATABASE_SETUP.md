# WebShopX Database Setup for phpMyAdmin

## สำหรับใช้กับ XAMPP + phpMyAdmin

### ขั้นตอนการตั้งค่า Database MongoDB ด้วย MongoDB Compass

**Option 1: ใช้ MongoDB Atlas (Cloud)**
1. ไปที่ https://www.mongodb.com/cloud/atlas
2. สร้าง Account และ Free Cluster
3. Copy Connection String
4. ใส่ใน `.env` file: `MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/webshopx`

**Option 2: ใช้ MongoDB Local**
1. ดาวน์โหลด MongoDB Community https://www.mongodb.com/try/download/community
2. ติดตั้ง MongoDB
3. เปิด MongoDB Service
4. ใช้ MongoDB Compass เชื่อมต่อ `mongodb://localhost:27017`

### สำหรับ XAMPP (Apache + MySQL + phpMyAdmin)

หากต้องการใช้ MySQL แทน MongoDB:

**Database Schema (SQL)**

```sql
-- Users
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

-- Products
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

-- Orders
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

-- Order Items
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

-- Payments
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

-- Site Config
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
```

### วิธีใช้:

1. เปิด phpMyAdmin (http://localhost/phpmyadmin)
2. สร้าง Database ชื่อ `webshopx`
3. เลือก Database แล้วไปที่ SQL
4. Copy & Paste SQL Schema ข้างบน
5. Execute

## ตั้งค่า Backend ให้ใช้ MySQL แทน MongoDB

แปลง `.env`:
```
DB_TYPE=mysql
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=webshopx
DB_PORT=3306
```
