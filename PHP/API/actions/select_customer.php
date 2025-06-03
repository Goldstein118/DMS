<?php

if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !==''){
    $stmt = $conn->prepare("SELECT * FROM tb_customer WHERE customer_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    OR alamat LIKE CONCAT ('%',?,'%')
    OR no_telp LIKE CONCAT ('%',?,'%')
    OR ktp LIKE CONCAT ('%',?,'%')
    OR npwp LIKE CONCAT ('%',?,'%')
    OR status LIKE CONCAT ('%',?,'%')
    OR nitko LIKE CONCAT ('%',?,'%')
    OR term_pembayaran LIKE CONCAT ('%',?,'%')
    OR max_invoice LIKE CONCAT ('%',?,'%')
    OR max_piutang LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('sssssssssss',$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql="SELECT * FROM tb_customer";
    $result=$conn->query($sql);
}

    if ($result) {
    $customer_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customer_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($customer_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>