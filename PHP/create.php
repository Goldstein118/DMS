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

         if ($action === 'submit_user') {
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
        } elseif ($action === 'submit_role') {

            $requiredFields = ['name_role', 'akses_role'];
            $fields = validate_1($data, $requiredFields);

            $name_role = $fields['name_role'];
            $akses_role = $fields['akses_role'];
            // Validate fields
            validate_2($name_role, '/^[a-zA-Z\s]+$/', "Invalid name format");
            validate_2($akses_role, '/^[0-9]+$/', "Invalid division format");

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
            $default = ['supplier_status' => 'aktif'];
            $fields = validate_1($data, $requiredFields, $default);

            $supplier_nama = $fields['supplier_nama'];
            $supplier_alamat = $fields['supplier_alamat'];
            $supplier_no_telp = $fields['supplier_no_telp'];
            $supplier_ktp = $fields['supplier_ktp'];
            $supplier_npwp = $fields['supplier_npwp'];
            $supplier_status = $fields['supplier_status'];

            validate_2($supplier_nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
            validate_2($supplier_alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
            validate_2($supplier_no_telp, '/^[+]?[\d\s\-]+$/', "Invalid phone number format");
            validate_2($supplier_ktp, '/^[0-9]+$/', "Invalid KTP format");
            validate_2($supplier_npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");



            $supplier_id = generateCustomID('SU', 'tb_supplier', 'supplier_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_supplier (supplier_id, nama, alamat, no_telp, ktp, npwp, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$supplier_id, $supplier_nama, $supplier_alamat, $supplier_no_telp, $supplier_ktp, $supplier_npwp, $supplier_status],
                "sssssss"
            );
            echo json_encode(["success" => true, "message" => "Supplier saved successfully", "data" => ["supplier_id" => $supplier_id]]);
        } elseif ($action === 'submit_customer') {
            $requiredFields = ['name_customer', 'alamat_customer', 'no_telp_customer', 'nik_customer', 'npwp_customer', 'nitko', 'term_payment', 'max_invoice', 'max_piutang', 'status_customer'];
            $default = ['status_customer' => 'aktif'];
            $fields = validate_1($data, $requiredFields, $default);
            $nama_customer = $fields['name_customer'];
            $alamat_customer = $fields['alamat_customer'];
            $no_telp_customer = $fields['no_telp_customer'];
            $ktp_customer = $fields['nik_customer'];
            $npwp_customer = $fields['npwp_customer'];
            $nitko = $fields['nitko'];
            $term_payment = $fields['term_payment'];
            $max_invoice = $fields['max_invoice'];
            $max_piutang = $fields['max_piutang'];
            $status_customer = $fields['status_customer'];

            validate_2($nama_customer, '/^[a-zA-Z\s]+$/', "Invalid name format");
            validate_2($alamat_customer, '/^[a-zA-Z0-9, .-]+$/', "Invalid address format");
            validate_2($no_telp_customer, '/^[+]?[\d\s\-]+$/', "Invalid phone number format");
            validate_2($ktp_customer, '/^[0-9]+$/', "Invalid KTP format");
            validate_2($npwp_customer, '/^[0-9 .-]+$/', "Invalid NPWP format");
            validate_2($nitko, '/^[a-zA-Z0-9, .-]+$/', "Invalid nitko format");
            validate_2($term_payment, '/^[a-zA-Z0-9 ]+$/', "Invalid term payment format");
            validate_2($max_invoice, '/^[a-zA-Z0-9 ]+$/', "Invalid max invoice format");
            validate_2($max_piutang, '/^[a-zA-Z0-9 ]+$/', "Invalid msx piutang format");
            $customer_id = generateCustomID('CU', 'tb_customer', 'customer_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_customer (customer_id,nama,alamat,no_telp,ktp,npwp,status,nitko,term_pembayaran,max_invoice,max_piutang) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
                [$customer_id, $nama_customer, $alamat_customer, $no_telp_customer, $ktp_customer, $npwp_customer, $status_customer, $nitko, $term_payment, $max_invoice, $max_piutang],
                "sssssssssss"
            );

            echo json_encode(["success" => true, "message" => "Customer saved successfully", "data" => ["customer_id" => $customer_id]]);
        } elseif ($action === 'submit_channel') {
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

            echo json_encode(["success" => true, "message" => "Channel saved successfully", "data" => ["channel_id" => $channel_id]]);
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
