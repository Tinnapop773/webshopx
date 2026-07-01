<?php
class Order {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create order
    public function createOrder($userId, $items, $paymentMethod, $shippingAddress, $notes = '') {
        $totalAmount = 0;

        // Calculate total and check stock
        foreach ($items as $item) {
            $product = $this->conn->prepare("SELECT price, stock FROM products WHERE id = ?");
            $product->bind_param("i", $item['product_id']);
            $product->execute();
            $result = $product->get_result()->fetch_assoc();

            if (!$result) {
                return ['success' => false, 'message' => 'สินค้าไม่พบ'];
            }

            if ($result['stock'] < $item['quantity']) {
                return ['success' => false, 'message' => 'สต็อกไม่เพียงพอ'];
            }

            $totalAmount += $result['price'] * $item['quantity'];
        }

        // Generate order number
        $orderNumber = 'ORD-' . time();

        // Create order
        $stmt = $this->conn->prepare("INSERT INTO orders (order_number, user_id, total_amount, payment_method, shipping_address, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siidss", $orderNumber, $userId, $totalAmount, $paymentMethod, $shippingAddress, $notes);
        
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
        }

        $orderId = $this->conn->insert_id;

        // Add order items
        foreach ($items as $item) {
            $product = $this->conn->prepare("SELECT name, price FROM products WHERE id = ?");
            $product->bind_param("i", $item['product_id']);
            $product->execute();
            $productData = $product->get_result()->fetch_assoc();

            $subtotal = $productData['price'] * $item['quantity'];

            $itemStmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
            $itemStmt->bind_param("iisidd", $orderId, $item['product_id'], $productData['name'], $item['quantity'], $productData['price'], $subtotal);
            $itemStmt->execute();
        }

        return ['success' => true, 'message' => 'สร้างคำสั่งซื้อสำเร็จ', 'order_id' => $orderId, 'order_number' => $orderNumber];
    }

    // Get user orders
    public function getUserOrders($userId, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;

        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Get total
        $countStmt = $this->conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
        $countStmt->bind_param("i", $userId);
        $countStmt->execute();
        $total = $countStmt->get_result()->fetch_assoc()['total'];

        return [
            'orders' => $orders,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    // Get order by ID
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get order items
    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
