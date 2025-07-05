<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['nama', 'tanggal_berlaku', 'tanggal_selesai'];
    $field = validate_1($data, $requiredFields);
    $nama = $field['nama'];
    $tanggal_berlaku = $field['tanggal_berlaku'];
    $tanggal_selesai = $field['tanggal_selesai'];
    $jenis_bonus = $data['jenis_bonus'];
    $brand_val = $data['jenis_brand'];
    $customer_val = $data['jenis_customer'];
    $produk_val = $data['jenis_produk'];
    $akumulasi = $data['akumulasi'];
    $prioritas = $data['prioritas'];
    $jenis_diskon = $data['jenis_diskon'];
    $jumlah_diskon = $data['jumlah_diskon'];
    $status_promo = $data['status_promo'];
    $qty_akumulasi = $data['qty_akumulasi'];
    $qty_min = $data['qty_min'];
    $qty_max = $data['qty_max'];
    $quota = $data['quota'];
    $qty_bonus = $data['qty_bonus'];
    $diskon_bonus_barang = $data['diskon_bonus_barang'];

    $json_brand = json_encode($brand_val);
    $json_customer = json_encode($customer_val);
    $json_produk = json_encode($produk_val);
    validate_2($nama, '/^[a-zA-Z0-9\s]+$/', "Invalid name format");

    $promo_id = generateCustomID('PRO', 'tb_promo', 'promo_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_promo(promo_id,nama,tanggal_berlaku,tanggal_selesai,jenis_bonus,akumulasi,prioritas,jenis_diskon,jumlah_diskon)
        VALUES (?,?,?,?,?,?,?,?,?)",
        [
            $promo_id,
            $nama,
            $tanggal_berlaku,
            $tanggal_selesai,
            $jenis_bonus,
            $jenis_diskon,
            $akumulasi,
            $prioritas,
            $jumlah_diskon
        ],
        "sssssssss"
    );

    $promo_kondisi_id = generateCustomID('PRK', 'tb_promo_kondisi', 'promo_kondisi_id', $conn);

    executeInsert(
        $conn,
        " INSERT INTO tb_promo_kondisi (promo_kondisi_id,promo_id,jenis_brand,jenis_customer,jenis_produk,status,qty_akumulasi,qty_min,qty_max,quota) 
        VALUES (?,?,?,?,?,?,?,?,?,?)",
        [
            $promo_kondisi_id,
            $promo_id,
            $json_brand,
            $json_customer,
            $json_produk,
            $status_promo,
            $qty_akumulasi,
            $qty_min,
            $qty_max,
            $quota
        ],
        "ssssssssss"
    );
    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["promo_id" => $promo_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

//18