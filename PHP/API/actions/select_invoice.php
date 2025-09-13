<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($data['table']) && $data['table'] === 'detail_invoice' && isset($data['invoice_id'])) {
    $invoice_id = $data['invoice_id'];



    $sql = "SELECT 
            d.detail_invoice_id,
            d.pembelian_id,
            d.produk_id,
            d.qty,
            d.harga,
            d.diskon,
            d.satuan_id,
            p.nama AS produk_nama,
            s.nama AS satuan_nama
        FROM tb_detail_invoice d
        LEFT JOIN tb_produk p ON p.produk_id = d.produk_id
        LEFT JOIN tb_satuan s ON s.satuan_id = d.satuan_id
        WHERE d.invoice_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === 'biaya_tambahan_invoice' && isset($data['invoice_id'])) {
    $invoice_id = $data['invoice_id'];

    $sql = "SELECT b.data_biaya_id , b.keterangan ,b.jlh,d.nama AS biaya_nama
        FROM tb_biaya_tambahan_invoice b
        LEFT JOIN tb_data_biaya d ON b.data_biaya_id = d.data_biaya_id
        WHERE b.invoice_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === 'invoice' && isset($data['invoice_id'])) {
    $invoice_id = $data['invoice_id'];

    $sql = "SELECT i.invoice_id,i.tanggal_invoice,i.no_invoice_supplier,i.tanggal_input_invoice,i.tanggal_po,i.tanggal_pengiriman,i.tanggal_terima,
    i.supplier_id,i.pembelian_id,i.keterangan,i.no_pengiriman,i.total_qty,i.ppn,i.nominal_ppn,i.diskon,i.nominal_pph,i.biaya_tambahan,i.sub_total,i.grand_total,
    i.created_on,i.created_by,i.status,s.nama AS supplier_nama FROM tb_invoice i
    LEFT JOIN tb_supplier s ON i.supplier_id= s.supplier_id
     WHERE invoice_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else {

    $sql = "SELECT i.invoice_id,i.tanggal_invoice,i.no_invoice_supplier,i.tanggal_input_invoice,i.tanggal_po,i.tanggal_pengiriman,i.tanggal_terima,
    i.supplier_id,i.pembelian_id,i.keterangan,i.no_pengiriman,i.total_qty,i.ppn,i.nominal_ppn,i.diskon,i.nominal_pph,i.biaya_tambahan,i.sub_total,i.grand_total,
    i.created_on,i.created_by,i.status,s.nama AS supplier_nama
    FROM tb_invoice i LEFT JOIN tb_supplier s ON i.supplier_id=s.supplier_id";
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
