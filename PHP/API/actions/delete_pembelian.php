<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['pembelian_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Pembelian id tidak ditemukan"]);
        exit;
    }
    $pembelian_id = trim($data['pembelian_id']);
    $status = trim($data['status']);
    $ket_cancel = trim($data['keterangan_cancel']);
    $cancel_by = trim($data['cancel_by']);

    // $stmt_biaya_tambahan = $conn->prepare("DELETE FROM tb_biaya_tambahan WHERE pembelian_id=?");
    // $stmt_biaya_tambahan->bind_param("s", $pembelian_id);
    // $execute_biaya_tambahan = $stmt_biaya_tambahan->execute();

    // $stmt_detail = $conn->prepare("DELETE FROM tb_detail_pembelian WHERE pembelian_id=?");
    // $stmt_detail->bind_param("s", $pembelian_id);
    // $execute_detail = $stmt_detail->execute();

    // $stmt = $conn->prepare("DELETE FROM tb_pembelian WHERE pembelian_id = ?");
    // $stmt->bind_param("s", $pembelian_id);
    // $execute = $stmt->execute();

    $stmt = $conn->prepare("UPDATE tb_pembelian SET status =?,keterangan_cancel=?,cancel_by=? WHERE pembelian_id = ?");
    $stmt->bind_param("ssss", $status, $ket_cancel, $cancel_by, $pembelian_id);
    $execute = $stmt->execute();

    if ($execute) {
        http_response_code(200);
        echo json_encode(["message" => "Purchase Order berhasil tercancel"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Purchase Order tidak ditemukan"]);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Purchase Order tidak dapat dihapus karena masih digunakan";
        if ($table) {
            $errorMsg .= " di tabel `$table`.";
        }

        http_response_code(409);
        echo json_encode(["error" => $errorMsg]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}
$stmt->close();
$conn->close();
