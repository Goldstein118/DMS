
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=2.0">
</head>
<body>
    <button id="create_karyawan">Create Karyawan</button>
    <div id="toggleDiv_karyawan" class="hidden_karyawan">
        <label for="id_karyawan">ID:</label>
        <input type="text" id="id_karyawan" name="id_karyawan"><br><br>
        <label for="name">Name:</label>
        <input type="text" id="name_karyawan" name="name_karyawan"><br><br>
        <label for="divisi">Divisi:</label>
        <input type="text" id="divisi_karyawan" name="divisi_karyawan"><br><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone_karyawan" name="phone_karyawan"><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address_karyawan" name="address_karyawan"><br><br>
        <label for ="nik">NIK:</label>
        <input type="text" id="nik_karyawan" name="nik_karyawan"><br><br>
        <button id="submit_karyawan">Submit</button>
    </div>
    <button id="create_role">create Role</button>
    <div id="toggleDiv_role" class="hidden_role">
        <label for="id_role">ID:</label>
        <input type="text" id="id_role" name="id_role"><br><br>
        <label for="name_role">Name:</label>
        <input type="text" id="name_role" name="name_role"><br><br>
        <label for="akses">Akses</label>
        <input type="text" id="akses_role" name="akses"><br><br>
        <button id="submit_role">Submit</button>
    </div>
    <button id="create_user">Create User</button>
    <div id="toggleDiv_user" class="hidden_user">
        <label for="id_user">ID:</label>
        <input type="text" id="id_user" name="id_user"><br><br>
        <button id="submit_user">Submit</button>
    </div>
    <script src="create.js?v=2.0"></script>
</body>
</html>
<?php
include('db.php');
?>