<?php

$username = "LucasDatabase";
$password = "LucasDatabase";
$hostname = "localhost";
$database = "Todo";

//connection to the database
$conn = mysqli_connect($hostname, $username, $password, $database)
  or die("Unable to connect to MySQL");