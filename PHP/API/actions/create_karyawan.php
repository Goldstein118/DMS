<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['name_karyawan', 'role_id', 'departement_karyawan', 'status_karyawan'];
    $default = ['status_karyawan' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    $nama = $fields['name_karyawan'];
    $role_id = $fields['role_id'];
    $departement = $fields['departement_karyawan'];
    $status = $fields['status_karyawan'];

    $telp = $fields['no_telp_karyawan'] ?? '';
    $alamat = $fields['address_karyawan'] ?? '';
    $ktp = $fields['nik_karyawan'] ?? '';
    $npwp = $fields['npwp_karyawan'] ?? '';

    // Validate only if not empty
    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validate_2($telp, '/^[+]?[\d\s\-]+$/', "Invalid phone format");
    validate_2($ktp, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");

    // Generate ID and insert
    $karyawan_id = generateCustomID('KA', 'tb_karyawan', 'karyawan_id', $conn);

    $stmt = $conn->prepare("INSERT INTO tb_karyawan (karyawan_id, nama, departement, no_telp, alamat, ktp, npwp, status, role_id) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $karyawan_id, $nama, $departement, $telp, $alamat, $ktp, $npwp, $status, $role_id);
    if (!$stmt->execute()) {
        throw new Exception("DB insert error: " . $stmt->error);
    }

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["karyawan_id" => $karyawan_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
