<?php
require_once __DIR__ . '/../utils/helpers.php';
try{
$requiredFields = ['name_role', 'akses_role'];
$fields = validate_1($data, $requiredFields);

$name_role = $fields['name_role'];
$akses_role = $fields['akses_role'];
$id_role = generateCustomID('RO', 'tb_role', 'role_id', $conn);

validate_2($name_role, '/^[a-zA-Z\s]+$/', "Invalid name format");
validate_2($akses_role, '/^[0-9]+$/', "Invalid division format");


executeInsert(
    $conn,
    "INSERT INTO tb_role (role_id, nama, akses) VALUES (?, ?, ?)",
    [$id_role, $name_role, $akses_role],
    "sss"
);

echo json_encode(["success" => true, "message" => "Role saved successfully", "data" => ["role_id" => $id_role]]);
} catch(Exception $e){
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

