<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_signin.php");
    exit();
}

$patientName = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bgimg03_window.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
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

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 15px auto;
            padding: 20px;
            max-width: 800px;
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        ul li:last-child {
            border-bottom: none;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($patientName); ?></h1>
    </header>
    <main>
        <div class="card">
            <h2>Patient Dashboard</h2>
            <p>Select an option to proceed:</p>
            <ul>
                <li>
                    <button onclick="window.location.href='make_appointment.php'">Make Appointments</button>
                </li>
                <li>
                    <button onclick="window.location.href='appointment_notifications.php'">View Appointments</button>
                </li>
                <li>
                    <button onclick="window.location.href='bill_payment.php'">Pay Bills</button>
                </li>
                <li>
                    <button onclick="window.location.href='room_allocation.php'">View Room Allocation</button>
                </li>
                <li>
                    <button onclick="window.location.href='test_reports.php'">View Test Reports</button>
                </li>
                <li>
                    <button onclick="window.location.href='index.php'">Logout</button>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>
