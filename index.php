
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button id="create">Create Karyawan</button>
    <div id="toggleDiv" class="hidden">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="divisi">Divisi:</label>
        <input type="text" id="divisi" name="divisi"><br><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone"><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address"><br><br>
        <label for ="nik">NIK:</label>
        <input type="text" id="nik" name="nik"><br><br>
        <button id="submit">Submit</button>
    </div>
    <button id="create_role">create Role</button>
    <div id="toggleDiv" class="hidden">
        <label for="id_role">ID:</label>
        <input type="text" id="id_role" name="id_role"><br><br>
        <label for="name_role">Name:</label>
        <input type="text" id="name_role" name="name_role"><br><br>
        <label for="akses">Akses</label>
        <input type="text" id="akses_role" name="akses"><br><br>
        <button id="submit_role">Submit</button>
    </div>
    <button id="create">Create User</button>
    <div id="toggleDiv" class="hidden">
        <label for="id_user">ID:</label>
        <input type="text" id="id_user" name="id_user"><br><br>
    </div>
    <script src="create.js"></script>
</body>
</html>
<?php
include('db.php');
?>