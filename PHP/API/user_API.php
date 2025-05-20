<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include '../db.php';
$sql_user = "SELECT user.user_id, user.karyawan_id, karyawan.nama AS karyawan_nama FROM tb_user user
             JOIN tb_karyawan karyawan ON user.karyawan_id = karyawan.karyawan_id";
$result_user = $conn->query($sql_user);

if ($result_user) {
    $userData = [];
    while ($row = mysqli_fetch_assoc($result_user)) {
        $userData[] = $row;
    }
    http_response_code(200);
    echo json_encode($userData);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}?>