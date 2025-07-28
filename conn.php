<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tech24_atmms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection faild");
}
