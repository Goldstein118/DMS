<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['nama','bank','nama_rekening','no_rekening'];
    $fields = validate_1($data, $requiredFields);

    $nama = $fields['nama'];
    $bank = $fields['bank'];
    $nama_rekening = $fields['nama_rekening'];
    $no_rekening = $fields['no_rekening'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($bank, '/^[a-zA-Z\s]+$/', "Invalid bank format");
    validate_2($nama_rekening, '/^[a-zA-Z,. ]+$/', "Invalid nama rekening format");
    validate_2($no_rekening, '/^[0-9]+$/', "Invalid nomor rekening format");

    $divisi_id = generateCustomID('DI', 'tb_divisi', 'divisi_id', $conn);

    executeInsert(
        $conn,
        "INSERT INTO tb_divisi (divisi_id,nama,bank,nama_rekening,no_rekening) VALUES (?,?,?,?,?)",
        [$divisi_id,$nama,$bank,$nama_rekening,$no_rekening],
        "sssss"
    );
    echo json_encode([
        "success" => true,
        "message" => "Berhasil",
        "data" => ["divisi_id" => $divisi_id]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
