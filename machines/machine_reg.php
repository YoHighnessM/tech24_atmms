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
</head>

<body>
    <div>
        <div>
            <h1>Register New Machine</h1>
            <a href="machine_list.php">Cancel</a>
        </div>
        <form method="POST">
            <div>
                <div>
                    <label for="terminal_number">Terminal Number:</label>
                    <input type="text" id="terminal_number" name="terminal_number">
                </div>
            </div>

            <div>
                <div>
                    <label for="bank">Bank: <span>*</span></label>
                    <select id="bank" name="bank" required>
                        <option value="" disabled selected>Select Bank</option>
                        <?php while ($b = $banks->fetch_assoc()): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="branch">Branch: <span>*</span></label>
                    <input type="text" id="branch" name="branch" required>
                </div>
            </div>

            <div>
                <div>
                    <label for="form_type">Form Type: <span>*</span></label>
                    <select id="form_type" name="form_type" required>
                        <option value="" disabled selected>Select Form Type</option>
                        <option value="TTW">TTW</option>
                        <option value="Lobby">Lobby</option>
                    </select>
                </div>
                <div>
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
                <div>
                    <label for="context">Context:</label>
                    <input type="text" id="context" name="context" value="Branch">
                </div>
            </div>

            <div>
                <div>
                    <label for="atm_name">ATM Name:</label>
                    <select id="atm_name" name="atm_name">
                        <option value="" disabled selected>Select ATM Name</option>
                        <option value="ATM - 1">ATM - 1</option>
                        <option value="ATM - 2">ATM - 2</option>
                        <option value="ATM - 3">ATM - 3</option>
                        <option value="ATM - 4">ATM - 4</option>
                    </select>
                </div>
                <div>
                    <label for="district">District: <span>*</span></label>
                    <select id="district" name="district" required>
                        <option value="" disabled selected>Select District</option>
                        <?php while ($d = $districts->fetch_assoc()): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div>
                <div>
                    <label for="serial_number">Serial Number:</label>
                    <input type="text" id="serial_number" name="serial_number" value="-">
                </div>
            </div>

            <div>
                <div>
                    <label>Per Diem: <span>*</span></label>
                    <div>
                        <div>
                            <input type="radio" name="per_diem" id="per_diem_yes" value="Yes" required>
                            <label for="per_diem_yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="per_diem" id="per_diem_no" value="No" required>
                            <label for="per_diem_no">No</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="technician">Technician: <span>*</span></label>
                    <select id="technician" name="technician" required>
                        <option value="" disabled selected>Select Technician</option>
                        <?php while ($t = $technicians->fetch_assoc()): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['fullname'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="status">Status: <span>*</span></label>
                    <select id="status" name="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Relocated">Relocated</option>
                    </select>
                </div>
            </div>

            <div>
                <div>
                    <label for="coordinates">Coordinates:</label>
                    <input type="text" id="coordinates" name="coordinates" value="-">
                </div>
            </div>

            <div>
                <button type="button" onclick="window.location.href='machine_list.php'">Cancel</button>
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
</body>

</html>