<?php
require_once '../config/db.php';
require_once '../classes/Auth.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$auth = new Auth($conn);

if ($action === 'register') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fullName = $_POST['full_name'] ?? $username;

    echo json_encode($auth->register($username, $email, $password, $fullName));
} elseif ($action === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    echo json_encode($auth->login($email, $password));
} elseif ($action === 'logout') {
    $auth->logout();
    echo json_encode(['success' => true, 'message' => 'ออกจากระบบสำเร็จ']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
