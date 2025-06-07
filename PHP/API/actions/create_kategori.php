<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_kategori'];
    $field = validate_1($data, $requiredFields);
    $nama_kategori = $field['name_kategori'];
    validate_2($nama_kategori, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $kategori_id = generateCustomID('KA', 'tb_kategori', 'kategori_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_kategori(kategori_id,nama)VALUES (?,?)",
        [$kategori_id, $nama_kategori],
        "ss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["kategori_id" => $kategori_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
