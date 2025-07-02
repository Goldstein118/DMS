<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['frezzer_id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "frezzer_id tidak ditemukan"]);
        exit;
    }

    $frezzer_id = trim($data['frezzer_id']);
    $stmt = $conn->prepare("DELETE FROM tb_frezzer WHERE frezzer_id = ?");
    $stmt->bind_param("s", $frezzer_id);
    $execute = $stmt->execute();


    if ($execute && $stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(["message" => "Frezzer berhasil terhapus"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Frezzer tidak ditemukan"]);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Frezzer tidak dapat dihapus karena masih digunakan";
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