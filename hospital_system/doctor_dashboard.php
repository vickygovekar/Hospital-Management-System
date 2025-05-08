<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: employee_signin.php"); 
    exit();
}

$doctorName = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            background-image: url('bgimg04_docbg.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        header {
            background-color: #007BFF;
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
        <h1>Welcome, Dr. <?php echo htmlspecialchars($doctorName); ?></h1>
    </header>
    <main>
        <div class="card">
            <h2>Dashboard</h2>
            <p>Use the options below to manage your activities:</p>
            <ul>
                <li>
                    <button onclick="window.location.href='manage_appointments.php'">View Appointments</button>
                </li>
                <li>
                    <button onclick="window.location.href='manage_patients.php'">Manage Patient Records</button>
                </li>
                <li>
                    <button onclick="window.location.href='prescribe_medicine.php'">Prescribe Medicine</button>
                </li>
                <li>
                    <button onclick="window.location.href='logout.php'">Logout</button>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>
