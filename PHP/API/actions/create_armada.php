<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['nama','karyawan_id'];
    $field = validate_1($data, $requiredFields);
    $nama = $field['nama'];
    $karyawan_id=$field['karyawan_id'];
    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $armada_id = generateCustomID('AR', 'tb_armada', 'armada_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_armada(armada_id,nama,karyawan_id) VALUES (?,?,?)",
        [$armada_id,$nama,$karyawan_id],
        "sss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["armada_id" => $armada_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
