<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['kode_barcode','status',];
    $field = validate_1($data, $requiredFields);
    $kode_barcode = $field['kode_barcode'];
    $tipe = $field['tipe'];
    $status = $field['status'];
    $merek=$field['merek'];
    $size=$field['size'];

    validate_2($kode_barcode, '/^[a-zA-Z0-9\s]+$/', "Invalid name format");

    $frezzer_id = generateCustomID('FR', 'tb_frezzer', 'frezzer_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_frezzer(frezzer_id,kode_barcode,tipe,status,merek,size)VALUES (?,?,?,?,?,?)",
        [$frezzer_id, $kode_barcode,$tipe,$status,$merek,$size],
        "ssssss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["frezzer_id" => $frezzer_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
