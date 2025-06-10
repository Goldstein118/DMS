<?php

if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !==''){
    $stmt = $conn->prepare("SELECT c.customer_id, c.nama, c.alamat, c.no_telp, c.ktp, c.npwp, c.status,
    c.nitko,c.term_pembayaran,c.max_invoice,c.max_piutang, c.channel_id ,ch.nama AS channel_nama FROM tb_customer c 
    JOIN tb_channel ch ON c.channel_id = ch.channel_id WHERE c.customer_id LIKE CONCAT ('%',?,'%')
    OR c.nama LIKE CONCAT ('%',?,'%')
    OR c.alamat LIKE CONCAT ('%',?,'%')
    OR c.no_telp LIKE CONCAT ('%',?,'%')
    OR c.ktp LIKE CONCAT ('%',?,'%')
    OR c.npwp LIKE CONCAT ('%',?,'%')
    OR c.status LIKE CONCAT ('%',?,'%')
    OR c.nitko LIKE CONCAT ('%',?,'%')
    OR c.term_pembayaran LIKE CONCAT ('%',?,'%')
    OR c.max_invoice LIKE CONCAT ('%',?,'%')
    OR c.max_piutang LIKE CONCAT ('%',?,'%')
    OR ch.nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('ssssssssssss',$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {


    $sql="SELECT c.customer_id, c.nama, c.alamat, c.no_telp, c.ktp, c.npwp, c.status,
    c.nitko, c.term_pembayaran, c.max_invoice, c.max_piutang, c.channel_id, ch.nama AS channel_nama,
    g_ktp.external_link AS ktp_link, g_npwp.external_link AS npwp_link
    FROM tb_customer c 
    JOIN tb_channel ch ON c.channel_id = ch.channel_id
    LEFT JOIN tb_gambar g_ktp ON g_ktp.customer_id = c.customer_id AND g_ktp.tipe = 'ktp'
    LEFT JOIN tb_gambar g_npwp ON g_npwp.customer_id = c.customer_id AND g_npwp.tipe = 'npwp'";

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