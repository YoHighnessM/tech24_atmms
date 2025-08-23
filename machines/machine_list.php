<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    WHERE
        m.status <> 'Deleted'
    ORDER BY
    m.branch ASC
");

$districts = "SELECT * FROM districts";
$result = $conn->query($districts);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Machines List</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <style>
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
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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

        .header-right-content {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
            min-width: 300px;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-buttons a {
            font-size: 0.9rem;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease;
            font-weight: 500;
            border: none;
            white-space: nowrap;
        }

        .header-buttons .btn-primary {
            background-color: #3498db;
            color: #fff;
        }

        .header-buttons .btn-primary:hover {
            background-color: #2980b9;
        }

        .header-buttons .btn-secondary {
            background-color: #95a5a6;
            color: #fff;
        }

        .header-buttons .btn-secondary:hover {
            background-color: #7f8c8d;
        }


        .search-bar {
            position: relative;
            width: 100%;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem;
            padding-left: 3rem;
            border-radius: 0.75rem;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: border-color 0.2s ease;
            box-sizing: border-box;
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

        .table-container {
            overflow-x: auto;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
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
            padding: 1.25rem 1rem;
            font-weight: 500;
            color: #555;
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .table-modern tbody tr:hover td {
            background-color: #f9f9f9;
        }

        .table-actions a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 1rem;
            transition: color 0.2s ease;
        }

        .table-actions a i {
            font-size: 1.2rem;
            vertical-align: middle;
        }

        .table-actions a {
            margin-right: 1.2rem;
        }

        .table-actions a:hover {
            color: #2980b9;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-right-content {
                width: 100%;
                margin-top: 1rem;
                align-items: stretch;
            }

            .header-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .header-buttons a {
                text-align: center;
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
                    if ($result->rowCount() > 0) {
                        while ($district = $result->fetch(PDO::FETCH_ASSOC)) {
                            $district_names[] = $district['name'];
                        }
                        echo implode(', ', $district_names);
                        $result->execute();
                    }
                    ?>
                </h5>
            </div>
            <div class="header-right-content">
                <form onsubmit="event.preventDefault();" class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search machines..." name="search_query" onkeyup="filterTable()" />
                </form>
                <div class="header-buttons">
                    <a href="machine_reg.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Machine
                    </a>
                    <a href="counts.php" class="btn btn-secondary">Stats</a>
                    <div class="dropdown-menu-wrapper" style="position:relative;display:inline-block;">
                        <button class="dropdown-toggle" style="background:none;border:none;cursor:pointer;padding:0 10px;">
                            <i class="fa-solid fa-ellipsis-vertical" style="font-size:1.5rem;"></i>
                        </button>
                        <div class="dropdown-menu" style="display:none;position:absolute;right:0;top:120%;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);min-width:200px;z-index:1000;">
                            <a href="../daily_report/report_list.php" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:#333;text-decoration:none;margin-top:4px;margin-bottom:4px;">
                                <i class="fa-solid fa-calendar-day"></i> Daily Report
                            </a>
                            <hr style="margin:0 0 4px 0;border:none;border-top:1px solid #eee;">
                            <a href="#" onclick="downloadExcel()" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:#333;text-decoration:none;margin-top:4px;margin-bottom:4px;">
                                <i class="fa-solid fa-file-excel"></i> Download as Excel
                            </a>
                            <hr style="margin:0 0 4px 0;border:none;border-top:1px solid #eee;">
                            <a href="../logout.php" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:#e74c3c;text-decoration:none;background:#fbeee7;margin-top:4px;margin-bottom:4px;">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
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
                    if ($select_machines->rowCount() > 0) {
                        while ($row = $select_machines->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td title='" . htmlspecialchars($row['terminal_number'] ?? '') . "'>" . htmlspecialchars($row['terminal_number'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['bank_name'] ?? '') . "'>" . htmlspecialchars($row['bank_name'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['branch'] ?? '') . "'>" . htmlspecialchars($row['branch'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['form_type'] ?? '') . "'>" . htmlspecialchars($row['form_type'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['type'] ?? '') . "'>" . htmlspecialchars($row['type'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['context'] ?? '') . "'>" . htmlspecialchars($row['context'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['machine_name'] ?? '') . "'>" . htmlspecialchars($row['machine_name'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['district_name'] ?? '') . "'>" . htmlspecialchars($row['district_name'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['serial_number'] ?? '') . "'>" . htmlspecialchars($row['serial_number'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['per_diem'] ?? '') . "'>" . htmlspecialchars($row['per_diem'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['technician_name'] ?? '') . "'>" . htmlspecialchars($row['technician_name'] ?? '') . "</td>";
                            echo "<td title='" . htmlspecialchars($row['status'] ?? '') . "'>" . htmlspecialchars($row['status'] ?? '') . "</td>";
                            echo '<td class="table-actions">';
                            echo '<a href="more_info.php?id=' . $row['id'] . '" title="More Info"><i class="fa-solid fa-circle-info"></i></a>';
                            echo '<a href="machine_edit.php?id=' . $row['id'] . '" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
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
    <script src="script.js"></script>
</body>

</html>