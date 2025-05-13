<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST)'){
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['role_ID']) || !isset($data['role_name'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "role_ID or role_name is missing"]);
        exit;
    }
    $role_ID=$data['role_ID'];
    $role_name=$data['role_name'];
    $akses=$data['akses'];

    $stmt = $conn->prepare("UPDATE tb_role SET role_name = ?, akses = ? WHERE role_ID = ?");
    if(!$stmt){
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("sss", $role_name, $akses, $role_ID);
    if ($sql->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Role updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update role: " . $sql->error]);
    }
    $sql->close();
}

?>