<?php
require_once __DIR__ . '/../utils/helpers.php';

try {
    if (!$data) {
        throw new Exception("Invalid JSON input");
    }

    // Start transaction
    $conn->begin_transaction();



    if (isset($data['promo_id']) && isset($data['status']) && isset($data['table']) && $data['table'] === "update_status") {
        $promo_id = $data['promo_id'];
        $status = $data['status'];

        $stmt = $conn->prepare("UPDATE tb_promo SET status = ? WHERE promo_id = ?");
        $stmt->bind_param(
            "ss",
            $status,
            $promo_id
        );
        $stmt->execute();
        $stmt->close();
        $conn->commit();


        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "Update promo status."]);
    }

    if (isset($data['table']) && $data['table'] === "update_promo") {
        // Required fields
        $requiredFields = [
            'promo_id',
            'nama',
            'tanggal_berlaku',
            'tanggal_selesai',
            'jenis_bonus',
            'akumulasi',
            'jenis_diskon',
            'status',
            'quota',
            'satuan_id'
        ];
        $fields = validate_1($data, $requiredFields);


        // Extract and sanitize input
        $promo_id = $fields['promo_id'];
        $nama = $fields['nama'];
        $tanggal_berlaku = $fields['tanggal_berlaku'];
        $tanggal_selesai = $fields['tanggal_selesai'];
        $jenis_bonus = $fields['jenis_bonus'] ?? 'barang';
        $akumulasi = $fields['akumulasi'] ?? '';
        $kelipatan = $fields['kelipatan'];
        $prioritas = $fields['prioritas'] ?? '';
        $jenis_diskon = $fields['jenis_diskon'] ?? '';
        $jumlah_diskon = $fields['jumlah_diskon'] ?? '';
        $status = $fields['status'];
        $quota = $fields['quota'] ?? '';
        $promo_kondisi = $data['promo_kondisi'] ?? [];
        $promo_bonus_barang = $data['promo_bonus_barang'] ?? [];
        $satuan_id = $data['satuan_id'];

        $prioritas = toFloat($prioritas);
        $jumlah_diskon = toFloat($jumlah_diskon);
        $quota = toFloat($quota);
        // Basic input validation
        validate_2($nama, '/^[a-zA-Z0-9\s]+$/', "Format nama tidak valid");
        validate_2($prioritas, '/^\d+$/', "Format prioritas tidak valid");
        validate_2($quota, '/^\d+$/', "Format quota tidak valid");



        if ($jenis_diskon === "nominal") {
            validate_2($jumlah_diskon, '/^\d+$/', "Format jumlah diskon tidak valid");
        }
        // Update main promo
        $stmt = $conn->prepare("UPDATE tb_promo SET 
        nama = ?, 
        tanggal_berlaku = ?, 
        tanggal_selesai = ?, 
        jenis_bonus = ?, 
        akumulasi = ?, 
        prioritas = ?,
        kelipatan=?,
        jenis_diskon = ?, 
        jumlah_diskon = ?, 
        quota = ?, 
        status = ?,
        satuan_id =?
        WHERE promo_id = ?");
        $stmt->bind_param(
            "ssssssssddsss",
            $nama,
            $tanggal_berlaku,
            $tanggal_selesai,
            $jenis_bonus,
            $akumulasi,
            $prioritas,
            $kelipatan,
            $jenis_diskon,
            $jumlah_diskon,
            $quota,
            $status,
            $satuan_id,
            $promo_id
        );
        $stmt->execute();
        $stmt->close();

        // Handle promo_kondisi
        $delete_kondisi_stmt = $conn->prepare("DELETE FROM tb_promo_kondisi WHERE promo_id = ?");
        $delete_kondisi_stmt->bind_param("s", $promo_id);
        $delete_kondisi_stmt->execute();
        $delete_kondisi_stmt->close();

        foreach ($promo_kondisi as $promo) {
            $jenis_kondisi = $promo['jenis_kondisi'];
            $kondisi = json_encode($promo['kondisi']);
            $exclude_include = $promo['exclude_include'] ?? null;
            $qty_akumulasi = $promo['qty_akumulasi'] ?? null;
            $qty_min = $promo['qty_min'] ?? null;
            $qty_max = $promo['qty_max'] ?? null;
            $promo_kondisi_id = generateCustomID('PRK', 'tb_promo_kondisi', 'promo_kondisi_id', $conn);

            $qty_akumulasi = toFloat($qty_akumulasi);
            $qty_max = toFloat($qty_max);
            $qty_min = toFloat($qty_min);
            validate_2($qty_akumulasi, '/^\d+$/', "Format qty akumulasi tidak valid");
            validate_2($qty_max, '/^\d+$/', "Format qty max tidak valid");
            validate_2($qty_min, '/^\d+$/', "Format qty min tidak valid");


            $stmt_insert = $conn->prepare("INSERT INTO tb_promo_kondisi (
        promo_kondisi_id, promo_id, jenis_kondisi, kondisi, qty_akumulasi, qty_min, exclude_include, qty_max
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param(
                "ssssddsd",
                $promo_kondisi_id,
                $promo_id,
                $jenis_kondisi,
                $kondisi,
                $qty_akumulasi,
                $qty_min,
                $exclude_include,
                $qty_max
            );
            $stmt_insert->execute();
            $stmt_insert->close();
        }


        // Handle promo_bonus_barang
        // Always delete old bonus_barang rows for the promo
        $delete_bonus_stmt = $conn->prepare("DELETE FROM tb_promo_bonus_barang WHERE promo_id = ?");
        $delete_bonus_stmt->bind_param("s", $promo_id);
        $delete_bonus_stmt->execute();
        $delete_bonus_stmt->close();

        // Then insert new ones
        foreach ($promo_bonus_barang as $promo) {
            $qty_bonus = $promo['qty_bonus'] ?? null;
            $jenis_diskon = $promo['jenis_diskon'] ?? null;
            $jlh_diskon = $promo['jlh_diskon'] ?? null;
            $produk_id = $promo['produk_id'] ?? null;
            $promo_bonus_barang_id = generateCustomID('PRB', 'tb_promo_bonus_barang', 'promo_bonus_barang_id', $conn);

            $qty_bonus = toFloat($qty_bonus);
            $jlh_diskon = toFloat($jlh_diskon);
            validate_2($qty_bonus, '/^\d+$/', "Format qty bonus tidak valid");
            validate_2($jlh_diskon, '/^\d+$/', "Format jlh diskon tidak valid");



            $stmt_insert = $conn->prepare("INSERT INTO tb_promo_bonus_barang (
        promo_bonus_barang_id, promo_id, qty_bonus, jenis_diskon, jlh_diskon, produk_id
    ) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param(
                "ssssds",
                $promo_bonus_barang_id,
                $promo_id,
                $qty_bonus,
                $jenis_diskon,
                $jlh_diskon,
                $produk_id
            );
            $stmt_insert->execute();
            $stmt_insert->close();
        }

        $conn->commit(); // Commit transaction

        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "Promo dan kondisi berhasil diupdate."]);
    }
} catch (Exception $e) {
    $conn->rollback(); // Roll back on error
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
