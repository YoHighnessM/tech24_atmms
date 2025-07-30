<?php
include "../conn.php";

$select_machines = $conn->query("
    SELECT 
        m.*,
        b.name AS bank_name,
        d.name AS district_name,
        u.fullname AS technician_name
    FROM 
        machines m
    LEFT JOIN 
        banks b ON m.bank_id = b.id
    LEFT JOIN 
        districts d ON m.district_id = d.id
    LEFT JOIN 
        users u ON m.technician_id = u.id
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machines List</title>
</head>

<body>
    <h1>Machines Management</h1>
    <h3>Machines List</h3>

    <div>
        <table>
            <thead>
                <tr>
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
                </tr>
            </thead>
            <tbody>
                <?php
                if ($select_machines->num_rows > 0) {
                    while ($row = $select_machines->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['terminal_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bank_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['branch']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['form_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['context']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['machine_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['district_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['serial_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['per_diem']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['technician_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['coordinates']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo '<td><a href="edit_machine.php?id=' . $row['id'] . '">Edit</a> | <a href="delete_machine.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this machine?\')">Delete</a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No machines found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>