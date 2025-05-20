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

  <!-- jQuery (use full version, not slim) -->
  <script src="<?php echo $_ENV['VENDOR_BASE_URL']?>/jquery-3.6.0.min.js"></script>

  <!-- Select2 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/select2.min.css" rel="stylesheet" />

  <!-- DataTables CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/dataTables.min.css" rel="stylesheet" />


  <!-- Bootstrap 5 CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?php echo $_ENV['VENDOR_BASE_URL']?>/bootstrap-icons.css">

    <!-- Toastr CSS -->
  <link href="<?php echo $_ENV['VENDOR_BASE_URL']?>/toastr.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL']; ?>../style.css?v=2.0">

  <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

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

  <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>


</head>

<body>
  <!--spinner-->
  <div id="loading_spinner" class="d-flex justify-content-center align-items-center" style="height: 100vh; visibility : visible">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

  <!-- Modal_karyawan -->
  <div class="modal fade" id="modal_karyawan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" for="name">Nama:</label>
          <input class="form-control" type="text" id="name_karyawan" name="name_karyawan" value=""><br>
          <label class="form-label" for="divisi">Divisi:</label>
          <input class="form-control" type="text" id="divisi_karyawan" name="divisi_karyawan" value=""><br>
          <label class="form-label" for="role_select">Role:</label>
          <select class ="form-select" id="role_select">
            <option value="">Pilih Role</option>
          </select><br><br>
          <label class="form-label" for="phone">Nomor Telepon:</label>
          <input class="form-control" type="text" id="phone_karyawan" name="phone_karyawan" value=""><br>
          <label class="form-label" for="address">Address:</label>
          <input class="form-control" type="text" id="address_karyawan" name="address_karyawan" value=""><br>
          <label class="form-label" for="nik">NIK:</label>
          <input class="form-control" type="text" id="nik_karyawan" name="nik_karyawan" value=""><br>
          <label class="form-label" for="npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="npwp_karyawan" name="npwp_karyawan" value=""><br>
          <label class="form-label" for="status_karyawan">Status:</label>
          <select class="form-select" id="status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_karyawan" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal_karyawan_update-->
  <div class="modal fade" id="modal_karyawan_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
        <div class="modal-body">
          <label class="form-label" for="update_karyawan_ID">Kode Karyawan:</label>
          <input class="form-control" type="text" id="update_karyawan_ID" disabled><br>
          <label class="form-label" for="update_name_karyawan">Name:</label>
          <input class="form-control" type="text" id="update_name_karyawan" name="name_karyawan_update"><br>
          <label class="form-label" for="update_divisi_karyawan">Divisi:</label>
          <input class="form-control" type="text" id="update_divisi_karyawan" name="divisi_karyawan_update"><br>
          <label class="form-label" for="update_role_select">Role:</label>
          <select class="form-select" id="update_role_select">
            <option value="">Pilih Role</option>
          </select><br><br>
          <label class="form-label" for="update_phone_karyawan">No Telepon:</label>
          <input class="form-control" type="text" id="update_phone_karyawan" name="phone_karyawan_update"><br>
          <label class="form-label" for="update_address_karyawan">Address:</label>
          <input class="form-control" type="text" id="update_address_karyawan" name="address_karyawan_update"><br>
          <label class="form-label" for="update_nik_karyawan">NIK:</label>
          <input class="form-control" type="text" id="update_nik_karyawan" name="nik_karyawan_update"><br>
          <label class="form-label" for="update_npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="update_npwp_karyawan" name="npwp_karyawan" value=""><br>
          <label class="form-label" for="status_karyawan">Status:</label> <br>
          <select class="form-select" id="update_status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
          </select><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_karyawan_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
     </div>
  </div>
      <!-- Offcanvas Sidebar for mobile (visible on small screens) -->
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
      <div id="main" >
        <div id="app1" class="table-responsive">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_karyawan">
            <i class="bi bi-person-plus-fill"></i> Karyawan
          </button>
          <input type="text" id="search_karyawan" placeholder="Cari Karyawan..." class="form-control mb-3">
          <table id="table_karyawan" class="cell-border compact stripe hover order-column">
            <thead>
              <tr>
                <th>Kode Karyawan</th>
                <th>Nama</th>
                <th>Nama Role</th>
                <th>Divisi</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>KTP</th>
                <th>NPWP</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="karyawan_table_body">
              <!-- Data will be populated here -->
            </tbody>
          </table>
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
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_karyawan.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_karyawan.js?v=2.0"></script>
  <script src="<?php echo $_ENV['BASE_URL']; ?>../JS/side_bar.js"></script>
</body>
</html>