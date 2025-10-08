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


function cek_promo_kondisi_produk_brand($array_promo_kondisi, $items, $is_kelipatan)
{
    $validPromos = [];

    foreach ($array_promo_kondisi as $subArray) {
        $groupIsValid = true;
        $promoId = null;

        // Tracking for kelipatan calculation
        $qty_kelipatan_produk = 0;
        $qty_kelipatan_brand = 0;
        $qty_max_produk = 0;
        $qty_max_brand = 0;
        $total_qty_produk = 0;
        $total_qty_brand = 0;

        foreach ($subArray as $kondisiObj) {
            $jenis_kondisi = $kondisiObj['jenis_kondisi'] ?? '';
            $exclude_include = $kondisiObj['exclude_include'] ?? '';
            $qty_min = isset($kondisiObj['qty_min']) ? (int)$kondisiObj['qty_min'] : 0;
            $qty_max = isset($kondisiObj['qty_max']) ? (int)$kondisiObj['qty_max'] : 0;
            $parsedKondisi = is_string($kondisiObj['kondisi']) ? json_decode($kondisiObj['kondisi'], true) : $kondisiObj['kondisi'];

            $promoId = $promoId ?: ($kondisiObj['promo_id'] ?? null);

            if (!in_array($jenis_kondisi, ['produk', 'brand'])) {
                continue;
            }
            if (!is_array($parsedKondisi)) {
                $groupIsValid = false;
                break;
            }

            $hasValidMatch = false;

            // === PRODUK CHECK ===
            if ($jenis_kondisi === 'produk') {
                foreach ($items as $item) {
                    $produk_id = $item['produk_id'] ?? '';
                    $qty = (int)($item['qty'] ?? 0);

                    if (in_array($produk_id, $parsedKondisi) && $qty >= $qty_min) {
                        $hasValidMatch = true;
                        $total_qty_produk += $qty;

                        if ($is_kelipatan && !empty($kondisiObj['qty_akumulasi'])) {
                            $qty_kelipatan_produk = (int)$kondisiObj['qty_akumulasi'];
                        }

                        if ($qty_max > 0) {
                            $qty_max_produk = $qty_max;
                        }
                    }
                }
            }

            // === BRAND CHECK ===
            elseif ($jenis_kondisi === 'brand') {
                $brandQtyMap = [];

                foreach ($items as $item) {
                    $brand_id = $item['brand_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);

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
                        $total_qty_brand += $totalQty;

                        if ($is_kelipatan && !empty($kondisiObj['qty_akumulasi'])) {
                            $qty_kelipatan_brand = (int)$kondisiObj['qty_akumulasi'];
                        }

                        if ($qty_max > 0) {
                            $qty_max_brand = $qty_max;
                        }

                        break;
                    }
                }
            }

            // === Include / Exclude Logic ===
            if (($exclude_include === 'include' && !$hasValidMatch) ||
                ($exclude_include === 'exclude' && $hasValidMatch)
            ) {
                $groupIsValid = false;
                break;
            }
        }

        // === Final Validation & Bonus Calculation ===
        if ($groupIsValid && $promoId) {

            $qty_kelipatan = 0;
            if ($qty_kelipatan_produk > 0 && $qty_kelipatan_brand > 0) {
                $qty_kelipatan = min($qty_kelipatan_produk, $qty_kelipatan_brand);
            } elseif ($qty_kelipatan_produk > 0) {
                $qty_kelipatan = $qty_kelipatan_produk;
            } elseif ($qty_kelipatan_brand > 0) {
                $qty_kelipatan = $qty_kelipatan_brand;
            }

            // Combine max qty if needed
            $qty_max_final = max($qty_max_produk, $qty_max_brand);
            $qty_total = max($total_qty_produk, $total_qty_brand);

            $bonus_kelipatan = hitung_kelipatan_bonus($qty_total, $qty_kelipatan, $qty_max_final);

            $validPromos[] = [
                'promo_id' => $promoId,
                "kelipatan" => $qty_kelipatan,
                'bonus_kelipatan' => $bonus_kelipatan
            ];
        }
    }

    return $validPromos;
}


