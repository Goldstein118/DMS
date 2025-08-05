<?php
require_once __DIR__ . '/../utils/helpers.php';

try {

    if (isset($data['tanggal_po'])) {
        $requiredFields = ['pembelian_id', 'tanggal_po', 'supplier_id'];
        $fields = validate_1($data, $requiredFields);
        $pembelian_id = $fields['pembelian_id'];
        $tanggal_po = $fields['tanggal_po'];
        $supplier_id = $fields['supplier_id'];
        $keterangan = $fields['keterangan'];
        $total_qty = $fields['total_qty'];
        $ppn = $fields['ppn'];
        $nominal_ppn = $fields['nominal_ppn'];
        $diskon = $fields['diskon'];
        $nominal_pph = $fields['nominal_pph'];
        $biaya_tambahan = $fields['biaya_tambahan'];
        $grand_total = $fields['grand_total'];
        $status = $fields['status'];

        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_po =?,supplier_id=?,keterangan=?,total_qty=?,ppn=?,
    nominal_ppn=?,diskon=?,nominal_pph=?,biaya_tambahan=?,grand_total=?,status=?
        WHERE promo_id = ?");
        $stmt->bind_param(
            "sssssssssssss",
            $pembelian_id,
            $tanggal_po,
            $supplier_id,
            $keterangan,
            $total_qty,
            $ppn,
            $nominal_ppn,
            $diskon,
            $nominal_pph,
            $biaya_tambahan,
            $grand_total,
            $status,
            $promo_id
        );
        $stmt->execute();
        $stmt->close();



        $delete_stmt = $conn->prepare("DELETE FROM tb_detail_pembelian WHERE promo_id = ?");
        $delete_stmt->bind_param("s", $promo_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        if (isset($fields['details'])) {
            $detial = $fields['details'];

            foreach ($detail as $item) {
                $produk_id = $detail['produk_id'];
                $qty = $detail['qty'];
                $harga = $detail['harga'];
                $satuan_id = $detail['satuan_id'];
                $diskon = $detail['diskon'];

                $detail_pembelian_id = generateCustomID('DPE', 'tb_detail_pembelian', 'detail_pembelian_id', $conn);

                executeInsert(
                    $conn,
                    "INSERT INTO tb_detail_pembelian (detail_pembelian_id ,pembelian_id, produk_id,qty,harga,diskon,satuan_id) 
                
                VALUES (?,?,?,?,?,?,?)",

                    [$detail_pembelian_id, $pembelian_id, $produk_id, $qty, $harga, $diskon, $satuan_id],
                    "sssssss"
                );
            }
        }

        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "Pembelian berhasil diupdate"]);
    }

    if (isset($data['tanggal_pengiriman'])) {

        $pembelian_id = $data['pembelian_id'];
        $tanggal_pengiriman = $data['tanggal_pengiriman'];
        $no_pengiriman = $data['no_pengiriman'];

        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_pengiriman = ?,  no_pengiriman = ? WHERE pembelian_id = ?");
        $stmt->bind_param("sss", $tanggal_pengiriman, $no_pengiriman, $pembelian_id);
        $stmt->execute();
        $stmt->close();
        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "tanggal_pengiriman berhasil diupdate"]);
    }

    if (isset($data['tanggal_terima'])) {

        $pembelian_id = $data['pembelian_id'];
        $tanggal_terima = $data['tanggal_terima'];

        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_terima = ? WHERE pembelian_id = ?");
        $stmt->bind_param("ss", $tanggal_pengiriman, $pembelian_id);
        $stmt->execute();
        $stmt->close();
        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "tanggal_terima berhasil diupdate"]);
    }

    if (isset($data['tanggal_invoice'])) {

        $pembelian_id = $data['pembelian_id'];
        $tanggal_invoice = $data['tanggal_invoice'];
        $no_invoice = $data['no_invoice'];
        $tanggal_input_invoice = date("Y-m-d");

        $stmt = $conn->prepare("UPDATE tb_pembelian SET tanggal_invoice = ? ,no_invoice_supplier=? ,tanggal_input_invoice=? WHERE pembelian_id = ?");
        $stmt->bind_param("ssss", $tanggal_invoice, $no_invoice, $tanggal_input_invoice, $pembelian_id);
        $stmt->execute();
        $stmt->close();
        http_response_code(200);
        echo json_encode(["ok" => true, "message" => "tanggal_invoice berhasil diupdate"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}

$conn->close();
