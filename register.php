<?php
$usersFile = 'admin.json';

// Lấy dữ liệu từ file JSON
function getAdmin() {
    global $usersFile;
    if (file_exists($usersFile)) {
        $usersData = file_get_contents($usersFile);
        return json_decode($usersData, true);
    }
    return [];
}

// Lưu dữ liệu vào file JSON
function saveUsers($users) {
    global $usersFile;
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
}

// Xử lý yêu cầu đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Lấy danh sách người dùng hiện tại
    $users = getAdmin();

    // Kiểm tra nếu tên người dùng đã tồn tại
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            echo json_encode(['error' => 'Username has been used']);
            exit();
        }
    }

    // Thêm người dùng mới vào danh sách
    $newUser = [
        'username' => $username,
        'password' => $hashedPassword
    ];
    $users[] = $newUser;

    // Lưu lại vào file JSON
    saveUsers($users);

    echo json_encode(['message' => 'Account has been created']);
}
