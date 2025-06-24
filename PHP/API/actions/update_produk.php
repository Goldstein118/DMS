<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['produk_id', 'nama', 'kategori_id', 'brand_id', 'status'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    $produk_id = $fields['produk_id'];
    $nama = $fields['nama'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku'] ?? '';
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal'] ?? '';


    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,.\- ]*$/', "Invalid SKU format");
    validate_2($harga_minimal, '/^[0-9., ]+$/', "Invalid harga minimal format");


    $stmt = $conn->prepare("UPDATE tb_produk SET nama = ?, no_sku = ?, status = ?, harga_minimal = ?, kategori_id = ?, brand_id = ? 
                            WHERE produk_id = ?");
    $stmt->bind_param("sssssss", $nama, $no_sku, $status, $harga_minimal, $kategori_id, $brand_id, $produk_id);
    if (!$stmt->execute()) throw new Exception("Product update failed: " . $stmt->error);
    $stmt->close();

    if (isset($data['details'])) {
        $stmt_delete = $conn->prepare("DELETE FROM tb_detail_pricelist WHERE pricelist_id = ? AND produk_id = ?");

        $stmt_insert = $conn->prepare("INSERT INTO tb_detail_pricelist (detail_pricelist_id, harga, pricelist_id, produk_id) 
                                       VALUES (?, ?, ?, ?)");
        foreach ($data['details'] as $item) {
            $pricelist_id = $item['pricelist_id'];
            $harga = $item['harga'];
            
            $stmt_delete->bind_param("ss", $pricelist_id, $produk_id);
            $stmt_delete->execute();


            $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
            $stmt_insert->bind_param("ssss", $detail_pricelist_id, $harga, $pricelist_id, $produk_id);
            $stmt_insert->execute();
        }

        $stmt_delete->close();
        $stmt_insert->close();
    }
    else{}

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Produk & Pricelist berhasil diupdate"]);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error updating produk: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}


$conn->close();
