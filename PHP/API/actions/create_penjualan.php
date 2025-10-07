<?php
require_once __DIR__ . '/../utils/helpers.php';

function cek_promo_kondisi_customer_channel($array_promo_kondisi, $channel_id, $customer_id)
{
    $validPromoIds = [];

    foreach ($array_promo_kondisi as $subArray) {
        $groupIsValid = true;
        $promoId = null;

        foreach ($subArray as $kondisiObj) {
            $jenis_kondisi = $kondisiObj['jenis_kondisi'] ?? '';
            $exclude_include = $kondisiObj['exclude_include'] ?? '';
            $kondisi = $kondisiObj['kondisi'] ?? '[]';
            $promoId = $promoId ?? ($kondisiObj['promo_id'] ?? null);

            // Parse the 'kondisi' JSON string into PHP array
            $parsedKondisi = [];
            if (is_string($kondisi)) {
                $parsedKondisi = json_decode($kondisi, true);
            } elseif (is_array($kondisi)) {
                $parsedKondisi = $kondisi;
            }

            if ($parsedKondisi === null || !is_array($parsedKondisi)) {
                // Failed to parse JSON
                error_log("Failed to parse kondisi: " . json_encode($kondisi));
                $groupIsValid = false;
                break;
            }

            if ($jenis_kondisi === "customer") {
                if ($exclude_include === "include") {
                    if (!in_array($customer_id, $parsedKondisi)) {
                        $groupIsValid = false;
                        break;
                    }
                } elseif ($exclude_include === "exclude") {
                    if (in_array($customer_id, $parsedKondisi)) {
                        $groupIsValid = false;
                        break;
                    }
                }
            } elseif ($jenis_kondisi === "channel") {
                if ($exclude_include === "include") {
                    if (!in_array($channel_id, $parsedKondisi)) {
                        $groupIsValid = false;
                        break;
                    }
                } elseif ($exclude_include === "exclude") {
                    if (in_array($channel_id, $parsedKondisi)) {
                        $groupIsValid = false;
                        break;
                    }
                }
            }
        }

        if ($groupIsValid && $promoId) {
            $validPromoIds[] = $promoId;
        }
    }

    return $validPromoIds;
}

function cek_promo_kondisi_produk_brand($array_promo_kondisi, $items, $is_akumulasi)
{
    $validPromoIds = [];

    foreach ($array_promo_kondisi as $subArray) {
        $groupIsValid = true;
        $promoId = null;

        foreach ($subArray as $kondisiObj) {
            $jenis_kondisi = $kondisiObj['jenis_kondisi'] ?? '';
            $exclude_include = $kondisiObj['exclude_include'] ?? '';
            $qty_min = isset($kondisiObj['qty_min']) ? (int)$kondisiObj['qty_min'] : 0;
            $qty_max = isset($kondisiObj['qty_max']) ? (int)$kondisiObj['qty_max'] : 0;
            if (isset($kondisiObj['qty_akumulasi']) && $kondisiObj['qty_akumulasi'] != 0 && $is_akumulasi) {
                $qty_kelipatan = (int)$kondisiObj['qty_akumulasi'];
            }

            $promoId = $promoId ?: ($kondisiObj['promo_id'] ?? null);

            if (!in_array($jenis_kondisi, ['produk', 'brand'])) {
                continue;
            }

            $parsedKondisi = [];
            if (is_string($kondisiObj['kondisi'])) {
                $parsedKondisi = json_decode($kondisiObj['kondisi'], true);
            } else {
                $parsedKondisi = $kondisiObj['kondisi'];
            }

            if (!is_array($parsedKondisi)) {
                $groupIsValid = false;
                break;
            }

            $hasValidMatch = false;

            if ($jenis_kondisi === 'produk') {
                foreach ($items as $item) {
                    $produk_id = $item['produk_id'] ?? '';
                    $qty = isset($item['qty']) ? (int)$item['qty'] : 0;

                    if (in_array($produk_id, $parsedKondisi) && $qty >= $qty_min) {
                        $hasValidMatch = true;
                        break;
                    }
                }
            } elseif ($jenis_kondisi === 'brand') {
                $brandQtyMap = [];

                foreach ($items as $item) {
                    $brand_id = $item['brand_id'] ?? null;
                    $qty = isset($item['qty']) ? (int)$item['qty'] : 0;


                    if (!$brand_id || !in_array($brand_id, $parsedKondisi)) {
                        continue;
                    }


                    if (!isset($brandQtyMap[$brand_id])) {
                        $brandQtyMap[$brand_id] = 0;
                    }

                    $brandQtyMap[$brand_id] += $qty;
                }


                foreach ($brandQtyMap as $totalQty) {
                    if ($totalQty >= $qty_min) {
                        $hasValidMatch = true;
                        break;
                    }
                }
            }


            // Handle include / exclude logic
            if (($exclude_include === 'include' && !$hasValidMatch) ||
                ($exclude_include === 'exclude' && $hasValidMatch)
            ) {
                $groupIsValid = false;
                break;
            }
        }

        if ($groupIsValid && $promoId) {

            $validPromoIds[] = $promoId;
        }
    }

    return $validPromoIds;
}


