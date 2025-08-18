<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: machine_list.php");
    exit();
}

$machine_id = $_GET['id'];

$query = "
    SELECT 
        m.*,
        b.id AS bank_id,
        d.id AS district_id,
        u.id AS technician_id
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
$machine_data = $result->fetch(PDO::FETCH_ASSOC);

if (!$machine_data) {
    echo "Machine not found.";
    exit();
}

$banks = $conn->query("SELECT id, name FROM banks");
$districts = $conn->query("SELECT id, name FROM districts");
$technicians = $conn->query("SELECT id, fullname FROM users");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $update_query = "
        UPDATE machines
        SET
            terminal_number = '$terminal_number',
            bank_id = '$bank_id',
            branch = '$branch',
            form_type = '$form_type',
            type = '$type',
            context = '$context',
            machine_name = '$atm_name',
            district_id = '$district_id',
            serial_number = '$serial_number',
            per_diem = '$per_diem',
            technician_id = '$technician_id',
            coordinates = '$coordinates',
            status = '$status'
        WHERE
            id = '$machine_id'
    ";

    if ($conn->query($update_query)) {
        header("Location: machine_list.php");
        exit();
    } else {
        $errorInfo = $conn->errorInfo();
        echo "Error: " . $errorInfo[2];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Machine</title>
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
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
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

        .btn-danger {
            background-color: #e74c3c;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger:hover {
            background-color: #c0392b;
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
                <h1>Edit Machine Details</h1>
            </div>
            <a href="machine_list.php" class="btn btn-secondary">Back to List</a>
        </div>

        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($machine_id) ?>">

            <div class="form-group-full-width">
                <label for="terminal_number">Terminal Number:</label>
                <input type="text" id="terminal_number" name="terminal_number" value="<?= htmlspecialchars($machine_data['terminal_number']) ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="bank">Bank: <span>*</span></label>
                    <select id="bank" name="bank" required>
                        <option value="" disabled>Select Bank</option>
                        <?php while ($b = $banks->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $b['id'] ?>" <?= ($machine_data['bank_id'] == $b['id']) ? 'selected' : '' ?>>
                                <?= $b['name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="branch">Branch: <span>*</span></label>
                    <input type="text" id="branch" name="branch" value="<?= htmlspecialchars($machine_data['branch']) ?>" required>
                </div>
            </div>

            <div class="form-row three-columns">
                <div class="form-group">
                    <label for="form_type">Form Type: <span>*</span></label>
                    <select id="form_type" name="form_type" required>
                        <option value="" disabled>Select Form Type</option>
                        <option value="TTW" <?= ($machine_data['form_type'] === 'TTW') ? 'selected' : '' ?>>TTW</option>
                        <option value="Lobby" <?= ($machine_data['form_type'] === 'Lobby') ? 'selected' : '' ?>>Lobby</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Type: <span>*</span></label>
                    <select id="type" name="type" required>
                        <option value="" disabled>Select Machine Type</option>
                        <option value="ATM" <?= ($machine_data['type'] === 'ATM') ? 'selected' : '' ?>>ATM</option>
                        <option value="Depositor" <?= ($machine_data['type'] === 'Depositor') ? 'selected' : '' ?>>Depositor</option>
                        <option value="Recycler" <?= ($machine_data['type'] === 'Recycler') ? 'selected' : '' ?>>Recycler</option>
                        <option value="STM" <?= ($machine_data['type'] === 'STM') ? 'selected' : '' ?>>STM</option>
                        <option value="VTM" <?= ($machine_data['type'] === 'VTM') ? 'selected' : '' ?>>VTM</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="context">Context:</label>
                    <input type="text" id="context" name="context" value="<?= htmlspecialchars($machine_data['context']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="atm_name">ATM Name:</label>
                    <select id="atm_name" name="atm_name">
                        <option value="" <?= ($machine_data['machine_name'] === '-') ? 'selected' : '' ?>>Select ATM Name</option>
                        <option value="ATM - 1" <?= ($machine_data['machine_name'] === 'ATM - 1') ? 'selected' : '' ?>>ATM - 1</option>
                        <option value="ATM - 2" <?= ($machine_data['machine_name'] === 'ATM - 2') ? 'selected' : '' ?>>ATM - 2</option>
                        <option value="ATM - 3" <?= ($machine_data['machine_name'] === 'ATM - 3') ? 'selected' : '' ?>>ATM - 3</option>
                        <option value="ATM - 4" <?= ($machine_data['machine_name'] === 'ATM - 4') ? 'selected' : '' ?>>ATM - 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">District: <span>*</span></label>
                    <select id="district" name="district" required>
                        <option value="" disabled>Select District</option>
                        <?php
                        $districts->execute();
                        while ($d = $districts->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $d['id'] ?>" <?= ($machine_data['district_id'] == $d['id']) ? 'selected' : '' ?>>
                                <?= $d['name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group-full-width">
                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" value="<?= htmlspecialchars($machine_data['serial_number']) ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Per Diem: <span>*</span></label>
                    <div class="radio-group">
                        <label><input type="radio" name="per_diem" value="Yes" <?= ($machine_data['per_diem'] === 'Yes') ? 'checked' : '' ?> required> Yes</label>
                        <label><input type="radio" name="per_diem" value="No" <?= ($machine_data['per_diem'] === 'No') ? 'checked' : '' ?> required> No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="technician">Technician: <span>*</span></label>
                    <select id="technician" name="technician" required>
                        <option value="" disabled>Select Technician</option>
                        <?php
                        $technicians->execute();
                        while ($t = $technicians->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $t['id'] ?>" <?= ($machine_data['technician_id'] == $t['id']) ? 'selected' : '' ?>>
                                <?= $t['fullname'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="coordinates">Coordinates:</label>
                    <input type="text" id="coordinates" name="coordinates" value="<?= htmlspecialchars($machine_data['coordinates']) ?>">
                </div>
                <div class="form-group">
                    <label for="status">Status: <span>*</span></label>
                    <select id="status" name="status" required>
                        <option value="" disabled>Select Status</option>
                        <option value="Active" <?= ($machine_data['status'] === 'Active') ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= ($machine_data['status'] === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="Relocated" <?= ($machine_data['status'] === 'Relocated') ? 'selected' : '' ?>>Relocated</option>
                        <option value="Deleted" <?= ($machine_data['status'] === 'Deleted') ? 'selected' : '' ?>>Deleted</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="machine_list.php" class="btn btn-secondary">Cancel</a>
                <a href="machine_delete.php?id=<?= htmlspecialchars($machine_data['id']) ?>" class="btn btn-danger">Delete</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</body>

</html>