<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    file_put_contents('php://stderr', "Incoming DELETE data: " . print_r($data, true));

    if (!isset($data['supplier_id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "supplier_ID is missing"]);
        exit;
    }

    $supplier_id = $data['supplier_id'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM tb_supplier WHERE supplier_id = ?");
    if (!$stmt) {
        file_put_contents('php://stderr', "Prepare failed: " . $conn->error . "\n");
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $supplier_id);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Supplier deleted successfully"]);
    } else {
        file_put_contents('php://stderr', "Execute failed: " . $stmt->error . "\n");
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete supplier: " . $stmt->error]);
    }
    $stmt->close();
}
?>