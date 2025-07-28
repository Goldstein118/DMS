<?php
require_once __DIR__ . '/../utils/helpers.php';


try {
    $requiredFields = [];
    $default = [
        'status' => 'aktif',
    ];


    $fields = validate_1($data, $requiredFields, $default);
    $tanggal_po = $fields['tanggal_po'];
    $tanggal_pengiriman = $fields['tanggal_pengiriman'];
    $tanggal_terima = $fields['tanggal_terima'];
    $tanggal_invoice = $fields['tanggal_invoice'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $no_invoice_supplier = $fields['no_invoice_supplier'];
    $no_pengiriman = $fields['no_pengiriman'];
    $total_qty = $fields['total_qty'];
    $ppn = $fields['ppn'];
    $nominal_ppn = $fields['nominal_ppn'];
    $diskon = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $biaya_tambahan = $fields['biaya_tambahan'];
    $grand_total = $fields['grand_total'];
    $status = $fields['status'];
    $created_by = $fields['created_by'];



    // validate_2($nama_pembelian, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $pembelian_id = generateCustomID('PE', 'tb_pembelian', 'pembelian_id', $conn);
    $total_qty = 0; // Atur sesuai kebutuhan, misalnya jumlah qty dari semua detail

    executeInsert(
        $conn,
        "INSERT INTO tb_pembelian (pembelian_id,tanggal_po,tanggal_pengiriman,tanggal_terima,tanggal_invoice,supplier_id,keterangan,
    no_invoice_supplier,no_pengiriman,total_qty,ppn,nominal_ppn,diskon,nominal_pph,biaya_tambahan,grand_total,
    status,created_by) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [
            $pembelian_id,
            $tanggal_po,
            $tanggal_pengiriman,
            $tanggal_terima,
            $tanggal_invoice,
            $supplier_id,
            $keterangan,
            $no_invoice_supplier,
            $no_pengiriman,
            $total_qty,
            $ppn,
            $nominal_ppn,
            $diskon,
            $nominal_pph,
            $biaya_tambahan,
            $grand_total,
            $status,
            $created_by
        ],
        "ssssssssssssssssss"
    );


    if (isset($data['details'])) {
        foreach ($data['details'] as $detail) {
            if (!isset($detail['produk_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $produk_id = $detail['produk_id'];
            $qty = $detail['qty'];
            $harga = $detail['harga'];
            $satuan_id = $detail['satuan_id'];
            $diskon = $detail['diskon'];

            $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pembelian (detail_pembelian_id ,pembelian_id, produk_id,qty,harga,diskon,satuan_id) 
                
                VALUES (?,?,?,?,?,?,?)",

                [$detail_pembelian_id, $pembelian_id, $produk_id, $qty, $harga, $diskon, $satuan_id],
                "sssssss"
            );
        }
    }



    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["pembelian_id" => $pembelian_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
