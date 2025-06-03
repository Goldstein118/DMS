<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
if (!isset($data['channel_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Channel ID tidak ditemukan"]);
    exit;
}
$channel_id = trim($data['channel_id']);

    $stmt = $conn->prepare("DELETE FROM tb_channel WHERE channel_id = ?");
    $stmt->bind_param("s", $channel_id);
    $execute= $stmt->execute(); 
    
    if($execute&&$stmt->affected_rows>0){
    http_response_code(200);
    echo json_encode(["message" => "Channel berhasil terhapus"]);
    }
    else {
        http_response_code(400);
        echo json_encode(["error"=> "Channel tidak ditemukan"]);
    }


} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {

        $table = null;
        if (preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $e->getMessage(), $matches)) {
            $table = $matches[1];
        }
        $errorMsg = "Channel tidak dapat dihapus karena masih digunakan";
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


?>