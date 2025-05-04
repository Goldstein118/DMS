
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button id="create">Create_karyawan</button>
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
    <button id="create_role">Create</button>
    <div id="toggleDiv" class="hidden">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="divisi">Divisi:</label>
        <button id="submit">Submit</button>
    </div>
    <button id="create">Create</button>
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
    <script src="create.js"></script>
</body>
</html>
<?php
include('db.php');
?>