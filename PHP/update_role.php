<?php
include 'db.php';

// Add CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if JSON decoding failed
    if ($data === null) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "error" => "Invalid JSON payload"]);
        exit;
    }

    // Validate required fields
    $requiredFields = ['role_id', 'nama', 'akses'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Missing field: $field"]);
            exit;
        }
    }

    // Extract and sanitize inputs
    $role_ID = $data['role_id'];
    $role_name = $data['nama'];
    $akses = $data['akses'];

    // Input validation

    if (!preg_match('/^[a-zA-Z\s]+$/', $role_name)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Invalid name format"]);
        exit;
    }

    if (!preg_match('/^[0-9]+$/', $akses)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Invalid akses format"]);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE tb_role SET nama = ?, akses = ? WHERE role_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $role_name, $akses, $role_ID);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Role updated successfully"]);
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    }

    // Close the statement
    $stmt->close();
}
?>