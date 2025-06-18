<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !=='') {
    $stmt = $conn->prepare("
    ");
    $stmt->bind_param();
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT* FROM tb_pricelist";
$result = $conn->query($sql);
}

    if ($result) {
    $pricelist_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pricelist_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($pricelist_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>