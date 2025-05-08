<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: employee_signin.php");
    exit();
}

include('connect.php');

$doctorName = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $action = $_POST['action']; // Accept or Reject

    $status = ($action == 'accept') ? 'Accepted' : 'Rejected';

    $sql = "UPDATE appointments SET status = '$status' WHERE id = $appointmentId AND doctor_name = '$doctorName'";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Appointment ' . ucfirst($status) . '"); window.location.href="manage_appointments.php";</script>';
    } else {
        echo '<script>alert("Error updating appointment: ' . mysqli_error($conn) . '");</script>';
    }
}

// Query to fetch all appointments for the logged-in doctor
$query = "SELECT * FROM appointments WHERE doctor_name = '$doctorName' ORDER BY status, appointment_date, appointment_time";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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

        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .accept {
            background-color: #28a745;
            color: white;
        }

        .reject {
            background-color: #dc3545;
            color: white;
        }

        .back {
            background-color: #007BFF;
            color: white;
            margin: 20px 0;
            display: block;
            text-align: center;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            max-width: 200px;
            margin: 0 auto;
        }

        .back:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Appointments</h2>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                <td class="status <?php echo strtolower($row['status']); ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                </td>
                <td>
                    <?php if ($row['status'] === 'Pending') { ?>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="accept" class="accept">Accept</button>
                        </form>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="reject" class="reject">Reject</button>
                        </form>
                    <?php } else { ?>
                        No Actions Available
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <a href="doctor_dashboard.php" class="back">Back to Dashboard</a>
    </div>
</body>
</html>
