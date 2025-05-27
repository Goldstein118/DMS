<?php
// Add CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    if($data === null){
        http_response_code(400);
        echo json_encode(["success"=>false,"error"=>"Invalid JSON payload"]);
    exit;
    }
    $requiredFields=['customer_id','nama','alamat','no_telp','ktp','npwp','status','nitko','term_pembayaran','max_invoice','max_piutang'];
    foreach($requiredFields as $field){
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
        error_log("Missing or empty field: $field");
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Missing or empty field: $field"]);
        exit;
        }
    }

    $customer_id =$data['customer_id'];
    $nama = $data['nama'];
    $no_telp = $data['no_telp'];
    $alamat = $data['alamat'];
    $ktp = $data['ktp'];
    $npwp = $data['npwp'];
    $status = $data['status'];
    $nitko=$data['nitko'];
    $term_pembayaran=$data['term_pembayaran'];
    $max_invoice=$data['max_invoice'];
    $max_piutang =$data['max_piutang'];

    function validateField($field, $pattern, $errorMessage)
    {
        if (!preg_match($pattern, $field)) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => $errorMessage]);
            exit;
        }
    }

    validateField($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validateField($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validateField($no_telp, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
    validateField($ktp, '/^[0-9]+$/', "Invalid KTP format");
    validateField($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");
    validateField($nitko,'/^[a-zA-Z0-9,. ]+$/',"Invalid NITKO format");
    validateField($term_pembayaran,'/^[a-zA-Z0-9,. ]+$/',"Invalid term pembayaran format");
    validateField($max_invoice,'/^[a-zA-Z0-9,. ]+$/',"Invalid max invoice fromat");
    validateField($max_piutang,'/^[a-zA-Z0-9,. ]+$/',"Invalid max piutang formt");

    $stmt = $conn->prepare("UPDATE tb_customer SET nama=?,no_telp=?, alamat=?, ktp=?, npwp=?, nitko=?, term_pembayaran=? ,max_invoice=? ,max_piutang =? status=? WHERE customer_id=?");
    $stmt->bind_param("ssssssssss",$nama, $no_telp, $alamat, $ktp, $npwp, $status, $nitko,$term_pembayaran,$max_invoice,$max_piutang,$customer_id);

    if($stmt->execute()){
        echo json_encode(["success"=>true]);
    }else{
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Database update failed"]);
    }
    $stmt->close();
    $conn->close();
}
?>