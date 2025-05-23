<?php
require_once 'createDB.php';
require_once 'env_loader.php';
include('db.php');
include ("{$_ENV['BASE_PATH']}/PHP/config/vendor_paths.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabel Karyawan</title>

  <!-- jQuery -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/jquery-3.6.0.min.js"></script>
  <!-- DataTables CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/dataTables.min.css" rel="stylesheet" />
  <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/mermaid.min.css" rel="stylesheet" />
  <!-- Bootstrap 5 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap-icons.css">
    <!-- Select2 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/select2.min.css" rel="stylesheet" />

    <!-- Toastr CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/toastr.min.css" rel="stylesheet">
    
  


  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL']; ?>../style.css?v=2.0">



  <!-- Select2 JS -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/select2.min.js"></script>

  <!-- DataTables JS -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/dataTables.min.js"></script>

  <!-- Popper.js -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/popper.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/sweetalert2@11.js"></script>

    <!-- Toastr JS -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/toastr.min.js"></script>

  <!--Grid.js -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/gridjs.umd.js"></script>



</head>

<body>
  <!--spinner-->
  <div id="loading_spinner" class="d-flex justify-content-center align-items-center" style="height: 100vh; visibility : visible">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>
<button class="btn btn-primary d-lg-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
  ☰ Menu
</button>
<div class="container-fluid">
  <diV class = "row">
<?php
include('sidebar.php');

// Get the page from the query string
$page = $_GET['page'] ?? 'tabel_karyawan';

// Whitelist allowed pages for security
$allowed_pages = ['tabel_karyawan', 'tabel_role', 'tabel_user','tabel_supplier'];

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
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_karyawan.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_karyawan.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_role.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_role.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_user.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_supplier.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_supplier.js?v=2.0"></script>
  <script src="<?php echo $_ENV['BASE_URL']; ?>../JS/side_bar.js"></script>
</body>
</html>