<?php
require_once __DIR__ . '/../utils/helpers.php';



if (isset($data['tanggal_penjualan'])) {

    try {
        // Validation
        $requiredFields = ['tanggal_penjualan', 'gudang_id', 'customer_id', 'penjualan_id'];

        $fields = validate_1($data, $requiredFields);

        $penjualan_id = $fields['penjualan_id'];
        $tanggal_penjualan = $fields['tanggal_penjualan'];
        $gudang_id = $fields['gudang_id'];
        $customer_id = $fields['customer_id'];
        $keterangan = $fields['keterangan'];
        $keterangan_invoice = $fields['keterangan_invoice'];
        $keterangan_gudang = $fields['keterangan_gudang'];
        $keterangan_pengiriman = $fields['keterangan_pengiriman'];
        $ppn = $fields['ppn'];
        $diskon_penjualan = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $status = $fields['status'];

        $created_by = $fields['created_by'];



        $ppn_unformat = toFloat($ppn);
        $diskon_penjualan = toFloat($diskon_penjualan);
        $nominal_pph_unformat = toFloat($nominal_pph);

        // validate_2($ppn_unformat, '/^\d+$/', "Format ppn  tidak valid");
        validate_2($diskon_penjualan, '/^\d+$/', "Format diskon  unformat tidak valid");
        validate_2($nominal_pph_unformat, '/^\d+$/', "Format nominal pph unformat tidak valid");
        // Generate ID
        $retur_penjualan_id = generateCustomID('RPJ', 'tb_retur_penjualan', 'retur_penjualan_id', $conn);
        $retur_penjualan_history_id = generateCustomID('RPJH', 'tb_retur_penjualan_history', 'retur_penjualan_history_id', $conn);

        executeInsert(
            $conn,
            "INSERT INTO tb_retur_penjualan (retur_penjualan_id,penjualan_id, tanggal_penjualan,customer_id,gudang_id, keterangan_penjualan, ppn,diskon, nominal_pph, status, created_by,keterangan_invoice,keterangan_gudang,keterangan_pengiriman)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?)",
            [
                $retur_penjualan_id,
                $penjualan_id,
                $tanggal_penjualan,
                $customer_id,
                $gudang_id,
                $keterangan,
                $ppn_unformat,
                $diskon_penjualan,
                $nominal_pph_unformat,
                $status,
                $created_by,
                $keterangan_invoice,
                $keterangan_gudang,
                $keterangan_pengiriman,

            ],
            "ssssssdddsssss"
        );


        executeInsert(
            $conn,
            "INSERT INTO tb_retur_penjualan_history (retur_penjualan_history_id,retur_penjualan_id,penjualan_id, tanggal_penjualan_after,customer_id_after,
            gudang_id_after, keterangan_penjualan_after, ppn_after,diskon_after, nominal_pph_after,
            status_after, created_by_after,keterangan_invoice_after,keterangan_gudang_after,keterangan_pengiriman_after)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $retur_penjualan_history_id,
                $retur_penjualan_id,
                $penjualan_id,
                $tanggal_penjualan,
                $customer_id,

                $gudang_id,
                $keterangan,
                $ppn_unformat,
                $diskon_penjualan,
                $nominal_pph_unformat,

                $status,
                $created_by,
                $keterangan_invoice,
                $keterangan_gudang,
                $keterangan_pengiriman,
            ],
            "sssssssdddsssss"
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


                $qty_unformat = toFloat($qty);
                $harga_unformat = toFloat($harga);
                $diskon_unformat = toFloat($diskon);

                validate_2($qty_unformat, '/^\d+$/', "Format qty detail tidak valid");
                validate_2($harga_unformat, '/^\d+$/', "Format harga detail tidak valid");
                validate_2($diskon_unformat, '/^\d+$/', "Format diskon detail tidak valid");

                $total_qty += $qty_unformat;
                $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

                $detail_retur_penjualan_id = generateCustomID('DPJ', 'tb_detail_retur_penjualan', 'detail_retur_penjualan_id', $conn);

                $detail_retur_penjualan_history_id = generateCustomID('DPJH', 'tb_detail_retur_penjualan_history', 'detail_retur_penjualan_history_id', $conn);
                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_retur_penjualan (detail_retur_penjualan_id, retur_penjualan_id, produk_id, qty, harga, diskon, satuan_id,urutan)
                VALUES (?, ?, ?, ?, ?, ?, ?,?)",
                    [
                        $detail_retur_penjualan_id,
                        $retur_penjualan_id,
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
                    "INSERT INTO tb_detail_retur_penjualan_history (
                    detail_retur_penjualan_history_id, retur_penjualan_history_id, produk_id, qty, harga, 
                    diskon, satuan_id,urutan,tipe_detail_retur_penjualan_history)
                VALUES (?,?,?,?,?,?,?,?,?)",
                    [
                        $detail_retur_penjualan_history_id,
                        $retur_penjualan_history_id,
                        $produk_id,
                        $qty_unformat,
                        $harga_unformat,

                        $diskon_unformat,
                        $satuan_id,
                        $urutan_detail,
                        "A"
                    ],
                    "sssddssds"
                );
                $urutan_detail += 1;
            }
        }


        // === Final Calculation ===
        $sub_total = $total_harga - $diskon_penjualan;
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
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,sub_total_after=?
                            WHERE penjualan_id = ?");
        $stmt_history->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt_history->execute();
        $stmt_history->close();

        echo json_encode([
            "success" => true,
            "message" => "Berhasil",
            "data" => [
                "penjualan_id" => $penjualan_id,

            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
}
