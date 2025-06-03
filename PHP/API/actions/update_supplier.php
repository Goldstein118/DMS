<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    $requiredFields = ['supplier_id', 'nama', 'alamat', 'no_telp', 'ktp', 'npwp', 'status'];

    $field = validate_1($data, $requiredFields);
    $supplier_id = $data['supplier_id'];
    $nama = $data['nama'];
    $no_telp = $data['no_telp'];
    $alamat = $data['alamat'];
    $ktp = $data['ktp'];
    $npwp = $data['npwp'];
    $status = $data['status'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validate_2($no_telp, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
    validate_2($ktp, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");


    $stmt = $conn->prepare("UPDATE tb_supplier SET nama=?,no_telp=?, alamat=?, ktp=?, npwp=?, status=? WHERE supplier_id=?");
    $stmt->bind_param("sssssss", $nama, $no_telp, $alamat, $ktp, $npwp, $status, $supplier_id);

    if ($stmt->execute()) {
        error_log("Supplier updated successfully: ID = $supplier_id");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "supplier berhasil terupdate"]);
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
