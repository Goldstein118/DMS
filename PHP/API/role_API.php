<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include '../db.php';
$sql_role = "SELECT * FROM tb_role";
$result_role = $conn->query($sql_role);

if ($result_role) {
    $roleData = [];
    while ($row = mysqli_fetch_assoc($result_role)) {
        $roleData[] = $row;
    }
    http_response_code(200);
    echo json_encode($roleData);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
?>