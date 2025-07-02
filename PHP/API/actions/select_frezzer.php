<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !==''){
    $stmt = $conn->prepare("SELECT * FROM tb_frezzer WHERE frezzer_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql="SELECT * FROM tb_frezzer";
    $result=$conn->query($sql);
}

    if ($result) {
    $frezzer_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $frezzer_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($frezzer_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>