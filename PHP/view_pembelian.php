<?php

require_once 'env_loader.php';
include('db.php');
include("{$_ENV['BASE_PATH']}/PHP/config/vendor_paths.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Purchase Order</title>

    <!-- jQuery -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/dataTables.min.css" rel="stylesheet" />
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/mermaid.min.css" rel="stylesheet" />
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/bootstrap-icons.css">
    <!-- Select2 CSS -->
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/select2.min.css" rel="stylesheet" />

    <!-- Toastr CSS -->
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/toastr.min.css" rel="stylesheet">
    <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/datepicker.css" rel="stylesheet">

    <!-- Select2 JS -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/select2.min.js"></script>

    <!-- DataTables JS -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/dataTables.min.js"></script>

    <!-- Popper.js -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/sweetalert2@11.js"></script>

    <!-- Toastr JS -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/toastr.min.js"></script>
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/datepicker.js"></script>

    <!--Grid.js -->
    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/gridjs.umd.js"></script>

    <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/sjcl.min.js"></script>

    <style>
        body {
            background: #fff;
            color: #001028;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 0;
        }

        #td_sub_total,
        #td_diskon,
        #td_ppn,
        #td_pph,
        #td_grand_total,
        #total_biaya_tambahan {
            text-align: right;
        }

        #logo_joyday {
            width: 15%;
        }

        table th {
            background-color: #b2cef8 !important;
            color: #333;
            font-weight: bold;
        }

        @media print {
            .page-break {
                page-break-before: always;

            }

            .no_print {
                display: none;
            }
        }

        /* #td_keterangan {
            background-color: #f0f0f0;
            text-align: left;
            font-weight: bold;
            padding: 10px;
        } */
    </style>
</head>

<body class="container py-4">
    <header class="mb-4">


        <img src="../images/logo_joyday.png" alt="Logo" id="logo_joyday" />


        <div class="text-center">
            <h1 style="display: block">Purchase Order</h1>
            <span class="text-muted" id="view_pembelian_id" style="display:inline-block"></span>
            <!-- <span class="text-muted">/</span>
            <span class="text-muted" id="nama_pembelian" style="display:inline-block"></span> -->
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm no_print" id="print">
            <i class="bi bi-printer"></i> Print
        </button>

        <button type="button" class="btn btn-outline-danger btn-sm no_print" onclick="window.close()">
            <i class="bi bi-x-lg"></i> Tutup
        </button>

        <div style="display: grid; 
                    justify-items: start;
                    justify-content: end;">
            <span class="text-muted" style="float : inline-end ; " id="view_tanggal_po"></span>
            <span class="text-muted " style="float : inline-end ;" id="view_tanggal_terima"></span>
            <span class="text-muted " style="float : inline-end ;" id="view_tanggal_pengiriman"></span>
            <span class="text-muted " style="float : inline-end ;" id="view_tanggal_invoice"></span>
            <span class="text-muted " style="float : inline-end ;" id="status_pembelian"></span>
        </div>
        <div class="row">
            <div class="col">
                <span class="text-muted " style="float : inline-start ;" id="nama_supplier"></span>

            </div>
        </div>

    </header>

    <main>

        <table
            class="table table-hover table-bordered table-sm table"
            id="detail_pembelian">
            <thead id="view_detail_pembelian_thead">
                <tr>
                    <th colspan="6" style="text-align: center;">Purchase Order</th>
                </tr>
                <tr>
                    <th scope="col" style="max-width: 9px; text-align:center">No</th>
                    <th scope="col">Produk</th>
                    <th scope="col">Kuantitas</th>
                    <th scope="col">Satuan</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Diskon</th>
                </tr>
            </thead>
            <tbody id="view_detail_pembelian_tbody"></tbody>
            <tfoot id="view_detail_pembelian_tfoot">
                <tr>
                    <td colspan="4" rowspan="5" id="td_keterangan">
                    </td>
                    <td colspan="1">Sub total</td>
                    <td colspan="1" id="td_sub_total"></td>
                </tr>
                <tr>

                    <td colspan="1">Diskon</td>
                    <td colspan="1" id="td_diskon"></td>
                </tr>
                <tr>

                    <td colspan="1">PPN</td>
                    <td colspan="1" id="td_ppn"></td>
                </tr>

                <tr>

                    <td colspan="1">PPH</td>
                    <td colspan="1" id="td_pph"></td>
                </tr>
                <tr>

                    <td colspan="1">Grand total</td>
                    <td colspan="1" id="td_grand_total"></td>
                </tr>
            </tfoot>
        </table>
        <div class="page-break"></div>



        <div class="mb-4" id="div_biaya_tambahan_header" style="display: none;">


            <img src="../images/logo_joyday.png" alt="Logo" id="logo_joyday" />


            <div class="text-center">
                <h1 style="display: block">Purchase Order</h1>
                <span class="text-muted" id="biaya_view_pembelian_id" style="display:inline-block"></span>
                <!-- <span class="text-muted">/</span>
            <span class="text-muted" id="nama_pembelian" style="display:inline-block"></span> -->
            </div>



            <div style="display: grid; 
                    justify-items: start;
                    justify-content: end;">
                <span class="text-muted" style="float : inline-end ; " id="biaya_tanggal_po"></span>
                <span class="text-muted " style="float : inline-end ;" id="biaya_tanggal_terima"></span>
                <span class="text-muted " style="float : inline-end ;" id="biaya_tanggal_pengiriman"></span>
                <span class="text-muted " style="float : inline-end ;" id="biaya_tanggal_invoice"></span>
                <span class="text-muted " style="float : inline-end ;" id="biaya_status_pembelian"></span>
            </div>
            <div class="row">
                <div class="col">
                    <span class="text-muted " style="float : inline-start ;" id="biaya_nama_supplier"></span>

                </div>
            </div>

        </div>

        <table
            class="table table-hover table-bordered table-sm"
            id="biaya_tambahan">
            <thead id="view_biaya_tambahan_thead">
                <tr>
                    <th colspan="4" style="text-align: center;">Biaya Tambahan</th>
                </tr>
                <tr>
                    <th scope="col" style="max-width: 9px; text-align:center">No</th>
                    <th scope="col">Biaya</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Jumlah</th>
                </tr>
            </thead>
            <tbody id="view_biaya_tambahan_tbody"></tbody>
            <tfoot id="view_biaya_tambahan_tfoot">
                <tr>
                    <td colspan="2"></td>
                    <td>Total</td>
                    <td id="total_biaya_tambahan"></td>
                </tr>
            </tfoot>
        </table>


    </main>
    <?php
    ?>
    <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/view_pembelian.js?v=2.1"></script>

    <?php
    ?>
</body>

</html>