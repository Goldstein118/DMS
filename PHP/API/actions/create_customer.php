<?php
require_once __DIR__ . '/../utils/helpers.php';
$upload_dir = __DIR__ . '/../../../uploads';
$base_url = 'http://localhost/DMS/uploads/'; 

try {
    $requiredFields = ['name_customer', 'status_customer','channel_id'];
    $default = ['status_customer' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);
    $nama_customer = $fields['name_customer'];
    $alamat_customer = $fields['alamat_customer']??'';
    $no_telp_customer = $fields['no_telp_customer']??'';
    $ktp_customer = $fields['nik_customer']??'';
    $npwp_customer = $fields['npwp_customer']??'';
    $nitko = $fields['nitko']??'';
    $term_payment = $fields['term_payment']??'';
    $max_invoice = $fields['max_invoice']??'';
    $max_piutang = $fields['max_piutang']??'';
    $longitude = ($fields['longitude'] ?? '') !== '' ? $fields['longitude'] : null;
    $latidude = ($fields['latidude'] ?? '') !== '' ? $fields['latidude'] : null;

    $status_customer = $fields['status_customer'];
    $channel_id = $fields['channel_id'];

    validate_2($nama_customer, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($alamat_customer, '/^[a-zA-Z0-9, .-]+$/', "Invalid address format");
    validate_2($no_telp_customer, '/^[+]?[\d\s\-]+$/', "Invalid phone number format");
    validate_2($ktp_customer, '/^[0-9]+$/', "Invalid KTP format");
    validate_2($npwp_customer, '/^[0-9 .-]+$/', "Invalid NPWP format");
    validate_2($nitko, '/^[a-zA-Z0-9, .-]+$/', "Invalid nitko format");
    validate_2($term_payment, '/^[a-zA-Z0-9 ]+$/', "Invalid term payment format");
    validate_2($max_invoice, '/^[a-zA-Z0-9 ]+$/', "Invalid max invoice format");
    validate_2($max_piutang, '/^[0-9. ]+$/', "Invalid max piutang format");
    validate_2($longitude,'/^[-+]?((1[0-7]\d|\d{1,2})(\.\d{1,6})?|180(\.0{1,6})?)$/',"Invalid Longitude Format");
    validate_2($latidude,'/^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/',"Invalid Latidude Format");
    $customer_id = generateCustomID('CU', 'tb_customer', 'customer_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_customer (customer_id,nama,alamat,no_telp,ktp,npwp,status,nitko,term_pembayaran,max_invoice,max_piutang,longitude,latidude,channel_id) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [$customer_id, $nama_customer, $alamat_customer, $no_telp_customer, $ktp_customer, $npwp_customer, $status_customer, $nitko,
        $term_payment, $max_invoice, $max_piutang,$longitude,$latidude,$channel_id],
        "sssssssssssdds"
    );


 function resizeImage($file, $maxWidth = 1280, $maxHeight = 720) {
    $imgInfo = getimagesize($file);
    if (!$imgInfo) {
        return false;
    }

    list($width, $height) = $imgInfo;

    // Return original image if it's already smaller
    if ($width <= $maxWidth && $height <= $maxHeight) {
        return file_get_contents($file);
    }

    $src = imagecreatefromstring(file_get_contents($file));
    if (!$src) {
        return false;
    }

    $scale = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int) round($width * $scale);
    $newHeight = (int) round($height * $scale);

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    ob_start();
    imagejpeg($dst, null, 85);
    $imageData = ob_get_clean();

    imagedestroy($src);
    imagedestroy($dst);

    return $imageData;
}


    foreach (['ktp_image' => 'ktp', 'npwp_image' => 'npwp'] as $field => $tipe) {
    if (
        isset($_FILES[$field]) &&
        $_FILES[$field]['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES[$field]['tmp_name'])
    ) {
        $file = $_FILES[$field]['tmp_name'];

        // Validate it's a real image
        $imgInfo = getimagesize($file);
        if ($imgInfo === false) {
            throw new Exception("Uploaded file for '$field' is not a valid image.");
        }

        // (Optional) Check allowed MIME types
        $mime = mime_content_type($file);
        $allowedMimes = ['image/jpeg', 'image/png','image/jpg'];
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception("Unsupported image format for '$field'.");
        }

        // Resize and save
        $blobData = resizeImage($file); 
        $filename = uniqid($tipe . '_') . '.jpg';
        $filepath = $upload_dir .'/'. $filename;
        $external_link = $base_url . $filename;

        file_put_contents($filepath, $blobData);

        // Save metadata to DB
        $gambar_id = generateCustomID('IMG', 'tb_gambar', 'gambar_id', $conn);
        executeInsert(
            $conn,
            "INSERT INTO tb_gambar (gambar_id, tipe, customer_id, internal_link, external_link, blob_data) VALUES (?, ?, ?, ?, ?, ?)",
            [$gambar_id, $tipe, $customer_id, $filepath, $external_link, $blobData],
            "ssssss"
        );
    }
}

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["customer_id" => $customer_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}







