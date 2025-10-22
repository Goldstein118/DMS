<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['retur_pembelian_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "retur_pembelian_id  tidak ditemukan"]);
        exit;
    }
    $retur_pembelian_id = trim($data['retur_pembelian_id']);
    $status = trim($data['status']);
    $ket_cancel = trim($data['keterangan_cancel']);
    $cancel_by = trim($data['cancel_by']);

    // $stmt_detail = $conn->prepare("DELETE FROM tb_detail_retur_pembelian WHERE retur_pembelian_id=?");
    // $stmt_detail->bind_param("s", $retur_pembelian_id);
    // $execute_detail = $stmt_detail->execute();

    // $stmt = $conn->prepare("DELETE FROM tb_retur_pembelian WHERE retur_pembelian_id = ?");
    // $stmt->bind_param("s", $retur_pembelian_id);
    // $execute = $stmt->execute();

    $stmt = $conn->prepare("UPDATE tb_retur_pembelian SET status =?,keterangan_cancel=?,cancel_by=? WHERE retur_pembelian_id = ?");
    $stmt->bind_param("ssss", $status, $ket_cancel, $cancel_by, $retur_pembelian_id);
    $execute = $stmt->execute();

    $stmt_history = $conn->prepare("UPDATE tb_retur_pembelian_history SET status_after =?,keterangan_cancel_after=?,cancel_by_after=? WHERE retur_pembelian_id_after = ?");
    $stmt_history->bind_param("ssss", $status, $ket_cancel, $cancel_by, $pembelian_id);
    $execute = $stmt_history->execute();



    if ($execute) {
        http_response_code(200);
        echo json_encode(["message" => "Retur pembelian berhasil terhapus"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Retur pembelian tidak ditemukan"]);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Retur pembelian tidak dapat dihapus karena masih digunakan";
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
$stmt_history->close();
$conn->close();
