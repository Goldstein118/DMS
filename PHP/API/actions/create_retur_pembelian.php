    <?php
    require_once __DIR__ . '/../utils/helpers.php';

    try {

        $requiredFields = ['tanggal_invoice', 'pembelian_id'];

        $fields = validate_1($data, $requiredFields);
        $pembelian_id = $data['pembelian_id'];
        $tanggal_invoice = $data['tanggal_invoice'];
        $no_invoice = $data['no_invoice'];
        $status = $data['status'];
        $invoice_id = $data['invoice_id'];
        $retur_pembelian_id = generateCustomID('RTN', 'tb_retur_pembelian', 'retur_pembelian_id', $conn);
        $retur_pembelian_history_id = generateCustomID('RTNH', 'tb_retur_pembelian_history', 'retur_pembelian_history_id', $conn);
        $tanggal_input_invoice = date("Y-m-d");
        $tanggal_po = $fields['tanggal_po'];
        $supplier_id = $fields['supplier_id'];
        $keterangan = $fields['keterangan'];
        $ppn = $fields['ppn'];
        $diskon_invoice = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $status = $fields['status'];
        $created_by = $fields['created_by'];
        $no_pengiriman = $fields['no_pengiriman'];

        $tanggal_terima = $fields['tanggal_terima'];
        $tanggal_pengiriman = $fields['tanggal_pengiriman'];

        $ppn_unformat = toFloat($ppn);
        $diskon_invoice_unformat = toFloat($diskon_invoice);
        $nominal_pph_unformat = toFloat($nominal_pph);

        // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
        validate_2($diskon_invoice_unformat, '/^\d+$/', "Format diskon invoice unformat tidak valid");
        validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");

        executeInsert($conn, "INSERT INTO tb_retur_pembelian(retur_pembelian_id,invoice_id,tanggal_invoice,no_invoice_supplier,tanggal_input_invoice,pembelian_id, tanggal_po, supplier_id, keterangan, ppn,diskon, nominal_pph, status, created_by,no_pengiriman,tanggal_terima,tanggal_pengiriman) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [
            $retur_pembelian_id,
            $invoice_id,
            $tanggal_invoice,
            $no_invoice,
            $tanggal_input_invoice,
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $created_by,
            $no_pengiriman,
            $tanggal_terima,
            $tanggal_pengiriman
        ], "sssssssssdddsssss");


        executeInsert($conn, "INSERT INTO tb_retur_pembelian_history(retur_pembelian_history_id,retur_pembelian_id_after,invoice_id_after,tanggal_invoice_after,no_invoice_supplier_after,tanggal_input_invoice_after,pembelian_id_after, tanggal_po_after, supplier_id_after, keterangan_after, ppn_after,diskon_after, nominal_pph_after, status_after, created_by_after,no_pengiriman_after,tanggal_terima_after,tanggal_pengiriman_after) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [

            $retur_pembelian_history_id,
            $retur_pembelian_id,
            $invoice_id,
            $tanggal_invoice,
            $no_invoice,
            $tanggal_input_invoice,
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $ppn_unformat,
            $diskon_invoice_unformat,
            $nominal_pph_unformat,
            $status,
            $created_by,
            $no_pengiriman,
            $tanggal_terima,
            $tanggal_pengiriman
        ], "ssssssssssdddsssss");


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

                $detail_retur_pembelian_id = generateCustomID('DRTN', 'tb_detail_retur_pembelian', 'detail_retur_pembelian_id', $conn);
                $detail_retur_pembelian_history_id = generateCustomID('DRTNH', 'tb_detail_retur_pembelian_history', 'detail_retur_pembelian_history_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_retur_pembelian (detail_retur_pembelian_id, retur_pembelian_id,pembelian_id, produk_id, qty, harga, diskon, satuan_id,urutan,invoice_id)
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

        // === Final Calculation ===
        $sub_total = $total_harga - $diskon_invoice_unformat;
        $nominal_ppn = $sub_total * $ppn_unformat;
        $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

        // === Update Purchase Summary ===
        $stmt = $conn->prepare("UPDATE tb_retur_pembelian 
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,sub_total=?
                            WHERE pembelian_id = ?");
        $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $pembelian_id);
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
                "retur_pembelian_id" => $retur_pembelian_id
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
