<?php
// Turn on error reporting for debugging purposes.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include '../conn.php';

// Check if a machine ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $machine_id = $_GET['id'];

    // Perform a "soft delete" by updating the status to 'Deleted'
    $query = "UPDATE machines SET status = 'Deleted' WHERE id = '$machine_id'";

    if ($conn->query($query)) {
        // Redirect to the machine list with a success status
        header("Location: machine_list.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    // Redirect back to the machine list if no ID is provided
    header("Location: machine_list.php");
    exit();
}

$conn->close();
