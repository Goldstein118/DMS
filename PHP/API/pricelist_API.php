<?php
require_once '../db.php';
require_once '../cek_akses.php';
require_once '../cek_akses_contex.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE'); 
header('Access-Control-Allow-Headers: Content-Type');
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
            'table'  => 'tb_pricelist',
        ]);

        if (!$hasContextAccess) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied (context)']);
            exit;
        }
    } else {
        // No context info, fall back to normal access check
        checkAccess($conn, $user_id, 'tb_pricelist', 44); // View access
    }
        require __DIR__ . '/actions/select_pricelist.php';
        break;

    case 'create':
        checkAccess($conn, $user_id, 'tb_pricelist', 45); // Create access
        require  __DIR__ . '/actions/create_pricelist.php';
        break;

    case 'update':
        checkAccess($conn, $user_id, 'tb_pricelist', 46); // Edit access
        require  __DIR__ . '/actions/update_pricelist.php';
        break;

    case 'delete':
        checkAccess($conn, $user_id, 'tb_pricelist', 47); // Delete access
        require  __DIR__ . '/actions/delete_pricelist.php';
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        break;
}
?>
