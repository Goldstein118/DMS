<?php
require_once 'createDB.php';
require_once 'env_loader.php';
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabel Karyawan</title>

  <!-- jQuery (use full version, not slim) -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/jquery-3.6.0.min.js"></script>



  <!-- DataTables CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/dataTables.min.css" rel="stylesheet" />


  <!-- Bootstrap 5 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap-icons.css">

    <!-- Select2 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/select2.min.css" rel="stylesheet" />

    <!-- Toastr CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/toastr.min.css" rel="stylesheet">
    
  
  <link href="<?php echo $_ENV['VENDOR_BASE_URL'] ?>/mermaid.min.css" rel="stylesheet" />

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

<!-- Modal -->
<div class="modal fade" id="modal_user_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit user</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_user_ID">Kode User:</label>
        <input class="form-control" type="text" id="update_user_ID" disabled><br><br>
        <label class="form-label" for="update_karyawan_ID">Kode Karyawan:</label>
        <select class="form-label" id="update_karyawan_ID">
            <option value=""></option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_user_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<button class="btn btn-primary d-lg-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
  ☰ Menu
</button>
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" style="width: 200px;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Navigasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled ps-0">
          <li class="mb-1">
            <button
              class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
              data-bs-toggle="collapse"
              data-bs-target="#data-collapse"
              aria-expanded="true">
              <i
                class="bi bi-chevron-down toggle-icon"
                data-target="#karyawan-collapse"></i>
              Data
            </button>
            <div class="collapse show" id="data-collapse">
              <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Karyawan</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>tabel_user.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel User</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>tabel_role.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Role</a>
                </li>
              </ul>
            </div>
          </li>
    </ul>
  </div>
</div>

<div class="container-fluid">
  <diV class = "row">
          <div class="sidebar col-lg-2 d-none d-lg-block bg-light p-2">
        <a
          href="/"
          class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
          <svg class="bi pe-none me-2" width="30" height="24">
            <use xlink:href="#bootstrap" />
          </svg>
          <span class="fs-5 fw-semibold">Navigasi</span>
        </a>
        <ul class="list-unstyled ps-0">
          <li class="mb-1">
            <button
              class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
              data-bs-toggle="collapse"
              data-bs-target="#data-collapse"
              aria-expanded="true">
              <i
                class="bi bi-chevron-down toggle-icon"
                data-target="#karyawan-collapse"></i>
              Data
            </button>
            <div class="collapse show" id="data-collapse">
              <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Karyawan</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>tabel_user.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel User</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>tabel_role.php"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Role</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
          </div>
          <!-- Main content -->
    <main class="col-12 col-lg-10 ms-auto px-3" >
<div id="main">
        <div id="app2" class="table-responsive">
          <input type="text" id="search_user" placeholder="Cari User..." class="form-control mb-3">
        <div id ="table_user"></div>
        </div>
  </div>
</div>
  </main>

  </div>

  <footer class="bg-body-tertiary text-center">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="#">MDBootstrap.com</a>
    </div>
  </footer>
    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/select_user.js?v=2.0"></script>
    <script  src="<?php echo $_ENV['BASE_URL'];?>../JS/side_bar.js"></script>
</body>
</html>
