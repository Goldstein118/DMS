<?php
require_once __DIR__ . '/../utils/helpers.php';

try {

    if (isset($data['tanggal_pengiriman'])) {


        $pembelian_id = $data['pembelian_id'];
        $tanggal_pengiriman = $data['tanggal_pengiriman'];
        $no_pengiriman = $data['no_pengiriman'];
        // Update main pembelian
        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_pengiriman = ?,  no_pengiriman = ? WHERE pembelian_id = ?");
        $stmt->bind_param("sss", $tanggal_pengiriman, $no_pengiriman, $pembelian_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($data['tanggal_terima'])) {


        $pembelian_id = $data['pembelian_id'];
        $tanggal_terima = $data['tanggal_terima'];
        // Update main pembelian
        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_terima = ? WHERE pembelian_id = ?");
        $stmt->bind_param("ss", $tanggal_pengiriman, $pembelian_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($data['tanggal_invoice'])) {


        $pembelian_id = $data['pembelian_id'];
        $tanggal_terima = $data['tanggal_invoice'];
        $no_invoice = $data['no_invoice_supplier'];
        // Update main pembelian
        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_invoice = ? ,no_invoice_supplier=? WHERE pembelian_id = ?");
        $stmt->bind_param("sss", $tanggal_pengiriman, $no_invoice, $pembelian_id);
        $stmt->execute();
        $stmt->close();
    }


    // foreach ($detail as $item) {
    //     $produk_id = $item['produk_id'];
    //     $harga = $item['harga'];

    //     if (!$produk_id || !$harga) continue;


    //     $detail_pembelian_id = generateCustomID('DE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
    //     $stmt_insert->bind_param("ssss", $detail_pembelian_id, $harga, $pembelian_id, $produk_id);
    //     $stmt_insert->execute();
    // }

    // $stmt_insert->close();

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Pricelist dan detail berhasil diupdate"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
