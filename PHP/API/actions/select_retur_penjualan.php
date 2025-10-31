<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
if (isset($data['table']) && $data['table'] === 'tb_retur_penjualan' && isset($data['retur_penjualan_id'])) {
    $retur_penjualan_id = $data['retur_penjualan_id'];
    $sql = "SELECT *FROM tb_retur_penjualan WHERE retur_penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $retur_penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['table']) && $data['table'] === 'view_retur_penjualan' && isset($data['retur_penjualan_id'])) {
    $retur_penjualan_id = $data['retur_penjualan_id'];
    $sql = "SELECT penjualan.retur_penjualan_id,penjualan.penjualan_id,penjualan.tanggal_penjualan,penjualan.customer_id,penjualan.promo_id,penjualan.gudang_id,penjualan.keterangan_penjualan,penjualan.no_pengiriman,penjualan.total_qty,penjualan.ppn,penjualan.nominal_ppn,penjualan.diskon,penjualan.nominal_pph,penjualan.sub_total,penjualan.grand_total,penjualan.status,penjualan.keterangan_invoice,penjualan.keterangan_pengiriman,penjualan.keterangan_gudang,customer.nama AS nama_customer,customer.alamat,customer.nama_jalan,customer.rt,customer.kelurahan,customer.kecamatan,customer.jenis_customer,gudang.nama AS nama_gudang FROM tb_retur_penjualan penjualan 
    LEFT JOIN tb_customer customer ON customer.customer_id=penjualan.customer_id
    LEFT JOIN tb_gudang gudang ON gudang.gudang_id = penjualan.gudang_id
    WHERE retur_penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $retur_penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['table']) && $data['table'] === 'tb_detail_retur_penjualan' && isset($data['retur_penjualan_id'])) {
    $retur_penjualan_id = $data['retur_penjualan_id'];
    $sql = "SELECT *FROM tb_detail_retur_penjualan WHERE retur_penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $retur_penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['table']) && $data['table'] === 'view_detail_retur_penjualan' && isset($data['retur_penjualan_id'])) {
    $retur_penjualan_id = $data['retur_penjualan_id'];
    $sql = "SELECT detail.retur_penjualan_id,detail.produk_id,detail.urutan,detail.qty,detail.harga,detail.diskon,detail.satuan_id,produk.nama AS nama_produk,satuan.nama AS nama_satuan FROM tb_detail_retur_penjualan detail 
    LEFT JOIN tb_produk produk ON detail.produk_id = produk.produk_id
    LEFT JOIN tb_satuan satuan ON detail.satuan_id = satuan.satuan_id
    WHERE retur_penjualan_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $retur_penjualan_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT p.retur_penjualan_id, p.penjualan_id,p.tanggal_penjualan,p.keterangan_penjualan,p.gudang_id,p.promo_id,p.customer_id,
    p.no_pengiriman,p.total_qty,p.ppn,p.nominal_ppn,p.diskon,p.nominal_pph,p.grand_total,p.created_on,p.created_by,p.status,g.nama AS gudang_nama,c.nama AS customer_nama
     FROM tb_retur_penjualan p 
     LEFT JOIN tb_gudang g ON p.gudang_id=g.gudang_id
     LEFT JOIN tb_customer c ON c.customer_id=p.customer_id
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
