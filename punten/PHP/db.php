<?php  

    //if there is no session, start one
    if(!isset($_SESSION)){
        session_start();
    }

    $host = "localhost";
    $username = "LucasDatabase";
    $password = "LucasDatabase";
    $db = "punten";

    $conn = mysqli_connect($host, $username, $password, $db);

