<?php
    $servername="localhost";
    $username="root";
    $password="";
    
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
        role_ID VARCHAR(10) PRIMARY KEY NOT NULL,
        nama VARCHAR(100),
        akses VARCHAR(100)
    )";

    if (!$conn->query($role)) {
        die("Error creating tb_role: " . mysqli_error($conn));
    }

    $karyawan="CREATE TABLE IF NOT EXISTS tb_Karyawan (
    karyawan_ID VARCHAR(10) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,role_ID VARCHAR(10),FOREIGN KEY (role_ID) REFERENCES tb_role(role_ID) , divisi VARCHAR(100), noTelp VARCHAR(20),alamat VARCHAR(100),KTP_NPWP VARCHAR(100)
    )";
    if($conn->query($karyawan)){
        try{

        }
        catch(Error){
            echo mysqli_error($conn);
        }
    }

    $user = "CREATE TABLE IF NOT EXISTS tb_User (
    user_ID VARCHAR(10) PRIMARY KEY NOT NULL, karyawan_ID VARCHAR(10),FOREIGN KEY (karyawan_ID) REFERENCES tb_karyawan(karyawan_ID)
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