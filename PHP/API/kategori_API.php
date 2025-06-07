<?php
require_once '../db.php';
require_once '../cek_akses.php';
require_once '../cek_akses_contex.php';

header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');


$rawInput=file_get_contents("php://input");

$data = json_decode($rawInput, true) ?? [];
error_log("Incoming data: " . print_r($data, true));

$user_id = $data['user_id'] ?? $_GET['user_id'] ?? $_POST['user_id']??null;


// Get the action from query or body
$action =$data['action']?? $_GET['action'] ?? $_POST['action'] ?? null;


if (!$action) {
    http_response_code(400);
    echo json_encode(["error" => "No action specified"]);
    exit;
}
if (!$user_id) {
    http_response_code(400);
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

// Handle based on action
switch ($action) {
    case 'select':
    $target = $_GET['target'] ?? $data['target'] ?? null;
    $contextAction = $_GET['context'] ?? $data['context'] ?? null;

    if ($target && $contextAction) {
        $hasContextAccess = checkContextAccess($conn, $user_id, [
            'action' => $contextAction,
            'target' => $target,
            'table'  => 'tb_kategori',
        ]);

        if (!$hasContextAccess) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied (context)']);
            exit;
        }
    } else {
        // No context info, fall back to normal access check
        checkAccess($conn, $user_id, 'tb_kategori', 24); // View access
    }
        require __DIR__ . '/actions/select_kategori.php';
        break;

    case 'create':
        checkAccess($conn, $user_id, 'tb_kategori', 25); // Create access
        require  __DIR__ . '/actions/create_kategori.php';
        break;

    case 'update':
        checkAccess($conn, $user_id, 'tb_kategori', 26); // Edit access
        require  __DIR__ . '/actions/update_kategori.php';
        break;

    case 'delete':
        checkAccess($conn, $user_id, 'tb_kategori', 27); // Delete access
        require  __DIR__ . '/actions/delete_kategori.php';
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        break;
}
?>