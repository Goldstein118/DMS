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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src ="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
</head>
<body>

    
    <div id="toggleDiv_karyawan" class="hidden_karyawan">
        <label for="name">Name:</label>
        <input type="text" id="name_karyawan" name="name_karyawan"><br><br>
        <label for="divisi">Divisi:</label>
        <input type="text" id="divisi_karyawan" name="divisi_karyawan"><br><br>
        <select  id="role_select">
            <option value="">Select Role</option>
        </select>
        <label for="phone">Phone:</label>
        <input type="text" id="phone_karyawan" name="phone_karyawan"><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address_karyawan" name="address_karyawan"><br><br>
        <label for ="nik">NIK:</label>
        <input type="text" id="nik_karyawan" name="nik_karyawan"><br><br>
        <button id="submit_karyawan" >Submit</button>
    </div>


    <div id="toggleDiv_role" class="hidden_role">
        <label for="name_role">Name:</label>
        <input type="text" id="name_role" name="name_role"><br><br>
        <label for="akses">Akses</label>
        <input type="text" id="akses_role" name="akses"><br><br>
        <button id="submit_role" >Submit</button>
    </div>

    <div id="app1">
        <table id="table_karyawan" class="cell-border compact stripe hover order-column" style="width:100%">
            <thead>
                <tr>
                    <th>Karyawan_ID</th>
                    <th>Name</th>
                    <th>Role_ID</th>
                    <th>Divisi</th>
                    <th>no Telp</th>
                    <th>Alamat</th>
                    <th>KTP/NPWP</th>
                    <th>Action</th>
                    <th><button id="create_karyawan" class="create_button" >Create Karyawan</button></th>
                </tr>
            </thead>
            <tbody id="karyawan_table_body">
                <!-- Data will be populated here -->
            </tbody>
        </table>

    </div><br><hr><br>
    <div id="app2">
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
        <button id="delete_user">Delete User</button>
        <button id="update_user">Update User</button>
    </div><hr><br>
    <div id="app3">
    <table id="table_role" class="cell-border compact stripe hover order-column" style="width:100%">
            <thead>
                <tr>
                    <th>Role_ID</th>
                    <th>Name</th>
                    <th>Akses</th>
                    <th>Action</th>
                    <th><button id="create_role" class="create_button">create Role</button></th>
                </tr>
            </thead>
            <tbody id="role_table_body">
                <!-- Data will be populated here -->
            </tbody>
        </table>
        <button id="delete_role">Delete Role</button>
        <button id="update_role">Update Role</button>   
    </div><br><hr><br>
    <script type="module" src="../JS/select.js?v=2.0"></script>

    <script type="module" src="../JS/create.js?v=2.0"></script>
</body>
</html>
