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

    $pricelist_id = generateCustomID('PR', 'tb_pricelist', 'pricelist_id', $conn);
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





    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["pricelist_id" => $pricelist_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
