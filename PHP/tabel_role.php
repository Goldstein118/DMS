<?php
require_once 'createDB.php';
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
    <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL'];?>../style.css?v=2.0">

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- DataTables JS -->
    <script src ="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    
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
<body>

<!-- Modal_role-->
<div class="modal fade" id="modal_role" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="name_role">Nama:</label>
        <input type="text" id="name_role" name="name_role"><br><br>
        <label for="akses">Akses</label>
        <input type="text" id="akses_role" name="akses"><br><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button  id="submit_role" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal_role_update-->
<div class="modal fade" id="modal_role_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label >Kode Role :</label>
        <input type="text" id="update_role_ID" disabled ><br><br>
        <label >Nama:</label>
        <input type="text" id="update_role_name" ><br><br>
        <label >Akses</label>
        <input type="text" id="update_role_akses" ><br><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button  id="submit_role_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="w3-teal w3-bar">
  <span>    </span>
  <span class="w3-bar-item w3-xlarge">Tabel Role</span>
</div>

<!-- Flex Container -->
<div id="layout" style="display: flex; height: calc(100vh - 50px);">

  <!-- Sidebar -->
  <div id="mySidebar" class="w3-light-grey w3-bar-block" style="width: 25%; min-width: 150px; max-width: 400px; overflow-y: auto;">
    <a href="<?php echo $_ENV['BASE_URL'];?>index.php" class="w3-bar-item w3-button">Tabel Karyawan</a>
    <a href="<?php echo $_ENV['BASE_URL'];?>tabel_user.php" class="w3-bar-item w3-button">Tabel User</a>
    <a href="<?php echo $_ENV['BASE_URL'];?>tabel_role.php" class="w3-bar-item w3-button">Tabel Role</a>
  </div>

  <!-- Resizer -->
  <div id="resizer" style="width: 5px; cursor: ew-resize; background: #ccc;"></div>

  <!-- Main content -->
  <div id="main" style="flex: 1; overflow: auto; padding: 16px;">
        <div id="app3" class="w3-container">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_role"><i class="bi bi-plus-square"></i> Role </button>
            <table id="table_role" class="cell-border compact stripe hover order-column" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode Role</th>
                        <th>Nama</th>
                        <th>Akses</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="role_table_body">
                    <!-- Data will be populated here -->
                </tbody>
            </table>

        </div>
  </div>
</div>


    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/select_role.js?v=2.0"></script>
    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/submit_role.js?v=2.0"></script>
    <script  src="<?php echo $_ENV['BASE_URL'];?>../JS/side_bar.js"></script>
</body>
</html>
