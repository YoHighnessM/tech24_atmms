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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8fafc;
        }

        /* Set headers, buttons, and other UI elements to Inter */
        .card-title,
        .text-muted,
        .btn,
        .search-bar .form-control {
            font-family: 'Inter', sans-serif;
        }

        /* Set table body and header to Open Sans */
        .table {
            font-family: 'Open Sans', sans-serif;
        }

        .card {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border-radius: 0;
            width: 100vw;
            height: 100vh;
            padding: 2rem !important;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
        }

        .card-title,
        .text-center {
            color: #343a40;
        }

        .h4.text-center.mb-4.text-muted {
            font-weight: 400;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-top: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .header-text-left {
            display: flex;
            flex-direction: column;
        }

        .header-text-left h1 {
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .header-text-left h5 {
            margin-top: 0;
        }

        .header-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .search-bar {
            width: 300px;
        }

        .search-bar .form-control {
            border-radius: 0.25rem;
        }

        .search-bar .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .header-container .d-flex {
            margin-bottom: 0 !important;
        }

        .btn {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #e2e6ea;
            border-color: #dae0e5;
            color: #343a40;
        }

        .btn-counts {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
            transition: all 0.3s ease;
        }

        .btn-counts:hover {
            background-color: #0056b3;
            border-color: #004a9e;
        }

        .table-responsive {
            margin-top: 1.5rem;
            width: 100%;
            flex-grow: 1;
            min-height: 0;
            overflow-x: auto;
            overflow-y: auto;
        }

        .table {
            table-layout: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #212529 !important;
            color: #fff !important;
            z-index: 2;
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 0.65rem;
            border-bottom: 2px solid #dee2e6;
        }

        th,
        td {
            padding: 0.65rem;
            font-size: 0.85rem;
            vertical-align: middle;
            color: #333;
            min-height: 43px;
        }

        th:not(.actions-col),
        td:not(.actions-col) {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Actions column header */
        th.actions-col {
            min-width: 90px;
            max-width: 110px;
            width: 110px;
            text-align: center;
        }

        /* Actions column cells */
        td.actions-col {
            min-width: 90px;
            max-width: 110px;
            width: 110px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        /* Three dots placeholder */
        .action-placeholder {
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* Buttons dropdown */
        .actions-dropdown {
            gap: 0.25rem;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s, visibility 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        tr:hover .action-placeholder {
            opacity: 0;
            pointer-events: none;
        }

        tr:hover .actions-dropdown {
            visibility: visible;
            opacity: 1;
        }

        /* Button sizing for taller rows */
        .actions-dropdown .btn {
            padding: 0.3rem 0.55rem;
            font-size: 0.8rem;
            border-radius: 4px;
        }

        .table-striped>tbody>tr:hover {
            background-color: #e9f0ff !important;
            transition: background-color 0.3s;
        }

        .hidden-row {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="header-container">
                <div class="header-text-left">
                    <h1 class="card-title">
                        <a href="machine_list.php" class="text-dark">Machines Management</a>
                    </h1>
                    <h5 class="text-muted">
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
                <div class="header-actions">
                    <form class="search-bar" onsubmit="event.preventDefault();">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search machines..." name="search_query" onkeyup="filterTable()" />
                        </div>
                    </form>
                    <div class="d-flex justify-content-end">
                        <a href="machine_reg.php" class="btn btn-outline-secondary me-2">Add Machine</a>
                        <a href="banks.php" class="btn btn-outline-secondary me-2">Banks</a>
                        <a href="districts.php" class="btn btn-outline-secondary me-2">Districts</a>
                        <a href="technicians.php" class="btn btn-outline-secondary me-2">Technicians</a>
                        <a href="counts.php" class="btn btn-counts">Counts</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered align-middle mb-0">
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
                            <th class="actions-col">Actions</th>
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
                                echo '<td class="actions-col">';
                                echo '<div class="action-placeholder">...</div>';
                                echo '<div class="actions-dropdown">';
                                echo '<a href="more_info.php?id=' . $row['id'] . '" class="btn btn-outline-info" title="More Info"><i class="bi bi-info-circle"></i></a>';
                                echo '<a href="edit_machine.php?id=' . $row['id'] . '" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>';
                                echo '</div>';
                                echo '</td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='13' class='text-center'>No machines found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function filterTable() {
            const input = document.querySelector('.search-bar input[name="search_query"]');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.table-responsive table');
            const tr = table.querySelectorAll('tbody tr');

            tr.forEach(row => {
                let rowVisible = false;
                row.querySelectorAll('td:not(.actions-col)').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        rowVisible = true;
                    }
                });
                if (rowVisible || filter === '') {
                    row.classList.remove('hidden-row');
                } else {
                    row.classList.add('hidden-row');
                }
            });
        }
    </script>
</body>

</html>