<?php
require_once __DIR__ . '/../utils/helpers.php';

function toFloat($value)
{
    return (float)str_replace(',', '', trim($value));
}

try {
    // Validation
    $requiredFields = ['tanggal_po'];
    $default = ['status' => 'aktif'];
    $fields = validate_1($data, $requiredFields, $default);

    // Extract main fields
    $tanggal_po = $fields['tanggal_po'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $ppn = $fields['ppn'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $status = $fields['status'];
    $created_by = $fields['created_by'];

    $ppn_unformat = toFloat($ppn);
    $diskon_invoice_unformat = toFloat($diskon_invoice);
    $nominal_pph_unformat = toFloat($nominal_pph);

    // Generate ID
    $pembelian_id = generateCustomID('PE', 'tb_pembelian', 'pembelian_id', $conn);

    // Insert main purchase
    executeInsert(
        $conn,
        "INSERT INTO tb_pembelian (pembelian_id, tanggal_po, supplier_id, keterangan, ppn,diskon, nominal_pph, status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
        [
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $created_by
        ],
        "sssssssss"
    );

    $total_qty = 0;
    $total_harga = 0;

    // === Process Detail Items ===
    if (isset($data['details'])) {
        foreach ($data['details'] as $detail) {
            if (!isset($detail['produk_id']) || !isset($detail['harga'])) {
                throw new Exception("Detail produk atau harga tidak lengkap.");
            }

            $produk_id = $detail['produk_id'];
            $qty = $detail['qty'];
            $harga = $detail['harga'];
            $diskon = $detail['diskon'];
            $satuan_id = $detail['satuan_id'];

            $qty_unformat = toFloat($qty);
            $harga_unformat = toFloat($harga);
            $diskon_unformat = toFloat($diskon);

            $total_qty += $qty_unformat;
            $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

            $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pembelian (detail_pembelian_id, pembelian_id, produk_id, qty, harga, diskon, satuan_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $detail_pembelian_id,
                    $pembelian_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id
                ],
                "sssssss"
            );
        }
    }


    $total_biaya_tambahan = 0;
    if (isset($data['biaya_tambahan'])) {
        foreach ($data['biaya_tambahan'] as $biaya) {
            if (!isset($biaya['data_biaya_id']) || !isset($biaya['jumlah']) || !isset($biaya['keterangan'])) {
                throw new Exception("Informasi biaya tambahan tidak lengkap.");
            }

            $data_biaya_id = $biaya['data_biaya_id'];
            $jumlah = $biaya['jumlah'];
            $jumlah_unformat = toFloat($jumlah);
            $keterangan_biaya = $biaya['keterangan'];

            $total_biaya_tambahan += $jumlah_unformat;

            $biaya_tambahan_id = generateCustomID('DBT', 'tb_biaya_tambahan', 'biaya_tambahan_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan (biaya_tambahan_id, pembelian_id, data_biaya_id, jlh, keterangan)
                VALUES (?, ?, ?, ?, ?)",
                [
                    $biaya_tambahan_id,
                    $pembelian_id,
                    $data_biaya_id,
                    $jumlah_unformat,
                    $keterangan_biaya
                ],
                "sssss"
            );
        }
    }

    // === Final Calculation ===
    $sub_total = $total_harga - $diskon_invoice_unformat + $total_biaya_tambahan;
    $nominal_ppn = $sub_total * $ppn_unformat;
    $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

    // === Update Purchase Summary ===
    $stmt = $conn->prepare("UPDATE tb_pembelian 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,biaya_tambahan= ?
                            WHERE pembelian_id = ?");
    $stmt->bind_param("sssss", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $pembelian_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        "success" => true,
        "message" => "Berhasil",
        "data" => [
            "pembelian_id" => $pembelian_id
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
