<?php

require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields=['frezzer_id','kode_barcode','status'];

$field = validate_1($data,$requiredFields);
$frezzer_id = $field['frezzer_id'];
$kode_barcode = $field['kode_barcode'];
$tipe=$field['tipe'];
$status = $field['status'];
$merek=$field['merek'];
$size=$field['size'];


validate_2($kode_barcode,'/^[a-zA-Z0-9\s]+$/', "Invalid name format");

$stmt = $conn->prepare("UPDATE tb_frezzer SET kode_barcode=?,tipe=?,status=?,merek=?,size=? WHERE frezzer_id=?");
$stmt->bind_param("ssssss",$kode_barcode,$tipe,$status,$merek,$size,$frezzer_id);

if($stmt->execute()){
    error_log("Frezzer updated successfully: ID = $frezzer_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Frezzer berhasil terupdate"]);
}
else{
    error_log("Failed to execute statement: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
}
$stmt->close();
}
catch(Exception $e){
    http_response_code(500);
    echo json_encode(["success"=> false,"error"=> $e->getMessage()]);
}

$conn->close();
?>