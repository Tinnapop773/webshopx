<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebShopX - ร้านค้าออนไลน์</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        header {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 1rem 0;
            border-bottom: 3px solid #3498db;
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        nav h1 {
            font-size: 2rem;
            color: #3498db;
        }

        nav .links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        nav a, nav button {
            text-decoration: none;
            color: #333;
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1rem;
        }

        nav .btn-primary {
            background: #3498db;
            color: white;
            border-radius: 5px;
            padding: 0.7rem 1.5rem;
        }

        nav .btn-primary:hover {
            background: #2980b9;
        }

        .hero {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .products-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s;
        }

        .product-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 1rem;
        }

        .product-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #3498db;
        }

        .btn-add {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            background: #27ae60;
        }

        footer {
            background: #333;
            color: white;
            padding: 2rem;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
        }

        .footer-section p, .footer-section a {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            text-decoration: none;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #555;
            padding-top: 2rem;
            text-align: center;
            color: #aaa;
        }

        .loading {
            text-align: center;
            padding: 2rem;
        }

        .no-products {
            text-align: center;
            padding: 2rem;
            color: #999;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
            }

            nav .links {
                width: 100%;
                justify-content: center;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }

            .hero h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once 'config/db.php';
    require_once 'classes/Product.php';
    require_once 'classes/Admin.php';

    $product = new Product($conn);
    $admin = new Admin($conn);
    $config = $admin->getConfig();

    $page = $_GET['page'] ?? 1;
    $search = $_GET['search'] ?? '';
    $result = $product->getAllProducts($page, 12, $search);
    $products = $result['products'];
    ?>

    <header>
        <nav>
            <h1><?php echo htmlspecialchars($config['site_name'] ?? 'WebShopX'); ?></h1>
            <div class="links">
                <input type="text" placeholder="🔍 ค้นหาสินค้า..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; width: 200px;">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="pages/cart.php">🛒 ตระกร้า</a>
                    <a href="pages/orders.php">📦 คำสั่งซื้อ</a>
                    <a href="pages/profile.php">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="api/auth.php?action=logout" class="btn-primary">ออกจากระบบ</a>
                <?php else: ?>
                    <a href="pages/login.php">เข้าสู่ระบบ</a>
                    <a href="pages/register.php" class="btn-primary">สมัครสมาชิก</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <section class="hero">
        <h2>🎉 ยินดีต้อนรับ <?php echo htmlspecialchars($config['site_name'] ?? 'WebShopX'); ?></h2>
        <p>ค้นหาสินค้าที่คุณชื่นชอบจากเรา</p>
    </section>

    <section class="container">
        <h3 class="products-title">🔥 สินค้าแนะนำ</h3>
        
        <?php if (empty($products)): ?>
            <div class="no-products">ไม่มีสินค้า</div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $prod): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-image" onerror="this.src='https://via.placeholder.com/250x200?text=No+Image'">
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($prod['name']); ?></div>
                            <div class="product-description"><?php echo htmlspecialchars(substr($prod['description'], 0, 100)); ?>...</div>
                            <div class="product-footer">
                                <span class="product-price" style="color: <?php echo htmlspecialchars($config['primary_color'] ?? '#3498db'); ?>">฿<?php echo number_format($prod['price'], 2); ?></span>
                                <button class="btn-add" style="background: <?php echo htmlspecialchars($config['secondary_color'] ?? '#2ecc71'); ?>">ซื้อเลย</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>เกี่ยวกับเรา</h4>
                <p><?php echo htmlspecialchars($config['site_name'] ?? 'WebShopX'); ?> เป็นแพลตฟอร์มขายสินค้าออนไลน์แบบครบถ้วน</p>
            </div>
            <div class="footer-section">
                <h4>ลิงค์ด่วน</h4>
                <a href="#">สินค้า</a>
                <a href="#">ติดต่อเรา</a>
                <a href="#">นโยบายความเป็นส่วนตัว</a>
            </div>
            <div class="footer-section">
                <h4>ติดต่อ</h4>
                <p>📧 info@webshopx.com</p>
                <p>📱 080-xxx-xxxx</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 <?php echo htmlspecialchars($config['site_name'] ?? 'WebShopX'); ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
