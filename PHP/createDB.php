<?php
    require_once __DIR__ .'/env_loader.php';
    $servername=$_ENV['DB_HOST'];
    $username=$_ENV['DB_USERNAME'];
    $password=$_ENV['DB_PASSWORD'];
    
    $conn= mysqli_connect($servername,$username,$password);
    
    if($conn->connect_error){
        die("Connection Failed :". $conn->connect_error);
    }
    $sql ="CREATE DATABASE IF NOT EXISTS data_DB";
    if (mysqli_query($conn,$sql)){
        try{
        }
        catch(Error){
            echo mysqli_error($conn);
        }
    }
    mysqli_select_db($conn, "data_DB");
    $role = "CREATE TABLE IF NOT EXISTS tb_role (
        role_id VARCHAR(10) PRIMARY KEY NOT NULL,
        nama VARCHAR(100),
        akses VARCHAR(100)
    )";

    if (!$conn->query($role)) {
        die("Error creating tb_role: " . mysqli_error($conn));
    }

    $karyawan="CREATE TABLE IF NOT EXISTS tb_Karyawan (
    karyawan_id VARCHAR(10) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,role_id VARCHAR(10),FOREIGN KEY (role_id) REFERENCES tb_role(role_id) ON DELETE SET NULL, divisi VARCHAR(100), noTelp VARCHAR(20),alamat VARCHAR(100),ktp VARCHAR(100),npwp VARCHAR(100),status VARCHAR(10) DEFAULT 'aktif'
    )";
    if($conn->query($karyawan)){
        try{

        }
        catch(Error){
            echo mysqli_error($conn);
        }
    }

    $user = "CREATE TABLE IF NOT EXISTS tb_User (
    user_id VARCHAR(10) PRIMARY KEY NOT NULL, karyawan_id VARCHAR(10), FOREIGN KEY (karyawan_id) REFERENCES tb_karyawan(karyawan_id) ON DELETE SET NULL

    )";
        if($conn->query($user)){
            try{
    
            }
            catch(Error){
                echo mysqli_error($conn);
            }
        }

    mysqli_close($conn);
?> 