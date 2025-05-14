<?php
include 'db.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

function generateCustomID($prefix, $table, $column, $conn) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) || !preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
        throw new Exception("Invalid table or column name");
    }

    $month = date('m');
    $year = date('y');
    $like_pattern = "$prefix$month$year-%";

    $query = $conn->prepare("SELECT MAX($column) AS last_id FROM $table WHERE $column LIKE ?");
    $query->bind_param("s", $like_pattern);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $last_id = $row['last_id'] ?? null;

    if ($last_id) {
        $last_number = (int)substr($last_id, -3);
        $new_number = str_pad($last_number + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_number = '001';
    }

    return $prefix . $month . $year . '-' . $new_number;
}

function executeInsert($conn, $query, $params, $types) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }
    $stmt->close();
}

try {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $action = $data['action'] ?? null;

    if ($action === 'submit_karyawan') {
        $name_karyawan = $data['name_karyawan'] ?? null;
        $divisi_karyawan = $data['divisi_karyawan'] ?? null;
        $phone_karyawan = $data['phone_karyawan'] ?? null;
        $address_karyawan = $data['address_karyawan'] ?? null;
        $nik_karyawan = $data['nik_karyawan'] ?? null;
        $role_id = $data['role_id'] ?? null;

        if (!$name_karyawan || !$divisi_karyawan || !$phone_karyawan || !$address_karyawan || !$nik_karyawan || !$role_id) {
            throw new Exception("Invalid input data");
        }

        $id_karyawan = generateCustomID('KA', 'tb_karyawan', 'karyawan_ID', $conn);
        $conn->begin_transaction();

        executeInsert(
            $conn,
            "INSERT INTO tb_karyawan (karyawan_ID, nama, divisi, noTelp, alamat, KTP_NPWP, role_ID) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$id_karyawan, $name_karyawan, $divisi_karyawan, $phone_karyawan, $address_karyawan, $nik_karyawan, $role_id],
            "sssssss"
        );

        $id_user = generateCustomID('US', 'tb_user', 'user_ID', $conn);
        executeInsert(
            $conn,
            "INSERT INTO tb_user (user_ID, karyawan_ID) VALUES (?, ?)",
            [$id_user, $id_karyawan],
            "ss"
        );

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Data saved successfully", "data" => ["karyawan_id" => $id_karyawan, "user_id" => $id_user]]);
    } elseif ($action === 'submit_role') {
        $name_role = $data['name_role'] ?? null;
        $akses_role = $data['akses_role'] ?? null;

        if (!$name_role || !$akses_role) {
            throw new Exception("Invalid input data");
        }

        $id_role = generateCustomID('RO', 'tb_role', 'role_ID', $conn);
        executeInsert(
            $conn,
            "INSERT INTO tb_role (role_ID, nama, akses) VALUES (?, ?, ?)",
            [$id_role, $name_role, $akses_role],
            "sss"
        );

        echo json_encode(["success" => true, "message" => "Role saved successfully", "data" => ["role_id" => $id_role]]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid action"]);
    }
} catch (Exception $e) {
    $conn->rollback();
    error_log($e->getMessage());
    echo json_encode(["success" => false, "message" => "An error occurred. Please try again later."]);
}

$conn->close();
?>