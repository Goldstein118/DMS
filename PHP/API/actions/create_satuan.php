<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_satuan'];
    $field = validate_1($data, $requiredFields);
    $nama_satuan = $field['name_satuan'];
    $id_referensi = $field['id_referensi'];
    $qty_satuan = $field['qty_satuan'];

    validate_2($nama_satuan, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $satuan_id = generateCustomID('SA', 'tb_satuan', 'satuan_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_satuan(satuan_id,nama,id_referensi,qty)VALUES (?,?,?,?)",
        [$satuan_id, $nama_satuan, $id_referensi, $qty_satuan],
        "ssss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["satuan_id" => $satuan_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
