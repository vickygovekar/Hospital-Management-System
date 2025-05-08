<?php
session_start();

// Ensure only nurses can access this page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Nurse') {
    header("Location: employee_signin.php");
    exit();
}

include('connect.php'); // Database connection

$nurseName = $_SESSION['username']; // Current logged-in nurse's username

// Fetch pending room requests
$query = "
    SELECT rr.id AS request_id, rr.patient_name, p.id AS patient_id, 
           r.id AS room_id, r.room_type, r.cost_per_day 
    FROM room_requests rr
    JOIN patient p ON rr.patient_name = p.name
    JOIN rooms r ON rr.room_id = r.id
    WHERE rr.status = 'Pending'
";
$result = mysqli_query($conn, $query);

// Handle nurse assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    $status = ($action == 'accept') ? 'Accepted' : 'Rejected';

    // Fetch the nurse ID using the session username
    $nurseQuery = "SELECT id FROM employees WHERE name = '$nurseName'";
    $nurseResult = mysqli_query($conn, $nurseQuery);
    if ($nurseRow = mysqli_fetch_assoc($nurseResult)) {
        $nurseId = $nurseRow['id'];
    } else {
        echo "<script>alert('Nurse ID not found. Please check your account.'); window.location.href='nurse_dashboard.php';</script>";
        exit();
    }

    // Update room request status
    $updateQuery = "UPDATE room_requests SET status = '$status', nurse_assigned = '$nurseName' WHERE id = $requestId";
    if (mysqli_query($conn, $updateQuery)) {
        if ($status == 'Accepted') {
            // Fetch room and patient details for assignment
            $roomQuery = "SELECT room_id, patient_name FROM room_requests WHERE id = $requestId";
            $roomResult = mysqli_query($conn, $roomQuery);
            $roomData = mysqli_fetch_assoc($roomResult);

            $roomId = $roomData['room_id'];
            $patientName = $roomData['patient_name'];

            // Fetch patient ID based on patient name
            $patientQuery = "SELECT id FROM patient WHERE name = '$patientName'";
            $patientResult = mysqli_query($conn, $patientQuery);
            $patientData = mysqli_fetch_assoc($patientResult);
            $patientId = $patientData['id'];

            // Insert into room_assignments table
            $assignQuery = "INSERT INTO room_assignments (room_id, nurse_id, patient_id, assigned_date) 
                            VALUES ($roomId, $nurseId, $patientId, CURDATE())";
            if (!mysqli_query($conn, $assignQuery)) {
                echo "<script>alert('Error assigning room: " . mysqli_error($conn) . "');</script>";
            }

            // Mark room as unavailable
            $roomUpdate = "UPDATE rooms SET availability = FALSE WHERE id = $roomId";
            mysqli_query($conn, $roomUpdate);
        }
        echo "<script>alert('Room request $status successfully.'); window.location.href='nurse_room_assign.php';</script>";
    } else {
        echo "<script>alert('Error updating room request: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Room Assignment</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007BFF; color: white; }
        button { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .accept { background-color: #28a745; color: white; }
        .reject { background-color: #dc3545; color: white; }
        .dashboard-btn { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .dashboard-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Room Requests</h2>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Room Type</th>
                <th>Cost per Day</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo htmlspecialchars($row['cost_per_day']); ?></td>
                <td>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                        <button type="submit" name="action" value="accept" class="accept">Accept</button>
                    </form>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                        <button type="submit" name="action" value="reject" class="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        <button class="dashboard-btn" onclick="window.location.href='nurse_dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
