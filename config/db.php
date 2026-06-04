<?php
$host = 'localhost';
$port = '3307';
$dbname = 'share_leftovers';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>