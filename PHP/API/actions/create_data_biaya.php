<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_data_biaya'];
    $field = validate_1($data, $requiredFields);
    $nama_data_biaya = $field['name_data_biaya'];

    validate_2($nama_data_biaya, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $data_biaya_id = generateCustomID('DB', 'tb_data_biaya', 'data_biaya_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_data_biaya(data_biaya_id,nama)VALUES (?,?)",
        [$data_biaya_id, $nama_data_biaya],
        "ss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["data_biaya_id" => $data_biaya_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
