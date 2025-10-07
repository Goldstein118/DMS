<?php

require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields=['armada_id','nama','karyawan_id'];

$field = validate_1($data,$requiredFields);
$armada_id = $data['armada_id'];
$nama = $data['nama'];
$karyawan_id = $data['karyawan_id'];

validate_2($nama,'/^[a-zA-Z\s]+$/', "Invalid name format");

$stmt = $conn->prepare("UPDATE tb_armada SET nama=?,karyawan_id=? WHERE armada_id=?");
$stmt->bind_param("sss",$nama,$karyawan_id,$armada_id);

if($stmt->execute()){
    error_log("Armada updated successfully: ID = $armada_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Armada berhasil terupdate"]);
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