<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    $requiredFields = ['divisi_id','nama','bank','nama_rekening','no_rekening'];

    $fields = validate_1($data, $requiredFields);
    $divisi_id = $data['divisi_id'];
    $nama = $data['nama'];
    $bank = $fields['bank'];
    $nama_rekening = $fields['nama_rekening'];
    $no_rekening = $fields['no_rekening'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($bank, '/^[a-zA-Z\s]+$/', "Invalid bank format");
    validate_2($nama_rekening, '/^[a-zA-Z,. ]+$/', "Invalid nama rekening format");
    validate_2($no_rekening, '/^[0-9]+$/', "Invalid nomor rekening format");


    $stmt = $conn->prepare("UPDATE tb_divisi SET nama=?, bank=?,nama_rekening=?,no_rekening=? WHERE divisi_id=?");
    $stmt->bind_param("sssss", $nama,$bank,$nama_rekening,$no_rekening, $divisi_id);

    if ($stmt->execute()) {
        error_log("Divisi updated successfully: ID = $divisi_id");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "divisi berhasil terupdate"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
$stmt->close();
$conn->close();