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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo $_ENV['BASE_URL'];?>../style.css?v=2.0">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src ="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>
<body>

    <div id="toggleDiv_karyawan" class="hidden_karyawan">
        <label for="name">Name:</label>
        <input type="text" id="name_karyawan" name="name_karyawan" value=""><br><br>
        <label for="divisi">Divisi:</label>
        <input type="text" id="divisi_karyawan" name="divisi_karyawan"value=""><br><br>
        <label for="role_select">Role:</label>
        <select  id="role_select">
            <option value="">Select Role</option>
        </select>
        <label for="phone">Phone:</label>
        <input type="text" id="phone_karyawan" name="phone_karyawan"value=""><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address_karyawan" name="address_karyawan"value=""><br><br>
        <label for ="nik">NIK:</label>
        <input type="text" id="nik_karyawan" name="nik_karyawan"value=""><br><br>
        <label for="npwp_karyawan">NPWP:</label>
        <input type="text" id="npwp_karyawan" name="npwp_karyawan"value=""><br><br>
        <label for="status_karyawan">Status:</label><br>
        <select id="status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
        </select><br><br>
        <button id="submit_karyawan" >Submit</button>
    </div>

    <div id="toggleDiv_karyawan_update" class="hidden_karyawan_update">
        <label for="update_karyawan_ID">Karyawan ID:</label>
        <input type="text" id="update_karyawan_ID" disabled><br><br>
        <label for="update_name_karyawan">Name:</label>
        <input type="text" id="update_name_karyawan" name="name_karyawan_update"><br><br>
        <label for="update_divisi_karyawan">Divisi:</label>
        <input type="text" id="update_divisi_karyawan" name="divisi_karyawan_update"><br><br>
        <label for="update_role_select">Role:</label>
        <select  id="update_role_select">
            <option value="">Select Role</option>
        </select>
        <label for="update_phone_karyawan">Phone:</label>
        <input type="text" id="update_phone_karyawan" name="phone_karyawan_update"><br><br>
        <label for="update_address_karyawan">Address:</label>
        <input type="text" id="update_address_karyawan" name="address_karyawan_update"><br><br>
        <label for ="update_nik_karyawan">NIK:</label>
        <input type="text" id="update_nik_karyawan" name="nik_karyawan_update"><br><br>
        <label for="update_npwp_karyawan">NPWP:</label>
        <input type="text" id="update_npwp_karyawan" name="npwp_karyawan"value=""><br><br>
        <label for="status_karyawan">Status:</label> <br>
        <select id="update_status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non Aktif</option>
        </select><br><br>
        <button id="submit_karyawan_update" >Submit</button>
    </div>
<!-- Sidebar -->
<!-- Top Header / Navbar -->
<div class="w3-teal w3-bar">
  <button id="toggleSidebar" class="w3-bar-item w3-button w3-teal w3-large">☰</button>
  <span class="w3-bar-item w3-xlarge">Tabel Karyawan</span>
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
    <div id="app1" class="w3-container">
      <button id="create_karyawan" class="create_button">[&plus;] Karyawan</button>
      <table id="table_karyawan" class="cell-border compact stripe hover order-column" style="width:100%">
        <thead>
          <tr>
            <th>Karyawan_ID</th>
            <th>Name</th>
            <th>Role_ID</th>
            <th>Divisi</th>
            <th>no Telp</th>
            <th>Alamat</th>
            <th>KTP</th>
            <th>NPWP</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="karyawan_table_body">
          <!-- Data will be populated here -->
        </tbody>
      </table>
    </div>
  </div>
</div>

    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/select_karyawan.js?v=2.0"></script>
    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/submit_karyawan.js?v=2.0"></script>
    <script src="<?php echo $_ENV['BASE_URL'];?>../JS/side_bar.js"></script>
</body>
</html>
