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
    $requiredFields = ['user_id', 'karyawan_ID'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Missing field: $field"]);
            exit;
        }
    }

    // Extract and sanitize inputs
    $user_ID = $data['user_id'];
    $karyawan_ID = $data['karyawan_id'];




    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE tb_user SET karyawan_id = ? WHERE user_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("ss", $karyawan_ID, $user_ID);
    
    if ($stmt->execute()) {
        error_log("Karyawan updated successfully: ID = $karyawan_ID");
        http_response_code(200);
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    }

    // Close the statement and connection
    $stmt->close();
}
?>