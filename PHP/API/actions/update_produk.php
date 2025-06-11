<?php
require_once __DIR__ . '/../utils/helpers.php';
try{
    $requiredFields = ['produk_id','nama','kategori_id','brand_id','status'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    // Extract and validate fields
    $produk_id=$fields['produk_id'];
    $nama = $fields['nama'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku']??'';
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal']??'';

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,.- ]+$/', "Invalid no sku format");
    validate_2($harga_minimal, '/^[0-9. ]+$/', "Invalid no harga minimal format");


$stmt = $conn->prepare("UPDATE tb_produk SET nama = ?,no_sku=?,status =?,harga_minimal =?, kategori_id =?,brand_id=? WHERE produk_id = ?");
$stmt->bind_param("sssssss", $nama,$no_sku, $status,$harga_minimal ,$kategori_id,$brand_id,$produk_id);
if ($stmt->execute()) {
    error_log("Produk updated successfully: ID = $produk_id");
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Produk berhasil terupdate"]);
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
