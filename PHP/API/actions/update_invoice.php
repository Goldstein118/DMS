<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['invoice_id', 'tanggal_invoice', 'pembelian_id'];

    $invoice_id = $data['invoice_id'];
    $pembelian_id = $data['pembelian_id'];
    $tanggal_invoice = $data['tanggal_invoice'];
    $no_invoice = $data['no_invoice'];
    $status = $data['status'];

    $stmt = $conn->prepare("UPDATE tb_invoice SET tanggal_invoice = ?,no_invoice_supplier =? ,pembelian_id = ? WHERE invoice_id =?");
    $stmt->bind_param("ssss", $tanggal_invoice, $no_invoice, $pembelian_id, $invoice_id);
    $stmt->execute();
    $stmt->close();
    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "tanggal_pengiriman berhasil diupdate"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}
