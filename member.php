<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Phương thức được cho phép
header('Access-Control-Allow-Headers: Content-Type'); // Tiêu đề được cho phép
include 'auth.php';

requireAuth();
$post_data = 'team_members.json';

// Lấy dữ liệu từ file JSON
function getPosts() {
    global $post_data;
    $data = file_get_contents($post_data);
    return json_decode($data, true);
}

// Ghi dữ liệu vào file JSON
function savePosts($p) {
    global $post_data;
    file_put_contents($post_data, json_encode($p, JSON_PRETTY_PRINT));
}

// Xử lý các phương thức HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Lấy
        echo json_encode(getPosts());
        break;

    case 'POST':
        // Add
        $input = json_decode(file_get_contents('php://input'), true);
        $p = getPosts();
        $input['id'] = count($p) + 1; // Tự động tăng ID
        $p[] = $input;
        savePosts($p);
        echo json_encode($input);
        break;

    case 'PUT':
        // Edit
        $input = json_decode(file_get_contents('php://input'), true);
        $p = getPosts();
        // foreach ($p as &$post) {
        //     if ($post['id'] == $input['id']) {
        //         $post['name'] = $input['name'];
        //         $post['price'] = $input['price'];
        //     }
        // }

        // đổi các trường sao cho đúng controller
        foreach ($p as &$u) {
            if ($u['id'] == $input['id']) {
                $u['name'] = $input['name'];
                $u['description'] = $input['description'];
                $u['gender'] = $input['gender'];
                $u['image'] = $input['image'];
                $u['tag1'] = $input['tag1'];
                $u['tag2'] = $input['tag2'];
                $u['tag3'] = $input['tag3'];
                $u['is_admin'] = 0;
            }
        }
        savePosts($p);
        echo json_encode($input);
        break;

    case 'DELETE':
        // Xóa
        $input = json_decode(file_get_contents('php://input'), true);
        $p = getPosts();
        $p = array_filter($p, function($post) use ($input) {
            return $post['id'] != $input['id'];
        });
        savePosts(array_values($p));
        echo json_encode(['message' => 'Bài viết đã được xóa.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Phương thức không được hỗ trợ.']);
        break;
}
