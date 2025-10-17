<?php
require_once 'env_loader.php';
require_once 'createDB.php';
require_once 'db.php';
require_once $_ENV['BASE_PATH'] . '/PHP/config/vendor_paths.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DMS</title>

  <!-- jQuery -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/jquery-3.6.0.min.js"></script>

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


  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL']; ?>../style.css?v=2.0.1">



  <!-- Select2 JS -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/select2.min.js"></script>



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




</head>

<body>
  <div id="loading_spinner" class="d-flex justify-content-center align-items-center" style="height: 100vh; visibility : visible; background-color: white;">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
    <dotlottie-wc src="https://lottie.host/a7f7ef11-5190-400a-8dfd-92eac990725c/MxZMJwW8Oi.lottie" style="width: 500px;height: 500px" speed="1" autoplay loop></dotlottie-wc>
  </div>


  <!-- https://lottie.host/ae531907-211c-4d6b-a8e1-4e26dc786593/mcCjC4AkcM.lottie -->
  <!-- https://lottie.host/a7f7ef11-5190-400a-8dfd-92eac990725c/MxZMJwW8Oi.lottie -->

  <div id="menu_container">
    <button class="btn btn-primary d-lg-none m-2 btn-sm" id="menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
  </div>

  <div class="container-fluid">
    <diV class="row">
      <?php
      include('sidebar.php');

      // Get the page from the query string
      $page = $_GET['page'] ?? 'dashboard';

      // Whitelist allowed pages for security
      $allowed_pages = [
        'dashboard',
        'tabel_karyawan',
        'tabel_role',
        'tabel_user',
        'tabel_supplier',
        'tabel_customer',
        'tabel_channel',
        'tabel_kategori',
        'tabel_brand',
        'tabel_produk',
        'tabel_divisi',
        'tabel_gudang',
        'tabel_pricelist',
        'tabel_armada',
        'tabel_frezzer',
        'tabel_promo',
        'tabel_satuan',
        'tabel_pembelian',
        'tabel_data_biaya',
        'tabel_invoice',
        'tabel_retur_pembelian',
        'tabel_penjualan',
        'tabel_retur_penjualan'
      ];

      if (in_array($page, $allowed_pages)) {
        include $page . '.php';
      } else {
        echo "<p>Page Not Found</p>";
      }
      ?>

    </div>
    <?php
    include('footer.php');
    ?>
    <?php
    // Only load JS files needed for the current page
    switch ($page) {
      case 'tabel_karyawan':
    ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_karyawan.js?v=2.0.1"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_karyawan.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_role':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_role.js?v=2.0.1"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_role.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_user':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_user.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_user.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_supplier':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_supplier.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_supplier.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_customer':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_customer.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_customer.js?v=2.0.1"></script>
      <?php
        break;
      case 'tabel_channel':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_channel.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_channel.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_kategori':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_kategori.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_kategori.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_brand':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_brand.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_brand.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_produk':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_produk.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_produk.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_divisi':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_divisi.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_divisi.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_gudang':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_gudang.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_gudang.js?v=2.0"></script>
      <?php
        break;

      case 'tabel_pricelist':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_pricelist.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_pricelist.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_armada':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_armada.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_armada.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_frezzer':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_frezzer.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_frezzer.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_promo':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_promo.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_promo.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_satuan':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_satuan.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_satuan.js?v=2.0"></script>
      <?php
        break;

      case 'tabel_pembelian':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_pembelian.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_pembelian.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_data_biaya':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_data_biaya.js?v=2.0.2"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_data_biaya.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_invoice':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_invoice.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_invoice.js?v=2.0"></script>
      <?php
        break;

      case 'tabel_retur_pembelian':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_retur_pembelian.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_retur_pembelian.js?v=2.0"></script>
      <?php
        break;
      case 'tabel_penjualan':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_penjualan.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_penjualan.js?v=2.0"></script>
      <?php
        break;

      case 'tabel_retur_penjualan':
      ?>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_retur_penjualan.js?v=2.0"></script>
        <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_retur_penjualan.js?v=2.0"></script>
    <?php
        break;
    }
    ?>

    <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/side_bar.js?v=2.0"></script>
    <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/helper.js?v=2.0.1"></script>
</body>



</html>