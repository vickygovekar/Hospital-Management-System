<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_signin.php");
    exit();
}

include('connect.php');

$patientName = $_SESSION['username'];

// Fetch rooms that are available and not already assigned to the patient
$query = "
    SELECT r.*
    FROM rooms r
    LEFT JOIN room_assignments ra ON r.id = ra.room_id AND ra.patient_id = (SELECT patient_id FROM patient WHERE name = '$patientName')
    WHERE r.availability = TRUE
";
$result = mysqli_query($conn, $query);

// Handle room request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['room_id'])) {
    $roomId = $_POST['room_id'];

    // Insert room request
    $insertQuery = "INSERT INTO room_requests (patient_name, room_id) VALUES ('$patientName', $roomId)";
    if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('Room request sent successfully.'); window.location.href='room_allocation.php';</script>";
    } else {
        echo "<script>alert('Error requesting room: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Allocation</title>
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
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .dashboard-button {
            margin-top: 20px;
            background-color: #007BFF;
        }

        .dashboard-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Room Allocation</h2>
        <p>Select a room to request:</p>
        <table>
            <tr>
                <th>Room Type</th>
                <th>Cost per Day</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo htmlspecialchars($row['cost_per_day']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="room_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Request Room</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        <!-- Dashboard Button -->
        <form action="patient_dashboard.php">
            <button type="submit" class="dashboard-button">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>
