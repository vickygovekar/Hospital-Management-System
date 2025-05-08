<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_signin.php");
    exit();
}

include('connect.php');

$patientName = $_SESSION['username'];

// Fetch patient ID based on the logged-in patient's username
$query = "SELECT id FROM patient WHERE name = '$patientName'";
$result = mysqli_query($conn, $query);
$patient = mysqli_fetch_assoc($result);
$patientId = $patient['id'];

// Fetch existing test report for the patient using patient_id
$query = "SELECT tr.*, p.name AS patient_name FROM test_reports tr
          JOIN patient p ON p.id = tr.patient_id
          WHERE tr.patient_id = '$patientId' ORDER BY tr.report_date DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$testReport = mysqli_fetch_assoc($result);

// Handle request for a new test report (overwrite existing)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_new_report'])) {
    // Generate a new report (you can include any logic to generate the test report data)
    $newReportDate = date('Y-m-d H:i:s');
    $bloodPressure = rand(110, 140) . "/" . rand(70, 90);  // Example data generation
    $bloodSugar = rand(80, 120);  // Example data generation
    $cholesterol = rand(150, 200);  // Example data generation

    // Insert the new report and overwrite the old one
    $insertQuery = "INSERT INTO test_reports (patient_id, report_date, blood_pressure, blood_sugar, cholesterol)
                    VALUES ('$patientId', '$newReportDate', '$bloodPressure', $bloodSugar, $cholesterol)";
    
    if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('New test report generated successfully.'); window.location.href='test_reports.php';</script>";
    } else {
        echo "<script>alert('Error generating new test report: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reports</title>
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
        <h2>Test Report</h2>

        <!-- Check if there is an existing test report -->
        <?php if ($testReport) { ?>
        <h3>Current Test Report for <?php echo htmlspecialchars($testReport['patient_name']); ?>:</h3>
        <table>
            <tr>
                <th>Report Date</th>
                <th>Blood Pressure</th>
                <th>Blood Sugar</th>
                <th>Cholesterol</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($testReport['report_date']); ?></td>
                <td><?php echo htmlspecialchars($testReport['blood_pressure']); ?></td>
                <td><?php echo htmlspecialchars($testReport['blood_sugar']); ?></td>
                <td><?php echo htmlspecialchars($testReport['cholesterol']); ?></td>
            </tr>
        </table>
        <?php } else { ?>
        <p>No test reports available. Please request a new report.</p>
        <?php } ?>

        <!-- Request a new test report -->
        <form method="POST" action="">
            <button type="submit" name="request_new_report">Request New Test Report</button>
        </form>

        <!-- Dashboard Button -->
        <form action="patient_dashboard.php">
            <button type="submit" class="dashboard-button">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>
