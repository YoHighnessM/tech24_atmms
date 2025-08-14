<?php
session_start();
include "../conn.php";

// Check if an ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: machine_list.php");
    exit();
}

$machine_id = $_GET['id'];

// **SECURITY WARNING**: This code uses "bare PHP" for testing as requested.
// This is HIGHLY VULNERABLE to SQL injection attacks and is UNSAFE for production use.
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
    // Machine not found, redirect back to the list
    header("Location: machine_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Machine Details</title>
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
            overflow-x: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8fafc;
        }

        .card {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border-radius: 0.5rem;
            width: 100%;
            padding: 2rem;
            background-color: #fff;
            margin: 2rem 0;
        }

        .main-container {
            padding-left: 2rem;
            padding-right: 2rem;
        }

        h2,
        .btn {
            font-family: 'Inter', sans-serif;
        }

        .card-title,
        .form-label,
        .detail-label,
        .detail-value {
            font-family: 'Open Sans', sans-serif;
        }

        .card-title {
            color: #343a40;
            font-weight: 600;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 1rem;
        }

        .header-text-left h1 {
            margin: 0;
        }

        .btn-back {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            font-weight: 500;
        }

        .detail-row {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #6c757d;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            background-color: #f8f9fa;
            word-wrap: break-word;
        }

        /* Styling for sections to match registration page */
        .form-section {
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .form-section-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        .no-border-top {
            border-top: none;
            margin-top: 0;
            padding-top: 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid main-container">
        <div class="card">
            <div class="header-container">
                <div class="header-text-left">
                    <h1 class="card-title">Machine Details</h1>
                </div>
                <a href="machine_list.php" class="btn btn-back"><i class="bi bi-arrow-left-circle-fill me-2"></i>Back to List</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section-title no-border-top">Basic Information</div>
                        <div class="row">
                            <div class="col-12">
                                <div class="detail-row">
                                    <div class="detail-label">Terminal Number</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['terminal_number']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Bank</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['bank_name']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Branch</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['branch']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-section-title no-border-top">Machine Details</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="detail-row">
                                    <div class="detail-label">Form Type</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['form_type']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-row">
                                    <div class="detail-label">Type</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['type']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-row">
                                    <div class="detail-label">Context</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['context']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">ATM Name</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['machine_name']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">District</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['district_name']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="detail-row">
                                    <div class="detail-label">Serial Number</div>
                                    <div class="detail-value"><?= htmlspecialchars($machine_data['serial_number']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Technician & Status</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Per Diem</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['per_diem']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Technician</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['technician_name']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Coordinates</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['coordinates']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Status</div>
                                <div class="detail-value"><?= htmlspecialchars($machine_data['status']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>