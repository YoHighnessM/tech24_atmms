<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = ucwords($_POST['fullname']);
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (fullname, username, password) 
    VALUES ('$fullname', '$username', '$hashed_password')";

    $result = $conn->query($query);

    if ($result) {
        echo "Success";
        header("Location: user_reg.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
</head>

<body>
    <h2>User Register</h2>

    <form method="POST">
        <div>
            <label>Full Name:</label>
            <input type="text" name="fullname" required>
        </div>
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</body>

</html>