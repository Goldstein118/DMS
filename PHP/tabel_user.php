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
    <div id="toggleDiv_user_update" class="hidden_user_update">
        <label for="update_user_ID">User ID:</label>
        <input type="text" id="update_user_ID" disabled><br><br>
        <label for="update_karyawan_ID">Karyawan ID:</label>
        <select  id="update_karyawan_ID">
            <option value=""></option>
        </select>
        <button id ="submit_user_update">Submit</button>
    </div>
<div class="w3-teal w3-bar">
  <button id="toggleSidebar" class="w3-bar-item w3-button w3-teal w3-large">☰</button>
  <span class="w3-bar-item w3-xlarge">Tabel User</span>
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
        <div id="app2" class="w3-container">
            <table id="table_user" class="cell-border compact stripe hover order-column">
                <thead>
                    <tr>
                        <th>User_ID</th>
                        <th>Karyawan_ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="user_table_body">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
  </div>
</div>
    <script type="module" src="<?php echo $_ENV['BASE_URL'];?>../JS/select_user.js?v=2.0"></script>
    <script  src="<?php echo $_ENV['BASE_URL'];?>../JS/side_bar.js"></script>
</body>
</html>
