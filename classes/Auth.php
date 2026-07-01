<?php
session_start();
require_once 'db.php';

class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Register
    public function register($username, $email, $password, $fullName) {
        // Check if user exists
        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $check->bind_param("ss", $email, $username);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Email หรือ Username มีการใช้งานแล้ว'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, full_name, role, status) VALUES (?, ?, ?, ?, 'user', 'active')");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $fullName);
        
        if ($stmt->execute()) {
            // Send Discord notification
            $this->sendDiscordNotification('register', [
                'username' => $username,
                'email' => $email
            ]);
            return ['success' => true, 'message' => 'สมัครสมาชิกสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Login
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, email, password, role, balance, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'];
        }

        $user = $result->fetch_assoc();

        if ($user['status'] !== 'active') {
            return ['success' => false, 'message' => 'บัญชีนี้ไม่ได้ใช้งาน'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['balance'] = $user['balance'];

        return ['success' => true, 'message' => 'เข้าสู่ระบบสำเร็จ', 'user' => $user];
    }

    // Logout
    public function logout() {
        session_destroy();
        return true;
    }

    // Check if logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Check if admin
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Send Discord notification
    public function sendDiscordNotification($type, $data) {
        $webhookUrl = getenv('DISCORD_WEBHOOK_URL');
        if (!$webhookUrl) return;

        $message = '';
        $color = 3498875; // Blue

        switch($type) {
            case 'register':
                $message = "🎉 **สมัครสมาชิกใหม่**\n**Username:** {$data['username']}\n**Email:** {$data['email']}\n**เวลา:** " . date('Y-m-d H:i:s');
                $color = 3447003;
                break;
            case 'deposit':
                $message = "💰 **เติมเงินเข้ากระเป๋า**\n**ผู้ใช้:** {$data['username']}\n**จำนวนเงิน:** ฿{$data['amount']}\n**ยอดคงเหลือ:** ฿{$data['balance']}\n**เวลา:** " . date('Y-m-d H:i:s');
                $color = 65280;
                break;
            case 'order':
                $message = "📦 **มีการซื้อสินค้า**\n**ผู้ใช้:** {$data['username']}\n**เลขคำสั่ง:** {$data['orderNumber']}\n**จำนวนเงิน:** ฿{$data['totalAmount']}\n**เวลา:** " . date('Y-m-d H:i:s');
                $color = 15105570;
                break;
        }

        $postData = json_encode([
            'embeds' => [[
                'color' => $color,
                'description' => $message,
                'timestamp' => date('c')
            ]]
        ]);

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>
