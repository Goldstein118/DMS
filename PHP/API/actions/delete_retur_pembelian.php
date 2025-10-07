<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['retur_pembelian_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "retur_pembelian_id  tidak ditemukan"]);
        exit;
    }
    $retur_pembelian_id = trim($data['retur_pembelian_id']);


    $stmt_detail = $conn->prepare("DELETE FROM tb_detail_retur_pembelian WHERE retur_pembelian_id=?");
    $stmt_detail->bind_param("s", $retur_pembelian_id);
    $execute_detail = $stmt_detail->execute();

    $stmt = $conn->prepare("DELETE FROM tb_retur_pembelian WHERE retur_pembelian_id = ?");
    $stmt->bind_param("s", $retur_pembelian_id);
    $execute = $stmt->execute();

    if ($stmt && $stmt_detail->affected_rows > 0) {
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
        $errorMsg = "Invoice tidak dapat dihapus karena masih digunakan";
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
