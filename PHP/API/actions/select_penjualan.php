<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
if (isset($data['table']) && $data['table'] === 'tb_penjualan' && isset($data['penjualan_id'])) {
    $penjualan_id = $data['penjualan_id'];
    $sql = "SELECT *FROM tb_penjualan WHERE penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['table']) && $data['table'] === 'tb_detail_penjualan' && isset($data['penjualan_id'])) {
    $penjualan_id = $data['penjualan_id'];
    $sql = "SELECT *FROM tb_detail_penjualan WHERE penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT p.penjualan_id,p.tanggal_penjualan,p.keterangan_penjualan,p.gudang_id,p.promo_id,
    p.no_pengiriman,p.total_qty,p.ppn,p.nominal_ppn,p.diskon,p.nominal_pph,p.grand_total,p.created_on,p.created_by,p.status,g.nama AS gudang_nama
     FROM tb_penjualan p 
     LEFT JOIN tb_gudang g ON p.gudang_id=g.gudang_id
     ";
    $result = $conn->query($sql);
}


if ($result) {
    $pembelian_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pembelian_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($pembelian_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
