<?php
require_once __DIR__ . '/../utils/helpers.php';

$upload_dir = __DIR__ . '/../../../uploads';
$base_url = 'http://localhost/DMS/uploads/';



function handle_image_remove($tipe, $customer_id, $conn)
{
    $check = $conn->prepare("SELECT gambar_id, internal_link FROM tb_gambar WHERE customer_id=? AND tipe=?");
    $check->bind_param("ss", $customer_id, $tipe);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $old = $result->fetch_assoc();
        $old_path = $old['internal_link'];
        if (file_exists($old_path)) {
            unlink($old_path);
        }

        $stmt = $conn->prepare("DELETE FROM tb_gambar WHERE customer_id=? AND tipe=?");
        $stmt->bind_param("ss", $customer_id, $tipe);
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

function handleImageUpload($field, $tipe, $customer_id, $conn, $upload_dir, $base_url)
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
        $filename = uniqid($tipe . '_') . '.jpg';
        $filepath = $upload_dir . '/' . $filename;
        $external_link = $base_url . $filename;

        file_put_contents($filepath, $blobData);
        $internal_link = $filepath;


        $check = $conn->prepare("SELECT gambar_id, internal_link FROM tb_gambar WHERE customer_id=? AND tipe=?");
        $check->bind_param("ss", $customer_id, $tipe);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $old = $result->fetch_assoc();
            $old_path = $old['internal_link'];
            if (file_exists($old_path)) {
                unlink($old_path); // Delete old file from the server
            }
            $stmt = $conn->prepare("UPDATE tb_gambar SET internal_link=?, external_link=?, blob_data=? WHERE customer_id=? AND tipe=?");
            $stmt->bind_param("sssss", $internal_link, $external_link, $blobData, $customer_id, $tipe);
        } else {
            $gambar_id = generateCustomID('IMG', 'tb_gambar', 'gambar_id', $conn);
            $stmt = $conn->prepare("INSERT INTO tb_gambar (gambar_id, tipe, customer_id, internal_link, external_link, blob_data) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $gambar_id, $tipe, $customer_id, $internal_link, $external_link, $blobData);
        }

        $stmt->execute();
        $stmt->close();
    }
}

try {
    $requiredFields = ['customer_id', 'nama', 'status', 'channel_id', 'pricelist_id'];
    $fields = validate_1($data, $requiredFields);

    $customer_id = $fields['customer_id'];
    $nama = $fields['nama'];
    $alamat = $fields['alamat'] ?? '';
    $no_telp = $fields['no_telp'] ?? '';
    $ktp = $fields['ktp'] ?? '';
    $npwp = $fields['npwp'] ?? '';
    $status = $fields['status'];
    $nitko = $fields['nitko'] ?? '';
    $term_pembayaran = $fields['term_pembayaran'] ?? '';
    $max_invoice = $fields['max_invoice'] ?? '';
    $max_piutang = $fields['max_piutang'] ?? '';
    $longitude = ($fields['longitude'] ?? '') !== '' ? $fields['longitude'] : null;
    $latitude = ($fields['latitude'] ?? '') !== '' ? $fields['latitude'] : null;
    $channel_id = $fields['channel_id'];
    $pricelist_id = $fields['pricelist_id'];
    $jenis_customer = $fields['jenis_customer'];

    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat, '/^[a-zA-Z0-9,. ]+$/', "Invalid address format");
    validate_2($no_telp, '/^[+]?[\d\s\-()]+$/', "Invalid phone format");
    validate_2($ktp, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp, '/^[0-9 .-]+$/', "Invalid NPWP format");
    validate_2($nitko, '/^[a-zA-Z0-9,. ]+$/', "Invalid NITKO format");
    validate_2($term_pembayaran, '/^[0-9]+$/', "Invalid term pembayaran format");
    validate_2($max_invoice, '/^[0-9]+$/', "Invalid max invoice format");
    validate_2($max_piutang, '/^[0-9., ]+$/', "Invalid max piutang format");
    validate_2($longitude, '/^[-+]?((1[0-7]\d|\d{1,2})(\.\d{1,6})?|180(\.0{1,6})?)$/', "Invalid Longitude Format");
    validate_2($latitude, '/^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/', "Invalid Latidude Format");
    if (isset($data['remove_ktp_file']) && $data['remove_ktp_file'] === 'true') {
        handle_image_remove('ktp', $customer_id, $conn);
    }

    if (isset($data['remove_npwp_file']) && $data['remove_npwp_file'] === 'true') {
        handle_image_remove('npwp', $customer_id, $conn);
    }
    $stmt = $conn->prepare("UPDATE tb_customer SET nama=?, alamat=?, no_telp=?, ktp=?, npwp=?, status=?, nitko=?, 
    term_pembayaran=?, max_invoice=?, max_piutang=?,longitude=?,latitude=?, channel_id=?,pricelist_id=?,jenis_customer WHERE customer_id=?");
    $stmt->bind_param(
        "ssssssssssddsss",
        $nama,
        $alamat,
        $no_telp,
        $ktp,
        $npwp,
        $status,
        $nitko,
        $term_pembayaran,
        $max_invoice,
        $max_piutang,
        $longitude,
        $latitude,
        $channel_id,
        $pricelist_id,
        $jenis_customer,
        $customer_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to update customer: " . $stmt->error);
    }
    $stmt->close();

    // Process image uploads
    handleImageUpload('ktp_file', 'ktp', $customer_id, $conn, $upload_dir, $base_url);
    handleImageUpload('npwp_file', 'npwp', $customer_id, $conn, $upload_dir, $base_url);

    echo json_encode(["success" => true, "message" => "Customer dan gambar berhasil diperbarui"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

$conn->close();
