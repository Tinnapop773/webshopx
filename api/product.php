<?php
require_once '../config/db.php';
require_once '../classes/Product.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$product = new Product($conn);

if ($action === 'get_all') {
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 12;
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';

    echo json_encode($product->getAllProducts($page, $limit, $search, $category));
} elseif ($action === 'get_by_id') {
    $id = $_GET['id'] ?? 0;
    echo json_encode($product->getProductById($id));
} elseif ($action === 'create') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Admin access required']);
        exit;
    }

    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price' => $_POST['price'] ?? 0,
        'min_price' => $_POST['min_price'] ?? 0,
        'max_price' => $_POST['max_price'] ?? null,
        'image' => $_POST['image'] ?? '',
        'stock' => $_POST['stock'] ?? 0,
        'stock_type' => $_POST['stock_type'] ?? 'permanent',
        'category' => $_POST['category'] ?? ''
    ];

    echo json_encode($product->createProduct($data));
} elseif ($action === 'update') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Admin access required']);
        exit;
    }

    $id = $_POST['id'] ?? 0;
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price' => $_POST['price'] ?? 0,
        'min_price' => $_POST['min_price'] ?? 0,
        'max_price' => $_POST['max_price'] ?? null,
        'image' => $_POST['image'] ?? '',
        'stock' => $_POST['stock'] ?? 0,
        'stock_type' => $_POST['stock_type'] ?? 'permanent',
        'category' => $_POST['category'] ?? '',
        'is_active' => $_POST['is_active'] ?? 1
    ];

    echo json_encode($product->updateProduct($id, $data));
} elseif ($action === 'delete') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Admin access required']);
        exit;
    }

    $id = $_POST['id'] ?? 0;
    echo json_encode($product->deleteProduct($id));
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
