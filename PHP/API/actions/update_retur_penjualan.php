<?php
require_once __DIR__ . '/../utils/helpers.php';

if (isset($data['penjualan_id']) && isset($data['update_penjualan'])) {

    try {

        $requiredFields = ['penjualan_id', 'tanggal_penjualan'];
        $fields = validate_1($data, $requiredFields);
        $retur_penjualan_id = $fields["retur_penjualan_id"];
        $penjualan_id = $fields['penjualan_id'];
        $tanggal_penjualan = $fields['tanggal_penjualan'];
        $customer_id = $fields['customer_id'];
        $gudang_id = $fields['gudang_id'];
        $keterangan_penjualan = $fields['keterangan'];
        $keterangan_gudang = $fields['keterangan_gudang'];
        $keterangan_invoice = $fields['keterangan_invoice'];
        $keterangan_pengiriman = $fields['keterangan_pengiriman'];
        $retur_penjualan_history_id = generateCustomID('RPJH', 'tb_retur_penjualan_history', 'retur_penjualan_history_id', $conn);


        $ppn = $fields['ppn'];
        $diskon_penjualan = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $status = $fields['status'];
        $created_by = $data['created_by'];
        $created_status = "update";


        $ppn_unformat = toFloat($ppn);
        $diskon_invoice_unformat = toFloat($diskon_penjualan);
        $nominal_pph_unformat = toFloat($nominal_pph);
        $tanggal_input_promo_berlaku = date('Y-m-d');


        validate_2($ppn_unformat, '/^\d+(\.\d+)?$/', "Format ppn unformat tidak valid");
        validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
        validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");


        $oldDataStmt = $conn->prepare("SELECT * FROM tb_retur_penjualan WHERE penjualan_id = ?");
        $oldDataStmt->bind_param("s", $penjualan_id);
        $oldDataStmt->execute();
        $oldDataResult = $oldDataStmt->get_result();
        $oldData = $oldDataResult->fetch_assoc();
        $oldDataStmt->close();

        $stmt = $conn->prepare("UPDATE tb_retur_penjualan SET tanggal_penjualan =?,customer_id=?,gudang_id=?,keterangan_penjualan=?,ppn=?,diskon=?,nominal_pph=?,status=?,keterangan_gudang=?,keterangan_invoice=?,keterangan_pengiriman=?
        WHERE penjualan_id = ?");
        $stmt->bind_param(
            "ssssdddsssss",
            $tanggal_penjualan,
            $customer_id,
            $gudang_id,
            $keterangan_penjualan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $keterangan_gudang,
            $keterangan_invoice,
            $keterangan_pengiriman,
            $penjualan_id
        );
        $stmt->execute();
        $stmt->close();

        executeInsert(
            $conn,
            "INSERT INTO tb_retur_penjualan_history (retur_penjualan_history_id,retur_penjualan_id,penjualan_id,
                tanggal_penjualan_before,customer_id_before,gudang_id_before, keterangan_penjualan_before,ppn_before,
                diskon_before, nominal_pph_before, status_before, created_by_before,tanggal_input_promo_berlaku_before,
                keterangan_invoice_before,keterangan_gudang_before,keterangan_pengiriman_before,no_pengiriman_before,
                
                tanggal_penjualan_after,customer_id_after,gudang_id_after, keterangan_penjualan_after,ppn_after,
                diskon_after, nominal_pph_after, status_after, created_by_after,tanggal_input_promo_berlaku_after,
                keterangan_invoice_after,keterangan_gudang_after,keterangan_pengiriman_after,no_pengiriman_after,created_status)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $retur_penjualan_history_id,
                $retur_penjualan_id,
                $penjualan_id,

                $oldData['tanggal_penjualan'],
                $oldData['customer_id'],
                $oldData['gudang_id'],
                $oldData['keterangan_penjualan'],
                $oldData['ppn'],

                $oldData['diskon'],
                $oldData['nominal_pph'],
                $oldData['status'],
                $oldData['created_by'],
                $oldData['tanggal_input_promo_berlaku'],

                $oldData['keterangan_invoice'],
                $oldData['keterangan_gudang'],
                $oldData['keterangan_pengiriman'],
                $oldData['no_pengiriman'],

                $tanggal_penjualan,
                $customer_id,
                $gudang_id,
                $keterangan_penjualan,
                $ppn_unformat,

                $diskon_penjualan,
                $nominal_pph_unformat,
                $status,
                $created_by,
                $tanggal_input_promo_berlaku,

                $keterangan_invoice,
                $keterangan_gudang,
                $keterangan_pengiriman,
                $oldData['no_pengiriman'],
                "update"
            ],
            "sssssssdddsssssssssssdddssssssss"
        );

        $total_qty = 0;
        $total_harga = 0;
        $urutan_detail = 0;
        // === Process Detail Items ===
        if (isset($data['details'])) {

            $existingDetailsStmt = $conn->prepare("SELECT * FROM tb_detail_retur_penjualan_history WHERE penjualan_id = ?");
            $existingDetailsStmt->bind_param("s", $penjualan_id);
            $existingDetailsStmt->execute();
            $existingDetailsResult = $existingDetailsStmt->get_result();

            while ($row = $existingDetailsResult->fetch_assoc()) {
                $detail_retur_penjualan_history_id = generateCustomID('DPJH', 'tb_detail_retur_penjualan_history', 'detail_retur_penjualan_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_retur_penjualan_history (detail_retur_penjualan_history_id, retur_penjualan_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_retur_penjualan_history)
                         VALUES (?,?,?,?,?,?,?,?,?)",
                    [
                        $detail_retur_penjualan_history_id,
                        $retur_penjualan_history_id,
                        $row['produk_id'],
                        $row['urutan'],
                        $row['qty'],
                        $row['harga'],
                        $row['diskon'],
                        $row['satuan_id'],
                        'B'
                    ],
                    "sssddddss"
                );
            }

            $existingDetailsStmt->close();



            $delete_stmt = $conn->prepare("DELETE FROM tb_detail_penjualan WHERE penjualan_id = ?");
            $delete_stmt->bind_param("s", $penjualan_id);
            $delete_stmt->execute();
            $delete_stmt->close();

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

                $detail_penjualan_id = generateCustomID('DPE', 'tb_detail_penjualan', 'detail_penjualan_id', $conn);
                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_retur_penjualan (detail_penjualan_id, penjualan_id, produk_id, qty, harga, diskon, satuan_id,urutan)
                VALUES (?, ?, ?, ?, ?, ?, ?,?)",
                    [
                        $detail_penjualan_id,
                        $penjualan_id,
                        $produk_id,
                        $qty_unformat,
                        $harga_unformat,
                        $diskon_unformat,
                        $satuan_id,
                        $urutan_detail
                    ],
                    "sssdddsd"
                );


                $detail_retur_penjualan_history_id = generateCustomID('DPJH', 'tb_detail_retur_penjualan_history', 'detail_retur_penjualan_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_penjualan_history (detail_penjualan_history_id, penjualan_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_penjualan_history)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $detail_penjualan_history_id,
                        $penjualan_history_id,
                        $produk_id,
                        $urutan_detail,
                        $qty_unformat,
                        $harga_unformat,
                        $diskon_unformat,
                        $satuan_id,
                        'A'
                    ],
                    "ssssdddss"
                );
                $urutan_detail += 1;
            }
        }

        // === Final Calculation ===
        $sub_total = $total_harga - $diskon_invoice_unformat;
        $nominal_ppn = $sub_total * $ppn_unformat;
        $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

        // === Update Purchase Summary ===
        $stmt = $conn->prepare("UPDATE tb_retur_penjualan 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,sub_total=?
                            WHERE penjualan_id = ?");
        $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt->execute();
        $stmt->close();

        $stmt_history = $conn->prepare("UPDATE tb_retur_penjualan_history 
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,sub_total_after =?
                            WHERE penjualan_id = ?");
        $stmt_history->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt_history->execute();
        $stmt_history->close();

        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "penjualan berhasil diupdate."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["ok" => false, "message" => $e->getMessage()]);
    }
}
