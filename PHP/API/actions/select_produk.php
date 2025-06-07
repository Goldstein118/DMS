<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !=='') {
    $stmt = $conn->prepare("SELECT p.produk_id,p.nama,p.no_sku,p.status,p.harga_minimal,p.kategori_id,k.nama 
    AS kategori_nama,p.brand_id,b.nama AS brand_nama FROM
    tb_produk p JOIN tb_kategori k ON p.kategori_id = k.kategori_id
    JOIN tb_brand b ON p.brand_id = b.brand_id WHERE p.produk_id LIKE CONCAT ('%',?,'%')
    OR p.nama LIKE CONCAT ('%',?,'%')
    OR p.no_sku LIKE CONCAT ('%',?,'%')
    OR p.status LIKE CONCAT ('%',?,'%')
    OR p.harga_minimal LIKE CONCAT ('%',?,'%')
    OR k.nama LIKE CONCAT ('%',?,'%')
    OR b.nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sssssss',$search,$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT p.produk_id,p.nama,p.no_sku,p.status,p.harga_minimal,p.kategori_id,k.nama AS kategori_nama,p.brand_id,b.nama AS brand_nama FROM
    tb_produk p JOIN tb_kategori k ON p.kategori_id = k.kategori_id
    JOIN tb_brand b ON p.brand_id = b.brand_id";
$result = $conn->query($sql);
}

    if ($result) {
    $produk_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produk_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($produk_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>
