<?php
require_once __DIR__ .'../env_loader.php';
    $servername=$_ENV['DB_HOST'];
    $port=$_ENV['DB_PORT'];
    $username=$_ENV['DB_USERNAME'];
    $password=$_ENV['DB_PASSWORD'];
    $db_name=$_ENV['DB_DATABASE'];
    $conn= mysqli_connect($servername,$username,$password,$db_name);
    if($conn->connect_error){
        die("Connection Failed :". $conn->connect_error);
    }
    
    ?>