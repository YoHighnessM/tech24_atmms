<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../conn.php";

// Fetch Technicians (from users table)
$technicians_query = $conn->query("SELECT id, fullname FROM users WHERE role = 'technician'");
$technicians = $technicians_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch Banks
$banks_query = $conn->query("SELECT id, name FROM banks");
$banks = $banks_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch Districts
$districts_query = $conn->query("SELECT id, name FROM districts");
$districts = $districts_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Daily Report</title>
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
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: border-color 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .checkbox-group input {
            width: auto;
        }

        .btn-submit {
            grid-column: 1 / -1;
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            justify-self: end;
        }

        .btn-submit:hover {
            background-color: #2980b9;
        }

        /* Custom multiselect styles */
        .multiselect {
            position: relative;
        }

        .selectBox {
            position: relative;
        }

        .selectBox select {
            width: 100%;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .overSelect {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }

        #checkboxes {
            display: none;
            border: 1px #dadada solid;
            border-radius: 0.5rem;
            background-color: #fff;
            position: absolute;
            z-index: 10;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
        }

        #checkboxes label {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            transition: background-color 0.2s ease;
        }

        #checkboxes label:hover {
            background-color: #f4f7f9;
        }

        #checkboxes input[type="checkbox"] {
            width: auto;
            /* shrink to fit */
            min-width: 16px;
            /* just enough for the box */
            margin-right: 0.5rem;
            flex: 0 0 auto;
            /* prevent stretching */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Add Daily Report</h1>
        </div>
        <form action="submit_report.php" method="POST" class="form-grid">
            <div class="form-group" style="grid-column: span 3;">
                <label for="technicians_name">Technician's Name</label>
                <div class="multiselect">
                    <div class="selectBox" onclick="showCheckboxes()">
                        <select>
                            <option>Select technicians</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="checkboxes">
                        <?php foreach ($technicians as $tech): ?>
                            <label for="tech_<?= $tech['id'] ?>">
                                <input type="checkbox" id="tech_<?= $tech['id'] ?>" name="technicians_name[]" value="<?= $tech['id'] ?>" onchange="updateSelectedTechnicians()" />
                                <?= htmlspecialchars($tech['fullname']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label for="bank_id">Bank</label>
                <select id="bank_id" name="bank_id" required>
                    <option value="">Select Bank</option>
                    <?php foreach ($banks as $bank): ?>
                        <option value="<?= $bank['id'] ?>"><?= htmlspecialchars($bank['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" required>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label for="district_id">District</label>
                <select id="district_id" name="district_id" required>
                    <option value="">Select District</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?= $district['id'] ?>"><?= htmlspecialchars($district['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="case_registration_type">Case Registration Type</label>
                <select id="case_registration_type" name="case_registration_type" required>
                    <option value="dashboard">Dashboard</option>
                    <option value="from_call">From Call</option>
                    <option value="telegram">Telegram</option>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="case">Case</label>
                <input type="text" id="case" name="case" required>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="registered_date">Registered Date</label>
                <input type="date" id="registered_date" name="registered_date" required>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="registered_time">Registered Time</label>
                <input type="time" id="registered_time" name="registered_time" required>
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="closed_date">Closed Date</label>
                <input type="date" id="closed_date" name="closed_date">
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="closed_time">Closed Time</label>
                <input type="time" id="closed_time" name="closed_time">
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label for="status">Status</label>
                <select id="status" name="status" required onchange="togglePendingFields()">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label for="resolution_method">Resolution Method</label>
                <textarea id="resolution_method" name="resolution_method"></textarea>
            </div>
            <div id="pending_fields" style="display: none; grid-column: 1 / -1; display: contents;">
                <div class="form-group" style="grid-column: span 6;">
                    <label for="completed_by">Completed By</label>
                    <select id="completed_by" name="completed_by">
                        <option value="">Select Technician</option>
                        <?php foreach ($technicians as $tech): ?>
                            <option value="<?= $tech['id'] ?>"><?= htmlspecialchars($tech['fullname']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group full-width">
                <label for="comment">Comment</label>
                <textarea id="comment" name="comment"></textarea>
            </div>
            <div class="form-group checkbox-group" style="grid-column: span 2;">
                <input type="checkbox" id="physical" name="physical" value="1">
                <label for="physical">Physical</label>
            </div>
            <div class="form-group checkbox-group" style="grid-column: span 2;">
                <input type="checkbox" id="phone" name="phone" value="1">
                <label for="phone">Phone</label>
            </div>
            <div class="form-group checkbox-group" style="grid-column: span 2;">
                <input type="checkbox" id="spare_part" name="spare_part" value="1" onchange="togglePartName()">
                <label for="spare_part">Spare Part</label>
            </div>
            <div class="form-group" id="part_name_group" style="display: none; grid-column: 1 / -1;">
                <label for="part_name">Part Name</label>
                <input type="text" id="part_name" name="part_name">
            </div>
            <div class="form-group checkbox-group" style="grid-column: span 2;">
                <input type="checkbox" id="pm" name="pm" value="1">
                <label for="pm">PM</label>
            </div>
            <button type="submit" class="btn-submit">Submit Report</button>
        </form>
    </div>

    <script>
        var expanded = false;

        function showCheckboxes() {
            var checkboxes = document.getElementById("checkboxes");
            if (!expanded) {
                checkboxes.style.display = "block";
                expanded = true;
            } else {
                checkboxes.style.display = "none";
                expanded = false;
            }
        }

        function updateSelectedTechnicians() {
            const checkboxes = document.querySelectorAll('#checkboxes input[type="checkbox"]');
            const selectBox = document.querySelector('.selectBox select');
            const selectedTechnicians = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const label = checkbox.parentElement;
                    selectedTechnicians.push(label.textContent.trim());
                }
            });

            if (selectedTechnicians.length > 0) {
                selectBox.options[0].text = selectedTechnicians.join(', ');
            } else {
                selectBox.options[0].text = 'Select technicians';
            }
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.multiselect')) {
                var checkboxes = document.getElementById("checkboxes");
                if (checkboxes.style.display === 'block') {
                    checkboxes.style.display = 'none';
                    expanded = false;
                }
            }
        });

        function togglePendingFields() {
            const status = document.getElementById('status').value;
            const pendingFields = document.getElementById('pending_fields');
            if (status === 'pending') {
                pendingFields.style.display = 'contents';
            } else {
                pendingFields.style.display = 'none';
            }
        }

        function togglePartName() {
            const sparePart = document.getElementById('spare_part').checked;
            const partNameGroup = document.getElementById('part_name_group');
            if (sparePart) {
                partNameGroup.style.display = 'block';
                partNameGroup.style.gridColumn = 'span 6';
            } else {
                partNameGroup.style.display = 'none';
            }
        }

        // Initialize visibility on page load
        togglePendingFields();
        togglePartName();
    </script>
</body>

</html>