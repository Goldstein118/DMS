<?php
if(!$conn){
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);
$upload_dir = __DIR__ . '/../../../uploads_produk';
$base_url = 'http://localhost/DMS/uploads_produk/';
function getImageUrl($gambar_id, $conn, $upload_dir, $base_url)
{


    // Fetch image metadata
    $sql = "SELECT internal_link, external_link, blob_data FROM tb_gambar_produk WHERE gambar_produk_id = ?";
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

if (strlen($search)>=3 && $search !=='') {
    $stmt = $conn->prepare("SELECT p.produk_id,p.nama,p.no_sku,p.status,p.harga_minimal,p.kategori_id,k.nama 
    AS kategori_nama,p.brand_id,b.nama AS brand_nama FROM
    tb_produk p 
    LEFT JOIN tb_kategori k ON p.kategori_id = k.kategori_id
    LEFT JOIN tb_brand b ON p.brand_id = b.brand_id WHERE p.produk_id LIKE CONCAT ('%',?,'%')
    OR p.nama LIKE CONCAT ('%',?,'%')
    OR p.no_sku LIKE CONCAT ('%',?,'%')
    OR p.status LIKE CONCAT ('%',?,'%')
    OR p.harga_minimal LIKE CONCAT ('%',?,'%')
    OR k.nama LIKE CONCAT ('%',?,'%')
    OR b.nama LIKE CONCAT ('%',?,'%')
    ");
    $stmt->bind_param('sssssss',$search,$search,$search,$search,$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT p.produk_id,p.nama,p.no_sku,p.status,p.harga_minimal,p.kategori_id,k.nama AS kategori_nama,p.brand_id,
    b.nama AS brand_nama,g.gambar_produk_id FROM
    tb_produk p 
    LEFT JOIN tb_kategori k ON p.kategori_id = k.kategori_id
    LEFT JOIN tb_brand b ON p.brand_id = b.brand_id
    LEFT JOIN tb_gambar_produk g ON g.produk_id=p.produk_id";
    

$result = $conn->query($sql);
}

    if ($result) {
    $produk_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $gambar_url = $row['gambar_produk_id']? getImageUrl($row['gambar_produk_id'],$conn,$upload_dir,$base_url) : null;
        $row['produk_link']=$gambar_url;
        $produk_data[] = $row;
    }
    http_response_code(200);
    echo json_encode($produk_data);
    } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
    }
?>
