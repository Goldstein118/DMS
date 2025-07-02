<?php
require_once __DIR__ . '/../utils/helpers.php';
$upload_dir = __DIR__ . '/../../../uploads_produk';
$base_url = 'http://localhost/DMS/uploads_produk/';
try {
    $requiredFields = ['name_produk', 'kategori_id', 'brand_id', 'status_produk'];
    $default = ['status_produk' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    // Extract and validate fields
    $nama = $fields['name_produk'];
    $kategori_id = $fields['kategori_id'];
    $brand_id = $fields['brand_id'];
    $no_sku = $fields['no_sku'] ?? '';
    $status = $fields['status_produk'];
    $harga_minimal = $fields['harga_minimal'] ?? '';
    $stock_awal = $fields['stock_awal']??'';


    validate_2($nama, '/^[a-zA-Z\s]+$/', "Invalid name format");
    validate_2($no_sku, '/^[a-zA-Z0-9,.\- ]+$/', "Invalid no sku format");
    validate_2($harga_minimal, '/^[0-9., ]+$/', "Invalid no harga minimal format");

    // Generate ID and insert
    $produk_id = generateCustomID('PR', 'tb_produk', 'produk_id', $conn);

    $stmt_produk = $conn->prepare("INSERT INTO tb_produk (produk_id, nama,no_sku,status,harga_minimal,kategori_id,brand_id,stock_awal) 
                                    VALUES (?,?,?,?,?,?,?,?)");
    $stmt_produk->bind_param("ssssssss", $produk_id, $nama, $no_sku, $status, $harga_minimal, $kategori_id, $brand_id,$stock_awal);
    if (!$stmt_produk->execute()) {
        throw new Exception("DB insert error: " . $stmt_produk->error);
    }



    if (isset($data['details']) && is_string($data['details'])) {
        $data['details'] = json_decode($data['details'], true);

        foreach ($data['details'] as $detail) {
            if (!isset($detail['pricelist_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $pricelist_id = $detail['pricelist_id'];
            $harga = $detail['harga'];

            $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pricelist (detail_pricelist_id ,harga ,pricelist_id, produk_id) VALUES (?, ?, ?, ?)",
                [$detail_pricelist_id, $harga, $pricelist_id, $produk_id],
                "ssss"
            );
        }
    } else {
    }

    function resizeImage($file, $maxWidth = 1280, $maxHeight = 720)
    {
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
    if (
        isset($_FILES['produk_gambar']) &&
        $_FILES['produk_gambar']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['produk_gambar']['tmp_name'])
    ) {
        $file = $_FILES['produk_gambar']['tmp_name'];

        // Validate it's a real image
        $imgInfo = getimagesize($file);
        if ($imgInfo === false) {
            throw new Exception("Uploaded file is not a valid image.");
        }

        // (Optional) Check allowed MIME types
        $mime = mime_content_type($file);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception("Unsupported image format.");
        }

        // Resize and save
        $blobData = resizeImage($file);
        $filename = uniqid('produk_') . '.jpg';
        $filepath = $upload_dir . '/' . $filename;
        $external_link = $base_url . $filename;

        file_put_contents($filepath, $blobData);

        // Save metadata to DB
        $gambar_produk_id = generateCustomID('IMGP', 'tb_gambar_produk', 'gambar_produk_id', $conn);
        executeInsert(
            $conn,
            "INSERT INTO tb_gambar_produk (gambar_produk_id, produk_id, internal_link, external_link, blob_data) 
         VALUES (?, ?, ?, ?, ?)",
            [$gambar_produk_id, $produk_id, $filepath, $external_link, $blobData],
            "sssss"
        );
    }

    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["produk_id" => $produk_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
