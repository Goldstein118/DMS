<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include 'db.php';

if($_SERVER['REQUEST_METHOD']=== 'DELETE'){
$data = json_decode(file_get_contents('php://input'),true);
file_put_contents('php://stderr', "Incoming DELETE data: " . print_r($data, true));

if (!isset($data['customer_id']))
{
    http_response_code(400);
    echo json_encode(["error"=>"customer_id is missing"]);
    exit;
}
$customer_id = $data['customer_id'];
$stmt=$conn->prepare("DELETE FROM tb_customer WHERE customer_id = ?");
if (!$stmt){
    file_put_contents('php://stderr',"Prepare Failed: " . $conn->error . "\n");
    http_response_code(500);
    echo json_encode(["error"=>"Failed to prepare statement :". $conn->error]);
    exit;
}
$stmt->bind_param("s",$customer_id);
if ($stmt->execute()){
    http_response_code(200);
    echo json_encode(["Message"=>"Customer deleted succsesfully"]);
}else {
    file_put_contents('php://stderr', "Execute failed: " . $stmt->error . "\n");
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete customer: " . $stmt->error]);
}
$stmt->close();
}


?>