<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json'); // Set the response content type to JSON
header('Content-Type: application/json');
include 'db.php';
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT * FROM tb_Karyawan";
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
