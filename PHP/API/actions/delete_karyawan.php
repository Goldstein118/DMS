
<?php
if (!isset($data['karyawan_ID'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "karyawan_ID is missing"]);
    exit;
}

$karyawan_ID = $data['karyawan_ID'];

// Prepare the DELETE statement
$stmt = $conn->prepare("DELETE FROM tb_karyawan WHERE karyawan_id = ?");
if (!$stmt) {
    file_put_contents('php://stderr', "Prepare failed: " . $conn->error . "\n");
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $karyawan_ID);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["message" => "Karyawan berhasil terhapus"]);
} else {
    // Check for foreign key constraint failure (MySQL error code 1451)
    if ($stmt->errno == 1451) {
        http_response_code(409); // Conflict
        echo json_encode(["error" => "Karyawan tidak dapat dihapus karena masih digunakan di tabel lain."]);
    } else {
        file_put_contents('php://stderr', "Execute failed: " . $stmt->error . "\n");
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete karyawan: " . $stmt->error]);
    }
}

$stmt->close();
?>
