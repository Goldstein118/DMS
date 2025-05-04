<?php
include 'db.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
/*$create_Role = "INSERT INTO tb_role (role_ID, nama, akses) VALUES
(1, 'Admin', 'Full Access'),
(2, 'User', 'Limited Access'),
(3, 'Guest', 'Read Only')";
//sementara akses
if($conn->query($create_Role) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error inserting data: " . $conn->error;
}


$create_user = "INSERT INTO tb_User (user_ID, karyawan_ID) VALUES
(1, 1),
(2, 2)";
if($conn->query($create_user) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error inserting data: " . $conn->error;
}
// Include your database connection
*/
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Extract data from the JSON
    $id = $data['id'] ?? null;
    $name = $data['name'] ?? null;
    $divisi = $data['divisi'] ?? null;
    $phone = $data['phone'] ?? null;
    $address = $data['address'] ?? null;
    $nik = $data['nik'] ?? null;

    // Validate the data (basic validation)
    if ($name && $divisi && $phone && $address && $nik) {
        // Insert the data into the database
        $stmt = $conn->prepare("INSERT INTO tb_Karyawan (karyawan_ID, nama, divisi, noTelp, alamat, KTP_NPWP) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $id, $name, $divisi, $phone, $address, $nik);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Data saved successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to save data: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input data"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}


$conn->close();
?>