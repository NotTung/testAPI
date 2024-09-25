<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Phương thức được cho phép
header('Access-Control-Allow-Headers: Content-Type'); // Tiêu đề được cho phép
include 'auth.php';

requireAuth();
$post_data = 'posts.json';

// for testing API only
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
        foreach ($p as &$post) {
            if ($post['id'] == $input['id']) {
                $post['title'] = $input['title'];
                $post['description'] = $input['description'];
                $post['first_paragraph'] = $input['first_paragraph'];
                $post['second_paragraph'] = $input['second_paragraph'];
                $post['third_paragraph'] = $input['third_paragraph'];
                $post['fourth_paragraph'] = $input['fourth_paragraph'];
                $post['fifth_paragraph'] = $input['fifth_paragraph'];
                $post['sixth_paragraph'] = $input['sixth_paragraph'];
                $post['author_name'] = $input['author_name'];
                $post['signature'] = $input['signature'];
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
