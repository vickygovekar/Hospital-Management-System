<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: employee_signin.php");
    exit();
}

$nurseName = $_SESSION['username'];

// Include database connection
include('connect.php');

// Fetch all rooms and their assignments
$query = "
    SELECT r.id AS room_id, r.room_type, r.cost_per_day, 
           e.name AS nurse_name, p.name AS patient_name, ra.assigned_date 
    FROM rooms r 
    LEFT JOIN room_assignments ra ON r.id = ra.room_id 
    LEFT JOIN employees e ON ra.nurse_id = e.id 
    LEFT JOIN patient p ON ra.patient_id = p.id 
    ORDER BY r.id
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td {
            text-align: center;
        }

        .not-assigned {
            color: red;
            font-weight: bold;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Room Assignment Schedule</h2>
        <p>Below is the schedule of room assignments. Rooms that have not been assigned will show as "Not Assigned."</p>
        <table>
    <thead>
        <tr>
            <th>Room Type</th>
            <th>Cost per Day</th>
            <th>Assigned Nurse</th>
            <th>Patient Name</th>
            <th>Assigned Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['room_type']); ?></td>
            <td><?php echo htmlspecialchars($row['cost_per_day']); ?></td>
            <td>
                <?php 
                if ($row['nurse_name']) {
                    echo htmlspecialchars($row['nurse_name']);
                } else {
                    echo "<span class='not-assigned'>Not Assigned</span>";
                }
                ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row['patient_name'] ?: "N/A"); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row['assigned_date'] ?: "N/A"); ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

        <button onclick="window.location.href='nurse_dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