function hitung_kelipatan_bonus($qty_total, $qty_kelipatan, $qty_max = 0)
{
    if ($qty_kelipatan <= 0 || $qty_total <= 0) return 0;

    if ($qty_max > 0 && $qty_total > $qty_max) {
        $qty_total = $qty_max;
    }

    return floor($qty_total / $qty_kelipatan);
}





if (isset($data['tanggal_penjualan']) && isset($data['create_penjualan'])) {

    try {
        // Validation
        $requiredFields = ['tanggal_penjualan'];

        $fields = validate_1($data, $requiredFields);


        $tanggal_penjualan = $fields['tanggal_penjualan'];
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
        $penjualan_id = generateCustomID('PJ', 'tb_penjualan', 'penjualan_id', $conn);
        // $penjualan_history_id = generateCustomID('POH', 'tb_pembelian_history', 'pembelian_history_id', $conn);

        // Insert main purchase
        executeInsert(
            $conn,
            "INSERT INTO tb_penjualan (penjualan_id, tanggal_penjualan,customer_id,gudang_id, keterangan, ppn,diskon, nominal_pph, status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)",
            [
                $penjualan_id,
                $tanggal_po,
                $customer_id,
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

        // executeInsert(
        //     $conn,
        //     "INSERT INTO tb_pembelian_history (pembelian_history_id,pembelian_id_after, tanggal_po_after, supplier_id_after,gudang_id_after, keterangan_after, ppn_after,diskon_after, nominal_ppn_after, status_after, cancel_by_after,created_status)
        // VALUES (?,?, ?, ?, ?, ?, ?, ?,?,?,?,?)",
        //     [
        //         $pembelian_history_id,
        //         $penjualan_id,
        //         $tanggal_po,
        //         $supplier_id,
        //         $gudang_id,
        //         $keterangan,
        //         $ppn_unformat,
        //         $diskon_invoice_unformat,
        //         $nominal_pph_unformat,
        //         $status,
        //         $created_by,
        //         $created_status
        //     ],
        //     "ssssssdddsss"
        // );

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
                // $tipe_detail_pembelian_history = "A";

                $qty_unformat = toFloat($qty);
                $harga_unformat = toFloat($harga);
                $diskon_unformat = toFloat($diskon);

                validate_2($qty_unformat, '/^\d+$/', "Format qty detail tidak valid");
                validate_2($harga_unformat, '/^\d+$/', "Format harga detail tidak valid");
                validate_2($diskon_unformat, '/^\d+$/', "Format diskon detail tidak valid");

                $total_qty += $qty_unformat;
                $total_harga += $qty_unformat * ($harga_unformat - $diskon_unformat);

                $detail_penjualan_id = generateCustomID('DPJ', 'tb_detail_penjualan', 'detail_penjualan_id', $conn);
                // $detail_pembelian_history_id = generateCustomID('DPOH', 'tb_detail_pembelian_history', 'detail_pembelian_history_id', $conn);
                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_penjualan (detail_penjualan_id, penjualan_id, produk_id, qty, harga, diskon, satuan_id,urutan)
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

                // executeInsert(
                //     $conn,
                //     "INSERT INTO tb_detail_pembelian_history (detail_pembelian_history_id, pembelian_history_id, produk_id, qty, harga, diskon, satuan_id,urutan,tipe_detail_pembelian_history)
                // VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
                //     [
                //         $detail_pembelian_history_id,
                //         $pembelian_history_id,
                //         $produk_id,
                //         $qty_unformat,
                //         $harga_unformat,
                //         $diskon_unformat,
                //         $satuan_id,
                //         $urutan_detail,
                //         $tipe_detail_pembelian_history
                //     ],
                //     "sssdddsds"
                // );
                $urutan_detail += 1;
            }
        }


        // === Final Calculation ===
        $sub_total = $total_harga - $diskon_invoice_unformat;
        $nominal_ppn = $sub_total * $ppn_unformat;
        $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

        // === Update Purchase Summary ===
        $stmt = $conn->prepare("UPDATE tb_penjualan
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,sub_total=?
                            WHERE penjualan_id = ?");
        $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt->execute();
        $stmt->close();


        // $stmt_history = $conn->prepare("UPDATE tb_pembelian_history 
        //                     SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,biaya_tambahan_after = ?,sub_total_after =?
        //                     WHERE pembelian_history_id = ?");
        // $stmt_history->bind_param("ddddds", $total_qty, $grand_total, $nominal_ppn, $total_biaya_tambahan, $sub_total, $pembelian_history_id);
        // $stmt_history->execute();
        // $stmt_history->close();
        echo json_encode([
            "success" => true,
            "message" => "Berhasil",
            "data" => [
                "penjualan_id" => $penjualan_id
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

        //KELIPATAN


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




        $promo_conditions_array = array_values($promo_kelipatan_kondisi); // group per promo

        $valid_promo_ids = cek_promo_kondisi_customer_channel($promo_conditions_array, $channel_id, $customer_id);

        foreach ($valid_promo_ids as $promo) {

            $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
            $stmt->bind_param("s", $promo);
            $stmt->execute();
            $result = $stmt->get_result();
            $conditions = [];
            while ($row = $result->fetch_assoc()) {
                $conditions[] = $row;
            }
            $promo_kelipatan_kondisi_2[$promo] = $conditions;
            $stmt->close();
        }

        $promo_conditions_array_2 = array_values($promo_kelipatan_kondisi_2);

        $valid_promos = cek_promo_kondisi_produk_brand($promo_conditions_array_2, $array_produk_brand_qty, true);

        // Log for debugging
        error_log("Valid customer/channel promo IDs: " . json_encode($valid_promo_ids));
        error_log("Valid promos after product/brand check: " . json_encode($valid_promos));




        // AKUMULASI
        $promo_akumulasi = [];
        $promo_akumulasi_kondisi = [];
        $promo_akumulasi_2 = [];
        if (count($valid_promos) == 0) {
            $stmt_akumulasi = $conn->prepare("SELECT * FROM tb_promo 
                                WHERE status = 'aktif' 
                                AND quota > 0 AND akumulasi = 'ya'
                                AND ? BETWEEN tanggal_berlaku AND tanggal_selesai
                                ORDER BY prioritas DESC
                                ");
            $stmt_akumulasi->bind_param("s", $tanggal_penjualan);
            $stmt_akumulasi->execute();
            $result = $stmt_akumulasi->get_result();

            while ($row = $result->fetch_assoc()) {
                $promo_akumulasi[] = $row;
            }
            $stmt_akumulasi->close();


            foreach ($promo_akumulasi as $promo) {
                $promo_id = $promo['promo_id'];
                $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
                $stmt->bind_param("s", $promo_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $conditions = [];
                while ($row = $result->fetch_assoc()) {
                    $conditions[] = $row;
                }
                $promo_akumulasi_kondisi[$promo_id] = $conditions;
                $stmt->close();
            }


            $promo_conditions_array_akumulasi = array_values($promo_akumulasi_kondisi); // group per promo



            $valid_promo_id_akumulasi =  cek_promo_kondisi_customer_channel($promo_conditions_array_akumulasi, $channel_id, $customer_id);




            foreach ($valid_promo_id_akumulasi as $promo) {

                $stmt = $conn->prepare("SELECT * FROM tb_promo  WHERE promo_id = ? ORDER BY prioritas DESC");
                $stmt->bind_param("s", $promo);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $promo_akumulasi_2[] = $row;
                }
                $stmt->close();
            }


            // $promo_conditions_array_akumulasi_2 = array_values($promo_akumulasi_kondisi_2);

            // $valid_promos = cek_promo_kondisi_produk_brand($promo_conditions_array_akumulasi_2, $array_produk_brand_qty, false);

            // Log for debugging
            error_log("Valid customer/channel promo IDs: " . json_encode($valid_promo_id_akumulasi));
            // error_log("Valid promos after product/brand check: " . json_encode($valid_promos));
        }


        echo json_encode([
            "success" => true,
            "message" => "Promo cek berhasil",
            "data" => [

                // "array_produk_brand_qty" => $array_produk_brand_qty,
                // "promo" => $promo_kelipatan,
                // "promo_kondisi" => $promo_kelipatan_kondisi,
                // "valid_customer_channel_promos" => $valid_promo_ids,
                // "promo_conditions_array_2" => $promo_conditions_array_2,
                "promo_akumulasi" => $promo_akumulasi,
                "promo_akumulasi_kondisi" => $promo_akumulasi_kondisi,
                "promo_akumulasi 2" => $promo_akumulasi_2,
                "valid_kelipatan_promo" => $valid_promos
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
