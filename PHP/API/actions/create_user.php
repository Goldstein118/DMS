<?php
require_once __DIR__ . '/../utils/helpers.php';
try {

    $requiredFields = ['karyawan_id', 'level'];
    $fields = validate_1($data, $requiredFields);

    $id_user = generateCustomID('US', 'tb_user', 'user_id', $conn);
    $id_karyawan = $fields['karyawan_id'];
    $level = $fields['level'];

    executeInsert(
        $conn,
        "INSERT INTO tb_user (user_id, karyawan_id, level) VALUES (?, ?, ?)",
        [$id_user, $id_karyawan, $level],
        "sss"
    );

    echo json_encode([
        "success" => true,
        "message" => "User saved successfully",
        "data" => ["user_id" => $id_user]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
