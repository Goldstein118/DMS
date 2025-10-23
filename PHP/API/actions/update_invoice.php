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
    $gudang_id = $fields['gudang_id'];
    $keterangan = $fields['keterangan'];
    $ppn = $fields['ppn'];
    $created_by = $fields['created_by'];
    $diskon_invoice = $fields['diskon'];
    $nominal_pph = $fields['nominal_pph'];
    $no_pengiriman = $fields['no_pengiriman'];
    $tanggal_terima = $fields['tanggal_terima'];
    $tanggal_pengiriman = $fields['tanggal_pengiriman'];
    $invoice_id = $fields['invoice_id'];
    $ppn_unformat = toFloat($ppn);
    $diskon_invoice_unformat = toFloat($diskon_invoice);
    $nominal_pph_unformat = toFloat($nominal_pph);
    $status = 'invoice';

    // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
    validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
    validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");

    $oldDataStmt = $conn->prepare("SELECT * FROM tb_invoice WHERE pembelian_id = ?");
    $oldDataStmt->bind_param("s", $pembelian_id);
    $oldDataStmt->execute();
    $oldDataResult = $oldDataStmt->get_result();
    $oldData = $oldDataResult->fetch_assoc();
    $oldDataStmt->close();



    $stmt = $conn->prepare("UPDATE tb_invoice SET 
    tanggal_invoice=?,
    no_invoice_supplier=?, 
    tanggal_po=?, 
    supplier_id=?, 
    gudang_id=?,
    keterangan=?, 
    ppn=?,
    diskon=?, 
    nominal_pph=?,
    no_pengiriman=?,
    tanggal_terima=?,
    tanggal_pengiriman=? WHERE pembelian_id =?");
    $stmt->bind_param(
        "ssssssdddssss",
        $tanggal_invoice,
        $no_invoice,
        $tanggal_po,
        $supplier_id,
        $gudang_id,

        $keterangan,
        $ppn,
        $diskon_invoice_unformat,
        $nominal_pph_unformat,
        $no_pengiriman,
        $tanggal_terima,

        $tanggal_pengiriman,
        $pembelian_id
    );
    $stmt->execute();
    $stmt->close();


    $invoice_history_id = generateCustomID('INH', 'tb_invoice_history', 'invoice_history_id', $conn);

    executeInsert(
        $conn,
        "INSERT INTO tb_invoice_history (
        invoice_history_id,invoice_id,tanggal_invoice_before,no_invoice_supplier_before,tanggal_input_invoice_before,
        tanggal_po_before,tanggal_pengiriman_before,tanggal_terima_before,supplier_id_before,gudang_id_before,
        pembelian_id_before,keterangan_before,no_pengiriman_before,total_qty_before,ppn_before,
        nominal_ppn_before,diskon_before,nominal_pph_before,biaya_tambahan_before,sub_total_before,
        grand_total_before,created_by_before,status_before,keterangan_cancel_before,cancel_by_before,
        
        tanggal_invoice_after,no_invoice_supplier_after,tanggal_input_invoice_after,tanggal_po_after,supplier_id_after, 
        gudang_id_after,keterangan_after,ppn_after,diskon_after,nominal_pph_after,
        no_pengiriman_after,tanggal_terima_after,tanggal_pengiriman_after,pembelian_id_after
    )
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        [
            $invoice_history_id,
            // Before
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
            $tanggal_invoice,
            $no_invoice,
            $oldData['tanggal_input_invoice'],
            $tanggal_po,
            $supplier_id,

            $gudang_id,
            $keterangan,
            $ppn,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,

            $no_pengiriman,
            $tanggal_terima,
            $tanggal_pengiriman,
            $pembelian_id
        ],

        "sssssssssssssddddddddsssssssssssdddssss"

    );




    $total_qty = 0;
    $total_harga = 0;
    $urutan_detail = 0;
    // === Process Detail Items ===
    if (isset($data['details'])) {


        $existingDetailsStmt = $conn->prepare("SELECT * FROM tb_detail_invoice WHERE pembelian_id = ?");
        $existingDetailsStmt->bind_param("s", $pembelian_id);
        $existingDetailsStmt->execute();
        $existingDetailsResult = $existingDetailsStmt->get_result();

        while ($row = $existingDetailsResult->fetch_assoc()) {
            $detail_invoice_history_id = generateCustomID('DINVH', 'tb_detail_invoice_history', 'detail_invoice_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_detail_invoice_history (detail_invoice_history_id, invoice_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_invoice_history)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $detail_invoice_history_id,
                    $invoice_history_id,
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


        $delete_stmt = $conn->prepare("DELETE FROM tb_detail_invoice WHERE pembelian_id = ?");
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

            $detail_invoice_id = generateCustomID('DINV', 'tb_detail_invoice', 'detail_invoice_id', $conn);
            executeInsert(
                $conn,
                "INSERT INTO tb_detail_invoice (detail_invoice_id, pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan,invoice_id)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
                [
                    $detail_invoice_id,
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


            $detail_invoice_history_id = generateCustomID('DINVH', 'tb_detail_invoice_history', 'detail_invoice_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_detail_invoice_history (detail_invoice_history_id, invoice_history_id, produk_id, urutan, qty, harga, diskon, satuan_id, tipe_detail_invoice_history)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $detail_invoice_history_id,
                    $invoice_history_id,
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





    $urutan_biaya_tambahan = 0;
    $total_biaya_tambahan = 0;
    if (isset($data['biaya_tambahan'])) {

        $existingTambahanStmt = $conn->prepare("SELECT * FROM tb_biaya_tambahan_invoice WHERE pembelian_id = ?");
        $existingTambahanStmt->bind_param("s", $pembelian_id);
        $existingTambahanStmt->execute();
        $existingTambahanResult = $existingTambahanStmt->get_result();

        while ($row = $existingTambahanResult->fetch_assoc()) {
            $biaya_tambahan_invoice_history_id = generateCustomID('BTINVH', 'tb_biaya_tambahan_invoice_history', 'biaya_tambahan_invoice_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan_invoice_history (biaya_tambahan_invoice_history_id, invoice_history_id, data_biaya_id, keterangan, jlh,urutan, tipe_biaya_tambahan_invoice_history)
                         VALUES (?,?,?,?,?,?,?)",
                [
                    $biaya_tambahan_invoice_history_id,
                    $invoice_history_id,
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



        $delete_stmt_biaya_tambahan = $conn->prepare("DELETE FROM tb_biaya_tambahan_invoice WHERE pembelian_id = ?");
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

            $biaya_tambahan_id = generateCustomID('BTINV', 'tb_biaya_tambahan_invoice', 'biaya_tambahan_invoice_id', $conn);
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

            $biaya_tambahan_invoice_history_id = generateCustomID('BTINVH', 'tb_biaya_tambahan_invoice_history', 'biaya_tambahan_invoice_history_id', $conn);

            executeInsert(
                $conn,
                "INSERT INTO tb_biaya_tambahan_invoice_history (biaya_tambahan_invoice_history_id, invoice_history_id, data_biaya_id, keterangan, jlh,urutan, tipe_biaya_tambahan_invoice_history)
                         VALUES (?,?,?,?,?,?,?)",
                [
                    $biaya_tambahan_invoice_history_id,
                    $invoice_history_id,
                    $data_biaya_id,
                    $keterangan_biaya,
                    $jumlah_unformat,
                    $urutan_biaya_tambahan,
                    'A'
                ],
                "ssssdds"
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

    $stmt_history = $conn->prepare("UPDATE tb_invoice_history
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,biaya_tambahan_after= ?,sub_total_after=?
                            WHERE pembelian_id_after = ?");
    $stmt_history->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_id);
    $stmt_history->execute();
    $stmt_history->close();
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
