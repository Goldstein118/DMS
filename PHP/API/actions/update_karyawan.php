<?php
require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields = ['karyawan_id', 'nama', 'role_id', 'divisi', 'no_telp', 'alamat', 'ktp', 'npwp', 'status'];

$field = validate_1($data, $requiredFields);
$karyawan_id = $data['karyawan_id'];
$nama = $data['nama'];
$role_ID = $data['role_id'];
$divisi = $data['divisi'];
$noTelp = $data['no_telp'];
$alamat = $data['alamat'];
$ktp_npwp = $data['ktp'];
$npwp = $data['npwp'];
$status = $data['status'];
// Validate fields
validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
validate_2($divisi, '/^[a-zA-Z0-9,. ]+$/', "Invalid division format");
validate_2($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
validate_2($noTelp, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
validate_2($ktp_npwp, '/^[0-9]+$/', "Invalid KTP format");
validate_2($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE tb_karyawan SET nama = ?, role_id = ?, divisi = ?, no_telp = ?, alamat = ?, ktp = ? ,npwp = ?, status =? WHERE karyawan_id = ?");
if (!$stmt) {
    error_log("Failed to prepare statement: " . $conn->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    exit;
}

// Bind parameters and execute the statement
$stmt->bind_param("sssssssss", $nama, $role_ID, $divisi, $noTelp, $alamat, $ktp_npwp, $npwp, $status, $karyawan_id);
if ($stmt->execute()) {
    error_log("Karyawan updated successfully: ID = $karyawan_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Karyawan berhasil terupdate"]);
} else {
    error_log("Failed to execute statement: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
}
} 
catch(Exception $e){
    http_response_code(500);
    echo json_encode(["success"=> false,"error"=> $e->getMessage()]);
}


// Close the statement and connection
$stmt->close();
$conn->close();
