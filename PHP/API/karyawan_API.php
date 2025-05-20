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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if ($search !== '') {
    $stmt = $conn->prepare("SELECT k.karyawan_id, k.nama, k.role_id, r.nama AS role_nama,k.divisi,k.noTelp,k.alamat,k.ktp,k.status
    FROM tb_karyawan k JOIN tb_role r ON k.role_id = r.role_id WHERE k.nama LIKE CONCAT ('%',?,'%')
    OR r.nama LIKE CONCAT ('%',?,%')
    ");
    $stmt->bind_param(['ss' => $search,$search]);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT karyawan.karyawan_id, karyawan.nama, karyawan.role_id, role.nama AS role_nama,
               karyawan.divisi, karyawan.noTelp, karyawan.alamat, karyawan.ktp, karyawan.npwp, karyawan.status
            FROM tb_karyawan karyawan
            JOIN tb_role role ON karyawan.role_id = role.role_id";
$result = $conn->query($sql);
}




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