if (isset($data['tanggal_penjualan'])) {

    try {
        // Validation
        $requiredFields = ['tanggal_penjualan'];

        $fields = validate_1($data, $requiredFields);

        // Extract main fields
        $tanggal_po = $fields['tanggal_penjualan'];
        $gudang_id = $fields['gudang_id'];
        $customer_id = $fields['customer_id'];
        $keterangan = $fields['keterangan'];
        $ppn = $fields['ppn'];
        $diskon = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $status = $fields['status'];
        $created_by = $fields['created_by'];



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
            "INSERT INTO tb_pembelian (pembelian_id, tanggal_po, supplier_id,gudang_id, keterangan, ppn,diskon, nominal_pph, status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)",
            [
                $pembelian_id,
                $tanggal_po,
                $supplier_id,
                $gudang_id,
                $keterangan,
                $ppn_unformat,
                $diskon_invoice_unformat,
                $nominal_pph_unformat,
                $status,
                $created_by
            ],
            "sssssdddss"
        );

        executeInsert(
            $conn,
            "INSERT INTO tb_pembelian_history (pembelian_history_id,pembelian_id_after, tanggal_po_after, supplier_id_after,gudang_id_after, keterangan_after, ppn_after,diskon_after, nominal_ppn_after, status_after, cancel_by_after,created_status)
        VALUES (?,?, ?, ?, ?, ?, ?, ?,?,?,?,?)",
            [
                $pembelian_history_id,
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
                $created_status
            ],
            "ssssssdddsss"
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

                $detail_pembelian_id = generateCustomID('DPO', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);
                $detail_pembelian_history_id = generateCustomID('DPOH', 'tb_detail_pembelian_history', 'detail_pembelian_history_id', $conn);
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


        $stmt_history = $conn->prepare("UPDATE tb_pembelian_history 
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,biaya_tambahan_after = ?,sub_total_after =?
                            WHERE pembelian_history_id = ?");
        $stmt_history->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_history_id);
        $stmt_history->execute();
        $stmt_history->close();
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
} else if (isset($data['cek_promo'])) {
    try {

        $customer_id = $data['customer_id'];
        $tanggal_penjualan = $data['tanggal_penjualan'];



        $stmt_channel_id = $conn->prepare("SELECT channel_id FROM tb_customer WHERE customer_id = ?");
        $stmt_channel_id->bind_param("s", $customer_id);
        $stmt_channel_id->execute();
        $result = $stmt_channel_id->get_result();
        $customer = $result->fetch_assoc();
        $channel_id = $customer['channel_id'] ?? '';
        $stmt_channel_id->close();

        if (!$channel_id) {
            throw new Exception("Channel ID tidak ditemukan.");
        }

        $array_produk_brand_qty = [];
        if (isset($data['details'])) {
            foreach ($data['details'] as $detail) {
                $produk_id = $detail['produk_id'];
                $qty = $detail['qty'];



                $stmt_brand_id = $conn->prepare("SELECT brand_id FROM tb_produk WHERE produk_id = ?");
                $stmt_brand_id->bind_param("s", $produk_id);
                $stmt_brand_id->execute();
                $result = $stmt_brand_id->get_result();
                $brand = $result->fetch_assoc();
                $brand_id = $brand['brand_id'] ?? '';
                $stmt_brand_id->close();


                $array_produk_brand_qty[] = [
                    "produk_id" => $produk_id,
                    "brand_id" => $brand_id,
                    "qty" => $qty
                ];
            }
        }




        $stmt_kelipatan = $conn->prepare("SELECT * FROM tb_promo 
                                WHERE status = 'aktif' 
                                AND quota > 0 AND kelipatan = 'ya'
                                AND ? BETWEEN tanggal_berlaku AND tanggal_selesai");
        $stmt_kelipatan->bind_param("s", $tanggal_penjualan);
        $stmt_kelipatan->execute();
        $result = $stmt_kelipatan->get_result();
        $promo_kelipatan = [];
        while ($row = $result->fetch_assoc()) {
            $promo_kelipatan[] = $row;
        }
        $stmt_kelipatan->close();

        $promo_kelipatan_kondisi = [];

        foreach ($promo_kelipatan as $promo) {
            $promo_id = $promo['promo_id'];
            $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $conditions = [];
            while ($row = $result->fetch_assoc()) {
                $conditions[] = $row;
            }
            $promo_kelipatan_kondisi[$promo_id] = $conditions;
            $stmt->close();
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
}
