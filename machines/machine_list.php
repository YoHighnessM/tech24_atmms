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

$districts = "SELECT * FROM districts";
$result = $conn->query($districts);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Machines List</title>
</head>

<body>
    <div>
        <div>
            <div>
                <h1>
                    <a href="machine_list.php">Machines Management</a>
                </h1>
                <h5>
                    Machines List for
                    <?php
                    $district_names = [];
                    if ($result->num_rows > 0) {
                        while ($district = $result->fetch_assoc()) {
                            $district_names[] = $district['name'];
                        }
                        echo implode(', ', $district_names);
                        $result->data_seek(0);
                    }
                    ?>
                </h5>
            </div>
            <div>
                <form onsubmit="event.preventDefault();">
                    <div>
                        <input type="text" placeholder="Search machines..." name="search_query" onkeyup="filterTable()" />
                    </div>
                </form>
                <div>
                    <a href="machine_reg.php">Add Machine</a>
                    <a href="banks.php">Banks</a>
                    <a href="districts.php">Districts</a>
                    <a href="technicians.php">Technicians</a>
                    <a href="counts.php">Counts</a>
                </div>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Terminal No</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>Form Type</th>
                            <th>Type</th>
                            <th>Context</th>
                            <th>Name</th>
                            <th>District</th>
                            <th>Serial No</th>
                            <th>Per-Diem</th>
                            <th>Technician</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($select_machines->num_rows > 0) {
                            while ($row = $select_machines->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td title='{$row['terminal_number']}'>" . htmlspecialchars($row['terminal_number']) . "</td>";
                                echo "<td title='{$row['bank_name']}'>" . htmlspecialchars($row['bank_name']) . "</td>";
                                echo "<td title='{$row['branch']}'>" . htmlspecialchars($row['branch']) . "</td>";
                                echo "<td title='{$row['form_type']}'>" . htmlspecialchars($row['form_type']) . "</td>";
                                echo "<td title='{$row['type']}'>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td title='{$row['context']}'>" . htmlspecialchars($row['context']) . "</td>";
                                echo "<td title='{$row['machine_name']}'>" . htmlspecialchars($row['machine_name']) . "</td>";
                                echo "<td title='{$row['district_name']}'>" . htmlspecialchars($row['district_name']) . "</td>";
                                echo "<td title='{$row['serial_number']}'>" . htmlspecialchars($row['serial_number']) . "</td>";
                                echo "<td title='{$row['per_diem']}'>" . htmlspecialchars($row['per_diem']) . "</td>";
                                echo "<td title='{$row['technician_name']}'>" . htmlspecialchars($row['technician_name']) . "</td>";
                                echo "<td title='{$row['status']}'>" . htmlspecialchars($row['status']) . "</td>";
                                echo '<td>';
                                echo '<div>...</div>';
                                echo '<div>';
                                echo '<a href="more_info.php?id=' . $row['id'] . '">More Info</a>';
                                echo '<a href="edit_machine.php?id=' . $row['id'] . '">Edit</a>';
                                echo '</div>';
                                echo '</td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='13'>No machines found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function filterTable() {
            const input = document.querySelector('input[name="search_query"]');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('table');
            const tr = table.querySelectorAll('tbody tr');

            tr.forEach(row => {
                let rowVisible = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        rowVisible = true;
                    }
                });
                if (rowVisible || filter === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>