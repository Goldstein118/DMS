<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($data['table']) && $data['table'] === 'detail_invoice' && isset($data['invoice_id'])) {
    $invoice_id = $data['invoice_id'];

    $sql = "SELECT * FROM tb_detail_invoice WHERE invoice_id=?";
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

    $sql = "SELECT * FROM tb_biaya_tambahan_invoice WHERE invoice_id=?";
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
    $sql = "SELECT * FROM tb_invoice";
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
