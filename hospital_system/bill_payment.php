<?php
session_start();
// Include database connection
include('connect.php');

// Fetch patient name from the session
$patient_name = $_SESSION['username']; // Assuming 'username' is the patient name in the session

// Fetch patient ID for the logged-in patient
$patient_id_query = "SELECT id FROM patient WHERE name = '$patient_name'";
$patient_id_result = mysqli_query($conn, $patient_id_query);
$patient_id_data = mysqli_fetch_assoc($patient_id_result);

if ($patient_id_data) {
    $patient_id = $patient_id_data['id'];

    // Fetch prescription total price for the patient using a JOIN with prescriptions
    $prescription_query = "
        SELECT SUM(p.total_price) AS total_prescription_price
        FROM prescriptions p
        JOIN patient pt ON p.patient_id = pt.id
        WHERE pt.id = '$patient_id'
    ";
    $prescription_result = mysqli_query($conn, $prescription_query);
    $prescription_data = mysqli_fetch_assoc($prescription_result);
    $total_prescription_price = $prescription_data['total_prescription_price'] ?? 0;

    // Fetch room allocation details and calculate room charges using a JOIN
    $room_query = "
        SELECT ra.assigned_date, r.id AS room_id, r.cost_per_day
        FROM room_assignments ra
        JOIN rooms r ON ra.room_id = r.id
        WHERE ra.patient_id = '$patient_id'
    ";
    $room_result = mysqli_query($conn, $room_query);
    $room_data = mysqli_fetch_assoc($room_result);

    if ($room_data) {
        $allocation_date = $room_data['assigned_date'];
        $room_id = $room_data['room_id'];
        $room_charge_per_day = $room_data['cost_per_day'];

        // Calculate days stayed (current date - allocation date)
        $days_stayed = (strtotime(date('Y-m-d')) - strtotime($allocation_date)) / (60 * 60 * 24);
        $total_room_charges = ceil($days_stayed) * $room_charge_per_day;
    } else {
        $total_room_charges = 0; // No room assignment
    }

    // Fetch non-expired appointment count and calculate appointment charges
    $appointments_query = "
        SELECT COUNT(*) AS total_appointments
        FROM appointments a
        JOIN patient pt ON a.patient_name = pt.name
        WHERE pt.id = '$patient_id' AND a.status = 'accepted'
    ";
    $appointments_result = mysqli_query($conn, $appointments_query);
    $appointments_data = mysqli_fetch_assoc($appointments_result);
    $total_appointments = $appointments_data['total_appointments'] ?? 0;
    $total_appointments_charges = $total_appointments * 500; // ₹500 per appointment

    // Calculate the total bill
    $total_bill = $total_prescription_price + $total_room_charges + $total_appointments_charges;

    // Insert the bill into the bills table
    $insert_bill_query = "INSERT INTO bills (patient_id, total_price) VALUES ('$patient_id', '$total_bill')";
    $insert_bill_result = mysqli_query($conn, $insert_bill_query);

    if ($insert_bill_result) {
        echo "<h3>Bill Generated Successfully</h3>";
    } else {
        echo "<p>Error generating bill: " . mysqli_error($conn) . "</p>";
    }

    // Handle payment button click
    if (isset($_POST['pay_bill'])) {
        // Update the bill status to 'paid' and set balance to zero
        $update_bill_query = "UPDATE bills SET total_price = 0 WHERE patient_id = '$patient_id'";
        $update_bill_result = mysqli_query($conn, $update_bill_query);

        if ($update_bill_result) {
            // Delete prescription records for the patient
            $delete_prescriptions_query = "
                DELETE p 
                FROM prescriptions p
                JOIN patient pt ON p.patient_id = pt.id
                WHERE pt.id = '$patient_id'
            ";
            mysqli_query($conn, $delete_prescriptions_query);

            // Handle room assignment deletion and availability update
            if (isset($room_id)) {
                $delete_room_assignment_query = "
                    DELETE FROM room_assignments
                    WHERE patient_id = '$patient_id' AND room_id = '$room_id'
                ";
                mysqli_query($conn, $delete_room_assignment_query);

                $update_room_availability_query = "
                    UPDATE rooms SET availability = 1 WHERE id = '$room_id'
                ";
                mysqli_query($conn, $update_room_availability_query);
            }

            // Mark accepted appointments as expired
            $update_appointments_query = "
                UPDATE appointments
                SET status = 'expired'
                WHERE patient_name = '$patient_name' AND status = 'accepted'
            ";
            mysqli_query($conn, $update_appointments_query);

            echo "<script>alert('Payment Successful! All records have been updated.');</script>";
        } else {
            echo "<script>alert('Error processing payment. Please try again.');</script>";
        }
    }
} else {
    echo "<p>Patient not found. Please contact the administrator.</p>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-details {
            font-size: 18px;
            margin-top: 20px;
        }

        .back-btn {
            background-color: #f8f9fa;
            border: 1px solid #007bff;
            color: #007bff;
            text-align: center;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #f1f1f1;
        }

        .pay-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }

        .pay-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bill Payments</h2>
        <div class="bill-details">
            <p><strong>Prescription Charges:</strong> ₹<?php echo $total_prescription_price; ?></p>
            <p><strong>Room Charges:</strong> ₹<?php echo $total_room_charges; ?></p>
            <p><strong>Appointment Charges:</strong> ₹<?php echo $total_appointments_charges; ?></p>
            <h3>Total Bill: ₹<?php echo $total_bill; ?></h3>
        </div>

        <!-- Pay Bill Button -->
        <form method="POST">
            <button type="submit" name="pay_bill" class="pay-btn">Pay Bill</button>
        </form>

        <!-- Back to Dashboard Button -->
        <a href="patient_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
