<?php
include "../conn.php";

// Count total districts
$districts_count_query = $conn->query("SELECT COUNT(*) AS total_districts FROM districts");
$total_districts = $districts_count_query->fetch(PDO::FETCH_ASSOC)['total_districts'];

// Count total machines
$machines_count_query = $conn->query("SELECT COUNT(*) AS total_machines FROM machines WHERE status <> 'Deleted'");
$total_machines = $machines_count_query->fetch(PDO::FETCH_ASSOC)['total_machines'];

// Get all districts
$all_districts_query = $conn->query("SELECT id, name FROM districts ORDER BY name");

// Get all banks
$all_banks_query = $conn->query("SELECT id, name FROM banks ORDER BY name");
$banks = [];
while ($bank_row = $all_banks_query->fetch(PDO::FETCH_ASSOC)) {
    $banks[] = $bank_row;
}

// Count machines by status
$machines_by_status_query = $conn->query("
    SELECT
        status,
        COUNT(*) AS machines_count
    FROM
        machines
    WHERE
        status <> 'Deleted'
    GROUP BY
        status
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machines Counts</title>
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
            max-width: 1200px;
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background-color: #ecf0f1;
            padding: 1.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            font-size: 2.5rem;
            color: #3498db;
        }

        .card-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }

        .card-content p {
            font-size: 1rem;
            margin: 0;
            color: #7f8c8d;
        }

        .district-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .district-card {
            background-color: #f9f9f9;
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
            padding: 1.5rem;
            transition: box-shadow 0.2s ease;
        }

        .district-card:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .district-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #3498db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .district-card h3 span {
            font-size: 1rem;
            font-weight: 700;
            background-color: #3498db;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
        }

        .bank-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .bank-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .bank-list li:last-child {
            border-bottom: none;
        }

        .bank-name {
            font-weight: 500;
            color: #555;
        }

        .bank-count {
            font-weight: 700;
            color: #3498db;
        }

        .district-status-count {
            font-size: 0.9rem;
            font-style: italic;
            font-weight: 400;
            color: #e74c3c;
            text-align: right;
            margin-top: 1rem;
        }

        .data-section {
            margin-bottom: 2rem;
        }

        .data-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }

        .data-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .data-item {
            display: flex;
            justify-content: space-between;
            background-color: #f9f9f9;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e0e0e0;
        }

        .data-item-label {
            font-weight: 500;
            color: #555;
        }

        .data-item-count {
            font-weight: 700;
            color: #3498db;
        }

        .back-button {
            display: inline-block;
            background-color: #95a5a6;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease;
            margin-top: 1rem;
        }

        .back-button:hover {
            background-color: #7f8c8d;
        }

        @media (max-width: 600px) {
            .summary-card {
                flex-direction: column;
                text-align: center;
            }

            .district-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Statistical Summary 📊</h1>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <i class="fas fa-city card-icon"></i>
                <div class="card-content">
                    <h2><?php echo $total_districts; ?></h2>
                    <p>Total Districts</p>
                </div>
            </div>
            <div class="summary-card">
                <i class="fas fa-server card-icon"></i>
                <div class="card-content">
                    <h2><?php echo $total_machines; ?></h2>
                    <p>Total Machines</p>
                </div>
            </div>
        </div>

        <div class="data-section">
            <h3>Machines by Status</h3>
            <div class="data-list">
                <?php
                if ($machines_by_status_query->rowCount() > 0) {
                    while ($row = $machines_by_status_query->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="data-item">';
                        echo '<span class="data-item-label">' . htmlspecialchars($row['status']) . '</span>';
                        echo '<span class="data-item-count">' . htmlspecialchars($row['machines_count']) . '</span>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No machines found with a status.</p>';
                }
                ?>
            </div>
        </div>

        <div class="data-section">
            <h3>Machines by District & Bank</h3>
            <div class="district-grid">
                <?php
                if ($all_districts_query->rowCount() > 0) {
                    while ($district = $all_districts_query->fetch(PDO::FETCH_ASSOC)) {
                        $district_id = $district['id'];
                        $district_name = htmlspecialchars($district['name']);

                        // Total active machines for the current district
                        $total_active_machines_district_query = $conn->query("SELECT COUNT(*) AS total FROM machines WHERE district_id = $district_id AND status = 'Active'");
                        $total_active_machines_district = $total_active_machines_district_query->fetch(PDO::FETCH_ASSOC)['total'];

                        // Count inactive machines
                        $inactive_count_query = $conn->query("SELECT COUNT(*) AS count FROM machines WHERE district_id = $district_id AND status = 'Inactive'");
                        $inactive_count = $inactive_count_query->fetch(PDO::FETCH_ASSOC)['count'];

                        // Count relocated machines
                        $relocated_count_query = $conn->query("SELECT COUNT(*) AS count FROM machines WHERE district_id = $district_id AND status = 'Relocated'");
                        $relocated_count = $relocated_count_query->fetch(PDO::FETCH_ASSOC)['count'];

                        echo '<div class="district-card">';
                        echo '<h3>' . $district_name . ' <span>' . $total_active_machines_district . ' Machines</span></h3>';
                        echo '<ul class="bank-list">';

                        foreach ($banks as $bank) {
                            $bank_id = $bank['id'];
                            $bank_name = htmlspecialchars($bank['name']);

                            $machines_count_query = $conn->query("SELECT COUNT(*) AS machines_count FROM machines WHERE district_id = $district_id AND bank_id = $bank_id AND status <> 'Deleted'");
                            $machines_count = $machines_count_query->fetch(PDO::FETCH_ASSOC)['machines_count'];

                            echo '<li>';
                            echo '<span class="bank-name">' . $bank_name . '</span>';
                            echo '<span class="bank-count">' . $machines_count . '</span>';
                            echo '</li>';
                        }

                        echo '</ul>';
                        if ($inactive_count > 0 || $relocated_count > 0) {
                            echo '<p class="district-status-count"><i>(';
                            if ($inactive_count > 0) {
                                echo $inactive_count . ' Inactive';
                            }
                            if ($inactive_count > 0 && $relocated_count > 0) {
                                echo ' & ';
                            }
                            if ($relocated_count > 0) {
                                echo $relocated_count . ' Relocated';
                            }
                            echo ')</i></p>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>No districts found.</p>';
                }
                ?>
            </div>
        </div>

        <a href="machine_list.php" class="back-button">Back to Machines List</a>
    </div>
</body>

</html>