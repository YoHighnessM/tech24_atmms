<?php
session_start();
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
        'atm_name',
        'district',
        'per_diem',
        'status'
    ];
    $errors = [];
    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === '-') {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
        }
    }

    if (count($errors) === 0) {
        $terminal_number = $_POST['terminal_number'];
        $bank_id = $_POST['bank'];
        $branch = $_POST['branch'];
        $form_type = $_POST['form_type'];
        $type = $_POST['type'];
        $context = $_POST['context'];
        $atm_name = $_POST['atm_name'];
        $district_id = $_POST['district'];
        $serial_number = $_POST['serial_number'];
        $per_diem = $_POST['per_diem'];
        $technician_id = $_POST['technician'];
        $coordinates = $_POST['coordinates'];
        $status = $_POST['status'];

        $query = "INSERT INTO machines (
            terminal_number, bank_id, branch, form_type, type, context,
            atm_name, district_id, serial_number, per_diem, technician_id,
            coordinates, status
        ) VALUES (
            '$terminal_number', '$bank_id', '$branch', '$form_type', '$type', '$context',
            '$atm_name', '$district_id', '$serial_number', '$per_diem', '$technician_id',
            '$coordinates', '$status'
        )";

        $result = $conn->query($query);

        if ($result) {
            echo "Machine registered successfully.";
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
<html>

<head>
    <title>Machine Registration</title>
</head>

<body>
    <h2>Register Machine</h2>

    <form method="POST">
        <div>
            <label>Terminal Number:</label>
            <input type="text" name="terminal_number">
        </div>

        <div>
            <label>Bank:</label>
            <select name="bank" required>
                <option value="" disabled selected>Select Bank</option>
                <?php while ($b = $banks->fetch_assoc()): ?>
                    <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Branch:</label>
            <input type="text" name="branch" required>
        </div>

        <div>
            <label>Form Type:</label>
            <select name="form_type" required>
                <option value="" disabled selected>Select Form Type</option>
                <option value="TTW">TTW</option>
                <option value="Lobby">Lobby</option>
            </select>
        </div>

        <div>
            <label>Type:</label>
            <select name="type" required>
                <option value="" disabled selected>Select Machine Type</option>
                <option value="ATM">ATM</option>
                <option value="Depositor">Depositor</option>
                <option value="Recycler">Recycler</option>
                <option value="STM">STM</option>
                <option value="VTM">VTM</option>
            </select>
        </div>

        <div>
            <label>Context:</label>
            <input type="text" name="context" value="Branch">
        </div>

        <div>
            <label>ATM Name:</label>
            <select name="atm_name" required>
                <option value="" disabled selected>Select ATM Name</option>
                <option value="ATM - 1">ATM - 1</option>
                <option value="ATM - 2">ATM - 2</option>
                <option value="ATM - 3">ATM - 3</option>
                <option value="ATM - 4">ATM - 4</option>
            </select>
        </div>

        <div>
            <label>District:</label>
            <select name="district" required>
                <option value="" disabled selected>Select District</option>
                <?php while ($d = $districts->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Serial Number:</label>
            <input type="text" name="serial_number" value="-">
        </div>

        <div>
            <label>Per Diem:</label>
            <label><input type="radio" name="per_diem" value="Yes" required> Yes</label>
            <label><input type="radio" name="per_diem" value="No" required> No</label>
        </div>

        <div>
            <label>Technician:</label>
            <select name="technician" required>
                <option value="" disabled selected>Select Technician</option>
                <?php while ($t = $technicians->fetch_assoc()): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['fullname'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Coordinates:</label>
            <input type="text" name="coordinates" value="-">
        </div>

        <div>
            <label>Status:</label>
            <select name="status" required>
                <option value="" disabled selected>Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Relocated">Relocated</option>
            </select>
        </div>

        <button type="submit">Register</button>
    </form>
</body>

</html>