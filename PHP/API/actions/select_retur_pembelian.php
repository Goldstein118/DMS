<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($data['table']) && $data['table'] === 'detail_retur_pembelian' && isset($data['retur_pembelian_id'])) {
    $retur_pembelian_id = $data['retur_pembelian_id'];



    $sql = "SELECT 
            d.retur_pembelian_id,
            d.pembelian_id,
            d.invoice_id,
            d.produk_id,
            d.qty,
            d.harga,
            d.diskon,
            d.satuan_id,
            p.nama AS produk_nama,
            s.nama AS satuan_nama
        FROM tb_detail_retur_pembelian d
        LEFT JOIN tb_produk p ON p.produk_id = d.produk_id
        LEFT JOIN tb_satuan s ON s.satuan_id = d.satuan_id
        WHERE d.retur_pembelian_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $retur_pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === 'retur_pembelian' && isset($data['retur_pembelian_id'])) {
    $retur_pembelian_id = $data['retur_pembelian_id'];

    $sql = "SELECT i.retur_pembelian_id, i.invoice_id,i.tanggal_invoice,i.no_invoice_supplier,i.tanggal_input_invoice,i.tanggal_po,i.tanggal_pengiriman,i.tanggal_terima,
    i.supplier_id,i.pembelian_id,i.keterangan,i.no_pengiriman,i.total_qty,i.ppn,i.nominal_ppn,i.diskon,i.nominal_pph,i.biaya_tambahan,i.sub_total,i.grand_total,
    i.created_on,i.created_by,i.status,s.nama AS supplier_nama FROM tb_retur_pembelian i
    LEFT JOIN tb_supplier s ON i.supplier_id= s.supplier_id
     WHERE i.retur_pembelian_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $retur_pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else {
    $sql = "SELECT * FROM tb_retur_pembelian";
    $result = $conn->query($sql);
    if ($result) {
        $invoice_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $invoice_data[] = $row;
        }
        http_response_code(200);
        echo json_encode($invoice_data);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
}
