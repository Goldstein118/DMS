<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search) >= 3 && $search !== '') {
    $stmt = $conn->prepare("SELECT p.pricelist_id,p.nama,p.harga_default,p.status,p.tanggal_berlaku FROM tb_pricelist p 
    WHERE p.pricelist_id LIKE CONCAT('%',?,'%')
    OR p.nama LIKE CONCAT('%',?,'%')
    OR p.harga_default LIKE CONCAT('%',?,'%')
    OR p.status LIKE CONCAT('%',?,'%')
    OR p.tanggal_berlaku LIKE CONCAT('%',?,'%')");
    $stmt->bind_param('sssss', $search, $search, $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['pricelist_id'])) {
    $pricelist_id = trim($data['pricelist_id']);
    $sql = "SELECT d.detail_pricelist_id, d.harga, d.pricelist_id, d.produk_id,
            produk.nama AS produk_nama, price.nama AS price_nama , price.harga_default,price.status,price.tanggal_berlaku
            FROM tb_detail_pricelist d
            JOIN tb_pricelist price ON d.pricelist_id = price.pricelist_id
            JOIN tb_produk produk ON produk.produk_id = d.produk_id
            WHERE price.pricelist_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pricelist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    if (!empty($detail_data)) {
        echo json_encode($detail_data);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "No details found for this pricelist id"]);
    }
}
 else {
    $sql = "SELECT p.pricelist_id,p.nama,p.harga_default,p.status,p.tanggal_berlaku,d.detail_pricelist_id FROM tb_pricelist p
            JOIN tb_detail_pricelist d ON d.pricelist_id = p.pricelist_id";
    $result = $conn->query($sql);
    if ($result) {
        $pricelist_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pricelist_data[] = $row;
        }
        http_response_code(200);
        echo json_encode($pricelist_data);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
}
