<?php
class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get dashboard data
    public function getDashboard() {
        $totalUsers = $this->conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
        $totalOrders = $this->conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
        $totalRevenue = $this->conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'")->fetch_assoc()['total'] ?? 0;
        
        $recentOrders = $this->conn->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

        return [
            'total_users' => $totalUsers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'recent_orders' => $recentOrders
        ];
    }

    // Get monthly sales
    public function getMonthlySales() {
        $result = $this->conn->query("SELECT YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total_amount) as total FROM orders WHERE status = 'completed' GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY year DESC, month DESC")->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Get yearly sales
    public function getYearlySales() {
        $result = $this->conn->query("SELECT YEAR(created_at) as year, COUNT(*) as count, SUM(total_amount) as total FROM orders WHERE status = 'completed' GROUP BY YEAR(created_at) ORDER BY year DESC")->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Get site config
    public function getConfig() {
        $result = $this->conn->query("SELECT * FROM site_config LIMIT 1")->fetch_assoc();
        if (!$result) {
            $this->conn->query("INSERT INTO site_config DEFAULT VALUES");
            $result = $this->conn->query("SELECT * FROM site_config LIMIT 1")->fetch_assoc();
        }
        return $result;
    }

    // Update site config
    public function updateConfig($data) {
        $stmt = $this->conn->prepare("UPDATE site_config SET site_name = ?, primary_color = ?, secondary_color = ?, bank_api_key = ?, bank_merchant_id = ?, truewallet_api_key = ?, discord_webhook_url = ?");
        $stmt->bind_param("sssssss", $data['site_name'], $data['primary_color'], $data['secondary_color'], $data['bank_api_key'], $data['bank_merchant_id'], $data['truewallet_api_key'], $data['discord_webhook_url']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'อัปเดตการตั้งค่าสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }
}
?>
