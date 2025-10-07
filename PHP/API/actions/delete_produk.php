
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
if (!isset($data['produk_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Produk id tidak ditemukan"]);
    exit;
}
$produk_id = trim($data['produk_id']);

    $conn->begin_transaction();


    $stmtSelect = $conn->prepare("SELECT internal_link FROM tb_gambar_produk WHERE produk_id = ?");
    $stmtSelect->bind_param("s", $produk_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();


    while ($row = $result->fetch_assoc()) {
        $path = $row['internal_link'];
        if ($path && file_exists($path)) {
            unlink($path); 
        }
    }
    $stmtSelect->close();
    $stmtGambar = $conn->prepare("DELETE FROM tb_gambar_produk WHERE produk_id = ?");
    $stmtGambar->bind_param("s", $produk_id);
    $stmtGambar->execute();
    $stmtGambar->close();

    $stmt = $conn->prepare("DELETE FROM tb_produk WHERE produk_id = ?");
    $stmt->bind_param("s", $produk_id);
    $execute= $stmt->execute(); 
    
    if($execute&&$stmt->affected_rows>0){
    $conn->commit();
    http_response_code(200);
    echo json_encode(["message" => "Produk berhasil terhapus"]);
    }
    else {
        http_response_code(400);
        echo json_encode(["error"=> "Produk tidak ditemukan"]);
    }


} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Produk tidak dapat dihapus karena masih digunakan";
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