<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include '../db.php'; // Include your database connection file
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT karyawan.karyawan_id, karyawan.nama, karyawan.role_id, role.nama AS role_nama,
               karyawan.divisi, karyawan.noTelp, karyawan.alamat, karyawan.ktp, karyawan.npwp, karyawan.status
            FROM tb_karyawan karyawan
            JOIN tb_role role ON karyawan.role_id = role.role_id";
$result = $conn->query($sql);

if ($result) {
    $karyawanData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawanData[] = $row;
    }
    http_response_code(200);
    echo json_encode($karyawanData);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}





?>
