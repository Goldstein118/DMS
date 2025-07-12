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
    $jenis_channel = json_encode($data['jenis_cahnnel'] ?? []);

    $exclude_include_brand = $data["exclude_include_brand"];
    $exclude_include_produk = $data["exclude_include_produk"];
    $exclude_include_customer = $data["exclude_include_customer"];
    $exlude_include_channel = $data["exclude_include_channel"];

    $status = $data['status'] ?? 'aktif';
    $qty_akumulasi = $data['qty_akumulasi'] ?? '';
    $qty_min = $data['qty_min'] ?? '';
    $qty_max = $data['qty_max'] ?? '';
    $quota = $data['quota'] ?? '';
    $qty_bonus = $data['qty_bonus'];
    $diskon_bonus_barang = $data['diskon_bonus_barang'];

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
    $check_kondisi_stmt = $conn->prepare("SELECT promo_kondisi_id FROM tb_promo_kondisi WHERE promo_id = ?");
    $check_kondisi_stmt->bind_param("s", $promo_id);
    $check_kondisi_stmt->execute();
    $result_kondisi = $check_kondisi_stmt->get_result();
    $check_kondisi_stmt->close();

    if ($result_kondisi->num_rows > 0) {
        // Update kondisi
        $stmt_update = $conn->prepare("UPDATE tb_promo_kondisi SET 
            jenis_customer = ?, jenis_brand = ?, jenis_produk = ?, jenis_channel=?,exclude_include_brand=?,exclude_include_customer=?,exclude_include_produk =?,exclude_include_channel =?,
            status = ?, qty_akumulasi = ?, qty_min = ?, qty_max = ?, quota = ?
            WHERE promo_id = ?");
        $stmt_update->bind_param(
            "ssssssssssssss",
            $jenis_customer,
            $jenis_brand,
            $jenis_produk,
            $jenis_channel,
            $exclude_include_brand,
            $exclude_include_customer,
            $exclude_include_produk,
            $exclude_include_channel,
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
            promo_kondisi_id, promo_id, jenis_customer, jenis_brand, jenis_produk,jenis_channel,
            exclude_include_brand,exclude_include_customer,exclude_include_produk,exclude_include_channel
            status, qty_akumulasi, qty_min, qty_max, quota
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?)");
        $stmt_insert->bind_param(
            "sssssssssssssss",
            $promo_kondisi_id,
            $promo_id,
            $jenis_customer,
            $jenis_brand,
            $jenis_produk,
            $jenis_channel,
            $exclude_include_brand,
            $exclude_include_customer,
            $exclude_include_produk,
            $exclude_include_channel,
            $status,
            $qty_akumulasi,
            $qty_min,
            $qty_max,
            $quota
        );
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $check_bonus_stmt = $conn->prepare("SELECT promo_bonus_barang_id FROM tb_promo_bonus_barang WHERE promo_id = ?");
    $check_bonus_stmt->bind_param("s", $promo_id);
    $check_bonus_stmt->execute();
    $result_bonus = $check_bonus_stmt->get_result();
    $check_bonus_stmt->close();

    if ($result_bonus->num_rows > 0) {
        // Update kondisi
        $stmt_update = $conn->prepare("UPDATE tb_promo_bonus_barang SET 
            qty_bonus = ?,jlh_diskon=? WHERE promo_id = ?");
        $stmt_update->bind_param(
            "sss",
            $qty_bonus,
            $diskon_bonus_barang,
            $promo_id
        );
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Insert new kondisi
        $promo_bonus_barang_id = generateCustomID('PRB', 'tb_promo_bonus_barang', 'promo_bonus_barang_id', $conn);
        $stmt_insert = $conn->prepare("INSERT INTO tb_promo_bonus_barang (
        promo_bonus_barang_id,promo_id,qty_bonus,jlh_diskon
        ) VALUES (?, ?, ?,?)");
        $stmt_insert->bind_param(
            "ssss",
            $promo_bonus_barang_id,
            $promo_id,
            $qty_bonus,
            $diskon_bonus_barang
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
