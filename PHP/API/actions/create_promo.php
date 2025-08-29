<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = [
        'nama',
        'tanggal_berlaku',
        'tanggal_selesai',
        'jenis_bonus',
        'akumulasi',
        'prioritas',
        'jenis_diskon',
        'status_promo',
        'quota',
        'satuan_id'
    ];
    $field = validate_1($data, $requiredFields);
    $nama = $field['nama'];
    $tanggal_berlaku = $field['tanggal_berlaku'];
    $tanggal_selesai = $field['tanggal_selesai'];
    $jenis_bonus = $field['jenis_bonus'];
    $akumulasi = $field['akumulasi'];
    $prioritas = $field['prioritas'];
    $jenis_diskon = $field['jenis_diskon'];
    $jumlah_diskon = $field['jumlah_diskon'];
    $status_promo = $field['status_promo'];
    $quota = $field['quota'];
    $satuan_id = $field['satuan_id'];
    $jumlah_diskon = toFloat($jumlah_diskon);
    if ($jenis_diskon === "nominal") {
        validate_2($jumlah_diskon, '/^\d+$/', "Format jumlah diskon tidak valid");
    }

    $prioritas = toFloat($prioritas);
    $quota = toFloat($quota);
    validate_2($nama, '/^[a-zA-Z0-9\s]+$/', "Format nama tidak valid");
    validate_2($prioritas, '/^\d+$/', "Format prioritas tidak valid");
    validate_2($quota, '/^\d+$/', "Format quota tidak valid");


    $promo_id = generateCustomID('PRO', 'tb_promo', 'promo_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_promo(promo_id,nama,tanggal_berlaku,tanggal_selesai,jenis_bonus,jenis_diskon
        ,akumulasi,prioritas,jumlah_diskon,quota,status,satuan_id)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
        [
            $promo_id,
            $nama,
            $tanggal_berlaku,
            $tanggal_selesai,
            $jenis_bonus,
            $jenis_diskon,
            $akumulasi,
            $prioritas,
            $jumlah_diskon,
            $quota,
            $status_promo,
            $satuan_id
        ],
        "sssssssdddss"
    );
    if (isset($data['promo_kondisi'])) {


        foreach ($data['promo_kondisi'] as $promo) {
            if (!isset($promo['kondisi'])) {
                throw new Exception("Promo kondisi kosong !");
            }
            $jenis_kondisi = $promo['jenis_kondisi'];
            $kondisi = json_encode($promo['kondisi']);
            $qty_akumulasi = $promo['qty_akumulasi'];
            $qty_min = $promo['qty_min'];
            $qty_max = $promo['qty_max'];
            $exclude_include = $promo['exclude_include'];


            $qty_akumulasi = toFloat($qty_akumulasi);
            $qty_max = toFloat($qty_max);
            $qty_min = toFloat($qty_min);
            validate_2($qty_akumulasi, '/^\d+$/', "Format qty akumulasi tidak valid");
            validate_2($qty_max, '/^\d+$/', "Format qty max tidak valid");
            validate_2($qty_min, '/^\d+$/', "Format qty min tidak valid");





            $promo_kondisi_id = generateCustomID('PRK', 'tb_promo_kondisi', 'promo_kondisi_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_promo_kondisi (promo_kondisi_id, promo_id,jenis_kondisi,kondisi,
                qty_akumulasi,qty_min,exclude_include,qty_max) 
                VALUES (?,?,?,?,?,?,?,?)",
                [
                    $promo_kondisi_id,
                    $promo_id,
                    $jenis_kondisi,
                    $kondisi,
                    $qty_akumulasi,
                    $qty_min,
                    $exclude_include,
                    $qty_max

                ],
                "ssssddsd"
            );
        }
    }

    if (isset($data['promo_bonus_barang'])) {
        foreach ($data['promo_bonus_barang'] as $promo) {
            if (!isset($promo['produk_id'])) {
                throw new Exception("Pilih salah satu produk !");
            }
            $produk_id = $promo['produk_id'];
            $qty_bonus = $promo['qty_bonus'];
            $jenis_diskon = $promo['jenis_diskon'];
            $jlh_diskon = $promo['jlh_diskon'];

            $qty_bonus = toFloat($qty_bonus);
            $jlh_diskon = toFloat($jlh_diskon);
            validate_2($qty_bonus, '/^\d+$/', "Format qty bonus tidak valid");
            validate_2($jlh_diskon, '/^\d+$/', "Format jlh diskon tidak valid");




            $promo_bonus_barang_id = generateCustomID('PRB', 'tb_promo_bonus_barang', 'promo_bonus_barang_id', $conn);

            executeInsert($conn, "INSERT INTO tb_promo_bonus_barang(promo_bonus_barang_id,promo_id,qty_bonus,jenis_diskon,jlh_diskon,produk_id) 
        VALUES (?,?,?,?,?,?)", [$promo_bonus_barang_id, $promo_id, $qty_bonus, $jenis_diskon, $jlh_diskon, $produk_id], "ssdsds");
        }
    }
    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["promo_id" => $promo_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

//18