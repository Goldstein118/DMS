<?php
require_once __DIR__ . '/../utils/helpers.php';



try {
    // Validation
    $requiredFields = ['tanggal_po'];

    $fields = validate_1($data, $requiredFields);

    // Extract main fields
    $tanggal_po = $fields['tanggal_po'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $ppn = $fields['ppn'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $status = $fields['status'];
    $created_by = $fields['created_by'];
    $created_status = "new";


    $ppn_unformat = toFloat($ppn);
    $diskon_invoice_unformat = toFloat($diskon_invoice);
    $nominal_pph_unformat = toFloat($nominal_pph);

    // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
    validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
    validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");
    // Generate ID
    $pembelian_id = generateCustomID('PO', 'tb_pembelian', 'pembelian_id', $conn);
    $pembelian_history_id = generateCustomID('POH', 'tb_pembelian_history', 'pembelian_history_id', $conn);

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
        "ssssdddss"
    );

    executeInsert(
        $conn,
        "INSERT INTO tb_pembelian_history (pembelian_history_id,pembelian_id_after, tanggal_po_after, supplier_id_after, keterangan_after, ppn_after,diskon_after, nominal_ppn_after, status_after, cancel_by_after,created_status)
        VALUES (?,?, ?, ?, ?, ?, ?, ?,?,?,?)",
        [
            $pembelian_history_id,
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $created_by,
            $created_status
        ],
        "sssssdddsss"
    );

    $total_qty = 0;
    $total_harga = 0;
    $urutan_detail = 0;
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
            $tipe_detail_pembelian_history = "A";

            $qty_unformat = toFloat($qty);
            $harga_unformat = toFloat($harga);
            $diskon_unformat = toFloat($diskon);

            validate_2($qty_unformat, '/^\d+$/', "Format qty detail tidak valid");
            validate_2($harga_unformat, '/^\d+$/', "Format harga detail tidak valid");
            validate_2($diskon_unformat, '/^\d+$/', "Format diskon detail tidak valid");

            $total_qty += $qty_unformat;
            $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

            $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
            $detail_pembelian_history_id = generateCustomID('DPH', 'tb_detail_pembelian_history', 'detail_pembelian_history_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pembelian (detail_pembelian_id, pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan)
                VALUES (?, ?, ?, ?, ?, ?, ?,?)",
                [
                    $detail_pembelian_id,
                    $pembelian_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id,
                    $urutan_detail
                ],
                "sssdddsd"
            );

            executeInsert(
                $conn,
                "INSERT INTO tb_detail_pembelian_history (detail_pembelian_history_id, pembelian_history_id, produk_id, qty, harga, diskon, satuan_id,urutan,tipe_detail_pembelian_history)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
                [
                    $detail_pembelian_history_id,
                    $pembelian_history_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id,
                    $urutan_detail,
                    $tipe_detail_pembelian_history
                ],
                "sssdddsds"
            );
            $urutan_detail += 1;
        }
    }

    $urutan_biaya_tambahan = 0;
    $total_biaya_tambahan = 0;
    if (isset($data['biaya_tambahan'])) {
        foreach ($data['biaya_tambahan'] as $biaya) {
            if (!isset($biaya['data_biaya_id']) || !isset($biaya['jumlah']) || !isset($biaya['keterangan'])) {
                throw new Exception("Informasi biaya tambahan tidak lengkap.");
            }

            $data_biaya_id = $biaya['data_biaya_id'];
            $jumlah = $biaya['jumlah'];

            $tipe_biaya_tambahan_history = "A";
            $jumlah_unformat = toFloat($jumlah);
            validate_2($jumlah_unformat, '/^\d+$/', "Format jumlah biaya tambahan tidak valid");


            $keterangan_biaya = $biaya['keterangan'];

            $total_biaya_tambahan += $jumlah_unformat;

            $biaya_tambahan_id = generateCustomID('DBT', 'tb_biaya_tambahan', 'biaya_tambahan_id', $conn);
            $biaya_tambahan_history_id = generateCustomID('BTH', 'tb_biaya_tambahan_history', 'biaya_tambahan_history_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan (biaya_tambahan_id, pembelian_id, data_biaya_id, jlh, keterangan,urutan)
                VALUES (?, ?, ?, ?, ?,?)",
                [
                    $biaya_tambahan_id,
                    $pembelian_id,
                    $data_biaya_id,
                    $jumlah_unformat,
                    $keterangan_biaya,
                    $urutan_biaya_tambahan
                ],
                "sssdsd"
            );



            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan_history (biaya_tambahan_history_id, pembelian_history_id, data_biaya_id, jlh, keterangan,urutan,tipe_biaya_tambahan_history)
                VALUES (?, ?, ?, ?, ?,?,?)",
                [
                    $biaya_tambahan_history_id,
                    $pembelian_history_id,
                    $data_biaya_id,
                    $jumlah_unformat,
                    $keterangan_biaya,
                    $urutan_biaya_tambahan,
                    $tipe_biaya_tambahan_history
                ],
                "sssdsds"
            );
            $urutan_biaya_tambahan += 1;
        }
    }

    // === Final Calculation ===
    $sub_total = $total_harga - $diskon_invoice_unformat + $total_biaya_tambahan;
    $nominal_ppn = $sub_total * $ppn_unformat;
    $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

    // === Update Purchase Summary ===
    $stmt = $conn->prepare("UPDATE tb_pembelian 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,biaya_tambahan= ?,sub_total=?
                            WHERE pembelian_id = ?");
    $stmt->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_id);
    $stmt->execute();
    $stmt->close();


    $stmt = $conn->prepare("UPDATE tb_pembelian_history 
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,biaya_tambahan_after = ?,sub_total_after =?
                            WHERE pembelian_history_id = ?");
    $stmt->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_history_id);
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
