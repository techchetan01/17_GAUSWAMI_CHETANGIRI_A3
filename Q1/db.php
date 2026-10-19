<?php
$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "shopping_cart";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection : " . $conn->connect_error);
}
?>