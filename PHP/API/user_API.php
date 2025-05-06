<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include '../db.php';
$sql_user = "SELECT * FROM tb_user";
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