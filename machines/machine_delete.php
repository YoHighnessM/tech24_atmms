<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conn.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $machine_id = $_GET['id'];
    $query = "UPDATE machines SET status = 'Deleted' WHERE id = '$machine_id'";

    if ($conn->query($query)) {
        header("Location: machine_list.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: machine_list.php");
    exit();
}

$conn->close();
