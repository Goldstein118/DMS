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





function cek_promo_non_kelipatan($promoList, $promoKondisi, $items)
{
    $groupedPromos = [];

    // 1. Group promos by 'nama'
    foreach ($promoList as $promo) {
        $groupedPromos[$promo['nama']][] = $promo;
    }
    error_log("Grouped promos: " . print_r($groupedPromos, true));

    // 2. Loop through each promo group
    foreach ($groupedPromos as $nama => $promos) {
        error_log("Processing promo group: $nama");

        usort($promos, function ($a, $b) {
            if ($b['prioritas'] === $a['prioritas']) {
                return strtotime($b['created_on']) - strtotime($a['created_on']);
            }
            return $b['prioritas'] - $a['prioritas'];
        });
        error_log("Sorted promos in group $nama: " . print_r($promos, true));

        foreach ($promos as $promo) {
            $promoId = $promo['promo_id'];
            error_log("Checking promo: $promoId");

            error_log("Checking conditions for promo_id=$promoId: " . (isset($promoKondisi[$promoId]) ? "FOUND" : "NOT FOUND"));

            if (!isset($promoKondisi[$promoId])) {
                error_log("No conditions found for promo $promoId, skipping");
                continue;
            }

            $kondisiList = $promoKondisi[$promoId];
            usort($kondisiList, function ($a, $b) {
                return ($b['qty_min'] ?? 0) - ($a['qty_min'] ?? 0);
            });
            error_log("Conditions for promo $promoId: " . print_r($kondisiList, true));

            $groupIsValid = true;

            foreach ($kondisiList as $kondisi) {
                $jenis_kondisi = $kondisi['jenis_kondisi'];
                $exclude_include = $kondisi['exclude_include'];
                $parsedKondisi = is_string($kondisi['kondisi']) ? json_decode($kondisi['kondisi'], true) : $kondisi['kondisi'];
                $qty_min = (int)($kondisi['qty_min'] ?? 0);

                if (!is_array($parsedKondisi)) {
                    error_log("Failed to parse kondisi for promo $promoId: " . $kondisi['kondisi']);
                    $groupIsValid = false;
                    break;
                }

                error_log("Parsed kondisi for promo $promoId: " . print_r($parsedKondisi, true));
                error_log("Checking condition: jenis_kondisi=$jenis_kondisi, exclude_include=$exclude_include, qty_min=$qty_min");

                $hasValidMatch = false;

                if ($jenis_kondisi === 'brand') {
                    $brandQtyMap = [];

                    foreach ($items as $item) {
                        $brand_id = $item['brand_id'] ?? null;
                        $qty = (int)($item['qty'] ?? 0);
                        if (!$brand_id) continue;

                        // Apply exclude/include only on brand_id (kondisi)
                        $isMatch = false;
                        if ($exclude_include === 'include' && in_array($brand_id, $parsedKondisi)) {
                            $isMatch = true;
                        } elseif ($exclude_include === 'exclude' && !in_array($brand_id, $parsedKondisi)) {
                            $isMatch = true;
                        }

                        if ($isMatch) {
                            if (!isset($brandQtyMap[$brand_id])) {
                                $brandQtyMap[$brand_id] = 0;
                            }
                            $brandQtyMap[$brand_id] += $qty;
                        }
                    }

                    error_log("Brand quantities after applying exclude/include for promo $promoId: " . print_r($brandQtyMap, true));

                    // Check quantity condition only after filtering by kondisi
                    foreach ($brandQtyMap as $brand => $totalQty) {
                        error_log("Checking brand '$brand' total qty: $totalQty against qty_min: $qty_min");
                        if ($totalQty >= $qty_min) {
                            $hasValidMatch = true;
                            break;
                        }
                    }
                } elseif ($jenis_kondisi === 'produk') {
                    $totalQtyProduk = 0;

                    foreach ($items as $item) {
                        $produk_id = $item['produk_id'] ?? '';
                        $qty = (int)($item['qty'] ?? 0);
                        if (!$produk_id) continue;

                        // Apply exclude/include only on produk_id (kondisi)
                        $isMatch = false;
                        if ($exclude_include === 'include' && in_array($produk_id, $parsedKondisi)) {
                            $isMatch = true;
                        } elseif ($exclude_include === 'exclude' && !in_array($produk_id, $parsedKondisi)) {
                            $isMatch = true;
                        }

                        if ($isMatch) {
                            $totalQtyProduk += $qty;
                        }
                    }

                    error_log("Total produk quantity after applying exclude/include for promo $promoId: $totalQtyProduk");
                    error_log("Checking produk total qty against qty_min: $qty_min");

                    // Check quantity condition only after filtering by kondisi
                    if ($totalQtyProduk >= $qty_min) {
                        $hasValidMatch = true;
                    }
                }

                error_log("Has valid match? " . ($hasValidMatch ? 'YES' : 'NO'));

                if (
                    ($exclude_include === 'include' && !$hasValidMatch) ||
                    ($exclude_include === 'exclude' && !$hasValidMatch)
                ) {
                    error_log("Condition failed for promo $promoId, exclude/include logic failed");
                    $groupIsValid = false;
                    break;
                }
            }


            if ($groupIsValid) {
                error_log("Promo $promoId is valid!");
                return [
                    'promo_id' => $promoId,
                ];
            } else {
                error_log("Promo $promoId is NOT valid");
            }
        }
    }

    error_log("No valid promos found");
    return null;
}


