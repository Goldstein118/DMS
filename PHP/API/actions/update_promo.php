<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    if (!$data) {
        throw new Exception("Invalid JSON input");
    }

    // Start transaction
    $conn->begin_transaction();

    // Required fields
    $requiredFields = ['promo_id', 'nama', 'tanggal_berlaku', 'tanggal_selesai'];
    $fields = validate_1($data, $requiredFields);

    // Extract and sanitize input
    $promo_id = $fields['promo_id'];
    $nama = $fields['nama'];
    $tanggal_berlaku = $fields['tanggal_berlaku'];
    $tanggal_selesai = $fields['tanggal_selesai'];
    $jenis_bonus = $data['jenis_bonus'] ?? 'barang';
    $akumulasi = $data['akumulasi'] ?? '';
    $prioritas = $data['prioritas'] ?? '';
    $jenis_diskon = $data['jenis_diskon'] ?? '';
    $jumlah_diskon = $data['jumlah_diskon'] ?? '';
    $status = $data['status'] ?? 'aktif';
    $quota = $data['quota'] ?? '';
    $promo_kondisi = $data['promo_kondisi'] ?? [];
    $promo_bonus_barang = $data['promo_bonus_barang'] ?? [];

    // Basic input validation
    validate_2($nama, '/^[a-zA-Z0-9\s]+$/', "Format nama tidak valid");

    // Update main promo
    $stmt = $conn->prepare("UPDATE tb_promo SET 
        nama = ?, tanggal_berlaku = ?, tanggal_selesai = ?, 
        jenis_bonus = ?, akumulasi = ?, prioritas = ?, 
        jenis_diskon = ?, jumlah_diskon = ?, quota = ?, status = ?
        WHERE promo_id = ?");
    $stmt->bind_param(
        "sssssssssss",
        $nama,
        $tanggal_berlaku,
        $tanggal_selesai,
        $jenis_bonus,
        $akumulasi,
        $prioritas,
        $jenis_diskon,
        $jumlah_diskon,
        $quota,
        $status,
        $promo_id
    );
    $stmt->execute();
    $stmt->close();

    // Handle promo_kondisi
    $delete_kondisi_stmt = $conn->prepare("DELETE FROM tb_promo_kondisi WHERE promo_id = ?");
    $delete_kondisi_stmt->bind_param("s", $promo_id);
    $delete_kondisi_stmt->execute();
    $delete_kondisi_stmt->close();

    foreach ($promo_kondisi as $promo) {
        $jenis_kondisi = $promo['jenis_kondisi'];
        $kondisi = json_encode($promo['kondisi']);
        $exclude_include = $promo['exclude_include'] ?? null;
        $qty_akumulasi = $promo['qty_akumulasi'] ?? null;
        $qty_min = $promo['qty_min'] ?? null;
        $qty_max = $promo['qty_max'] ?? null;
        $promo_kondisi_id = generateCustomID('PRK', 'tb_promo_kondisi', 'promo_kondisi_id', $conn);

        $stmt_insert = $conn->prepare("INSERT INTO tb_promo_kondisi (
        promo_kondisi_id, promo_id, jenis_kondisi, kondisi, qty_akumulasi, qty_min, exclude_include, qty_max
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param(
            "ssssssss",
            $promo_kondisi_id,
            $promo_id,
            $jenis_kondisi,
            $kondisi,
            $qty_akumulasi,
            $qty_min,
            $exclude_include,
            $qty_max
        );
        $stmt_insert->execute();
        $stmt_insert->close();
    }


    // Handle promo_bonus_barang
    // Always delete old bonus_barang rows for the promo
    $delete_bonus_stmt = $conn->prepare("DELETE FROM tb_promo_bonus_barang WHERE promo_id = ?");
    $delete_bonus_stmt->bind_param("s", $promo_id);
    $delete_bonus_stmt->execute();
    $delete_bonus_stmt->close();

    // Then insert new ones
    foreach ($promo_bonus_barang as $promo) {
        $qty_bonus = $promo['qty_bonus'] ?? null;
        $jenis_diskon = $promo['jenis_diskon'] ?? null;
        $jlh_diskon = $promo['jlh_diskon'] ?? null;
        $produk_id = $promo['produk_id'] ?? null;
        $promo_bonus_barang_id = generateCustomID('PRB', 'tb_promo_bonus_barang', 'promo_bonus_barang_id', $conn);

        $stmt_insert = $conn->prepare("INSERT INTO tb_promo_bonus_barang (
        promo_bonus_barang_id, promo_id, qty_bonus, jenis_diskon, jlh_diskon, produk_id
    ) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param(
            "ssssss",
            $promo_bonus_barang_id,
            $promo_id,
            $qty_bonus,
            $jenis_diskon,
            $jlh_diskon,
            $produk_id
        );
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $conn->commit(); // Commit transaction

    http_response_code(200);
    echo json_encode(["ok" => true, "message" => "Promo dan kondisi berhasil diupdate."]);
} catch (Exception $e) {
    $conn->rollback(); // Roll back on error
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
