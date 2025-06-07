<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_produk','kategori_id','brand_id','no_sku','status_produk','harga_minimal'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    // Extract and validate fields
    $nama = $fields['name_produk'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku'];
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,. ]+$/', "Invalid no sku format");
    validate_2($harga_minimal, '/^[a-zA-Z0-9,. ]+$/', "Invalid no harga minimal format");


    // Generate ID and insert
    $produk_id = generateCustomID('PR', 'tb_produk', 'produk_id', $conn);

    $stmt = $conn->prepare("INSERT INTO tb_produk (produk_id, nama,no_sku,status,harga_minimal,kategori_id,brand_id) 
                                    VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $produk_id, $nama,$no_sku,$status,$harga_minimal,$kategori_id,$brand_id);
    if (!$stmt->execute()) {
        throw new Exception("DB insert error: " . $stmt->error);
    }

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["produk_id" => $produk_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
