<?php

// local
// $servername = "localhost";
// $username = "root";
// $password = "root";
// $dbname = "tech24_atmms";

//https://www.freesqldatabase.com
// $servername = "sql8.freesqldatabase.com";
// $username = "sql8795282";
// $password = "net881DZEL";
// $dbname = "sql8795282";

// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
// die("Connection failed: " . $conn->connect_error);
// }

//neon

$env = parse_ini_file(__DIR__ . '/.env');

$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$port = $env['DB_PORT'];
$sslmode = $env['DB_SSLMODE'];

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    // echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
