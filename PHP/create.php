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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function generateCustomID($prefix, $table, $column, $conn)
    {
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

    function executeInsert($conn, $query, $params, $types)
    {
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
        $stmt->close();
    }
    function validate_1($data, $requiredFields, $default = [])
    {
        $result = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                error_log("Missing or empty field: $field");
                http_response_code(400);
                echo json_encode(["success" => false, "error" => "Missing or empty field: $field"]);
                exit;
            }
            $result[$field] = trim($data[$field]);
        }
        foreach ($default as $field => $default) {
            if (!isset($result[$field]) || $result[$field] === '') {
                $result[$field] = $default;
            }
        }
        return $result;
    }
    function validate_2($field, $pattern, $errorMessage)
    {
        if (!preg_match($pattern, $field)) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => $errorMessage]);
            exit;
        }
    }



    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $action = $data['action'] ?? null;

        if ($action === 'submit_karyawan') {

            $requiredFields = ['name_karyawan', 'role_id', 'divisi_karyawan', 'phone_karyawan', 'address_karyawan', 'nik_karyawan', 'npwp_karyawan', 'status_karyawan'];
            $defaults = ['status_karyawan' => 'aktif'];
            $fields = validate_1($data, $requiredFields, $defaults);

            $nama_karyawan = $fields['name_karyawan'];
            $role_id = $fields['role_id'];
            $divisi_karyawan = $fields['divisi_karyawan'];
            $noTelp_karyawan = $fields['phone_karyawan'];
            $alamat_karyawan = $fields['address_karyawan'];
            $nik_karyawan = $fields['nik_karyawan'];
            $npwp_karyawan = $fields['npwp_karyawan'];
            $status_karyawan = $fields['status_karyawan'];

            // Validate fields
            validate_2($nama_karyawan, '/^[a-zA-Z\s]+$/', "Invalid name format");
            validate_2($divisi_karyawan, '/^[a-zA-Z0-9, ]+$/', "Invalid division format");
            validate_2($alamat_karyawan, '/^[a-zA-Z0-9, ]+$/', "Invalid address format");
            validate_2($noTelp_karyawan, '/^[+]?[\d\s\-()]+$/', "Invalid phone number format");
            validate_2($nik_karyawan, '/^[a-zA-Z0-9, ]+$/', "Invalid KTP format");
            validate_2($npwp_karyawan, '/^[a-zA-Z0-9, ]+$/', "Invalid NPWP format");


            $id_karyawan = generateCustomID('KA', 'tb_karyawan', 'karyawan_id', $conn);
            $conn->begin_transaction();

            executeInsert(
                $conn,
                "INSERT INTO tb_karyawan (karyawan_id, nama, divisi, no_telp, alamat, ktp, npwp, status, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$id_karyawan, $nama_karyawan, $divisi_karyawan, $noTelp_karyawan, $alamat_karyawan, $nik_karyawan, $npwp_karyawan, $status_karyawan, $role_id],
                "sssssssss"
            );
            $id_user = generateCustomID('US', 'tb_user', 'user_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_user (user_id, karyawan_id) VALUES (?, ?)",
                [$id_user, $id_karyawan],
                "ss"
            );

            $conn->commit();
            echo json_encode(["success" => true, "message" => "Data saved successfully", "data" => ["karyawan_id" => $id_karyawan, "user_id" => $id_user]]);
        } elseif ($action === 'submit_role') {

            $requiredFields = ['name_role', 'akses_role'];
            $fields = validate_1($data, $requiredFields);

            $name_role = $fields['name_role'];
            $akses_role = $fields['akses_role'];
            // Validate fields
            validate_2($name_role, '/^[a-zA-Z\s]+$/', "Invalid name format");
            validate_2($akses_role, '/^[a-zA-Z0-9, ]+$/', "Invalid division format");

            $id_role = generateCustomID('RO', 'tb_role', 'role_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_role (role_id, nama, akses) VALUES (?, ?, ?)",
                [$id_role, $name_role, $akses_role],
                "sss"
            );

            echo json_encode(["success" => true, "message" => "Role saved successfully", "data" => ["role_id" => $id_role]]);
        } elseif ($action === 'submit_supplier') {
            $requiredFields = ['supplier_nama', 'supplier_alamat', 'supplier_no_telp', 'supplier_ktp', 'supplier_npwp', 'supplier_status'];
            $defaults = ['supplier_status' => 'aktif'];
            $fields = validate_1($data, $requiredFields, $defaults);

            $supplier_nama = $fields['supplier_nama'];
            $supplier_alamat = $fields['supplier_alamat'];
            $supplier_no_telp = $fields['supplier_no_telp'];
            $supplier_ktp = $fields['supplier_ktp'];
            $supplier_npwp = $fields['supplier_npwp'];
            $supplier_status = $fields['supplier_status'];

            $supplier_id = generateCustomID('SU', 'tb_supplier', 'supplier_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_supplier (supplier_id, nama, alamat, no_telp, ktp, npwp, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$supplier_id, $supplier_nama, $supplier_alamat, $supplier_no_telp, $supplier_ktp, $supplier_npwp, $supplier_status],
                "sssssss"
            );

            echo json_encode(["success" => true, "message" => "Supplier saved successfully", "data" => ["supplier_id" => $supplier_id]]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid action"]);
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        echo json_encode(["success" => false, "message" => "An error occurred. Please try again later."]);
    }
}

$conn->close();
