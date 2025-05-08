<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_signin.php");
    exit();
}

include('connect.php'); // Database connection

$patientName = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctorName = $_POST['doctor_name'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    $sql = "INSERT INTO appointments (patient_name, doctor_name, appointment_date, appointment_time, status)
            VALUES ('$patientName', '$doctorName', '$appointmentDate', '$appointmentTime', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Appointment requested successfully!"); window.location.href="patient_dashboard.php";</script>';
    } else {
        echo '<script>alert("Error requesting appointment: ' . mysqli_error($conn) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Request an Appointment</h2>
        <form action="" method="POST">
            <label for="doctor_name">Select Doctor:</label>
            <select name="doctor_name" id="doctor_name" required>
                <?php
                // Fetch list of doctors
                $query = "SELECT name FROM employees WHERE role = 'Doctor'";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                }
                ?>
            </select>

            <label for="appointment_date">Select Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" required>

            <label for="appointment_time">Select Time:</label>
                <select name="appointment_time" id="appointment_time" required>
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                </select>


            <button type="submit">Request Appointment</button>
        </form>
    </div>
</body>
</html>
