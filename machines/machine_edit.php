<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conn.php';

session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: machine_list.php");
    exit();
}

$machine_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $terminal_number = $_POST['terminal_number'] ?? '-';
    $bank_id = $_POST['bank_id'];
    $branch = ucwords($_POST['branch']);
    $district_id = $_POST['district_id'];
    $form_type = $_POST['form_type'];
    $type = $_POST['type'];
    $context = ucwords($_POST['context']);
    $machine_name = $_POST['machine_name'] ?? '-';
    $serial_number = $_POST['serial_number'] ?? '-';
    $ip_address = $_POST['ip_address'] ?? '-';
    $subnet_mask = $_POST['subnet_mask'] ?? '-';
    $default_gateway = $_POST['default_gateway'] ?? '-';
    $port_number = $_POST['port_number'] ?? '-';
    $coordinates = $_POST['coordinates'] ?? '-';
    $technician_id = $_POST['technician_id'];
    $per_diem = $_POST['per_diem'];
    $status = $_POST['status'];

    $update_query = "
        UPDATE machines
        SET
            terminal_number = '$terminal_number',
            bank_id = '$bank_id',
            branch = '$branch',
            district_id = '$district_id',
            form_type = '$form_type',
            type = '$type',
            context = '$context',
            machine_name = '$machine_name',
            serial_number = '$serial_number',
            ip_address = '$ip_address',
            subnet_mask = '$subnet_mask',
            default_gateway = '$default_gateway',
            port_number = '$port_number',
            coordinates = '$coordinates',
            technician_id = '$technician_id',
            per_diem = '$per_diem',
            status = '$status',
            updated_at = CURRENT_TIMESTAMP
        WHERE
            id = '$machine_id'
    ";

    if ($conn->query($update_query)) {
        header("Location: more_info.php?id=" . $machine_id);
        exit();
    } else {
        $errorInfo = $conn->errorInfo();
        echo "Error: " . $errorInfo[2];
    }
}

$query = "SELECT * FROM machines WHERE id = '$machine_id'";
$result = $conn->query($query);
$machine_data = $result->fetch(PDO::FETCH_ASSOC);

if (!$machine_data) {
    echo "Machine not found.";
    exit();
}

$banks = $conn->query("SELECT id, name FROM banks ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$districts = $conn->query("SELECT id, name FROM districts ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$technicians = $conn->query("SELECT id, fullname FROM users WHERE role = 'technician' ORDER BY fullname")->fetchAll(PDO::FETCH_ASSOC);

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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
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

        .card-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

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
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
        }

        input[type="text"],
        select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }

        @media (max-width: 992px) {
            .side-by-side-cards {
                grid-template-columns: 1fr;
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
            <a href="more_info.php?id=<?= htmlspecialchars($machine_id) ?>" class="btn btn-secondary">Cancel</a>
        </div>

        <form method="POST">
            <div class="card-container">
                <div class="card">
                    <div class="card-header">
                        <h2>Basic Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="terminal_number">Terminal Number</label>
                                <input type="text" id="terminal_number" name="terminal_number" value="<?= htmlspecialchars($machine_data['terminal_number']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="bank_id">Bank</label>
                                <select id="bank_id" name="bank_id" required>
                                    <?php foreach ($banks as $bank) : ?>
                                        <option value="<?= $bank['id'] ?>" <?= ($machine_data['bank_id'] == $bank['id']) ? 'selected' : '' ?>><?= htmlspecialchars($bank['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <input type="text" id="branch" name="branch" value="<?= htmlspecialchars($machine_data['branch']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="district_id">District</label>
                                <select id="district_id" name="district_id" required>
                                    <?php foreach ($districts as $district) : ?>
                                        <option value="<?= $district['id'] ?>" <?= ($machine_data['district_id'] == $district['id']) ? 'selected' : '' ?>><?= htmlspecialchars($district['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="side-by-side-cards">
                    <div class="card">
                        <div class="card-header">
                            <h2>Machine Specifications</h2>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="form_type">Form Type</label>
                                    <select id="form_type" name="form_type" required>
                                        <option value="Lobby" <?= ($machine_data['form_type'] == 'Lobby') ? 'selected' : '' ?>>Lobby</option>
                                        <option value="TTW" <?= ($machine_data['form_type'] == 'TTW') ? 'selected' : '' ?>>TTW</option>
                                        <option value="-" <?= ($machine_data['form_type'] == '-') ? 'selected' : '' ?>>-</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <input type="text" id="type" name="type" value="<?= htmlspecialchars($machine_data['type']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="context">Context</label>
                                    <input type="text" id="context" name="context" value="<?= htmlspecialchars($machine_data['context']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="machine_name">ATM Name</label>
                                    <input type="text" id="machine_name" name="machine_name" value="<?= htmlspecialchars($machine_data['machine_name']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="serial_number">Serial Number</label>
                                    <input type="text" id="serial_number" name="serial_number" value="<?= htmlspecialchars($machine_data['serial_number']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2>Network Configuration</h2>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="ip_address">IP Address</label>
                                    <input type="text" id="ip_address" name="ip_address" value="<?= htmlspecialchars($machine_data['ip_address']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="subnet_mask">Subnet Mask</label>
                                    <input type="text" id="subnet_mask" name="subnet_mask" value="<?= htmlspecialchars($machine_data['subnet_mask']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="default_gateway">Gateway</label>
                                    <input type="text" id="default_gateway" name="default_gateway" value="<?= htmlspecialchars($machine_data['default_gateway']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="port_number">Port Number</label>
                                    <input type="text" id="port_number" name="port_number" value="<?= htmlspecialchars($machine_data['port_number']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="side-by-side-cards">
                    <div class="card">
                        <div class="card-header">
                            <h2>Location & Contact</h2>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="coordinates">Coordinates</label>
                                    <input type="text" id="coordinates" name="coordinates" value="<?= htmlspecialchars($machine_data['coordinates']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2>Technician & Maintenance</h2>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="technician_id">Technician Name</label>
                                    <select id="technician_id" name="technician_id" required>
                                        <option value="" disabled selected>Choose Technician</option>
                                        <?php foreach ($technicians as $technician) : ?>
                                            <option value="<?= $technician['id'] ?>" <?= ($machine_data['technician_id'] == $technician['id']) ? 'selected' : '' ?>><?= htmlspecialchars($technician['fullname']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="per_diem">Per Diem</label>
                                    <select id="per_diem" name="per_diem" required>
                                        <option value="Yes" <?= ($machine_data['per_diem'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                        <option value="No" <?= ($machine_data['per_diem'] == 'No') ? 'selected' : '' ?>>No</option>
                                        <option value="-" <?= ($machine_data['per_diem'] == '-') ? 'selected' : '' ?>>-</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" required>
                                        <option value="Active" <?= ($machine_data['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                                        <option value="Inactive" <?= ($machine_data['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                                        <option value="Relocated" <?= ($machine_data['status'] == 'Relocated') ? 'selected' : '' ?>>Relocated</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</body>

</html>