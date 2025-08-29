<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

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
