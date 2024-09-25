<?php
session_start(); // Khởi tạo session

$adminData = 'admin.json';

// Lấy dữ liệu từ file JSON
function getAllAdmins() {
    global $adminData;
    if (file_exists($adminData)) {
        $usersData = file_get_contents($adminData);
        return json_decode($usersData, true);
    }
    return [];
}

// Xử lý yêu cầu đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lấy danh sách người dùng
    $users = getAllAdmins();

    // Tìm kiếm người dùng với tên đăng nhập
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Lưu session khi đăng nhập thành công
            $_SESSION['user_id'] = $username;
            echo json_encode(['message' => 'Authenticated. Hello '. $username]);
            exit();
        }
    }

    // Nếu không tìm thấy người dùng hoặc mật khẩu sai
    echo json_encode(['error' => 'Try again']);
}
