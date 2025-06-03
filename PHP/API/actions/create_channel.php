<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_channel'];
    $field = validate_1($data, $requiredFields);
    $nama_channel = $field['name_channel'];
    validate_2($nama_channel, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $channel_id = generateCustomID('CH', 'tb_channel', 'channel_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_channel(channel_id,nama)VALUES (?,?)",
        [$channel_id, $nama_channel],
        "ss"
    );

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["channel_id" => $channel_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
