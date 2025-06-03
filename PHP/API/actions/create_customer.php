<?php
require_once __DIR__ . '/../utils/helpers.php';


try {
    $requiredFields = ['name_customer', 'alamat_customer', 'no_telp_customer', 'nik_customer', 'npwp_customer', 'nitko', 'term_payment', 'max_invoice', 'max_piutang', 'status_customer'];
    $default = ['status_customer' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);
    $nama_customer = $fields['name_customer'];
    $alamat_customer = $fields['alamat_customer'];
    $no_telp_customer = $fields['no_telp_customer'];
    $ktp_customer = $fields['nik_customer'];
    $npwp_customer = $fields['npwp_customer'];
    $nitko = $fields['nitko'];
    $term_payment = $fields['term_payment'];
    $max_invoice = $fields['max_invoice'];
    $max_piutang = $fields['max_piutang'];
    $status_customer = $fields['status_customer'];

    validate_2($nama_customer, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat_customer, '/^[a-zA-Z0-9, .-]+$/', "Invalid address format");
    validate_2($no_telp_customer, '/^[+]?[\d\s\-]+$/', "Invalid phone number format");
    validate_2($ktp_customer, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp_customer, '/^[0-9 .-]+$/', "Invalid NPWP format");
    validate_2($nitko, '/^[a-zA-Z0-9, .-]+$/', "Invalid nitko format");
    validate_2($term_payment, '/^[a-zA-Z0-9 ]+$/', "Invalid term payment format");
    validate_2($max_invoice, '/^[a-zA-Z0-9 ]+$/', "Invalid max invoice format");
    validate_2($max_piutang, '/^[a-zA-Z0-9 ]+$/', "Invalid msx piutang format");
    $customer_id = generateCustomID('CU', 'tb_customer', 'customer_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_customer (customer_id,nama,alamat,no_telp,ktp,npwp,status,nitko,term_pembayaran,max_invoice,max_piutang) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
        [$customer_id, $nama_customer, $alamat_customer, $no_telp_customer, $ktp_customer, $npwp_customer, $status_customer, $nitko, $term_payment, $max_invoice, $max_piutang],
        "sssssssssss"
    );


    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["customer_id" => $customer_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
