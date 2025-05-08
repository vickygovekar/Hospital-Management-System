<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: employee_signin.php"); 
    exit();
}

$nurseName = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            background-image: url('bgimg05_nursebg.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        header {
            background-color: #28a745;
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
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, Nurse <?php echo htmlspecialchars($nurseName); ?></h1>
    </header>
    <main>
        <div class="card">
            <h2>Dashboard</h2>
            <p>Use the options below to manage your tasks:</p>
            <ul>
                <li>
                    <button onclick="window.location.href='view_schedule.php'">View Room Schedules</button>
                </li>
                <li>
                    <button onclick="window.location.href='nurse_room_assign.php'">View Room Assignments</button>
                </li>
                <li>
                    <button onclick="window.location.href='index.php'">Logout</button>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>
