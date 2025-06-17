<?php
require_once __DIR__ . '/../utils/helpers.php';
$upload_dir = __DIR__ . '/../../../uploads';
$base_url = 'http://localhost/DMS/uploads/';
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

function getImageUrl($gambar_id, $conn, $upload_dir, $base_url)
{


    // Fetch image metadata
    $sql = "SELECT internal_link, external_link, blob_data FROM tb_gambar WHERE gambar_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $gambar_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return null;

    $row = $result->fetch_assoc();
    $internal_link = $row['internal_link'];
    $external_link = $row['external_link'];
    $blob_data = $row['blob_data'];
    $not_empty = !empty($internal_link) && file_exists($internal_link) && is_readable($internal_link);
    // Case 1: File exists already
    if ($not_empty) {
        return convertFilePathToUrl($internal_link);
    }



    if ((!$not_empty || empty($external_link)) && !empty($blob_data)) {
        // Ensure directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Build paths
        $fileName = $gambar_id . '.jpg'; // Adjust extension if needed
        $internal_link = $upload_dir . '/' . $fileName;
        $external_link = $base_url . '/' . $fileName;
    }

    // Save blob data to disk
    if (!empty($blob_data) && !empty($internal_link)) {
        if (file_put_contents($internal_link, $blob_data) !== false) {
            // Save generated paths back to DB if needed
            $updateStmt = $conn->prepare("UPDATE tb_gambar SET internal_link = ?, external_link = ? WHERE gambar_id = ?");
            $updateStmt->bind_param("sss", $internal_link, $external_link, $gambar_id);
            $updateStmt->execute();

            return $external_link;
        }
    }

    // All else fails
    return null;
}

function convertFilePathToUrl($filePath)
{
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $realPath = realpath($filePath);
    if (!$realPath) return '/uploads/placeholder.jpg';

    if (strpos($realPath, $documentRoot) === 0) {
        $relativePath = str_replace('\\', '/', substr($realPath, strlen($documentRoot)));
        return $relativePath;
    }

    return '/uploads/placeholder.jpg';
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

if (strlen($search) >= 3 && $search !== '') {
    $stmt = $conn->prepare("SELECT 
            c.customer_id, c.nama, c.alamat, c.no_telp, c.ktp, c.npwp, c.status,
            c.nitko, c.term_pembayaran, c.max_invoice, c.max_piutang, c.channel_id,
            ch.nama AS channel_nama,
            g_ktp.gambar_id AS ktp_id,
            g_npwp.gambar_id AS npwp_id
        FROM tb_customer c
        LEFT JOIN tb_channel ch ON c.channel_id = ch.channel_id
        LEFT JOIN tb_gambar g_ktp ON g_ktp.customer_id = c.customer_id AND g_ktp.tipe = 'ktp'
        LEFT JOIN tb_gambar g_npwp ON g_npwp.customer_id = c.customer_id AND g_npwp.tipe = 'npwp'
        WHERE c.customer_id LIKE CONCAT('%', ?, '%') LIMIT 100
           OR c.nama LIKE CONCAT('%', ?, '%')LIMIT 100
           OR c.alamat LIKE CONCAT('%', ?, '%')LIMIT 100
           OR c.no_telp LIKE CONCAT('%', ?, '%') LIMIT 100
           OR c.ktp LIKE CONCAT('%', ?, '%') LIMIT 100
           OR c.npwp LIKE CONCAT('%', ?, '%')LIMIT 100
           OR c.status LIKE CONCAT('%', ?, '%')LIMIT 100
           OR c.nitko LIKE CONCAT('%', ?, '%')LIMIT 100
           OR c.term_pembayaran LIKE CONCAT('%', ?, '%') LIMIT 100
           OR c.max_invoice LIKE CONCAT('%', ?, '%') LIMIT 100
           OR c.max_piutang LIKE CONCAT('%', ?, '%')LIMIT 100
           OR ch.nama LIKE CONCAT('%', ?, '%') LIMIT 100
    ");
    $stmt->bind_param('ssssssssssss', $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {


    $sql = "SELECT c.customer_id, c.nama, c.alamat, c.no_telp, c.ktp, c.npwp, c.status,
        c.nitko, c.term_pembayaran, c.max_invoice, c.max_piutang, c.longitude, c.latitude,
        c.channel_id, ch.nama AS channel_nama,
        g_ktp.gambar_id AS ktp_id,
        g_npwp.gambar_id AS npwp_id
        FROM tb_customer c
        LEFT JOIN tb_channel ch ON c.channel_id = ch.channel_id
        LEFT JOIN tb_gambar g_ktp ON g_ktp.customer_id = c.customer_id AND g_ktp.tipe = 'ktp'
        LEFT JOIN tb_gambar g_npwp ON g_npwp.customer_id = c.customer_id AND g_npwp.tipe = 'npwp'";


    $result = $conn->query($sql);
}

if ($result) {
    $customer_data = [];


    while ($row = mysqli_fetch_assoc($result)) {
        $ktp_url = $row['ktp_id'] ? getImageUrl($row['ktp_id'], $conn, $upload_dir, $base_url) : null;
        $npwp_url = $row['npwp_id'] ? getImageUrl($row['npwp_id'], $conn, $upload_dir, $base_url) : null;

        $row['ktp_link'] = $ktp_url;
        $row['npwp_link'] = $npwp_url;
        $customer_data[] = $row;
    }

    http_response_code(200);
    echo json_encode($customer_data);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}
