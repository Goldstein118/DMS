<?php
require_once __DIR__ . '/../utils/helpers.php';
function toFloat($value)
{
    // Remove commas, trim whitespace, then cast to float
    return (float)str_replace(',', '', trim($value));
}


try {
    $requiredFields = ['tanggal_po'];
    $default = [
        'status' => 'aktif',
    ];


    $fields = validate_1($data, $requiredFields, $default);
    $tanggal_po = $fields['tanggal_po'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $ppn = $fields['ppn'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $biaya_tambahan = $fields['biaya_tambahan'];
    $status = $fields['status'];
    $created_by = $fields['created_by'];



    // validate_2($nama_pembelian, '/^[a-zA-Z\s]+$/', "Invalid name format");

    $pembelian_id = generateCustomID('PE', 'tb_pembelian', 'pembelian_id', $conn);

    executeInsert(
        $conn,
        "INSERT INTO tb_pembelian (pembelian_id,tanggal_po,supplier_id,keterangan
        ,ppn,nominal_pph,biaya_tambahan,
    status,created_by) 
    VALUES (?,?,?,?,?,?,?,?,?)",
        [
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn,
            $nominal_pph,
            $biaya_tambahan,
            $status,
            $created_by
        ],
        "sssssssss"
    );


    if (isset($data['details'])) {
        $total_qty = 0;
        $total_harga = 0;

        foreach ($data['details'] as $detail) {
            if (!isset($detail['produk_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $produk_id = $detail['produk_id'];
            $qty = $detail['qty'];
            $harga = $detail['harga'];
            $satuan_id = $detail['satuan_id'];
            $diskon = $detail['diskon'];

            $qty_unformat = toFloat($detail['qty']);
            $harga_unformat = toFloat($detail['harga']);
            $diskon_unformat = toFloat($detail['diskon']);

            $total_qty += $qty_unformat;
            $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

            $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pembelian (detail_pembelian_id ,pembelian_id, produk_id,qty,harga,diskon,satuan_id) 
                
                VALUES (?,?,?,?,?,?,?)",

                [$detail_pembelian_id, $pembelian_id, $produk_id, $qty, $harga, $diskon, $satuan_id],
                "sssssss"
            );
        }

        $ppn_unformat = toFloat($fields['ppn']);
        $diskon_invoice_unformat = toFloat($fields['diskon']);
        $nominal_pph_unformat = toFloat($fields['nominal_pph']);
        $biaya_tambahan_unformat = toFloat($fields['biaya_tambahan']);

        $nominal_ppn = $total_harga * $ppn_unformat;
        $sub_total = $total_harga - $diskon_invoice_unformat;
        $grand_total = $sub_total + $nominal_ppn;
        $stmt = $conn->prepare("UPDATE tb_pembelian SET total_qty = ?,  grand_total = ? , nominal_ppn =? WHERE pembelian_id = ?");
        $stmt->bind_param("ssss", $total_qty, $grand_total, $nominal_ppn, $pembelian_id);
        $stmt->execute();
        $stmt->close();
    }



    echo json_encode(["success" => true, "message" => "Berhasil", "data" => ["pembelian_id" => $pembelian_id]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
