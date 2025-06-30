<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if ($search !== ''&& strlen($search)>=3) {
    $stmt = $conn->prepare("SELECT r.role_id, r.nama, r.akses , u.user_id FROM tb_role r 
     JOIN tb_karyawan k ON r.role_id = k.role_id
     JOIN tb_user u ON u.karyawan_id=k.karyawan_id
    WHERE r.nama LIKE CONCAT ('%',?,'%')
    OR r.role_id LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql_role = "SELECT r.role_id,r.nama,r.akses FROM tb_role r";
    $result = $conn->query($sql_role);
}

    if ($result) {
        $role_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $role_data[] = $row;
        }
        http_response_code(200);
        echo json_encode($role_data);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
$conn->close();
?>