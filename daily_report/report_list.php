<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daily Report</title>
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
            white-space: nowrap;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>Daily Report</h1>
            </div>
            <div class="header-buttons">
                <a href="report_reg.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Report
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Technician's Name</th>
                        <th>Date</th>
                        <th>Bank</th>
                        <th>Branch</th>
                        <th>District</th>
                        <th>Case Reg. Type</th>
                        <th>Registered Date</th>
                        <th>Registered Time</th>
                        <th>Closed Date</th>
                        <th>Closed Time</th>
                        <th>Status</th>
                        <th>Resolution Method</th>
                        <th>Completed By</th>
                        <th>Comment</th>
                        <th>Physical</th>
                        <th>Phone</th>
                        <th>Spare Part</th>
                        <th>Part Name</th>
                        <th>PM</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body will be populated with data later -->
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>