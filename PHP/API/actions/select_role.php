<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if ($search !== ''&& strlen($search)>=5) {
    $stmt = $conn->prepare("SELECT role_id, nama, akses
    FROM tb_role WHERE nama LIKE CONCAT ('%',?,'%')
    OR role_id LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('ss',$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql_role = "SELECT * FROM tb_role";
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