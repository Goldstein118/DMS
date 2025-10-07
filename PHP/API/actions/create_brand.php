<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_brand'];
    $field = validate_1($data, $requiredFields);
    $nama_brand = $field['name_brand'];
    validate_2($nama_brand, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $brand_id = generateCustomID('BR', 'tb_brand', 'brand_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_brand(brand_id,nama)VALUES (?,?)",
        [$brand_id, $nama_brand],
        "ss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["brand_id" => $brand_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
