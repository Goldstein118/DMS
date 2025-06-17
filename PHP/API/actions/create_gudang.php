<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_gudang'];
    $field = validate_1($data, $requiredFields);
    $nama_gudang = $field['name_gudang'];
    $status = $data['status'];
    validate_2($nama_gudang, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $gudang_id = generateCustomID('GU', 'tb_gudang', 'gudang_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_gudang(gudang_id,nama,status)VALUES (?,?,?)",
        [$gudang_id, $nama_gudang,$status],
        "sss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["gudang_id" => $gudang_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
