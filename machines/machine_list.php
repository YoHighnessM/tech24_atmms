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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        body {
            background: #f8fafc;
            height: 100vh;
            width: 100vw;
        }
        .card {
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border-radius: 1rem;
            width: 100vw;
            height: 100vh;
            border-radius: 0;
            padding: 2rem !important;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
        }
        .table-responsive {
            margin-top: 1.5rem;
            width: 100%;
            flex-grow: 1;
            min-height: 0;
            overflow-y: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            background: #212529;
            color: #fff;
            z-index: 2;
            vertical-align: middle;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            font-size: 0.97rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        th, td {
            max-width: 140px;
            min-width: 90px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            cursor: pointer;
        }
        th.actions-col, td.actions-col {
            max-width: 110px;
            min-width: 90px;
        }
        /* New class for expanded rows */
        .expanded-row td {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: initial !important;
            word-break: break-word;
            min-height: initial;
            max-width: initial;
        }
    </style>
</head>

<body>
    <div class="card p-4 w-100 h-100" style="width:100vw;height:100vh;border-radius:0;">
        <div class="card-body d-flex flex-column h-100">
            <h1 class="card-title text-center mb-3">Machines Management</h1>
            <h4 class="text-center mb-4">Machines List</h4>
            <div class="table-responsive flex-grow-1" style="min-height:0;">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
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
                            <th>Coordinate</th>
                            <th>Status</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($select_machines->num_rows > 0) {
                            while ($row = $select_machines->fetch_assoc()) {
                                echo "<tr onclick='toggleRowExpansion(this)'>"; // Added onclick event
                                echo "<td title='" . htmlspecialchars($row['terminal_number']) . "'>" . htmlspecialchars($row['terminal_number']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['bank_name']) . "'>" . htmlspecialchars($row['bank_name']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['branch']) . "'>" . htmlspecialchars($row['branch']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['form_type']) . "'>" . htmlspecialchars($row['form_type']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['type']) . "'>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['context']) . "'>" . htmlspecialchars($row['context']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['machine_name']) . "'>" . htmlspecialchars($row['machine_name']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['district_name']) . "'>" . htmlspecialchars($row['district_name']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['serial_number']) . "'>" . htmlspecialchars($row['serial_number']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['per_diem']) . "'>" . htmlspecialchars($row['per_diem']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['technician_name']) . "'>" . htmlspecialchars($row['technician_name']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['coordinates']) . "'>" . htmlspecialchars($row['coordinates']) . "</td>";
                                echo "<td title='" . htmlspecialchars($row['status']) . "'>" . htmlspecialchars($row['status']) . "</td>";
                                echo '<td class="actions-col" onclick="event.stopPropagation()">'; // Added onclick to prevent row expansion on action click
                                echo '<a href="edit_machine.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary me-1">Edit</a>';
                                echo '<a href="delete_machine.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete this machine?\')">Delete</a>';
                                echo '</td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='14' class='text-center'>No machines found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleRowExpansion(row) {
            row.classList.toggle('expanded-row');
        }
    </script>
</body>

</html>