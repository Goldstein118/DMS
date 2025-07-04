<?php

require_once 'env_loader.php';
include('db.php');
include("{$_ENV['BASE_PATH']}/PHP/config/vendor_paths.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Detail Produk</title>

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
    @media print {
      body {
        background: #fff;
        color: #001028;
        font: size 0.875em;
      }

      h1 {
        text-align: center;
        padding: 10px 0;
      }

      #logo_joyday {
        width: 15%;
      }

      table th {
        background-color: #b2cef8 !important;
        color: #333;
        font-weight: bold;
      }


      #gambar_produk,
      #data_produk {
        max-height: fit-content;
      }

      .table-borderless th {
        background-color: #ffffff !important;
        font-weight: 600 !important;
        text-align: left;
      }

      .table-borderless td {
        text-align: right;
      }

      .table-borderless {
        float: inline-end;

        width: fit-content;
        padding: 0px;
        margin: 0px;
      }

      .no_print {
        display: none;
      }

    }

    body {
      background: #fff;
      color: #001028;
      font-size: 12px;
    }

    h1 {
      text-align: center;
      padding: 0px;
      margin: 0px;
    }

    #logo_joyday {
      width: 15%;
    }

    table th {
      background-color: #b2cef8 !important;
      color: #333;
      font-weight: bold;
    }


    #gambar_produk,
    #data_produk {
      max-height: fit-content;
      text-align: center;
    }

    .table-borderless th {
      background-color: #ffffff !important;
      font-weight: 600 !important;
      text-align: left;
    }

    .table-borderless td {
      text-align: right;
    }

    .table-borderless {
      float: inline-end;


      padding: 0px;
      margin: 0px;
    }
  </style>
</head>

<body class="container py-4">
  <header class="mb-4">
    <img src="../images/logo_joyday.png" alt="Logo" id="logo_joyday" />

    <div class="text-center">
      <h1 style="display: block">Produk</h1>
      <span class="text-muted" id="view_produk_id"></span>
    </div>
    <button type="button" class="btn btn-outline-primary btn-sm no_print" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    <button type="button" class="btn btn-outline-danger btn-sm no_print" onclick="window.close()"><i class="bi bi-x-lg"></i>Tutup</button>

  </header>

  <main>
    <div class="d-flex row_produk">
      <div class="w auto me-5" id="gambar_produk">
      </div>
      <div class="w auto ms-5" id="data_produk">
        <table class="table table-borderless">
          <tr id="tr_produk">
            <th>Produk</th>
            <td>:</td>
            <td id="nama_produk"></td>
          </tr>
          <tr id="tr_brand">
            <th>Brand</th>
            <td>:</td>
            <td id="nama_brand"></td>
          </tr>
          <tr id="tr_kategori">
            <th>Kategori</th>
            <td>:</td>
            <td id="nama_kategori"></td>
          </tr>
          <tr id="tr_harga_minimal">
            <th>Harga Minimal</th>
            <td>:</td>
            <td id="harga_minimal"></td>
          </tr>
          <tr id="tr_no_sku">
            <th>No SKU</th>
            <td>:</td>
            <td id="no_sku"></td>
          </tr>
          <tr id="tr_status">
            <th>Status</th>
            <td>:</td>
            <td id="status"></td>
          </tr>
        </table>
      </div>
    </div>
    <table
      class="table table-hover table-bordered table-sm mt-3 table-striped"
      id="detail_produk">
      <thead id="view_detail_produk_thead">
        <tr>
          <th scope="col" style="max-width:8px; text-align : center">No</th>
          <th scope="col">Pricelist</th>
          <th scope="col">Nama Pricelist</th>
          <th scope="col">Harga</th>
        </tr>
      </thead>
      <tbody id="view_detail_produk_tbody"></tbody>
    </table>
  </main>
  <?php
  ?>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/view_produk.js?v=2.0"></script>

  <?php
  ?>
</body>

</html>