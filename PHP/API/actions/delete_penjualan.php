<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['penjualan_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "penjualan id tidak ditemukan"]);
        exit;
    }
    $penjualan_id = trim($data['penjualan_id']);
    $status = trim($data['status']);
    $ket_cancel = trim($data['keterangan_cancel']);
    $cancel_by = trim($data['cancel_by']);



    // $stmt_detail = $conn->prepare("DELETE FROM tb_detail_penjualan WHERE penjualan_id=?");
    // $stmt_detail->bind_param("s", $penjualan_id);
    // $execute_detail = $stmt_detail->execute();

    // $stmt = $conn->prepare("DELETE FROM tb_penjualan WHERE penjualan_id = ?");
    // $stmt->bind_param("s", $penjualan_id);
    // $execute = $stmt->execute();

    $stmt = $conn->prepare("UPDATE tb_penjualan SET status =?,keterangan_cancel=?,cancel_by=? WHERE penjualan_id = ?");
    $stmt->bind_param("ssss", $status, $ket_cancel, $cancel_by, $penjualan_id);
    $execute = $stmt->execute();

    // $stmt_history = $conn->prepare("UPDATE tb_penjualan_history SET status_after =?,keterangan_cancel_after=?,cancel_by_after=? WHERE penjualan_id_after = ?");
    // $stmt_history->bind_param("ssss", $status, $ket_cancel, $cancel_by, $penjualan_id);
    // $execute = $stmt_history->execute();


    if ($execute) {
        http_response_code(200);
        echo json_encode(["message" => "Penjualan Order berhasil tercancel"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Penjualan Order tidak ditemukan"]);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Penjualan Order tidak dapat dihapus karena masih digunakan";
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
