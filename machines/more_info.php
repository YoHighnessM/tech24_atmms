<?php
session_start();
include "../conn.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: machine_list.php");
    exit();
}

$machine_id = $_GET['id'];

$query = "
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
        m.id = '$machine_id'
";

$result = $conn->query($query);
$machine_data = $result->fetch_assoc();

if (!$machine_data) {
    header("Location: machine_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Machine Details</title>
    <!-- Google Fonts for a modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <style>
        /* General body and container styling, consistent with machine_list.php */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Aligned to the top for better content display */
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            /* Increased max-width for a wider view */
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        /* Header section for the page title and back button */
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

        /* Button styling consistent with the other pages */
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s ease;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        /* Card styling for each section */
        .card-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* New class for side-by-side cards */
        .side-by-side-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .card-header {
            background-color: #f9f9f9;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            color: #34495e;
        }

        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .side-by-side-cards {
                grid-template-columns: 1fr;
                /* Stack cards on smaller screens */
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>Machine Details</h1>
            </div>
            <a href="machine_list.php" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-container">
            <!-- Basic Information Card - remains full width -->
            <div class="card">
                <div class="card-header">
                    <h2>Basic Information</h2>
                </div>
                <div class="card-body">
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Terminal Number</div>
                            <div class="detail-value"><?= htmlspecialchars($machine_data['terminal_number']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Bank</div>
                            <div class="detail-value"><?= htmlspecialchars($machine_data['bank_name']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Branch</div>
                            <div class="detail-value"><?= htmlspecialchars($machine_data['branch']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Machine Details and Technician & Status cards placed side-by-side -->
            <div class="side-by-side-cards">
                <!-- Machine Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h2>Machine Specifications</h2>
                    </div>
                    <div class="card-body">
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Form Type</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['form_type']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Type</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['type']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Context</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['context']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">ATM Name</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['machine_name']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">District</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['district_name']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Serial Number</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['serial_number']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technician & Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h2>Technician & Status</h2>
                    </div>
                    <div class="card-body">
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Per Diem</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['per_diem']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Technician</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['technician_name']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Coordinates</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['coordinates']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['status']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>