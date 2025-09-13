<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['tanggal_invoice', 'pembelian_id'];

    $fields = validate_1($data, $requiredFields);
    $pembelian_id = $data['pembelian_id'];
    $tanggal_invoice = $data['tanggal_invoice'];
    $no_invoice = $data['no_invoice'];
    $tanggal_po = $fields['tanggal_po'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $ppn = $fields['ppn'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $no_pengiriman = $fields['no_pengiriman'];
    $tanggal_terima = $fields['tanggal_terima'];
    $tanggal_pengiriman = $fields['tanggal_pengiriman'];
    $invoice_id = $fields['invoice_id'];
    $ppn_unformat = toFloat($ppn);
    $diskon_invoice_unformat = toFloat($diskon_invoice);
    $nominal_pph_unformat = toFloat($nominal_pph);

    // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
    validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
    validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");

    $stmt = $conn->prepare("UPDATE tb_invoice SET tanggal_invoice=?,no_invoice_supplier=?, tanggal_po=?, supplier_id=?, keterangan=?, ppn=?,diskon=?, nominal_pph=?,no_pengiriman=?,tanggal_terima=?,tanggal_pengiriman=? WHERE pembelian_id =?");
    $stmt->bind_param("sssssdddssss", $tanggal_invoice, $no_invoice, $tanggal_po, $supplier_id, $keterangan, $ppn, $diskon_invoice_unformat, $nominal_pph_unformat, $no_pengiriman, $tanggal_terima, $tanggal_pengiriman, $pembelian_id);
    $stmt->execute();
    $stmt->close();



    $delete_stmt = $conn->prepare("DELETE FROM tb_detail_invoice WHERE pembelian_id = ?");
    $delete_stmt->bind_param("s", $pembelian_id);
    $delete_stmt->execute();
    $delete_stmt->close();

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

            $qty_unformat = toFloat($qty);
            $harga_unformat = toFloat($harga);
            $diskon_unformat = toFloat($diskon);

            validate_2($qty_unformat, '/^\d+$/', "Format qty detail tidak valid");
            validate_2($harga_unformat, '/^\d+$/', "Format harga detail tidak valid");
            validate_2($diskon_unformat, '/^\d+$/', "Format diskon detail tidak valid");

            $total_qty += $qty_unformat;
            $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

            $detail_pembelian_id = generateCustomID('DPI', 'tb_detail_invoice', 'detail_invoice_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_invoice (detail_invoice_id, pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan,invoice_id)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
                [
                    $detail_pembelian_id,
                    $pembelian_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id,
                    $urutan_detail,
                    $invoice_id,
                ],
                "sssdddsds"
            );
            $urutan_detail += 1;
        }
    }

    $delete_stmt_biaya_tambahan = $conn->prepare("DELETE FROM tb_biaya_tambahan_invoice WHERE pembelian_id = ?");
    $delete_stmt_biaya_tambahan->bind_param("s", $pembelian_id);
    $delete_stmt_biaya_tambahan->execute();
    $delete_stmt_biaya_tambahan->close();



    $urutan_biaya_tambahan = 0;
    $total_biaya_tambahan = 0;
    if (isset($data['biaya_tambahan'])) {
        foreach ($data['biaya_tambahan'] as $biaya) {
            if (!isset($biaya['data_biaya_id']) || !isset($biaya['jumlah']) || !isset($biaya['keterangan'])) {
                throw new Exception("Informasi biaya tambahan tidak lengkap.");
            }

            $data_biaya_id = $biaya['data_biaya_id'];
            $jumlah = $biaya['jumlah'];
            $jumlah_unformat = toFloat($jumlah);
            validate_2($jumlah_unformat, '/^\d+$/', "Format jumlah biaya tambahan tidak valid");


            $keterangan_biaya = $biaya['keterangan'];

            $total_biaya_tambahan += $jumlah_unformat;

            $biaya_tambahan_id = generateCustomID('DBT', 'tb_biaya_tambahan_invoice', 'biaya_tambahan_invoice_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan_invoice (biaya_tambahan_invoice_id, pembelian_id, data_biaya_id, jlh, keterangan,urutan,invoice_id)
                VALUES (?, ?, ?, ?, ?,?,?)",
                [
                    $biaya_tambahan_id,
                    $pembelian_id,
                    $data_biaya_id,
                    $jumlah_unformat,
                    $keterangan_biaya,
                    $urutan_biaya_tambahan,
                    $invoice_id
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
    $stmt = $conn->prepare("UPDATE tb_invoice 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,biaya_tambahan= ?,sub_total=?
                            WHERE pembelian_id = ?");
    $stmt->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_id);
    $stmt->execute();
    $stmt->close();
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "Berhasil",
        "data" => [
            "invoice_id" => $invoice_id
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}
