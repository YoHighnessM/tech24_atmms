<?php
include "../conn.php";

$select_bank = $conn->query("SELECT * FROM machines");

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machines List</title>
</head>

<body>
    <h2>Machines List</h2>

    <div>
        <table>
            <thead>
                <th>Terminal Number</th>
                <th>Bank</th>
                <th>Branch</th>
                <th>Form Type</th>
                <th>Type</th>
                <th>Context</th>
                <th>Machine Name</th>
                <th>District</th>
                <th>Serial Number</th>
                <th>Per Diem</th>
                <th>Technician</th>
                <th>Coordinate</th>
                <th>Status</th>
                <th>Actions</th>
            </thead>
            <tbody>

                <?php

                if ($select_bank->num_rows > 0) {
                    while ($row = $select_bank->fetch_assoc()) {

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['terminal_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bank_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['branch']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['form_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['context']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['machine_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['district_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['serial_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['per_diem']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['technician_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['coordinates']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo '<td><a href="edit_machine.php?id=' . $row['id'] . '">Edit</a> | <a href="delete_machine.php?id=' . $row['id'] . '">Delete</a></td>';
                        echo "</tr>";
                    }
                }
                ?>

                <td></td>
            </tbody>
        </table>
    </div>
</body>

</html>