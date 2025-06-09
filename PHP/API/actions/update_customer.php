<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['customer_id', 'nama', 'alamat', 'no_telp', 'ktp', 'npwp', 'status', 'nitko', 'term_pembayaran', 'max_invoice', 'max_piutang','channel_id'];
    $filed = validate_1($data, $requiredFields);

    $customer_id = $data['customer_id'];
    $nama = $data['nama'];
    $no_telp = $data['no_telp'];
    $alamat = $data['alamat'];
    $ktp = $data['ktp'];
    $npwp = $data['npwp'];
    $status = $data['status'];
    $nitko = $data['nitko'];
    $term_pembayaran = $data['term_pembayaran'];
    $max_invoice = $data['max_invoice'];
    $max_piutang = $data['max_piutang'];
    $channel_id=$data['channel_id'];


    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validate_2($no_telp, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
    validate_2($ktp, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");
    validate_2($nitko, '/^[a-zA-Z0-9,. ]+$/', "Invalid NITKO format");
    validate_2($term_pembayaran, '/^[a-zA-Z0-9,. ]+$/', "Invalid term pembayaran format");
    validate_2($max_invoice, '/^[a-zA-Z0-9,. ]+$/', "Invalid max invoice fromat");
    validate_2($max_piutang, '/^[a-zA-Z0-9,. ]+$/', "Invalid max piutang formt");

    $stmt = $conn->prepare("UPDATE tb_customer SET nama=?,no_telp=?, alamat=?, ktp=?, npwp=?, status=? ,nitko=?,term_pembayaran=? ,
                            max_invoice=? ,max_piutang =?,channel_id =? WHERE customer_id=?");
    $stmt->bind_param("ssssssssssss", $nama, $no_telp, $alamat, $ktp, $npwp, $status, $nitko, $term_pembayaran, $max_invoice, $max_piutang,$channel_id, $customer_id);

    if ($stmt->execute()) {
        error_log("Customer updated successfully: ID = $customer_id");
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Customer berhasil terupdate"]);
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "An internal server error occurred"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

$stmt->close();
$conn->close();
