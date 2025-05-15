<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);


    if (!isset($data['user_ID'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "role_ID is missing"]);
        exit;
    }

    $user_ID = $data['user_ID'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM tb_user WHERE user_id = ?");
    if (!$stmt) {
        file_put_contents('php://stderr', "Prepare failed: " . $conn->error . "\n");
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $user_ID);
    if ($stmt->execute()) {
        http_response_code(200);
    } else {
        file_put_contents('php://stderr', "Execute failed: " . $stmt->error . "\n");
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete role: " . $stmt->error]);
    }
    $stmt->close();
}
?>