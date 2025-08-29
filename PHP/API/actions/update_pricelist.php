<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    $requiredFields = ['pricelist_id', 'nama', 'tanggal_berlaku'];
    $fields = validate_1($data, $requiredFields);

    $pricelist_id = $fields['pricelist_id'];
    $nama = $fields['nama'];
    $harga_default = $data['harga_default'] ?? 'ya';
    $status = $data['status'] ?? 'aktif';
    $tanggal_berlaku = $fields['tanggal_berlaku'];
    $detail = $data['detail'] ?? [];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Format nama tidak valid");

    // Update main pricelist
    $stmt = $conn->prepare("UPDATE tb_pricelist SET nama = ?, harga_default = ?, status = ?, tanggal_berlaku = ? WHERE pricelist_id = ?");
    $stmt->bind_param("sssss", $nama, $harga_default, $status, $tanggal_berlaku, $pricelist_id);
    $stmt->execute();
    $stmt->close();

    // Delete old details
    $stmt_delete = $conn->prepare("DELETE FROM tb_detail_pricelist WHERE pricelist_id = ?");
    $stmt_delete->bind_param("s", $pricelist_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Insert new details
    $stmt_insert = $conn->prepare("INSERT INTO tb_detail_pricelist (detail_pricelist_id, harga, pricelist_id, produk_id) VALUES (?, ?, ?, ?)");

    foreach ($detail as $item) {
        $produk_id = $item['produk_id'];
        $harga = $item['harga'];
        $harga = toFloat($harga);
        validate_2($harga, '/^\d+$/', "Format harga tidak valid");


        if (!$produk_id || !$harga) continue;


        $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
        $stmt_insert->bind_param("sdss", $detail_pricelist_id, $harga, $pricelist_id, $produk_id);
        $stmt_insert->execute();
    }

    $stmt_insert->close();

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Pricelist dan detail berhasil diupdate"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
