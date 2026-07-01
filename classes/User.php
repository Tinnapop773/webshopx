<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get user by ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, email, balance, role, status, full_name, phone, address, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update profile
    public function updateProfile($id, $fullName, $phone, $address) {
        $stmt = $this->conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fullName, $phone, $address, $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'โปรไฟล์ถูกแก้ไขสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Change password
    public function changePassword($id, $currentPassword, $newPassword) {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!password_verify($currentPassword, $result['password'])) {
            return ['success' => false, 'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $id);
        
        if ($updateStmt->execute()) {
            return ['success' => true, 'message' => 'เปลี่ยนรหัสผ่านสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Get all users (Admin)
    public function getAllUsers($page = 1, $limit = 10, $search = '') {
        $offset = ($page - 1) * $limit;
        $where = "";
        $types = "";
        $params = [];

        if (!empty($search)) {
            $where = " WHERE username LIKE ? OR email LIKE ? OR full_name LIKE ?";
            $search = "%$search%";
            $params = [$search, $search, $search];
            $types = "sss";
        }

        $query = "SELECT * FROM users" . $where . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        
        if ($types) {
            $stmt->bind_param($types . "ii", ...$params + [$limit, $offset]);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }
        
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Get total
        $countQuery = "SELECT COUNT(*) as total FROM users" . $where;
        $countStmt = $this->conn->prepare($countQuery);
        if ($types) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $total = $countStmt->get_result()->fetch_assoc()['total'];

        return [
            'users' => $users,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    // Update user (Admin)
    public function updateUser($id, $role, $status, $balance) {
        $stmt = $this->conn->prepare("UPDATE users SET role = ?, status = ?, balance = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $role, $status, $balance, $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'ผู้ใช้ถูกแก้ไขสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Delete user (Admin)
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'ผู้ใช้ถูกลบสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }

    // Add balance (Admin)
    public function addBalance($id, $amount) {
        $stmt = $this->conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->bind_param("di", $amount, $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'เติมเงินสำเร็จ'];
        }
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];
    }
}
?>
