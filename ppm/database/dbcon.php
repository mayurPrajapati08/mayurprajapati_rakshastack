<?php

$username = "root";
$password = "";
$host = "localhost";
$db = "pg_management";

$conn = mysqli_connect($host, $username, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>