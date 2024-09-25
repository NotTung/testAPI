<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Phương thức được cho phép
header('Access-Control-Allow-Headers: Content-Type'); // Tiêu đề được cho phép
include 'auth.php';

$post_data = 'team_members.json';

// Lấy dữ liệu từ file JSON
function getPosts() {
    global $post_data;
    $data = file_get_contents($post_data);
    return json_decode($data, true);
}

// Xử lý các phương thức HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Lấy
        echo json_encode(getPosts());
        break;

    default:
        http_response_code(401);
        echo json_encode(['message' => 'Stop']);
        break;
}
