<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if($search !==''&& strlen($search)>=3){
    $stmt =$conn->prepare("SELECT user.user_id, user.karyawan_id, karyawan.nama AS karyawan_nama , user.level 
    FROM tb_user user JOIN tb_karyawan karyawan ON user.karyawan_id = karyawan.karyawan_id WHERE user.user_id LIKE CONCAT ('%',?,'%')
    OR karyawan.nama LIKE CONCAT ('%',?,'%')
    OR user.level LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sss',$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $sql_user = "SELECT user.user_id, user.karyawan_id, karyawan.nama AS karyawan_nama , user.level 
    FROM tb_user user JOIN tb_karyawan karyawan ON user.karyawan_id = karyawan.karyawan_id";
    $result = $conn->query($sql_user);
}



if ($result) {
    $user_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $user_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($user_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
$conn->close();
?>