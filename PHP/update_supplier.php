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
    $data = json_decode(file_get_contents('php://input'), true);


    if ($data === null) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "error" => "Invalid JSON payload"]);
        exit;
    }
    $requiredFields = ['supplier_id', 'nama', 'alamat', 'no_telp', 'ktp', 'npwp', 'status'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            error_log("Missing or empty field: $field");
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Missing or empty field: $field"]);
            exit;
        }
    }
    $supplier_id = $data['supplier_id'];
    $nama = $data['nama'];
    $no_telp = $data['no_telp'];
    $alamat = $data['alamat'];
    $ktp = $data['ktp'];
    $npwp = $data['npwp'];
    $status = $data['status'];

    function validateField($field, $pattern, $errorMessage)
    {
        if (!preg_match($pattern, $field)) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => $errorMessage]);
            exit;
        }
    }

    // Validate fields
    validateField($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validateField($alamat, '/^[a-zA-Z0-9, ]+$/', "Invalid address format");
    validateField($no_telp, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
    validateField($ktp, '/^[a-zA-Z0-9, ]+$/', "Invalid KTP format");
    validateField($npwp, '/^[a-zA-Z0-9, ]+$/', "Invalid NPWP format");
    // Example SQL update (adjust table/fields as needed)
    $stmt = $conn->prepare("UPDATE tb_supplier SET nama=?,no_telp=?, alamat=?, ktp=?, npwp=?, status=? WHERE supplier_id=?");
    $stmt->bind_param("sssssss", $nama, $no_telp, $alamat, $ktp, $npwp, $status, $supplier_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Database update failed"]);
    }
    $stmt->close();
    $conn->close();
}
