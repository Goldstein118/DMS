<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=3 && $search !=='') {
    $stmt = $conn->prepare("SELECT karyawan.karyawan_id, karyawan.nama, karyawan.role_id, role.nama AS role_nama, karyawan.departement, karyawan.no_telp, karyawan.alamat, karyawan.ktp, karyawan.npwp,karyawan.status,u.user_id
    FROM tb_karyawan karyawan 
    LEFT JOIN tb_role role ON karyawan.role_id = role.role_id 
    LEFT JOIN tb_user u ON u.karyawan_id = karyawan.karyawan_id
    WHERE karyawan.karyawan_id LIKE CONCAT ('%',?,'%')
    OR karyawan.nama LIKE CONCAT ('%',?,'%')
    OR role.nama LIKE CONCAT ('%',?,'%') 
    OR karyawan.departement LIKE CONCAT ('%',?,'%')
    OR karyawan.no_telp LIKE CONCAT ('%',?,'%')
    OR karyawan.alamat LIKE CONCAT ('%',?,'%')
    OR karyawan.ktp LIKE CONCAT ('%',?,'%')
    OR karyawan.npwp LIKE CONCAT ('%',?,'%')
    OR karyawan.status LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sssssssss',$search,$search,$search,$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT k.karyawan_id, k.nama, k.role_id, r.nama AS role_nama,
            k.departement, k.no_telp, k.alamat, k.ktp, k.npwp, k.status,
            u.user_id FROM tb_karyawan k
            LEFT JOIN tb_role r ON k.role_id = r.role_id
            LEFT JOIN tb_user u ON u.karyawan_id = k.karyawan_id";
$result = $conn->query($sql);
}

    if ($result) {
    $karyawan_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($karyawan_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>
