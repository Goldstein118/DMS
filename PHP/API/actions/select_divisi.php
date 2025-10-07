<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !=='') {
    $stmt = $conn->prepare("SELECT * FROM tb_divisi WHERE divisi_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    OR bank LIKE CONCAT ('%',?,'%') 
    OR nama_rekening LIKE CONCAT ('%',?,'%')
    OR no_rekening LIKE CONCAT ('%',?,'%')

    ");
    $stmt->bind_param('sssss',$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM tb_divisi"; 
    $result = $conn->query($sql);
}

    if ($result) {
    $divisi_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $divisi_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($divisi_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>