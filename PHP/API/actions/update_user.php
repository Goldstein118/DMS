<?php
require_once __DIR__ . '/../utils/helpers.php';
try{
    $requiredFields = ['user_id', 'karyawan_id','level'];
    
    $field=validate_1($data,$requiredFields);
    $user_ID = $data['user_id'];
    $karyawan_ID = $data['karyawan_id'];
    $level = $data['level'];


    $stmt = $conn->prepare("UPDATE tb_user SET karyawan_id = ? , level = ? WHERE user_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
        exit;
    }

    $stmt->bind_param("sss", $karyawan_ID,$level, $user_ID);
    
    if ($stmt->execute()) {
        error_log("user updated successfully: ID = $karyawan_ID");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "User berhasil terupdate"]);
    } else {
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
