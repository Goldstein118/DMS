<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_produk', 'kategori_id', 'brand_id', 'status_produk'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    // Extract and validate fields
    $nama = $fields['name_produk'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku'] ?? '';
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal'] ?? '';


    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,.\- ]+$/', "Invalid no sku format");
    validate_2($harga_minimal, '/^[0-9., ]+$/', "Invalid no harga minimal format");

    // Generate ID and insert
    $produk_id = generateCustomID('PR', 'tb_produk', 'produk_id', $conn);

    $stmt_produk = $conn->prepare("INSERT INTO tb_produk (produk_id, nama,no_sku,status,harga_minimal,kategori_id,brand_id) 
                                    VALUES (?,?,?,?,?,?,?)");
    $stmt_produk->bind_param("sssssss", $produk_id, $nama, $no_sku, $status, $harga_minimal, $kategori_id, $brand_id);
    if (!$stmt_produk->execute()) {
        throw new Exception("DB insert error: " . $stmt_produk->error);
    }

    if (isset($data['details'])) {
        foreach ($data['details'] as $detail) {
            if (!isset($detail['pricelist_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $pricelist_id = $detail['pricelist_id'];
            $harga = $detail['harga'];

            $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pricelist (detail_pricelist_id ,harga ,pricelist_id, produk_id) VALUES (?, ?, ?, ?)",
                [$detail_pricelist_id, $harga, $pricelist_id, $produk_id],
                "ssss"
            );
        }
    }
    else{}


    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["produk_id" => $produk_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
