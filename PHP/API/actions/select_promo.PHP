<?php
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if ($search !== '' && strlen($search) >= 3) {
    $stmt = $conn->prepare("SELECT * FROM tb_promo
    WHERE promo_id LIKE CONCAT ('%',?,'%')
    OR nama LIKE CONCAT ('%',?,'%')
    OR tanggal_berlaku LIKE CONCAT ('%',?,'%')
    OR tanggal_selesai LIKE CONCAT ('%',?,'%')
    OR jenis_bonus LIKE CONCAT ('%',?,'%')
    OR akumulasi LIKE CONCAT ('%',?,'%')
    OR prioritas LIKE CONCAT ('%',?,'%')
    OR jenis_diskon LIKE CONCAT ('%',?,'%')
    OR jumlah_diskon LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('ssssssss', $search, $search, $search, $search, $search, $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['promo_id'])  && isset($data['table']) && $data['table'] == "tb_promo") {
    $promo_id = $data['promo_id'];
    $sql_kondisi = "SELECT * FROM tb_promo WHERE promo_id=?";
    $stmt = $conn->prepare($sql_kondisi);
    $stmt->bind_param("s", $promo_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['promo_id'])  && isset($data['table']) && $data['table'] == "tb_promo_kondisi") {
    $promo_id = $data['promo_id'];
    $sql_kondisi = "SELECT * FROM tb_promo_kondisi WHERE promo_id=?";
    $stmt = $conn->prepare($sql_kondisi);
    $stmt->bind_param("s", $promo_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['promo_id'])  && isset($data['table']) && $data['table'] == "tb_promo_bonus_barang") {
    $promo_id = $data['promo_id'];
    $sql_bonus = "SELECT b.promo_id,b.qty_bonus,b.jenis_diskon,b.jlh_diskon,b.produk_id,promo.nama AS nama_promo,promo.satuan_id,satuan.nama AS nama_satuan ,produk.nama AS nama_produk FROM tb_promo_bonus_barang b 
    LEFT JOIN tb_promo promo ON promo.promo_id = b.promo_id
    LEFT JOIN tb_satuan satuan ON promo.satuan_id = satuan.satuan_id
    LEFT JOIN tb_produk produk ON produk.produk_id = b.produk_id
    WHERE b.promo_id=?";
    $stmt = $conn->prepare($sql_bonus);
    $stmt->bind_param("s", $promo_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else if (isset($data['table']) && $data['table'] == "tb_promo_kondisi") {
    $sql_kondisi = "SELECT * FROM tb_promo_kondisi";
    $result = $conn->query($sql_kondisi);
} else if (isset($data['table']) && $data['table'] == "tb_promo_bonus_barang") {
    $sql_bonus = "SELECT * FROM tb_promo_bonus_barang";
    $result = $conn->query($sql_bonus);
} else {
    $sql_promo = "SELECT p.promo_id,p.nama,p.tanggal_berlaku,p.tanggal_selesai,p.jenis_bonus,p.akumulasi,p.kelipatan,p.prioritas,
    p.jenis_diskon,p.jumlah_diskon,p.quota,p.created_on,p.status,p.satuan_id,s.nama AS satuan_nama, p.jenis_promo FROM tb_promo p  
    LEFT JOIN tb_satuan s ON p.satuan_id = s.satuan_id";
    $result = $conn->query($sql_promo);
}

if ($result) {
    $promo_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $promo_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($promo_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
$conn->close();
