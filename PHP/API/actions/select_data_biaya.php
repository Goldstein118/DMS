<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search) >= 3 && $search !== '') {
    $stmt = $conn->prepare("SELECT * FROM tb_data_biaya WHERE data_biaya_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sss', $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM tb_data_biaya";
    $result = $conn->query($sql);
}

if ($result) {
    $data_biaya_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data_biaya_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($data_biaya_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