function cek_promo(mysqli $conn, string $customer_id, string $tanggal_penjualan, array $details): array
{
    // Get channel_id from customer
    $valid_promos = [];
    $stmt = $conn->prepare("SELECT channel_id FROM tb_customer WHERE customer_id = ?");
    $stmt->bind_param("s", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $stmt->close();

    $channel_id = $customer['channel_id'] ?? '';
    if (!$channel_id) {
        throw new Exception("Channel ID tidak ditemukan.");
    }



    $array_produk_brand_qty = [];
    foreach ($details as $detail) {
        $produk_id = $detail['produk_id'];
        $qty = $detail['qty'];

        $stmt = $conn->prepare("SELECT brand_id FROM tb_produk WHERE produk_id = ?");
        $stmt->bind_param("s", $produk_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();

        $brand_id = $brand['brand_id'] ?? '';

        $array_produk_brand_qty[] = [
            "produk_id" => $produk_id,
            "brand_id" => $brand_id,
            "qty" => $qty
        ];
    }

    // Get all active kelipatan promos
    $stmt = $conn->prepare("SELECT * FROM tb_promo 
                            WHERE status = 'aktif' 
                            AND quota > 0 AND jenis_promo = 'kelipatan'
                            AND ? BETWEEN tanggal_berlaku AND tanggal_selesai");
    $stmt->bind_param("s", $tanggal_penjualan);
    $stmt->execute();
    $result = $stmt->get_result();

    $promo_kelipatan = [];
    while ($row = $result->fetch_assoc()) {
        $promo_kelipatan[] = $row;
    }
    $stmt->close();

    // Get conditions per promo
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
        $stmt->close();
        $promo_kelipatan_kondisi[$promo_id] = $conditions;
    }

    // Check promos that match customer/channel
    $promo_conditions_array = array_values($promo_kelipatan_kondisi);
    $valid_promo_ids = cek_promo_kondisi_customer_channel($promo_conditions_array, $channel_id, $customer_id);

    // Re-fetch conditions for valid promos
    $promo_kelipatan_kondisi_2 = [];
    foreach ($valid_promo_ids as $promo_id) {
        $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
        $stmt->bind_param("s", $promo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conditions = [];
        while ($row = $result->fetch_assoc()) {
            $conditions[] = $row;
        }
        $stmt->close();
        $promo_kelipatan_kondisi_2[$promo_id] = $conditions;
    }

    $promo_conditions_array_2 = array_values($promo_kelipatan_kondisi_2);
    $valid_promos = cek_promo_kondisi_produk_brand($promo_conditions_array_2, $array_produk_brand_qty, true);

    // === Fallback to akumulasi if no valid kelipatan promo ===
    if (count($valid_promos) === 0) {
        $array_produk_brand_qty_2 = [];


        $stmt_penjualan = $conn->prepare("SELECT penjualan_id FROM tb_penjualan WHERE (promo_id IS NULL OR promo_id = '') 
        AND tanggal_penjualan BETWEEN (
        SELECT tanggal_penjualan 
        FROM tb_penjualan 
        WHERE tanggal_input_promo_berlaku IS NOT NULL 
        ORDER BY tanggal_input_promo_berlaku DESC 
        LIMIT 1
        ) AND ?
        ORDER BY tanggal_penjualan DESC 
        LIMIT 1");

        $stmt_penjualan->bind_param("s", $tanggal_penjualan);
        $stmt_penjualan->execute();
        $result_penjualan = $stmt_penjualan->get_result();

        if ($row = $result_penjualan->fetch_assoc()) {
            $penjualan_id = $row['penjualan_id'];


            $stmt_produk_qty = $conn->prepare("SELECT produk_id, qty FROM tb_detail_penjualan WHERE penjualan_id = ?");
            $stmt_produk_qty->bind_param("s", $penjualan_id);
            $stmt_produk_qty->execute();
            $result_produk_qty = $stmt_produk_qty->get_result();

            while ($item = $result_produk_qty->fetch_assoc()) {
                $produk_id = $item['produk_id'];
                $qty = $item['qty'];


                $stmt = $conn->prepare("SELECT brand_id FROM tb_produk WHERE produk_id = ?");
                $stmt->bind_param("s", $produk_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $brand = $result->fetch_assoc();
                $stmt->close();

                $brand_id = $brand['brand_id'] ?? '';

                $array_produk_brand_qty_2[] = [
                    "produk_id" => $produk_id,
                    "brand_id" => $brand_id,
                    "qty" => $qty
                ];
            }
        }


        $combined = [];
        function makeKey($produk_id, $brand_id)
        {
            return $produk_id . '_' . $brand_id;
        }

        foreach ($array_produk_brand_qty as $item) {
            $key = makeKey($item['produk_id'], $item['brand_id']);
            $combined[$key] = $item;
        }

        if (isset($array_produk_brand_qty_2)) {
            foreach ($array_produk_brand_qty_2 as $item) {
                $key = makeKey($item['produk_id'], $item['brand_id']);
                if (isset($combined[$key])) {
                    $combined[$key]['qty'] += $item['qty'];
                } else {
                    $combined[$key] = $item;
                }
            }
        }

        $array_produk_brand_qty_combine = array_values($combined);



        $stmt = $conn->prepare("SELECT * FROM tb_promo 
                                WHERE status = 'aktif' 
                                AND quota > 0 AND jenis_promo = 'akumulasi'
                                AND ? BETWEEN tanggal_berlaku AND tanggal_selesai
                                ORDER BY prioritas DESC");
        $stmt->bind_param("s", $tanggal_penjualan);
        $stmt->execute();
        $result = $stmt->get_result();

        $promo_akumulasi = [];
        while ($row = $result->fetch_assoc()) {
            $promo_akumulasi[] = $row;
        }
        $stmt->close();

        // Get conditions for akumulasi promos
        $promo_akumulasi_kondisi = [];
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
            $stmt->close();
            $promo_akumulasi_kondisi[$promo_id] = $conditions;
        }

        $promo_conditions_array_akumulasi = array_values($promo_akumulasi_kondisi);
        $valid_promo_id_akumulasi = cek_promo_kondisi_customer_channel($promo_conditions_array_akumulasi, $channel_id, $customer_id) ?? [];

        // Fetch valid akumulasi promos
        $promo_akumulasi_2 = [];
        foreach ($valid_promo_id_akumulasi as $promo_id) {
            $stmt = $conn->prepare("SELECT * FROM tb_promo WHERE promo_id = ? ORDER BY prioritas DESC");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $promo_akumulasi_2[] = $row;
            }
            $stmt->close();
        }

        // Get conditions for valid akumulasi promos
        $promo_akumulasi_kondisi_2 = [];
        foreach ($promo_akumulasi_2 as $promo) {
            $promo_id = $promo['promo_id'];
            $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $conditions = [];
            while ($row = $result->fetch_assoc()) {
                $conditions[] = $row;
            }
            $stmt->close();
            $promo_akumulasi_kondisi_2[$promo_id] = $conditions;
        }

        // Final check for valid akumulasi promos
        $valid_promos = cek_promo_non_kelipatan($promo_akumulasi_2, $promo_akumulasi_kondisi_2, $array_produk_brand_qty_combine) ?? [];
    }




    if (isset($valid_promos['promo_id'])) {

        $valid_promos = [$valid_promos];
    }




    if (count($valid_promos) === 0) {



        $stmt = $conn->prepare("SELECT * FROM tb_promo 
                                WHERE status = 'aktif' 
                                AND quota > 0 AND jenis_promo = 'prioritas'
                                AND ? BETWEEN tanggal_berlaku AND tanggal_selesai
                                ORDER BY prioritas DESC");
        $stmt->bind_param("s", $tanggal_penjualan);
        $stmt->execute();
        $result = $stmt->get_result();

        $promo_prioritas = [];
        while ($row = $result->fetch_assoc()) {
            $promo_prioritas[] = $row;
        }
        $stmt->close();

        // Get conditions for akumulasi promos
        $promo_prioritas_kondisi = [];
        foreach ($promo_prioritas as $promo) {
            $promo_id = $promo['promo_id'];
            $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $conditions = [];
            while ($row = $result->fetch_assoc()) {
                $conditions[] = $row;
            }
            $stmt->close();
            $promo_prioritas_kondisi[$promo_id] = $conditions;
        }

        $promo_conditions_array_prioritas = array_values($promo_prioritas_kondisi);
        $valid_promo_id_prioritas = cek_promo_kondisi_customer_channel($promo_conditions_array_prioritas, $channel_id, $customer_id) ?? [];

        // Fetch valid akumulasi promos
        $promo_prioritas_2 = [];
        foreach ($valid_promo_id_prioritas as $promo_id) {
            $stmt = $conn->prepare("SELECT * FROM tb_promo WHERE promo_id = ? ORDER BY prioritas DESC");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $promo_prioritas_2[] = $row;
            }
            $stmt->close();
        }

        // Get conditions for valid akumulasi promos
        $promo_prioritas_kondisi_2 = [];
        foreach ($promo_prioritas_2 as $promo) {
            $promo_id = $promo['promo_id'];
            $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
            $stmt->bind_param("s", $promo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $conditions = [];
            while ($row = $result->fetch_assoc()) {
                $conditions[] = $row;
            }
            $stmt->close();
            $promo_prioritas_kondisi_2[$promo_id] = $conditions;
        }

        // Final check for valid akumulasi promos
        $valid_promos = cek_promo_non_kelipatan($promo_prioritas_2, $promo_prioritas_kondisi_2, $array_produk_brand_qty) ?? [];
    }


    return $valid_promos;
}



if (isset($data['tanggal_penjualan']) && isset($data['create_penjualan'])) {

    try {
        // Validation
        $requiredFields = ['tanggal_penjualan', 'gudang_id', 'customer_id'];

        $fields = validate_1($data, $requiredFields);


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
        $penjualan_id = generateCustomID('PJ', 'tb_penjualan', 'penjualan_id', $conn);
        $no_pengiriman = generate_no_pengiriman('PJK', 'tb_penjualan', 'no_pengiriman', $conn);
        $penjualan_history_id = generateCustomID('PJH', 'tb_penjualan_history', 'penjualan_history_id', $conn);

        $promo_id = cek_promo($conn, $customer_id, $tanggal_penjualan, $data['details']);
        error_log("cek_promo result: " . print_r($promo_id, true));

        $promo_ids = array_column($promo_id, 'promo_id');
        error_log("Extracted promo_ids: " . print_r($promo_ids, true));

        $valid_promos_id = json_encode($promo_ids);
        error_log("JSON encoded promo_ids: " . $valid_promos_id);

        $bonus_kelipatan = isset($promo_id[0]['bonus_kelipatan']) ? $promo_id[0]['bonus_kelipatan'] : 1;
        error_log("bonus_kelipatan value: " . print_r($bonus_kelipatan, true));

        $tanggal_input_promo_berlaku = date('Y-m-d');

        if (!empty($promo_id)) {
            executeInsert(
                $conn,
                "INSERT INTO tb_penjualan (penjualan_id, tanggal_penjualan,customer_id,gudang_id,promo_id, keterangan_penjualan, ppn,diskon, nominal_pph, status, created_by,bonus_kelipatan,tanggal_input_promo_berlaku,keterangan_invoice,keterangan_gudang,keterangan_pengiriman,no_pengiriman)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?)",
                [
                    $penjualan_id,
                    $tanggal_penjualan,
                    $customer_id,
                    $gudang_id,
                    $valid_promos_id,
                    $keterangan,
                    $ppn_unformat,
                    $diskon_penjualan,
                    $nominal_pph_unformat,
                    $status,
                    $created_by,
                    $bonus_kelipatan,
                    $tanggal_input_promo_berlaku,
                    $keterangan_invoice,
                    $keterangan_gudang,
                    $keterangan_pengiriman,
                    $no_pengiriman
                ],
                "ssssssdddssdsssss"
            );

            executeInsert(
                $conn,
                "INSERT INTO tb_penjualan_history (penjualan_history_id,penjualan_id, tanggal_penjualan_after,customer_id_after,gudang_id_after,promo_id_after, keterangan_penjualan_after, ppn_after,diskon_after, nominal_pph_after, status_after, created_by_after,bonus_kelipatan_after,tanggal_input_promo_berlaku_after,keterangan_invoice_after,keterangan_gudang_after,keterangan_pengiriman_after,no_pengiriman_after,created_status)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    $penjualan_history_id,
                    $penjualan_id,
                    $tanggal_penjualan,
                    $customer_id,
                    $gudang_id,
                    $valid_promos_id,
                    $keterangan,
                    $ppn_unformat,
                    $diskon_penjualan,
                    $nominal_pph_unformat,
                    $status,
                    $created_by,
                    $bonus_kelipatan,
                    $tanggal_input_promo_berlaku,
                    $keterangan_invoice,
                    $keterangan_gudang,
                    $keterangan_pengiriman,
                    $no_pengiriman,
                    "new"
                ],
                "sssssssdddssdssssss"
            );


            foreach ($promo_ids as $promo_id) {

                $stmt_select = $conn->prepare("SELECT quota FROM tb_promo WHERE promo_id = ?");
                $stmt_select->bind_param("s", $promo_id);
                $stmt_select->execute();
                $result = $stmt_select->get_result();
                $row = $result->fetch_assoc();
                $stmt_select->close();

                if ($row && isset($row['quota'])) {
                    $current_quota = (int)$row['quota'];
                    $new_quota = max(0, $current_quota - 1);
                    $stmt_update = $conn->prepare("UPDATE tb_promo SET quota = ? WHERE promo_id = ?");
                    $stmt_update->bind_param("is", $new_quota, $promo_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                }
            }
        } else {
            executeInsert(
                $conn,
                "INSERT INTO tb_penjualan (penjualan_id, tanggal_penjualan,customer_id,gudang_id, keterangan_penjualan, ppn,diskon, nominal_pph, status, created_by,tanggal_input_promo_berlaku,keterangan_invoice,keterangan_gudang,keterangan_pengiriman,no_pengiriman)
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?)",
                [
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
                    $tanggal_input_promo_berlaku,
                    $keterangan_invoice,
                    $keterangan_gudang,
                    $keterangan_pengiriman,
                    $no_pengiriman
                ],
                "ssssdddssdsssss"
            );


            executeInsert(
                $conn,
                "INSERT INTO tb_penjualan_history (penjualan_history_id,penjualan_id, tanggal_penjualan_after,customer_id_after,gudang_id_after, keterangan_penjualan_after, ppn_after,diskon_after, nominal_pph_after, status_after, created_by_after,tanggal_input_promo_berlaku_after,keterangan_invoice_after,keterangan_gudang_after,keterangan_pengiriman_after,no_pengiriman_after,created_status_after)
        VALUES (?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    $penjualan_history_id,
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

                    $tanggal_input_promo_berlaku,
                    $keterangan_invoice,
                    $keterangan_gudang,
                    $keterangan_pengiriman,
                    $no_pengiriman,
                    "new"
                ],
                "ssssssdddssssssss"
            );
        }




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
                $detail_penjualan_history_id = generateCustomID('DPJH', 'tb_detail_penjualan_history', 'detail_penjualan_history_id', $conn);
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


                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_penjualan_history (detail_penjualan_history_id, penjualan_history_id, produk_id, qty, harga, diskon, satuan_id,urutan,tipe_detail_penjualan_history)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?)",
                    [
                        $detail_penjualan_history_id,
                        $penjualan_history_id,
                        $produk_id,
                        $qty_unformat,
                        $harga_unformat,
                        $diskon_unformat,
                        $satuan_id,
                        $urutan_detail,
                        'A'
                    ],
                    "sssdddsds"
                );
                $urutan_detail += 1;
            }
        }


        // === Final Calculation ===
        $sub_total = $total_harga - $diskon_penjualan;
        $nominal_ppn = $sub_total * $ppn_unformat;
        $grand_total = $sub_total + $nominal_ppn - $nominal_pph_unformat;

        // === Update Purchase Summary ===
        $stmt = $conn->prepare("UPDATE tb_penjualan
                            SET total_qty = ?, grand_total = ?, nominal_ppn = ?,sub_total=?
                            WHERE penjualan_id = ?");
        $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE tb_penjualan_history
                            SET total_qty_after = ?, grand_total_after = ?, nominal_ppn_after = ?,sub_total_after=?
                            WHERE penjualan_id = ?");
        $stmt->bind_param("dddds", $total_qty, $grand_total, $nominal_ppn, $sub_total, $penjualan_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            "success" => true,
            "message" => "Berhasil",
            "data" => [
                "penjualan_id" => $penjualan_id,
                "valid_kelipatan_promo" => $promo_id
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


        try {
            $valid_promos = cek_promo($conn, $customer_id, $tanggal_penjualan, $data['details']);

            echo json_encode([
                "success" => true,
                "message" => "Promo cek berhasil",
                "data" => [
                    "valid_kelipatan_promo" => [$valid_promos]
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }


        //KELIPATAN


        // $stmt_channel_id = $conn->prepare("SELECT channel_id FROM tb_customer WHERE customer_id = ?");
        // $stmt_channel_id->bind_param("s", $customer_id);
        // $stmt_channel_id->execute();
        // $result = $stmt_channel_id->get_result();
        // $customer = $result->fetch_assoc();
        // $channel_id = $customer['channel_id'] ?? '';
        // $stmt_channel_id->close();

        // if (!$channel_id) {
        //     throw new Exception("Channel ID tidak ditemukan.");
        // }

        // $array_produk_brand_qty = [];
        // if (isset($data['details'])) {
        //     foreach ($data['details'] as $detail) {
        //         $produk_id = $detail['produk_id'];
        //         $qty = $detail['qty'];



        //         $stmt_brand_id = $conn->prepare("SELECT brand_id FROM tb_produk WHERE produk_id = ?");
        //         $stmt_brand_id->bind_param("s", $produk_id);
        //         $stmt_brand_id->execute();
        //         $result = $stmt_brand_id->get_result();
        //         $brand = $result->fetch_assoc();
        //         $brand_id = $brand['brand_id'] ?? '';
        //         $stmt_brand_id->close();


        //         $array_produk_brand_qty[] = [
        //             "produk_id" => $produk_id,
        //             "brand_id" => $brand_id,
        //             "qty" => $qty
        //         ];
        //     }
        // }


        // $stmt_kelipatan = $conn->prepare("SELECT * FROM tb_promo 
        //                         WHERE status = 'aktif' 
        //                         AND quota > 0 AND kelipatan = 'ya'
        //                         AND ? BETWEEN tanggal_berlaku AND tanggal_selesai");
        // $stmt_kelipatan->bind_param("s", $tanggal_penjualan);
        // $stmt_kelipatan->execute();
        // $result = $stmt_kelipatan->get_result();
        // $promo_kelipatan = [];
        // while ($row = $result->fetch_assoc()) {
        //     $promo_kelipatan[] = $row;
        // }
        // $stmt_kelipatan->close();

        // $promo_kelipatan_kondisi = [];

        // foreach ($promo_kelipatan as $promo) {
        //     $promo_id = $promo['promo_id'];
        //     $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
        //     $stmt->bind_param("s", $promo_id);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     $conditions = [];
        //     while ($row = $result->fetch_assoc()) {
        //         $conditions[] = $row;
        //     }
        //     $promo_kelipatan_kondisi[$promo_id] = $conditions;
        //     $stmt->close();
        // }




        // $promo_conditions_array = array_values($promo_kelipatan_kondisi); // group per promo

        // $valid_promo_ids = cek_promo_kondisi_customer_channel($promo_conditions_array, $channel_id, $customer_id);

        // foreach ($valid_promo_ids as $promo) {

        //     $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
        //     $stmt->bind_param("s", $promo);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     $conditions = [];
        //     while ($row = $result->fetch_assoc()) {
        //         $conditions[] = $row;
        //     }
        //     $promo_kelipatan_kondisi_2[$promo] = $conditions;
        //     $stmt->close();
        // }

        // $promo_conditions_array_2 = array_values($promo_kelipatan_kondisi_2);

        // $valid_promos = cek_promo_kondisi_produk_brand($promo_conditions_array_2, $array_produk_brand_qty, true);

        // // Log for debugging
        // error_log("Valid customer/channel promo IDs: " . json_encode($valid_promo_ids));
        // error_log("Valid promos after product/brand check: " . json_encode($valid_promos));




        // // AKUMULASI
        // $promo_akumulasi = [];
        // $promo_akumulasi_kondisi = [];
        // $promo_akumulasi_2 = [];
        // if (count($valid_promos) == 0) {
        //     $stmt_akumulasi = $conn->prepare("SELECT * FROM tb_promo 
        //                         WHERE status = 'aktif' 
        //                         AND quota > 0 AND akumulasi = 'ya'
        //                         AND ? BETWEEN tanggal_berlaku AND tanggal_selesai
        //                         ORDER BY prioritas DESC
        //                         ");
        //     $stmt_akumulasi->bind_param("s", $tanggal_penjualan);
        //     $stmt_akumulasi->execute();
        //     $result = $stmt_akumulasi->get_result();

        //     while ($row = $result->fetch_assoc()) {
        //         $promo_akumulasi[] = $row;
        //     }
        //     $stmt_akumulasi->close();


        //     foreach ($promo_akumulasi as $promo) {
        //         $promo_id = $promo['promo_id'];
        //         $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
        //         $stmt->bind_param("s", $promo_id);
        //         $stmt->execute();
        //         $result = $stmt->get_result();
        //         $conditions = [];
        //         while ($row = $result->fetch_assoc()) {
        //             $conditions[] = $row;
        //         }
        //         $promo_akumulasi_kondisi[$promo_id] = $conditions;
        //         $stmt->close();
        //     }


        //     $promo_conditions_array_akumulasi = array_values($promo_akumulasi_kondisi); // group per promo



        //     $valid_promo_id_akumulasi =  cek_promo_kondisi_customer_channel($promo_conditions_array_akumulasi, $channel_id, $customer_id);




        //     foreach ($valid_promo_id_akumulasi as $promo) {

        //         $stmt = $conn->prepare("SELECT * FROM tb_promo  WHERE promo_id = ? ORDER BY prioritas DESC");
        //         $stmt->bind_param("s", $promo);
        //         $stmt->execute();
        //         $result = $stmt->get_result();

        //         while ($row = $result->fetch_assoc()) {
        //             $promo_akumulasi_2[] = $row;
        //         }
        //         $stmt->close();
        //     }


        //     foreach ($promo_akumulasi_2 as $promo) {
        //         $promo_id = $promo['promo_id'];
        //         $stmt = $conn->prepare("SELECT * FROM tb_promo_kondisi WHERE promo_id = ?");
        //         $stmt->bind_param("s", $promo_id);
        //         $stmt->execute();
        //         $result = $stmt->get_result();
        //         $conditions = [];
        //         while ($row = $result->fetch_assoc()) {
        //             $conditions[] = $row;
        //         }
        //         $promo_akumulasi_kondisi_2[$promo_id] = $conditions;
        //         $stmt->close();
        //     }




        //     $valid_promos = cek_promo_non_kelipatan($promo_akumulasi_2, $promo_akumulasi_kondisi_2, $array_produk_brand_qty);

        //     // Log for debugging
        //     error_log("Valid customer/channel promo IDs: " . json_encode($valid_promo_id_akumulasi));
        //     // error_log("Valid promos after product/brand check: " . json_encode($valid_promos));
        // }


        // echo json_encode([
        //     "success" => true,
        //     "message" => "Promo cek berhasil",
        //     "data" => [

        //         "array_produk_brand_qty" => [$array_produk_brand_qty],
        //         // "promo" => $promo_kelipatan,
        //         // "promo_kondisi" => $promo_kelipatan_kondisi,
        //         // "valid_customer_channel_promos" => $valid_promo_ids,
        //         // "promo_conditions_array_2" => [$promo_conditions_array_2],
        //         // "promo_akumulasi" => $promo_akumulasi,
        //         // "promo_akumulasi_kondisi" => [$promo_akumulasi_kondisi],
        //         "promo_akumulasi 2" => [$promo_akumulasi_2],
        //         "promo_akumulasi_kondisi_2" => [$promo_akumulasi_kondisi_2],
        //         "valid_kelipatan_promo" => [$valid_promos]
        //     ]
        // ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
}
