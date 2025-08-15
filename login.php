<?php
session_start();
include 'conn.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: machines/machine_list.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <div>
        <div>
            <div>
                <div>
                    <div>
                        <h3>Login</h3>
                        <?php if (!empty($error)): ?>
                            <div>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" autocomplete="off">
                            <div>
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" required autofocus>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div>
                                <button type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>