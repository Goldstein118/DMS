<?php
require_once __DIR__ . '/../utils/helpers.php';

try {

    if (isset($data['tanggal_pengiriman']) && $data['status'] === "pengiriman") {

        $pembelian_id = $data['pembelian_id'];
        $tanggal_pengiriman = $data['tanggal_pengiriman'];
        $no_pengiriman = $data['no_pengiriman'];
        $status = $data['status'];

        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_pengiriman = ?,  no_pengiriman = ? ,status =? WHERE pembelian_id = ?");
        $stmt->bind_param("ssss", $tanggal_pengiriman, $no_pengiriman, $status, $pembelian_id);
        $stmt->execute();
        $stmt->close();


        $stmt_history = $conn->prepare("UPDATE tb_pembelian_history SET tanggal_pengiriman_after = ?,  no_pengiriman_after = ? ,status_after =? WHERE pembelian_history_id = ?");
        $stmt_history->bind_param("ssss", $tanggal_pengiriman, $no_pengiriman, $status, $pembelian_id);
        $stmt_history->execute();
        $stmt_history->close();

        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "tanggal_pengiriman berhasil diupdate"]);
    }

    if (isset($data['tanggal_terima']) && $data['status'] === "terima") {

        $pembelian_id = $data['pembelian_id'];
        $tanggal_terima = $data['tanggal_terima'];
        $status = $data['status'];
        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_terima = ?,status =? WHERE pembelian_id = ?");
        $stmt->bind_param("sss", $tanggal_terima, $status, $pembelian_id);
        $stmt->execute();
        $stmt->close();


        $stmt_history = $conn->prepare("UPDATE tb_pembelian_history SET tanggal_terima_after = ?,status_after =? WHERE pembelian_history_id = ?");
        $stmt_history->bind_param("sss", $tanggal_terima, $status, $pembelian_id);
        $stmt_history->execute();
        $stmt_history->close();

        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "tanggal_terima berhasil diupdate"]);
    }



    if (isset($data['pembelian_id']) && isset($data['tanggal_po'])) {
        // Step 1: Get the old data before the update
        $oldDataStmt = $conn->prepare("SELECT * FROM tb_pembelian WHERE pembelian_id = ?");
        $oldDataStmt->bind_param("s", $pembelian_id);
        $oldDataStmt->execute();
        $oldDataResult = $oldDataStmt->get_result();
        $oldData = $oldDataResult->fetch_assoc();
        $oldDataStmt->close();

        $requiredFields = ['pembelian_id', 'tanggal_po', 'supplier_id'];
        $fields = validate_1($data, $requiredFields);
        $pembelian_id = $fields['pembelian_id'];
        $tanggal_po = $fields['tanggal_po'];
        $supplier_id = $fields['supplier_id'];
        $keterangan = $fields['keterangan'];
        $ppn = $fields['ppn'];
        $diskon = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $status = $fields['status'];

        $ppn_unformat = toFloat($ppn);
        $diskon_invoice_unformat = toFloat($diskon);
        $nominal_pph_unformat = toFloat($nominal_pph);


        validate_2($ppn_unformat, '/^\d+(\.\d+)?$/', "Format ppn unformat tidak valid");
        validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
        validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");
        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_po =?,supplier_id=?,keterangan=?,ppn=?
                                ,diskon=?,nominal_pph=?,status=?
        WHERE pembelian_id = ?");
        $stmt->bind_param(
            "sssdddss",
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $pembelian_id
        );
        $stmt->execute();
        $stmt->close();




        $pembelian_history_id = generateCustomID('POH', 'tb_pembelian_history', 'pembelian_history_id', $conn);

        executeInsert(
            $conn,
            "INSERT INTO tb_pembelian_history (
        pembelian_history_id,
        pembelian_id_before, tanggal_po_before, supplier_id_before, keterangan_before, ppn_before, diskon_before, nominal_ppn_before, status_before,
        pembelian_id_after, tanggal_po_after, supplier_id_after, keterangan_after, ppn_after, diskon_after, nominal_ppn_after, status_after,
        created_by_after, created_status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $pembelian_history_id,

                // Before
                $oldData['pembelian_id'],
                $oldData['tanggal_po'],
                $oldData['supplier_id'],
                $oldData['keterangan'],
                $oldData['ppn'],
                $oldData['diskon'],
                $oldData['nominal_pph'],
                $oldData['status'],

                // After
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
            "ssssdddsssssdddsss"
        );






        $total_qty = 0;
        $total_harga = 0;
        $urutan_detail = 0;
        // === Process Detail Items ===
        if (isset($data['details'])) {

            $existingDetailsStmt = $conn->prepare("SELECT * FROM tb_detail_pembelian WHERE pembelian_id = ?");
            $existingDetailsStmt->bind_param("s", $pembelian_id);
            $existingDetailsStmt->execute();
            $existingDetailsResult = $existingDetailsStmt->get_result();

            while ($row = $existingDetailsResult->fetch_assoc()) {
                $detail_pembelian_history_id = generateCustomID('DPH', 'tb_detail_pembelian_history', 'detail_pembelian_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_pembelian_history (detail_pembelian_history_id, pembelian_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_pembelian_history)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $detail_pembelian_history_id,
                        $pembelian_history_id, // Make sure this is generated and available before this point
                        $row['produk_id'],
                        $row['urutan'],
                        $row['qty'],
                        $row['harga'],
                        $row['diskon'],
                        $row['satuan_id'],
                        'B'
                    ],
                    "ssssdddss"
                );
            }

            $existingDetailsStmt->close();

            $delete_stmt = $conn->prepare("DELETE FROM tb_detail_pembelian WHERE pembelian_id = ?");
            $delete_stmt->bind_param("s", $pembelian_id);
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

                $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
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


                $detail_pembelian_history_id = generateCustomID('DPH', 'tb_detail_pembelian_history', 'detail_pembelian_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_pembelian_history (detail_pembelian_history_id, pembelian_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_pembelian_history)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $detail_pembelian_history_id,
                        $pembelian_history_id, // same ID as used in the header history
                        $produk_id,
                        $urutan_detail, // You'll need to manage this or set it to a default/increment
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



        $total_biaya_tambahan = 0;
        $urutan_tambahan = 0;
        if (isset($data['biaya_tambahan'])) {

            $existingTambahanStmt = $conn->prepare("SELECT * FROM tb_biaya_tambahan WHERE pembelian_id = ?");
            $existingTambahanStmt->bind_param("s", $pembelian_id);
            $existingTambahanStmt->execute();
            $existingTambahanResult = $existingTambahanStmt->get_result();

            while ($row = $existingTambahanResult->fetch_assoc()) {
                $biaya_tambahan_history_id = generateCustomID('BTH', 'tb_biaya_tambahan_history', 'biaya_tambahan_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_biaya_tambahan_history (biaya_tambahan_history_id, pembelian_history_id, data_biaya_id, keterangan, jlh,urutan, tipe_detail_pembelian_history)
                         VALUES (?,?,?,?,?,?,?)",
                    [
                        $biaya_tambahan_history_id,
                        $pembelian_history_id,
                        $row['data_biaya_id'],
                        $row['keterangan'],
                        $row['jlh'],
                        $row['urutan'],
                        'B'
                    ],
                    "ssssdds"
                );
            }

            $existingTambahanStmt->close();

            $delete_stmt_biaya_tambahan = $conn->prepare("DELETE FROM tb_biaya_tambahan WHERE pembelian_id = ?");
            $delete_stmt_biaya_tambahan->bind_param("s", $pembelian_id);
            $delete_stmt_biaya_tambahan->execute();
            $delete_stmt_biaya_tambahan->close();


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

                $biaya_tambahan_id = generateCustomID('DBT', 'tb_biaya_tambahan', 'biaya_tambahan_id', $conn);
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
                        $urutan_tambahan
                    ],
                    "sssdsd"
                );



                $biaya_tambahan_history_id = generateCustomID('BTH', 'tb_biaya_tambahan_history', 'biaya_tambahan_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_biaya_tambahan_history (biaya_tambahan_history_id, pembelian_history_id, data_biaya_id, keterangan, jlh,urutan, tipe_detail_pembelian_history)
                         VALUES (?,?,?,?,?,?,?)",
                    [
                        $biaya_tambahan_history_id,
                        $pembelian_history_id,
                        $data_biaya_id,
                        $keterangan_biaya,
                        $jumlah_unformat,
                        $urutan_tambahan,
                        'A'
                    ],
                    "ssssdds"
                );
                $urutan_tambahan += 1;
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
        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "Pembelian berhasil diupdate."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
