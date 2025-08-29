<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}


if (isset($data['table']) && $data['table'] === 'detail_pembelian' && isset($data['pembelian_id'])) {
    $pembelian_id = $data['pembelian_id'];

    $sql = "SELECT * FROM tb_detail_pembelian WHERE pembelian_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data["table"]) && $data["table"] === "biaya_tambahan" && isset($data["pembelian_id"])) {

    $pembelian_id = $data['pembelian_id'];

    $sql = "SELECT * FROM tb_biaya_tambahan WHERE pembelian_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === 'view_detail_pembelian' && isset($data['pembelian_id'])) {
    $pembelian_id = $data['pembelian_id'];

    $sql = "SELECT 
            d.detail_pembelian_id,
            d.pembelian_id,
            d.produk_id,
            d.qty,
            d.harga,
            d.diskon,
            d.satuan_id,
            p.nama AS produk_nama,
            s.nama AS satuan_nama
        FROM tb_detail_pembelian d
        LEFT JOIN tb_produk p ON p.produk_id = d.produk_id
        LEFT JOIN tb_satuan s ON s.satuan_id = d.satuan_id
        WHERE d.pembelian_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === "view_biaya_tambahan" && isset($data["pembelian_id"])) {

    $pembelian_id = $data['pembelian_id'];

    $sql = "SELECT 
           b.data_biaya_id , b.keterangan ,b.jlh,d.nama AS biaya_nama
        FROM tb_biaya_tambahan b
        LEFT JOIN tb_data_biaya d ON b.data_biaya_id = d.data_biaya_id
        WHERE b.pembelian_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['table']) && $data['table'] === "pembelian" && isset($data["pembelian_id"])) {

    $pembelian_id = $data['pembelian_id'];

    $sql = "SELECT p.pembelian_id,p.tanggal_po,p.tanggal_pengiriman,p.tanggal_terima,p.tanggal_invoice,p.supplier_id,p.keterangan,p.no_invoice_supplier,
    p.no_pengiriman,p.total_qty,p.ppn,p.nominal_ppn,p.diskon,p.nominal_pph,p.biaya_tambahan,p.sub_total,p.grand_total,p.created_on,p.created_by,p.status,s.nama AS supplier_nama
     FROM tb_pembelian p LEFT JOIN tb_supplier s ON p.supplier_id=s.supplier_id WHERE p.pembelian_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $detail_data[] = $row;
    }

    echo json_encode(["data" => $detail_data]);
    exit;
} else if (isset($data['pembelian_id']) && isset($data['select'])) {
    $pembelian_id = trim($data['pembelian_id']);
    $sql = "SELECT * FROM tb_pembelian WHERE pembelian_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt == false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
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
        echo json_encode(["error" => "No details found for this pembelian id"]);
    }
} else if (isset($data['pembelian_id'])) {
    $pembelian_id = trim($data['pembelian_id']);
    $sql = "SELECT tanggal_po,tanggal_pengiriman,tanggal_terima,tanggal_invoice,no_pengiriman,no_invoice_supplier FROM tb_pembelian WHERE pembelian_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt == false) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $pembelian_id);
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
        echo json_encode(["error" => "No details found for this pembelian id"]);
    }
} else {
    $sql = "SELECT p.pembelian_id,p.tanggal_po,p.tanggal_pengiriman,p.tanggal_terima,p.tanggal_invoice,p.supplier_id,p.keterangan,p.no_invoice_supplier,
    p.no_pengiriman,p.total_qty,p.ppn,p.nominal_ppn,p.diskon,p.nominal_pph,p.biaya_tambahan,p.grand_total,p.created_on,p.created_by,p.status,s.nama AS supplier_nama
     FROM tb_pembelian p LEFT JOIN tb_supplier s ON p.supplier_id=s.supplier_id";
    $result = $conn->query($sql);
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
}
