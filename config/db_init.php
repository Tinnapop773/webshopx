<?php
// Database Configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'webshopx';

// Create connection
$conn = new mysqli($host, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Create database if not exists
$createDb = "CREATE DATABASE IF NOT EXISTS webshopx CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($createDb) === TRUE) {
    // Select database
    $conn->select_db($database);
} else {
    die("Error creating database: " . $conn->error);
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Bangkok');

// Create Tables
$tables = "
-- Users Table
CREATE TABLE IF NOT EXISTS users (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  product_name VARCHAR(255),
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Site Config Table
CREATE TABLE IF NOT EXISTS site_config (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

// Execute each table creation
$tableArray = explode(';', $tables);
foreach ($tableArray as $table) {
    $table = trim($table);
    if (!empty($table)) {
        if ($conn->query($table) === FALSE) {
            // Ignore table already exists errors
            if ($conn->errno !== 1050) {
                error_log("Error creating table: " . $conn->error);
            }
        }
    }
}

// Insert default config if not exists
$checkConfig = $conn->query("SELECT * FROM site_config LIMIT 1");
if ($checkConfig && $checkConfig->num_rows === 0) {
    $conn->query("INSERT INTO site_config (site_name, primary_color, secondary_color) VALUES ('WebShopX', '#3498db', '#2ecc71')");
}

// Insert sample products if not exists
$checkProducts = $conn->query("SELECT COUNT(*) as count FROM products");
if ($checkProducts) {
    $result = $checkProducts->fetch_assoc();
    if ($result['count'] === 0) {
        $conn->query("
            INSERT INTO products (name, description, price, image, stock, category) VALUES 
            ('สินค้าตัวอย่าง 1', 'นี่คือสินค้าตัวอย่าง 1 ที่มีคุณสมบัติดี', 99.99, 'https://via.placeholder.com/250x200?text=Product+1', 10, 'category1'),
            ('สินค้าตัวอย่าง 2', 'นี่คือสินค้าตัวอย่าง 2 ที่มีคุณสมบัติดี', 149.99, 'https://via.placeholder.com/250x200?text=Product+2', 15, 'category2'),
            ('สินค้าตัวอย่าง 3', 'นี่คือสินค้าตัวอย่าง 3 ที่มีคุณสมบัติดี', 199.99, 'https://via.placeholder.com/250x200?text=Product+3', 8, 'category1'),
            ('สินค้าตัวอย่าง 4', 'นี่คือสินค้าตัวอย่าง 4 ที่มีคุณสมบัติดี', 79.99, 'https://via.placeholder.com/250x200?text=Product+4', 20, 'category2')
        ");
    }
}
?>
