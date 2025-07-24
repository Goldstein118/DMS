
<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    $requiredFields = ['satuan_id', 'nama'];

    $field = validate_1($data, $requiredFields);
    $satuan_id = $data['satuan_id'];
    $nama = $data['nama'];
    $id_referensi = $data['id_referensi'];
    $qty = $data['qty'];


    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    $stmt = $conn->prepare("UPDATE tb_satuan SET nama=?,id_referensi=?,qty=? WHERE satuan_id=?");
    $stmt->bind_param("ssss", $nama, $id_referensi, $qty, $satuan_id);

    if ($stmt->execute()) {
        error_log("Satuan updated successfully: ID = $satuan_id");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "satuan berhasil terupdate"]);
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




?>