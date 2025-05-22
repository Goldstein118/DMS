<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include '../db.php';
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if($search !==''&& strlen($search)>=5){
    $stmt =$conn->prepare("SELECT user.user_id, user.karyawan_id, karyawan.nama AS karyawan_nama FROM tb_user user JOIN tb_karyawan karyawan ON user.karyawan_id = karyawan.karyawan_id WHERE user.user_id LIKE CONCAT ('%',?,'%')
    OR karyawan.nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql_user = "SELECT user.user_id, user.karyawan_id, karyawan.nama AS karyawan_nama FROM tb_user user JOIN tb_karyawan karyawan ON user.karyawan_id = karyawan.karyawan_id";
    $result = $conn->query($sql_user);
}



if ($result) {
    $userData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $userData[] = $row;
    }
    http_response_code(200);
    echo json_encode($userData);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}?>