<?php
function checkAccess($conn, $userId, $table, $index, $target_user_id = null) {
    $accessMap = [
        'tb_karyawan' => 0,
        'tb_user' => 4,
        'tb_role' => 8,
        'tb_supplier' => 12,
        'tb_customer' => 16,
        'tb_channel' => 20,
        'tb_kategori' => 24,
        'tb_brand' => 28,
        'tb_produk' => 32,
        'tb_divisi'=>36
    ];




// Proceed to update the user with ID = $target_user_id

    if (!isset($accessMap[$table])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid table name"]);
        exit;
    }

    $startIndex = $index;

    // Get user's level and akses string
    $stmt = $conn->prepare("
        SELECT u.level, r.akses
        FROM tb_user u
        JOIN tb_karyawan k ON u.karyawan_id = k.karyawan_id
        JOIN tb_role r ON k.role_id = r.role_id
        WHERE u.user_id = ?
    ");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(["error" => "Access denied (user not found)"]);
        exit;
    }

    $row = $result->fetch_assoc();
    $level = strtolower($row['level'] ?? '');
    $aksesString = $row['akses'] ?? '';

    // Owner has full access
    if ($level === 'owner') {
        return true;
    }


    // Regular access check
    if (strlen($aksesString) <= $startIndex || $aksesString[$startIndex] !== '1') {
        http_response_code(403);
        echo json_encode(["error" => $level , $aksesString. `Access denied`]);
        exit;
    }
}

?>