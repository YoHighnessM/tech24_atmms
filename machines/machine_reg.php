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
        $terminal_number = !empty($_POST['terminal_number']) ? $_POST['terminal_number'] : '-';
        $bank_id = $_POST['bank'];
        $branch = ucwords($_POST['branch']);
        $form_type = $_POST['form_type'];
        $type = $_POST['type'];
        $context = ucwords($_POST['context']);
        $atm_name = !empty($_POST['atm_name']) ? $_POST['atm_name'] : '-';
        $district_id = $_POST['district'];
        $serial_number = !empty($_POST['serial_number']) ? $_POST['serial_number'] : '-';
        $per_diem = $_POST['per_diem'];
        $technician_id = !empty($_POST['technician']) ? $_POST['technician'] : '-';
        $coordinates = !empty($_POST['coordinates']) ? $_POST['coordinates'] : '-';
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

        $result = $conn->query($query);

        if ($result) {
            echo "Machine registered successfully.";
            header("Location: machine_reg.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Registration</title>
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
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow-x: hidden;
        }

        .card {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border: none;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 800px;
            padding: 2rem;
            background-color: #fff;
            margin: 2rem 0;
        }

        h2,
        .btn {
            font-family: 'Inter', sans-serif;
        }

        .form-label,
        .form-control,
        .form-select,
        .form-check-label {
            font-family: 'Open Sans', sans-serif;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 1rem;
        }

        .card-title {
            color: #343a40;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.25rem;
        }

        .btn {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004a9e;
        }

        .btn-back {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            font-weight: 500;
        }

        .btn-back:hover {
            background-color: #e2e6ea;
            border-color: #dae0e5;
            color: #343a40;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-section-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        .form-section {
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header-container">
            <h1 class="card-title">Register New Machine</h1>
            <a href="machine_list.php" class="btn btn-back"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cancel</a>
        </div>
        <form method="POST">
            <div class="row">
                <div class="col-12 form-group">
                    <label for="terminal_number" class="form-label">Terminal Number:</label>
                    <input type="text" class="form-control" id="terminal_number" name="terminal_number">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="bank" class="form-label">Bank: <span class="text-danger">*</span></label>
                    <select class="form-select" id="bank" name="bank" required>
                        <option value="" disabled selected>Select Bank</option>
                        <?php while ($b = $banks->fetch_assoc()): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="branch" class="form-label">Branch: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="branch" name="branch" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="form_type" class="form-label">Form Type: <span class="text-danger">*</span></label>
                    <select class="form-select" id="form_type" name="form_type" required>
                        <option value="" disabled selected>Select Form Type</option>
                        <option value="TTW">TTW</option>
                        <option value="Lobby">Lobby</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="type" class="form-label">Type: <span class="text-danger">*</span></label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" disabled selected>Select Machine Type</option>
                        <option value="ATM">ATM</option>
                        <option value="Depositor">Depositor</option>
                        <option value="Recycler">Recycler</option>
                        <option value="STM">STM</option>
                        <option value="VTM">VTM</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="context" class="form-label">Context:</label>
                    <input type="text" class="form-control" id="context" name="context" value="Branch">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="atm_name" class="form-label">ATM Name:</label>
                    <select class="form-select" id="atm_name" name="atm_name">
                        <option value="" disabled selected>Select ATM Name</option>
                        <option value="ATM - 1">ATM - 1</option>
                        <option value="ATM - 2">ATM - 2</option>
                        <option value="ATM - 3">ATM - 3</option>
                        <option value="ATM - 4">ATM - 4</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="district" class="form-label">District: <span class="text-danger">*</span></label>
                    <select class="form-select" id="district" name="district" required>
                        <option value="" disabled selected>Select District</option>
                        <?php while ($d = $districts->fetch_assoc()): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    <label for="serial_number" class="form-label">Serial Number:</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" value="-">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="form-label">Per Diem: <span class="text-danger">*</span></label>
                    <div class="d-flex radio-group mt-2">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="radio" name="per_diem" id="per_diem_yes" value="Yes" required>
                            <label class="form-check-label" for="per_diem_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="per_diem" id="per_diem_no" value="No" required>
                            <label class="form-check-label" for="per_diem_no">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label for="technician" class="form-label">Technician: <span class="text-danger">*</span></label>
                    <select class="form-select" id="technician" name="technician" required>
                        <option value="" disabled selected>Select Technician</option>
                        <?php while ($t = $technicians->fetch_assoc()): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['fullname'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="status" class="form-label">Status: <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Relocated">Relocated</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    <label for="coordinates" class="form-label">Coordinates:</label>
                    <input type="text" class="form-control" id="coordinates" name="coordinates" value="-">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 form-group mb-0">
                <button type="button" class="btn btn-back" onclick="window.location.href='machine_list.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>