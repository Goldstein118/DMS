<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if ($search !== '' && strlen($search) >= 3) {
    $stmt = $conn->prepare("SELECT * FROM tb_promo
    WHERE r.nama LIKE CONCAT ('%',?,'%')
    OR r.role_id LIKE CONCAT ('%',?,'%')");
    $stmt->bind_param('ss', $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['promo_id'])) {
    $promo_id = $data['promo_id'];
    $sql_kondisi = "SELECT * FROM tb_promo_kondisi";
    $result = $conn->query($sql_kondisi);
} else {
    $sql_promo = "SELECT * FROM tb_promo";
    $result = $conn->query($sql_promo);
}

if ($result) {
    $promo_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $promo_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($promo_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
$conn->close();
