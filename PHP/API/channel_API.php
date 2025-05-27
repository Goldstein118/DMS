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

if (strlen($search)>=5 && $search !==''){
    $stmt = $conn->prepare("SELECT * FROM tb_channel WHERE channel_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql="SELECT * FROM tb_channel";
    $result=$conn->query($sql);
}

    if ($result) {
    $channel_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $channel_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($channel_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>