<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_signin.php");
    exit();
}

include('connect.php'); // Connect to the database
$patientName = $_SESSION['username'];

// Query to fetch appointments for the logged-in patient
$query = "SELECT doctor_name, appointment_time, status 
          FROM appointments 
          WHERE patient_name = '$patientName' 
          ORDER BY appointment_time ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.accepted {
            color: green;
        }

        .status.rejected {
            color: red;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Appointment Notifications</h1>
    </header>
    <main>
        <h2>Hello, <?php echo htmlspecialchars($patientName); ?>!</h2>
        <p>Here are the statuses of your appointments:</p>
        <table>
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $statusClass = strtolower($row['status']); // For styling
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
                        echo "<td class='status $statusClass'>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button onclick="window.location.href='patient_dashboard.php'">Back to Dashboard</button>
    </main>
</body>
</html>
