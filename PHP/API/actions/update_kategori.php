<?php

require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields=['kategori_id','nama'];

$field = validate_1($data,$requiredFields);
$kategori_id = $data['kategori_id'];
$nama = $data['nama'];

validate_2($nama,'/^[a-zA-Z\s]+$/', "Invalid name format");

$stmt = $conn->prepare("UPDATE tb_kategori SET nama=? WHERE kategori_id=?");
$stmt->bind_param("ss",$nama,$kategori_id);

if($stmt->execute()){
    error_log("Kategori updated successfully: ID = $kategori_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Kategori berhasil terupdate"]);
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