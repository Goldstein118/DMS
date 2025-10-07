<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['invoice_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invoice id tidak ditemukan"]);
        exit;
    }
    $invoice_id = trim($data['invoice_id']);

    $stmt_biaya_tambahan = $conn->prepare("DELETE FROM tb_biaya_tambahan_invoice WHERE invoice_id=?");
    $stmt_biaya_tambahan->bind_param("s", $invoice_id);
    $execute_biaya_tambahan = $stmt_biaya_tambahan->execute();

    $stmt_detail = $conn->prepare("DELETE FROM tb_detail_invoice WHERE invoice_id=?");
    $stmt_detail->bind_param("s", $invoice_id);
    $execute_detail = $stmt_detail->execute();

    $stmt = $conn->prepare("DELETE FROM tb_invoice WHERE invoice_id = ?");
    $stmt->bind_param("s", $invoice_id);
    $execute = $stmt->execute();



    if ($execute && $execute_detail && $stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(["message" => "Invoice berhasil terhapus"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invoice tidak ditemukan"]);
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
