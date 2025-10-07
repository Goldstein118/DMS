<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['supplier_nama','supplier_status'];
    $default = ['supplier_status' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    $supplier_nama = $fields['supplier_nama'];
    $supplier_alamat = $fields['supplier_alamat'] ??'';
    $supplier_no_telp = $fields['supplier_no_telp']??'';
    $supplier_ktp = $fields['supplier_ktp']??'';
    $supplier_npwp = $fields['supplier_npwp']??'';
    $supplier_status = $fields['supplier_status'];

    validate_2($supplier_nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($supplier_alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validate_2($supplier_no_telp, '/^[+]?[\d\s\-]+$/', "Invalid phone number format");
    validate_2($supplier_ktp, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($supplier_npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");



    $supplier_id = generateCustomID('SU', 'tb_supplier', 'supplier_id', $conn);

    executeInsert(
        $conn,
        "INSERT INTO tb_supplier (supplier_id, nama, alamat, no_telp, ktp, npwp, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$supplier_id, $supplier_nama, $supplier_alamat, $supplier_no_telp, $supplier_ktp, $supplier_npwp, $supplier_status],
        "sssssss"
    );
    echo json_encode([
        "success" => true,
        "message" => "Berhasil",
        "data" => ["supplier_id" => $supplier_id]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
