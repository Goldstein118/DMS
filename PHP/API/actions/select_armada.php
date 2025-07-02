<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !==''){
    $stmt = $conn->prepare("SELECT * FROM tb_armada WHERE armada_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt -> bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql="SELECT a.armada_id,a.nama,a.karyawan_id,k.nama AS nama_karyawan FROM tb_armada a 
    LEFT JOIN tb_karyawan k ON k.karyawan_id=a.karyawan_id ";
    $result=$conn->query($sql);
}

    if ($result) {
    $armada_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $armada_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($armada_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>