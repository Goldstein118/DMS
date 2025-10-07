<?php

require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['data_biaya_id', 'nama'];

    $field = validate_1($data, $requiredFields);
    $data_biaya_id = $data['data_biaya_id'];
    $nama = $field['nama'];
    $status = $data['status'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $stmt = $conn->prepare("UPDATE tb_data_biaya SET nama=?,status =? WHERE data_biaya_id=?");
    $stmt->bind_param("sss", $nama, $status, $data_biaya_id);

    if ($stmt->execute()) {
        error_log("data biaya updated successfully: ID = $data_biaya_id");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "data biaya berhasil terupdate"]);
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
