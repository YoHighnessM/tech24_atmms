<?php

// $servername = "localhost";
// $username = "root";
// $password = "root";
// $dbname = "tech24_atmms";

$servername = "sql8.freesqldatabase.com";
$username = "sql8795282";
$password = "net881DZEL";
$dbname = "sql8795282";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
