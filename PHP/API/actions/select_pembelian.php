<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($data['pembelian_id'])) {
    $pembelian_id = trim($data['pembelian_id']);
    $sql = "SELECT tanggal_pengiriman,tanggal_terima,tanggal_invoice,no_pengiriman,no_invoice_supplier FROM tb_pembelian WHERE pembelian_id=?";
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
    $sql = "SELECT * FROM tb_pembelian";
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
