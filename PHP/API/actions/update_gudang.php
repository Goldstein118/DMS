<?php

require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields=['gudang_id','nama'];

$field = validate_1($data,$requiredFields);
$gudang_id = $data['gudang_id'];
$nama = $field['nama'];
$status = $data['status'];

validate_2($nama,'/^[a-zA-Z\s]+$/', "Invalid name format");

$stmt = $conn->prepare("UPDATE tb_gudang SET nama=?,status =? WHERE gudang_id=?");
$stmt->bind_param("sss",$nama,$status,$gudang_id);

if($stmt->execute()){
    error_log("Gudang updated successfully: ID = $gudang_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Gudang berhasil terupdate"]);
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