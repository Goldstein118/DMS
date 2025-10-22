<?php
require_once __DIR__ . '/../utils/helpers.php';
try {
    $requiredFields = ['tanggal_invoice', 'pembelian_id', 'retur_pembelian_id'];

    $fields = validate_1($data, $requiredFields);
    $retur_pembelian_id = $data['retur_pembelian_id'];
    $pembelian_id = $data['pembelian_id'];
    $gudang_id = $data['gudang_id'];
    $tanggal_invoice = $data['tanggal_invoice'];
    $no_invoice = $data['no_invoice'];
    $tanggal_po = $fields['tanggal_po'];
    $supplier_id = $fields['supplier_id'];
    $keterangan = $fields['keterangan'];
    $status = $fields['status'];
    $ppn = $fields['ppn'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $no_pengiriman = $fields['no_pengiriman'];
    $tanggal_terima = $fields['tanggal_terima'];
    $tanggal_pengiriman = $fields['tanggal_pengiriman'];
    $invoice_id = $fields['invoice_id'];
    $created_by = $fields['created_by'];
    $ppn_unformat = toFloat($ppn);
    $diskon_invoice_unformat = toFloat($diskon_invoice);
    $nominal_pph_unformat = toFloat($nominal_pph);

    // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
    validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
    validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");


    $oldDataStmt = $conn->prepare("SELECT * FROM tb_retur_pembelian WHERE retur_pembelian_id = ?");
    $oldDataStmt->bind_param("s", $retur_pembelian_id);
    $oldDataStmt->execute();
    $oldDataResult = $oldDataStmt->get_result();
    $oldData = $oldDataResult->fetch_assoc();
    $oldDataStmt->close();

    $retur_pembelian_history_id = generateCustomID('RTNH', 'tb_retur_pembelian_history', 'retur_pembelian_history_id', $conn);


    executeInsert(
        $conn,
        "INSERT INTO tb_retur_pembelian_history (
        retur_pembelian_history_id,retur_pembelian_id_before,invoice_id_before,tanggal_invoice_before,no_invoice_supplier_before,
        tanggal_input_invoice_before,tanggal_po_before,tanggal_pengiriman_before,tanggal_terima_before,supplier_id_before,
        gudang_id_before,pembelian_id_before,keterangan_before,no_pengiriman_before,total_qty_before,
        ppn_before,nominal_ppn_before,diskon_before,nominal_pph_before, biaya_tambahan_before,
        sub_total_before,grand_total_before,created_by_before,status_before,keterangan_cancel_before,
        cancel_by_before,
        pembelian_id_after, tanggal_po_after, supplier_id_after, gudang_id_after,keterangan_after,
        ppn_after,diskon_after, nominal_pph_after, status_after,created_by_after, created_status
    )
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [

            // Before
            $retur_pembelian_history_id,
            $oldData['retur_pembelian_id'],
            $oldData['invoice_id'],
            $oldData['tanggal_invoice'],
            $oldData['no_invoice_supplier'],

            $oldData['tanggal_input_invoice'],
            $oldData['tanggal_po'],
            $oldData['tanggal_pengiriman'],
            $oldData['tanggal_terima'],
            $oldData['supplier_id'],

            $oldData['gudang_id'],
            $oldData['pembelian_id'],
            $oldData['keterangan'],
            $oldData['no_pengiriman'],
            $oldData['total_qty'],

            $oldData['ppn'],
            $oldData['nominal_ppn'],
            $oldData['diskon'],
            $oldData['nominal_pph'],
            $oldData['biaya_tambahan'],

            $oldData['sub_total'],
            $oldData['grand_total'],
            $oldData['created_by'],
            $oldData['status'],
            $oldData['keterangan_cancel'],
            $oldData['cancel_by'],

            // After
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $gudang_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $created_by,
            'update'
        ],
        "ssssssssssssssddddddddsssssssssdddsss"


    );




    $stmt = $conn->prepare("UPDATE tb_retur_pembelian SET tanggal_invoice=?,no_invoice_supplier=?, tanggal_po=?, supplier_id=?, keterangan=?, ppn=?,diskon=?, nominal_pph=?,no_pengiriman=?,tanggal_terima=?,tanggal_pengiriman=? , pembelian_id =? WHERE retur_pembelian_id=?");
    $stmt->bind_param(
        "sssssdddsssss",
        $tanggal_invoice,
        $no_invoice,
        $tanggal_po,
        $supplier_id,
        $keterangan,
        $ppn,
        $diskon_invoice_unformat,
        $nominal_pph_unformat,
        $no_pengiriman,
        $tanggal_terima,
        $tanggal_pengiriman,
        $pembelian_id,
        $retur_pembelian_id
    );
    $stmt->execute();
    $stmt->close();

    $delete_stmt = $conn->prepare("DELETE FROM tb_detail_retur_pembelian WHERE retur_pembelian_id = ?");
    $delete_stmt->bind_param("s", $retur_pembelian_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    $total_qty = 0;
    $total_harga = 0;
    $urutan_detail = 0;
    // === Process Detail Items ===
    if (isset($data['details'])) {

        $existingDetailsStmt = $conn->prepare("SELECT * FROM tb_detail_retur_pembelian WHERE pembelian_id = ?");
        $existingDetailsStmt->bind_param("s", $pembelian_id);
        $existingDetailsStmt->execute();
        $existingDetailsResult = $existingDetailsStmt->get_result();

        while ($row = $existingDetailsResult->fetch_assoc()) {
            $detail_retur_pembelian_history_id = generateCustomID('RTNH', 'tb_detail_retur_pembelian_history', 'detail_retur_pembelian_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_detail_retur_pembelian_history (detail_retur_pembelian_history_id, retur_pembelian_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_pembelian_history)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $detail_pembelian_history_id,
                    $retur_pembelian_history_id,
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

        $delete_stmt = $conn->prepare("DELETE FROM tb_detail_retur_pembelian WHERE pembelian_id = ?");
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

            $detail_retur_pembelian_id = generateCustomID('DRTN', 'tb_detail_retur_pembelian', 'detail_retur_pembelian_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_retur_pembelian (detail_retur_pembelian_id,retur_pembelian_id, pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan,invoice_id)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)",
                [
                    $detail_retur_pembelian_id,
                    $retur_pembelian_id,
                    $pembelian_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id,
                    $urutan_detail,
                    $invoice_id,
                ],
                "ssssdddsds"
            );
            $detail_retur_pembelian_history_id = generateCustomID('DRTNH', 'tb_detail_retur_pembelian_history', 'detail_retur_pembelian_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_detail_retur_pembelian_history (detail_retur_pembelian_history_id,retur_pembelian_history_id,pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan,invoice_id,tipe_retur_pembelian_history)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)",
                [
                    $detail_retur_pembelian_history_id,
                    $retur_pembelian_history_id,
                    $pembelian_id,
                    $produk_id,
                    $qty_unformat,
                    $harga_unformat,
                    $diskon_unformat,
                    $satuan_id,
                    $urutan_detail,
                    $invoice_id,
                    'A'
                ],
                "ssssdddsdss"
            );
            $urutan_detail += 1;
        }
    }


    $sub_total = $total_harga - $diskon_invoice_unformat;
    $nominal_ppn = $sub_total * $ppn_unformat;
    $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

    // === Update Purchase Summary ===
    $stmt = $conn->prepare("UPDATE tb_retur_pembelian 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,sub_total=?
                            WHERE retur_pembelian_id = ?");
    $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $retur_pembelian_id);
    $stmt->execute();
    $stmt->close();


    $stmt = $conn->prepare("UPDATE tb_retur_pembelian_history 
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,sub_total_after=?
                            WHERE retur_pembelian_history_id = ?");
    $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $retur_pembelian_history_id);
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
