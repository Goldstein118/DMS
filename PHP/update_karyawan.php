<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log("Received Data: " . print_r($data, true));

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
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "error" => "Invalid JSON payload"]);
    exit;
    }
    $requiredFields = ['karyawan_ID', 'nama', 'role_ID', 'divisi', 'noTelp', 'alamat', 'KTP_NPWP'];
    foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        error_log("Missing or empty field: $field");
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Missing or empty field: $field"]);
        exit;
    }
    // Validate required fields

    // Extract and sanitize inputs
    $karyawan_ID = $data['karyawan_ID'];
    $nama = $data['nama'];
    $role_ID = $data['role_ID'];
    $divisi = $data['divisi'];
    $noTelp = $data['noTelp'];
    $alamat = $data['alamat'];
    $ktp_npwp = $data['KTP_NPWP'];
}
// Input validation

function validateField($field, $pattern, $errorMessage) {
    if (!preg_match($pattern, $field)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $errorMessage]);
        exit;
    }
}

// Validate fields
validateField($nama, '/^[a-zA-Z0-9\s,.\-]+$/', "Invalid name format");
validateField($divisi, '/^[a-zA-Z0-9\s,.\-]+$/', "Invalid division format");
validateField($alamat, '/^[a-zA-Z0-9\s,.\-]+$/', "Invalid address format");
validateField($noTelp, '/^\d{10,15}$/', "Invalid phone number format");
validateField(($ktp_npwp), '/^\d+$/', "Invalid KTP/NPWP format");

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE tb_karyawan SET nama = ?, role_ID = ?, divisi = ?, noTelp = ?, alamat = ?, KTP_NPWP = ? WHERE karyawan_ID = ?");
if (!$stmt) {
    error_log("Failed to prepare statement: " . $conn->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    exit;
}

// Bind parameters and execute the statement
$stmt->bind_param("sssssss", $nama, $role_ID, $divisi, $noTelp, $alamat, $ktp_npwp, $karyawan_ID);
if ($stmt->execute()) {
    error_log("Karyawan updated successfully: ID = $karyawan_ID");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Karyawan updated successfully"]);
} else {
    error_log("Failed to execute statement: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
}

// Close the statement and connection
$stmt->close();
$conn->close();

}








?>