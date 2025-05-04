<?php
    $servername="localhost";
    $username="root";
    $password="";
    $db_name="data_DB";
    $conn= mysqli_connect($servername,$username,$password,$db_name);
    if($conn->connect_error){
        die("Connection Failed :". $conn->connect_error);
    }?>