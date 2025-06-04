<?php

require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields=['brand_id','nama'];

$field = validate_1($data,$requiredFields);
$brand_id = $data['brand_id'];
$nama = $data['nama'];

validate_2($nama,'/^[a-zA-Z\s]+$/', "Invalid name format");

$stmt = $conn->prepare("UPDATE tb_brand SET nama=? WHERE brand_id=?");
$stmt->bind_param("ss",$nama,$brand_id);

if($stmt->execute()){
    error_log("Brand updated successfully: ID = $brand_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Brand berhasil terupdate"]);
}
else{
    error_log("Failed to execute statement: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
}

}
catch(Exception $e){
    http_response_code(500);
    echo json_encode(["success"=> false,"error"=> $e->getMessage()]);
}
$stmt->close();
$conn->close();
?>