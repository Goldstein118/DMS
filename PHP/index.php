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
  <title>Document</title>

  <!-- jQuery (use full version, not slim) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css" rel="stylesheet" />

  <!-- W3.CSS -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

  <!-- Toastr CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL']; ?>../style.css?v=2.0">

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
  <!-- Modal_karyawan -->
  <div class="modal fade" id="modal_karyawan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="name">Nama:</label>
          <input type="text" id="name_karyawan" name="name_karyawan" value=""><br><br>
          <label for="divisi">Divisi:</label>
          <input type="text" id="divisi_karyawan" name="divisi_karyawan" value=""><br><br>
          <label for="role_select">Role:</label>
          <select id="role_select">
            <option value="">Pilih Role</option>
          </select><br><br>
          <label for="phone">Nomor Telepon:</label>
          <input type="text" id="phone_karyawan" name="phone_karyawan" value=""><br><br>
          <label for="address">Address:</label>
          <input type="text" id="address_karyawan" name="address_karyawan" value=""><br><br>
          <label for="nik">NIK:</label>
          <input type="text" id="nik_karyawan" name="nik_karyawan" value=""><br><br>
          <label for="npwp_karyawan">NPWP:</label>
          <input type="text" id="npwp_karyawan" name="npwp_karyawan" value=""><br><br>
          <label for="status_karyawan">Status:</label>
          <select id="status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button id="submit_karyawan" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal_karyawan_update-->
  <div class="modal fade" id="modal_karyawan_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="update_karyawan_ID">Kode Karyawan:</label>
          <input type="text" id="update_karyawan_ID" disabled><br><br>
          <label for="update_name_karyawan">Name:</label>
          <input type="text" id="update_name_karyawan" name="name_karyawan_update"><br><br>
          <label for="update_divisi_karyawan">Divisi:</label>
          <input type="text" id="update_divisi_karyawan" name="divisi_karyawan_update"><br><br>
          <label for="update_role_select">Role:</label>
          <select id="update_role_select">
            <option value="">Pilih Role</option>
          </select>
          <label for="update_phone_karyawan">No Telepon:</label>
          <input type="text" id="update_phone_karyawan" name="phone_karyawan_update"><br><br>
          <label for="update_address_karyawan">Address:</label>
          <input type="text" id="update_address_karyawan" name="address_karyawan_update"><br><br>
          <label for="update_nik_karyawan">NIK:</label>
          <input type="text" id="update_nik_karyawan" name="nik_karyawan_update"><br><br>
          <label for="update_npwp_karyawan">NPWP:</label>
          <input type="text" id="update_npwp_karyawan" name="npwp_karyawan" value=""><br><br>
          <label for="status_karyawan">Status:</label> <br>
          <select id="update_status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
          </select><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button id="submit_karyawan_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Sidebar -->
  <!-- Top Header / Navbar -->
  <div class="w3-teal w3-bar">
    <span>    </span>
    <span class="w3-bar-item w3-xlarge">Tabel Karyawan</span>
  </div>

  <!-- Flex Container -->
  <div id="layout" style="display: flex; height: calc(100vh - 50px);">

    <!-- Sidebar -->
    <div id="mySidebar" class="w3-light-grey w3-bar-block" style="width: 25%; min-width: 150px; max-width: 400px; overflow-y: auto;">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown link
        </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
      <a class="dropdown-item" href="<?php echo $_ENV['BASE_URL']; ?>index.php" class="w3-bar-item w3-button">Tabel Karyawan</a>
      <a class="dropdown-item" href="<?php echo $_ENV['BASE_URL']; ?>tabel_user.php" class="w3-bar-item w3-button">Tabel User</a>
      <a class="dropdown-item" href="<?php echo $_ENV['BASE_URL']; ?>tabel_role.php" class="w3-bar-item w3-button">Tabel Role</a>
        </div>
      </li>    
    </div>

    <!-- Resizer -->
    <div id="resizer" style="width: 5px; cursor: ew-resize; background: #ccc;"></div>

    <!-- Main content -->
    <div id="main" style="flex: 1; overflow: auto; padding: 16px;">
      <div id="app1" class="w3-container">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_karyawan">
          <i class="bi bi-person-plus-fill"></i> Karyawan
        </button>
        <table id="table_karyawan" class="cell-border compact stripe hover order-column" style="width:100%">
          <thead>
            <tr>
              <th>Kode Karyawan</th>
              <th>Nama</th>
              <th>Role_ID</th>
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
  <footer class="bg-body-tertiary text-center">
    <div class="container p-4"></div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/select_karyawan.js?v=2.0"></script>
  <script type="module" src="<?php echo $_ENV['BASE_URL']; ?>../JS/submit_karyawan.js?v=2.0"></script>
  <script src="<?php echo $_ENV['BASE_URL']; ?>../JS/side_bar.js"></script>

</html>