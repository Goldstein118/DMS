<?php
require_once __DIR__ . '/../utils/helpers.php';


try {
    $requiredFields = ['name_pricelist', 'harga_default', 'status_pricelist', 'tanggal_berlaku'];
    $default = [
        'status_pricelist' => 'aktif',
        'harga_default' => 'ya'
    ];
    $fields = validate_1($data, $requiredFields, $default);
    $nama_pricelist = $fields['name_pricelist'];
    $harga_default = $fields['harga_default'];
    $status_pricelist = $fields['status_pricelist'];
    $tanggal_berlaku = $fields['tanggal_berlaku'];

    validate_2($nama_pricelist, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $pricelist_id = generateCustomID('PRI', 'tb_pricelist', 'pricelist_id', $conn);
    executeInsert(
        $conn,
        "INSERT INTO tb_pricelist (pricelist_id,nama,harga_default,status,tanggal_berlaku) 
        VALUES (?,?,?,?,?)",
        [
            $pricelist_id,
            $nama_pricelist,
            $harga_default,
            $status_pricelist,
            $tanggal_berlaku
        ],
        "sssss"
    );

    if (isset($data['details'])) {
        foreach ($data['details'] as $detail) {
            if (!isset($detail['produk_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $produk_id = $detail['produk_id'];
            $harga = $detail['harga'];
            $harga = toFloat($harga);
            validate_2($harga, '/^\d+$/', "Format harga tidak valid");


            $detail_pricelist_id = generateCustomID('DE', 'tb_detail_pricelist', 'detail_pricelist_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pricelist (detail_pricelist_id ,harga ,pricelist_id, produk_id) VALUES (?, ?, ?, ?)",
                [$detail_pricelist_id, $harga, $pricelist_id, $produk_id],
                "sdss"
            );
        }
    }



    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["pricelist_id" => $pricelist_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
