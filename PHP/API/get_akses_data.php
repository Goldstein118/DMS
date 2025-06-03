<?php
header('Content-Type: application/json');
require_once '../db.php';

$userId = $_GET['user_id'] ?? null;
if (!$userId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT u.level, r.akses ,k.nama
    FROM tb_user u
    JOIN tb_karyawan k ON u.karyawan_id = k.karyawan_id
    JOIN tb_role r ON k.role_id = r.role_id
    WHERE u.user_id = ?
");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode([
        "level" => $data['level'],
        "akses" => $data['akses'],
        "nama" => $data['nama']
    ]);
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}
?>
