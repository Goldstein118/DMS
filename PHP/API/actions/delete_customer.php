<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (!isset($data['customer_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "customer_id tidak ditemukan"]);
        exit;
    }

    $customer_id = $data['customer_id'];

    $conn->begin_transaction();


    $stmtSelect = $conn->prepare("SELECT internal_link FROM tb_gambar WHERE customer_id = ?");
    $stmtSelect->bind_param("s", $customer_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();


    while ($row = $result->fetch_assoc()) {
        $path = $row['internal_link'];
        if ($path && file_exists($path)) {
            unlink($path); 
        }
    }
    $stmtSelect->close();
    $stmtGambar = $conn->prepare("DELETE FROM tb_gambar WHERE customer_id = ?");
    $stmtGambar->bind_param("s", $customer_id);
    $stmtGambar->execute();
    $stmtGambar->close();


    $stmtCustomer = $conn->prepare("DELETE FROM tb_customer WHERE customer_id = ?");
    $stmtCustomer->bind_param("s", $customer_id);
    $stmtCustomer->execute();

    if ($stmtCustomer->affected_rows > 0) {
        $conn->commit();
        http_response_code(200);
        echo json_encode(["message" => "Customer, gambar, dan file berhasil dihapus"]);
    } else {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(["error" => "Customer tidak ditemukan"]);
    }

    $stmtCustomer->close();
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

$conn->close();
