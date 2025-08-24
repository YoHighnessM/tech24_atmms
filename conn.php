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


// #####################################################

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// $conn = new PDO(
//     "pgsql:host=$host;port=$port;dbname=$dbname",
//     $user,
//     $password,
//     [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
// );


// #####################################################

//neon

$envFile = __DIR__ . '/.env';
$env = [];

// Load .env if it exists (local dev)
if (file_exists($envFile)) {
    $parsed = parse_ini_file($envFile);
    if ($parsed !== false) {
        $env = $parsed;
    }
}

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Parse DATABASE_URL (Render/Neon)
    $dbopts = parse_url($databaseUrl);

    $host     = $dbopts['host'];
    $port     = $dbopts['port'] ?? 5432;
    $dbname   = ltrim($dbopts['path'], '/');
    $user     = $dbopts['user'];
    $password = $dbopts['pass'];
    $sslmode  = 'require';
} else {
    // Fallback to .env or individual env vars
    $host     = getenv('DB_HOST')     ?: ($env['DB_HOST']     ?? 'localhost');
    $port     = getenv('DB_PORT')     ?: ($env['DB_PORT']     ?? '5432');
    $dbname   = getenv('DB_NAME')     ?: ($env['DB_NAME']     ?? '');
    $user     = getenv('DB_USERNAME') ?: ($env['DB_USERNAME'] ?? '');
    $password = getenv('DB_PASSWORD') ?: ($env['DB_PASSWORD'] ?? '');
    $sslmode  = getenv('DB_SSLMODE')  ?: ($env['DB_SSLMODE']  ?? 'prefer');
}

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode";

try {
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
