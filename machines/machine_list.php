<?php
include "../conn.php";

// Fetch the machines and their related data from the database
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

// Fetch the list of districts for the page heading
$districts = "SELECT * FROM districts";
$result = $conn->query($districts);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Machines List</title>
    <!-- Google Fonts for a modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <style>
        /* General body and container styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            /* Removed max-width to allow the container to fill the page width */
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        /* Header section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
        }

        .header-title h5 {
            font-size: 1rem;
            font-weight: 400;
            color: #7f8c8d;
            margin: 0.5rem 0 0;
        }

        .header-links a {
            font-size: 0.9rem;
            color: #34495e;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
            margin-left: 0.5rem;
        }

        .header-links a:hover {
            background-color: #ecf0f1;
        }

        /* Search and actions section */
        .actions-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-bar {
            position: relative;
            flex-grow: 1;
            max-width: 400px;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem;
            padding-left: 3rem;
            border-radius: 0.75rem;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: border-color 0.2s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #3498db;
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .action-buttons .btn {
            background-color: #3498db;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .action-buttons .btn:hover {
            background-color: #2980b9;
        }

        /* Table styling to make it wide and without separate containers for each data cell */
        .table-container {
            overflow-x: auto;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
            /* Use collapse for a classic table look */
        }

        .table-modern thead tr {
            background-color: transparent;
        }

        .table-modern th {
            text-align: left;
            padding: 0.5rem 1rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .table-modern td {
            background-color: #fff;
            /* Revert to a single background for the whole table */
            padding: 1.25rem 1rem;
            font-weight: 500;
            color: #555;
            border-bottom: 1px solid #e0e0e0;
            /* Add bottom border to separate rows */
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .table-modern tbody tr:hover td {
            background-color: #f9f9f9;
            /* Hover effect on the entire row */
            box-shadow: none;
            /* No box-shadow for hover */
        }

        .table-actions a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 1rem;
            transition: color 0.2s ease;
        }

        .table-actions a:hover {
            color: #2980b9;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {

            .header-links,
            .actions-section {
                flex-direction: column;
                align-items: stretch;
            }

            .header-links a {
                margin: 0.25rem 0;
            }

            .search-bar {
                max-width: none;
            }

            .action-buttons {
                width: 100%;
                justify-content: center;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>
                    <a href="machine_list.php" style="color: #2c3e50; text-decoration: none;">Machines Management</a>
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
            <div class="header-links">
                <a href="banks.php">Banks</a>
                <a href="districts.php">Districts</a>
                <a href="technicians.php">Technicians</a>
                <a href="counts.php">Counts</a>
            </div>
        </div>

        <div class="actions-section">
            <form onsubmit="event.preventDefault();" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search machines..." name="search_query" onkeyup="filterTable()" />
            </form>
            <div class="action-buttons">
                <a href="machine_reg.php" class="btn">
                    <i class="fas fa-plus-circle me-2"></i> Add Machine
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="table-modern">
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
                            echo '<td class="table-actions">';
                            echo '<a href="more_info.php?id=' . $row['id'] . '">More Info</a>';
                            echo '<a href="edit_machine.php?id=' . $row['id'] . '">Edit</a>';
                            echo '</td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13' style='text-align: center; color: #7f8c8d; padding: 2rem;'>No machines found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function filterTable() {
            const input = document.querySelector('input[name="search_query"]');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.table-modern');
            const tr = table.querySelectorAll('tbody tr');

            tr.forEach(row => {
                let rowVisible = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        rowVisible = true;
                    }
                });
                if (rowVisible || filter === '') {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>