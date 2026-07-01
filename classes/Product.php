<?php
class Product {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get all products
    public function getAllProducts($page = 1, $limit = 12, $search = '', $category = '') {
        $offset = ($page - 1) * $limit;
        $where = "WHERE is_active = 1";
        $params = [];
        $types = "";

        if (!empty($search)) {
            $where .= " AND name LIKE ?";
            $params[] = "%$search%";
            $types .= "s";
        }

        if (!empty($category)) {
            $where .= " AND category = ?";
            $params[] = $category;
            $types .= "s";
        }

        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM products $where";
        $countStmt = $this->conn->prepare($countQuery);
        if ($types) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $total = $countStmt->get_result()->fetch_assoc()['total'];

        // Get products
        $query = "SELECT * FROM products $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types . "ii", ...array_merge($params, [$limit, $offset]));
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return [
            'products' => $products,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    // Get product by ID
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create product (Admin)
    public function createProduct($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, min_price, max_price, image, stock, stock_type, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddddiss", $data['name'], $data['description'], $data['price'], $data['min_price'], $data['max_price'], $data['image'], $data['stock'], $data['stock_type'], $data['category']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'สินค้าถูกสร้างสำเร็จ', 'id' => $this->conn->insert_id];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Update product (Admin)
    public function updateProduct($id, $data) {
        $stmt = $this->conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, min_price = ?, max_price = ?, image = ?, stock = ?, stock_type = ?, category = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssddddsissi", $data['name'], $data['description'], $data['price'], $data['min_price'], $data['max_price'], $data['image'], $data['stock'], $data['stock_type'], $data['category'], $data['is_active'], $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'สินค้าถูกแก้ไขสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Delete product (Admin)
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'สินค้าถูกลบสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }
}
?>
