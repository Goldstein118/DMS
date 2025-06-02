<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    $requiredFields = ['role_id', 'nama', 'akses'];
    validate_1($data, $requiredFields);

    $role_ID = $data['role_id'];
    $role_name = $data['nama'];
    $akses = $data['akses'];

    validate_2($role_name, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $stmt = $conn->prepare("UPDATE tb_role SET nama = ?, akses = ? WHERE role_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
        exit;
    }

    $stmt->bind_param("sss", $role_name, $akses, $role_ID);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Role berhasil terupdate"]);
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
$stmt->close();
$conn->close();
