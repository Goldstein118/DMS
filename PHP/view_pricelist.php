<?php

require_once 'env_loader.php';
include('db.php');
include("{$_ENV['BASE_PATH']}/PHP/config/vendor_paths.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Detail Pricelist</title>

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

    #logo_joyday {
      width: 15%;
    }

    table th {
      background-color: #b2cef8 !important;
      color: #333;
      ;
      font-weight: bold;
    }

    @media print {
      .no_print {
        display: none;
      }
    }
  </style>
</head>

<body class="container py-4">
  <header class="mb-4">
    <img src="../images/logo_joyday.png" alt="Logo" id="logo_joyday" />

    <div class="text-center">
      <h1 style="display: block">Pricelist</h1>
      <span class="text-muted" id="view_pricelist_id" style="display:inline-block"></span>
      <span class="text-muted">/</span>
      <span class="text-muted" id="nama_pricelist" style="display:inline-block"></span>
    </div>
    <button type="button" class="btn btn-outline-primary btn-sm no_print" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    <button type="button" class="btn btn-outline-danger btn-sm  no_print" onclick="window.close()"><i class="bi bi-x-lg"></i> Tutup</button>
    <span class="text-muted" id="view_tanggal_berlaku" style="float: inline-end"></span>
  </header>

  <main>

    <table
      class="table table-hover table-bordered table-sm table-striped"
      id="detail_pricelist">
      <thead id="view_detail_pricelist_thead">
        <tr>
          <th scope="col" style="max-width: 9px; text-align:center">No</th>
          <th scope="col">Kode</th>
          <th scope="col">Produk</th>
          <th scope="col">Harga</th>
        </tr>
      </thead>
      <tbody id="view_detail_pricelist_tbody"></tbody>
    </table>
  </main>
  <?php
  ?>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/view_pricelist.js?v=2.0"></script>

  <?php
  ?>
</body>

</html>