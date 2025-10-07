<?php

if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search) >= 3 && $search !== '') {
    $stmt = $conn->prepare("SELECT * FROM tb_supplier WHERE supplier_id LIKE CONCAT ('%',?,'%')
    OR nama     LIKE CONCAT ('%',?,'%')
    OR alamat   LIKE CONCAT ('%',?,'%') 
    OR no_telp  LIKE CONCAT ('%',?,'%')
    OR ktp      LIKE CONCAT ('%',?,'%')
    OR npwp     LIKE CONCAT ('%',?,'%')
    OR status   LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sssssss', $search, $search, $search, $search, $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['select']) && $data['select'] === "select") {
    $sql = "SELECT * FROM tb_supplier WHERE status = 'aktif'";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM tb_supplier";
    $result = $conn->query($sql);
}

if ($result) {
    $supplier_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $supplier_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($supplier_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
