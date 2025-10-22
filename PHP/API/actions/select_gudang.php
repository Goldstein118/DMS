<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search) >= 3 && $search !== '') {
    $stmt = $conn->prepare("SELECT * FROM tb_gudang WHERE gudang_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    OR status LIKE CONCAT('%',?,'%')
    ");
    $stmt->bind_param('sss', $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['select']) && $data['select'] === "select") {
    $sql = "SELECT * FROM tb_gudang WHERE status = 'aktif'";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM tb_gudang";
    $result = $conn->query($sql);
}

if ($result) {
    $gudang_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $gudang_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($gudang_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
