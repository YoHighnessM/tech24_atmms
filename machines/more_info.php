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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Machine Details</title>
</head>

<body>
    <div>
        <div>
            <div>
                <h1>Machine Details</h1>
            </div>
            <a href="machine_list.php">Back to List</a>
        </div>
        <div>
            <div>
                <div>
                    <div>
                        <div>Basic Information</div>
                        <div>
                            <div>
                                <div>
                                    <div>Terminal Number</div>
                                    <div><?= htmlspecialchars($machine_data['terminal_number']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>
                                    <div>Bank</div>
                                    <div><?= htmlspecialchars($machine_data['bank_name']) ?></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div>Branch</div>
                                    <div><?= htmlspecialchars($machine_data['branch']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>Machine Details</div>
                        <div>
                            <div>
                                <div>
                                    <div>Form Type</div>
                                    <div><?= htmlspecialchars($machine_data['form_type']) ?></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div>Type</div>
                                    <div><?= htmlspecialchars($machine_data['type']) ?></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div>Context</div>
                                    <div><?= htmlspecialchars($machine_data['context']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>
                                    <div>ATM Name</div>
                                    <div><?= htmlspecialchars($machine_data['machine_name']) ?></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div>District</div>
                                    <div><?= htmlspecialchars($machine_data['district_name']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>
                                    <div>Serial Number</div>
                                    <div><?= htmlspecialchars($machine_data['serial_number']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div>Technician & Status</div>
                    <div>
                        <div>
                            <div>
                                <div>Per Diem</div>
                                <div><?= htmlspecialchars($machine_data['per_diem']) ?></div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>Technician</div>
                                <div><?= htmlspecialchars($machine_data['technician_name']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div>
                                <div>Coordinates</div>
                                <div><?= htmlspecialchars($machine_data['coordinates']) ?></div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>Status</div>
                                <div><?= htmlspecialchars($machine_data['status']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>