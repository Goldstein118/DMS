    <?php
    require_once __DIR__ . '/../utils/helpers.php';

    try {

        $requiredFields = ['tanggal_invoice', 'pembelian_id'];

        $fields = validate_1($data, $requiredFields);
        $pembelian_id = $data['pembelian_id'];
        $tanggal_invoice = $data['tanggal_invoice'];
        $no_invoice = $data['no_invoice'];
        $status = $data['status'];
        $invoice_id = generateCustomID('IN', 'tb_invoice', 'invoice_id', $conn);
        $tanggal_input_invoice = date("Y-m-d");

        executeInsert($conn, "INSERT INTO tb_invoice(invoice_id,tanggal_invoice,no_invoice_supplier,tanggal_input_invoice,pembelian_id) VALUES (?,?,?,?,?)", [$invoice_id, $tanggal_invoice, $no_invoice, $tanggal_input_invoice, $pembelian_id], "sssss");

        $stmt = $conn->prepare("UPDATE tb_pembelian SET status =? WHERE pembelian_id = ?");
        $stmt->bind_param("ss", $status, $pembelian_id);
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
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
