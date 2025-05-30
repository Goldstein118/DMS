<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search)>=5 && $search !=='') {
    $stmt = $conn->prepare("SELECT karyawan.karyawan_id, karyawan.nama, karyawan.role_id, role.nama AS role_nama, karyawan.divisi, karyawan.no_telp, karyawan.alamat, karyawan.ktp, karyawan.npwp,karyawan.status
    FROM tb_karyawan karyawan JOIN tb_role role ON karyawan.role_id = role.role_id WHERE karyawan.karyawan_id LIKE CONCAT ('%',?,'%')
    OR karyawan.nama LIKE CONCAT ('%',?,'%')
    OR role.nama LIKE CONCAT ('%',?,'%') 
    OR karyawan.divisi LIKE CONCAT ('%',?,'%')
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
    $sql = "SELECT karyawan.karyawan_id, karyawan.nama, karyawan.role_id, role.nama AS role_nama,
               karyawan.divisi, karyawan.no_telp, karyawan.alamat, karyawan.ktp, karyawan.npwp, karyawan.status
            FROM tb_karyawan karyawan
            JOIN tb_role role ON karyawan.role_id = role.role_id";
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
