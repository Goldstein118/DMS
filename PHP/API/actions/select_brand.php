<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !==''){
    $stmt = $conn->prepare("SELECT * FROM tb_brand WHERE brand_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql="SELECT * FROM tb_brand";
    $result=$conn->query($sql);
}

    if ($result) {
    $brand_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $brand_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($brand_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>