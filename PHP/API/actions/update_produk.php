<?php
require_once __DIR__ . '/../utils/helpers.php';
$upload_dir = __DIR__ . '/../../../uploads_produk';
$base_url = 'http://localhost/DMS/uploads_produk/';

function handle_image_remove($produk_id, $conn)
{
    $check = $conn->prepare("SELECT gambar_produk_id, internal_link FROM tb_gambar_produk WHERE produk_id=?");
    $check->bind_param("s", $produk_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $old = $result->fetch_assoc();
        $old_path = $old['internal_link'];
        if (file_exists($old_path)) {
            unlink($old_path);
        }

        $stmt = $conn->prepare("DELETE FROM tb_gambar_produk WHERE produk_id=?");
        $stmt->bind_param("s", $produk_id);
        $stmt->execute();
        $stmt->close();
    }

    $check->close();
}


function resizeImage($file, $maxWidth = 1280, $maxHeight = 720)
{
    $imgInfo = getimagesize($file);
    if (!$imgInfo) return false;

    list($width, $height) = $imgInfo;
    if ($width <= $maxWidth && $height <= $maxHeight) {
        return file_get_contents($file);
    }

    $src = imagecreatefromstring(file_get_contents($file));
    if (!$src) return false;

    $scale = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int) ($width * $scale);
    $newHeight = (int) ($height * $scale);

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    ob_start();
    imagejpeg($dst, null, 85);
    $imageData = ob_get_clean();

    imagedestroy($src);
    imagedestroy($dst);

    return $imageData;
}

function handleImageUpload($field, $produk_id, $conn, $upload_dir, $base_url)
{
    if (
        isset($_FILES[$field]) &&
        $_FILES[$field]['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES[$field]['tmp_name'])
    ) {
        $file = $_FILES[$field]['tmp_name'];

        $imgInfo = getimagesize($file);
        if (!$imgInfo) throw new Exception("Invalid image for $field.");

        $mime = mime_content_type($file);
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($mime, $allowed)) throw new Exception("Unsupported format for $field.");

        $blobData = resizeImage($file);
        $filename = uniqid("produk" . '_') . '.jpg';
        $filepath = $upload_dir . '/' . $filename;
        $external_link = $base_url . $filename;

        file_put_contents($filepath, $blobData);
        $internal_link = $filepath;


        $check = $conn->prepare("SELECT gambar_produk_id, internal_link FROM tb_gambar_produk WHERE produk_id=?");
        $check->bind_param("s", $produk_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $old = $result->fetch_assoc();
            $old_path = $old['internal_link'];
            if (file_exists($old_path)) {
                unlink($old_path); // Delete old file from the server
            }
            $stmt = $conn->prepare("UPDATE tb_gambar_produk SET internal_link=?, external_link=?, blob_data=? WHERE produk_id=?");
            $stmt->bind_param("ssss", $internal_link, $external_link, $blobData, $produk_id);
        } else {
            $gambar_id = generateCustomID('IMGP', 'tb_gambar_produk', 'gambar_produk_id', $conn);
            $stmt = $conn->prepare("INSERT INTO tb_gambar_produk (gambar_produk_id, produk_id, internal_link, external_link, blob_data) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $gambar_id, $produk_id, $internal_link, $external_link, $blobData);
        }

        $stmt->execute();
        $stmt->close();
    }
}

try {
    $requiredFields = ['produk_id', 'nama', 'kategori_id', 'brand_id', 'status'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    $produk_id = $fields['produk_id'];
    $nama = $fields['nama'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku'] ?? '';
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal'] ?? '';
    $stock_awal = $fields['stock_awal'] ?? '';
    $satuan_id = $fields['satuan_id'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,.\- ]*$/', "Invalid SKU format");
    validate_2($harga_minimal, '/^[0-9., ]+$/', "Invalid harga minimal format");
    if (isset($data['remove_produk_file']) && $data['remove_produk_file'] === 'true') {
        handle_image_remove($produk_id, $conn);
    }

    $stmt = $conn->prepare("UPDATE tb_produk SET nama = ?, no_sku = ?, status = ?, harga_minimal = ?, kategori_id = ?, brand_id = ? ,stock_awal=?,satuan_id=?
                            WHERE produk_id = ?");
    $stmt->bind_param("sssssssss", $nama, $no_sku, $status, $harga_minimal, $kategori_id, $brand_id, $stock_awal, $satuan_id, $produk_id);
    if (!$stmt->execute()) throw new Exception("Product update failed: " . $stmt->error);
    $stmt->close();

    if (isset($data['details']) && is_string($data['details'])) {
        $data['details'] = json_decode($data['details'], true);
        $stmt_delete = $conn->prepare("DELETE FROM tb_detail_pricelist WHERE pricelist_id = ? AND produk_id = ?");

        $stmt_insert = $conn->prepare("INSERT INTO tb_detail_pricelist (detail_pricelist_id, harga, pricelist_id, produk_id) 
                                       VALUES (?, ?, ?, ?)");
        foreach ($data['details'] as $item) {
            $pricelist_id = $item['pricelist_id'];
            $harga = $item['harga'];

            $stmt_delete->bind_param("ss", $pricelist_id, $produk_id);
            $stmt_delete->execute();


            $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
            $stmt_insert->bind_param("ssss", $detail_pricelist_id, $harga, $pricelist_id, $produk_id);
            $stmt_insert->execute();
        }

        $stmt_delete->close();
        $stmt_insert->close();
    } else {
    }
    handleImageUpload('produk_file', $produk_id, $conn, $upload_dir, $base_url);

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Produk & Pricelist berhasil diupdate"]);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error updating produk: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}


$conn->close();
