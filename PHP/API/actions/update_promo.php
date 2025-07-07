<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    // Expected required fields
    $requiredFields = ['promo_id', 'nama', 'tanggal_berlaku', 'tanggal_selesai'];
    $fields = validate_1($data, $requiredFields);

    $promo_id = $fields['promo_id'];
    $nama = $fields['nama'];
    $tanggal_berlaku = $fields['tanggal_berlaku'];
    $tanggal_selesai = $fields['tanggal_selesai'];
    $jenis_bonus = $data['jenis_bonus'] ?? 'barang';
    $akumulasi = $data['akumulasi'] ?? '';
    $prioritas = $data['prioritas'] ?? '';
    $jenis_diskon = $data['jenis_diskon'] ?? '';
    $jumlah_diskon = $data['jumlah_diskon'] ?? '';

    $jenis_brand = json_encode($data['jenis_brand'] ?? []);
    $jenis_customer = json_encode($data['jenis_customer'] ?? []);
    $jenis_produk = json_encode($data['jenis_produk'] ?? []);
    $status = $data['status'] ?? 'aktif';
    $qty_akumulasi = $data['qty_akumulasi'] ?? '';
    $qty_min = $data['qty_min'] ?? '';
    $qty_max = $data['qty_max'] ?? '';
    $quota = $data['quota'] ?? '';

    validate_2($nama, '/^[a-zA-Z0-9\s]+$/', "Format nama tidak valid");

    // Update tb_promo
    $stmt = $conn->prepare("UPDATE tb_promo SET 
        nama = ?, tanggal_berlaku = ?, tanggal_selesai = ?, 
        jenis_bonus = ?, akumulasi = ?, prioritas = ?, 
        jenis_diskon = ?, jumlah_diskon = ? 
        WHERE promo_id = ?");
    $stmt->bind_param(
        "sssssssss",
        $nama,
        $tanggal_berlaku,
        $tanggal_selesai,
        $jenis_bonus,
        $akumulasi,
        $prioritas,
        $jenis_diskon,
        $jumlah_diskon,
        $promo_id
    );
    $stmt->execute();
    $stmt->close();

    // Check if tb_promo_kondisi exists
    $check_stmt = $conn->prepare("SELECT promo_kondisi_id FROM tb_promo_kondisi WHERE promo_id = ?");
    $check_stmt->bind_param("s", $promo_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();

    if ($result->num_rows > 0) {
        // Update kondisi
        $stmt_update = $conn->prepare("UPDATE tb_promo_kondisi SET 
            jenis_customer = ?, jenis_brand = ?, jenis_produk = ?, 
            status = ?, qty_akumulasi = ?, qty_min = ?, qty_max = ?, quota = ?
            WHERE promo_id = ?");
        $stmt_update->bind_param(
            "sssssssss",
            $jenis_customer,
            $jenis_brand,
            $jenis_produk,
            $status,
            $qty_akumulasi,
            $qty_min,
            $qty_max,
            $quota,
            $promo_id
        );
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Insert new kondisi
        $promo_kondisi_id = generateCustomID('PRK', 'tb_promo_kondisi', 'promo_kondisi_id', $conn);
        $stmt_insert = $conn->prepare("INSERT INTO tb_promo_kondisi (
            promo_kondisi_id, promo_id, jenis_customer, jenis_brand, jenis_produk,
            status, qty_akumulasi, qty_min, qty_max, quota
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param(
            "ssssssssss",
            $promo_kondisi_id,
            $promo_id,
            $jenis_customer,
            $jenis_brand,
            $jenis_produk,
            $status,
            $qty_akumulasi,
            $qty_min,
            $qty_max,
            $quota
        );
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Promo dan kondisi berhasil diupdate."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
