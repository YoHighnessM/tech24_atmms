<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conn.php';

$banks = $conn->query("SELECT id, name FROM banks");
$districts = $conn->query("SELECT id, name FROM districts");
$technicians = $conn->query("SELECT id, fullname FROM users");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = [
        'bank',
        'branch',
        'form_type',
        'type',
        'district',
        'per_diem',
        'technician',
        'status'
    ];
    $errors = [];
    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === '-') {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
        }
    }

    if (count($errors) === 0) {
        $terminal_number = $_POST['terminal_number'] ?: '-';
        $bank_id = $_POST['bank'];
        $branch = ucwords($_POST['branch']);
        $form_type = $_POST['form_type'];
        $type = $_POST['type'];
        $context = ucwords($_POST['context']);
        $atm_name = $_POST['atm_name'] ?: '-';
        $district_id = $_POST['district'];
        $serial_number = $_POST['serial_number'] ?: '-';
        $per_diem = $_POST['per_diem'];
        $technician_id = $_POST['technician'] ?: '-';
        $coordinates = $_POST['coordinates'] ?: '-';
        $status = $_POST['status'];

        $query = "INSERT INTO machines (
            terminal_number, bank_id, branch, form_type, type, context,
            machine_name, district_id, serial_number, per_diem, technician_id,
            coordinates, status
        ) VALUES (
            '$terminal_number', '$bank_id', '$branch', '$form_type', '$type', '$context',
            '$atm_name', '$district_id', '$serial_number', '$per_diem', '$technician_id',
            '$coordinates', '$status'
        )";

        if ($conn->query($query)) {
            header("Location: machine_reg.php");
            exit();
        } else {
            $errorInfo = $conn->errorInfo();
            echo "Error: " . $errorInfo[2];
        }
    } else {
        foreach ($errors as $error) {
            echo "<div style='color:red;'>$error</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Machine Registration</title>
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
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin: 2rem 0;
        }

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

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .form-row.three-columns .form-group {
            flex-basis: calc(33.333% - 1rem);
            min-width: 180px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 250px;
        }

        .form-group-full-width {
            width: 100%;
        }

        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
            color: #555;
        }

        input[type="text"],
        select {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 0.75rem;
            background-color: #f9f9f9;
            font-size: 0.95rem;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.2s ease;
        }

        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

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

        .btn-primary {
            background-color: #3498db;
            color: #fff;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .header-links {
                flex-direction: column;
                align-items: stretch;
            }

            .header-links a {
                margin: 0.25rem 0;
            }

            form {
                gap: 1rem;
            }

            .form-row,
            .form-row.three-columns {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>Register New Machine</h1>
            </div>
        </div>

        <form method="POST">
            <div class="form-group-full-width">
                <label for="terminal_number">Terminal Number:</label>
                <input type="text" id="terminal_number" name="terminal_number" value="-">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="bank">Bank: <span>*</span></label>
                    <select id="bank" name="bank" required>
                        <option value="" disabled selected>Select Bank</option>
                        <?php while ($b = $banks->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="branch">Branch: <span>*</span></label>
                    <input type="text" id="branch" name="branch" required>
                </div>
            </div>

            <div class="form-row three-columns">
                <div class="form-group">
                    <label for="form_type">Form Type: <span>*</span></label>
                    <select id="form_type" name="form_type" required>
                        <option value="" disabled selected>Select Form Type</option>
                        <option value="TTW">TTW</option>
                        <option value="Lobby">Lobby</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Type: <span>*</span></label>
                    <select id="type" name="type" required>
                        <option value="" disabled selected>Select Machine Type</option>
                        <option value="ATM">ATM</option>
                        <option value="Depositor">Depositor</option>
                        <option value="Recycler">Recycler</option>
                        <option value="STM">STM</option>
                        <option value="VTM">VTM</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="context">Context:</label>
                    <input type="text" id="context" name="context" value="Branch">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="atm_name">ATM Name:</label>
                    <select id="atm_name" name="atm_name">
                        <option value="" disabled selected>Select ATM Name</option>
                        <option value="ATM - 1">ATM - 1</option>
                        <option value="ATM - 2">ATM - 2</option>
                        <option value="ATM - 3">ATM - 3</option>
                        <option value="ATM - 4">ATM - 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">District: <span>*</span></label>
                    <select id="district" name="district" required>
                        <option value="" disabled selected>Select District</option>
                        <?php while ($d = $districts->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group-full-width">
                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" value="-">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Per Diem: <span>*</span></label>
                    <div class="radio-group">
                        <label><input type="radio" name="per_diem" value="Yes" required> Yes</label>
                        <label><input type="radio" name="per_diem" value="No" required> No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="technician">Technician: <span>*</span></label>
                    <select id="technician" name="technician" required>
                        <option value="" disabled selected>Select Technician</option>
                        <?php while ($t = $technicians->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['fullname'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="coordinates">Coordinates:</label>
                    <input type="text" id="coordinates" name="coordinates" value="-">
                </div>
                <div class="form-group">
                    <label for="status">Status: <span>*</span></label>
                    <select id="status" name="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Relocated">Relocated</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="machine_list.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</body>

</html>