<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: employee_signin.php");
    exit();
}

// Include database connection
include('connect.php');

// Fetch all patients for selection
$query = "SELECT id, name FROM patient ORDER BY name ASC";
$patients_result = mysqli_query($conn, $query);

// Fetch all available medicines
$medicines_query = "SELECT id, name FROM medicines ORDER BY name ASC";
$medicines_result = mysqli_query($conn, $medicines_query);

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected patient, medicine, quantity
    $patient_id = $_POST['patient_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];

    // Validate the inputs
    if (empty($patient_id) || empty($medicine_id) || empty($quantity)) {
        echo "<p style='color:red;'>Please fill all fields.</p>";
    } else {
        // Insert prescription into database
        $prescription_query = "
            INSERT INTO prescriptions (patient_id, medicine_id, quantity)
            VALUES ('$patient_id', '$medicine_id', '$quantity')
        ";

        $prescription_result = mysqli_query($conn, $prescription_query);

        if ($prescription_result) {
            // Get the last inserted prescription to display the total price
            $last_prescription_id = mysqli_insert_id($conn);
            $prescription_details_query = "SELECT total_price FROM prescriptions WHERE id = '$last_prescription_id'";
            $prescription_details_result = mysqli_query($conn, $prescription_details_query);
            $prescription_details = mysqli_fetch_assoc($prescription_details_result);

            echo "<p>Prescription successfully created. Total Price: ₹" . $prescription_details['total_price'] . "</p>";
            // Optionally, redirect after success
            header("Location: prescribe_medicine.php?status=success");
            exit();
        } else {
            echo "<p>Error creating prescription: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescribe Medicine</title>
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

        label {
            display: block;
            margin: 10px 0 5px;
        }

        select, input[type="number"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-align: center;
        }

        button:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Prescribe Medicine</h2>
        <form action="prescribe_medicine.php" method="POST">
            <!-- Select patient -->
            <label for="patient_id">Select Patient</label>
            <select name="patient_id" required>
                <option value="" disabled selected>Select a patient</option>
                <?php while ($patient = mysqli_fetch_assoc($patients_result)) { ?>
                    <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?></option>
                <?php } ?>
            </select>

            <!-- Select medicine -->
            <label for="medicine_id">Select Medicine</label>
            <select name="medicine_id" required>
                <option value="" disabled selected>Select a medicine</option>
                <?php while ($medicine = mysqli_fetch_assoc($medicines_result)) { ?>
                    <option value="<?php echo $medicine['id']; ?>"><?php echo $medicine['name']; ?></option>
                <?php } ?>
            </select>

            <!-- Quantity input -->
            <label for="quantity">Enter Quantity</label>
            <input type="number" name="quantity" min="1" required>

            <!-- Submit button -->
            <button type="submit">Prescribe Medicine</button>
        </form>
        <a href="doctor_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
