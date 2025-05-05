<?php
include 'db.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Function to generate custom ID
function generateCustomID($prefix, $table, $column, $conn) {
    $month = date('m'); // Current month
    $year = date('y');  // Last two digits of the current year

    // Get the last inserted ID from the table
    $query = "SELECT MAX($column) AS last_id FROM $table WHERE $column LIKE '$prefix$month$year-%'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $last_id = $row['last_id'] ?? null;

    // Extract the numeric part and increment it
    if ($last_id) {
        $last_number = (int)substr($last_id, -3); // Get the last 3 digits
        $new_number = str_pad($last_number + 1, 3, '0', STR_PAD_LEFT); // Increment and pad with zeros
    } else {
        $new_number = '001'; // Start with 001 if no previous ID exists for the current month
    }

    // Return the new custom ID
    return $prefix . $month . $year . '-' . $new_number;
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the JSON input

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $action = $data['action'] ?? null;

        if ($action == 'submit_karyawan') {
            $name_karyawan = $data['name_karyawan'] ?? null;
            $divisi_karyawan = $data['divisi_karyawan'] ?? null;
            $phone_karyawan = $data['phone_karyawan'] ?? null;
            $address_karyawan = $data['address_karyawan'] ?? null;
            $nik_karyawan = $data['nik_karyawan'] ?? null;
            $role_id = $data['role_id'] ?? null; // Foreign key for role
            // Generate custom ID for karyawan
            $id_karyawan = generateCustomID('KA', 'tb_karyawan', 'karyawan_ID', $conn);

            // Start a transaction
            $conn->begin_transaction();

            try {
                // Validate the data (basic validation)
                if ($name_karyawan && $divisi_karyawan && $phone_karyawan && $address_karyawan && $nik_karyawan && $role_id) {
                    // Insert the data into the tb_karyawan table
                    $stmt = $conn->prepare("INSERT INTO tb_karyawan (karyawan_ID, nama, divisi, noTelp, alamat, KTP_NPWP, role_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $id_karyawan, $name_karyawan, $divisi_karyawan, $phone_karyawan, $address_karyawan, $nik_karyawan, $role_id);

                    if (!$stmt->execute()) {
                        throw new Exception("Failed to save karyawan: " . $stmt->error);
                    }

                    $stmt->close();
                    // Generate custom ID for user
                    $id_user = generateCustomID('US', 'tb_user', 'user_ID', $conn);

                    // Insert the data into the tb_user table
                    $stmt = $conn->prepare("INSERT INTO tb_user (user_ID, karyawan_ID) VALUES (?, ?)");
                    $stmt->bind_param("ss", $id_user, $id_karyawan);

                    if (!$stmt->execute()) {
                        throw new Exception("Failed to save user: " . $stmt->error);
                    }

                    $stmt->close();

                    // Commit the transaction
                    $conn->commit();

                    echo json_encode(["success" => true, "message" => "Data saved successfully", "karyawan_id" => $id_karyawan, "user_id" => $id_user]);
                } else {
                    throw new Exception("Invalid input data");
                }
            } catch (Exception $e) {
                // Rollback the transaction on error
                $conn->rollback();
                echo json_encode(["success" => false, "message" => $e->getMessage()]);
            }
        } elseif ($action == 'submit_role') {
            $id_role = generateCustomID('RO', 'tb_role', 'role_ID', $conn);
            $name_role = $data['name_role'] ?? null;
            $akses_role = $data['akses_role'] ?? null;

            // Validate the data (basic validation)
            if ($name_role && $akses_role) {
                $stmt = $conn->prepare("INSERT INTO tb_role (role_ID, nama, akses) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $id_role, $name_role, $akses_role);

                if ($stmt->execute()) {
                    echo json_encode(["success" => true, "message" => "Role saved successfully", "role_id" => $id_role]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to save role: " . $stmt->error]);
                }

                $stmt->close();
            } else {
                echo json_encode(["success" => false, "message" => "Invalid input data"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid action"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}

$conn->close();
?>