
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>L'Hospital - Signup</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f8ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .signup-container {
      background-color: #ffffff;
      padding: 20px;
      width: 350px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    label {
      display: block;
      margin-top: 15px;
      color: #555;
    }

    input[type="text"],
    input[type="password"],
    input[type="tel"],
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #4caf50;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 4px;
      margin-top: 15px;
      cursor: pointer;
      font-size: 16px;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    .role-info {
      font-size: 12px;
      color: #777;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="signup-container">
  <h2>Sign Up</h2>
  <form action="signup.php" method="post">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <label for="address">Address</label>
    <input type="text" id="address" name="address" required>

    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>

    <label for="gender">Gender</label>
    <input type="radio" name="gender" value="m" >Male
    <input type="radio" name="gender" value="f" >Female
    <input type="radio" name="gender" value="o" >Other<br>
    
    <label for="role">Registering as</label>
    <select id="role" name="role" required>
      <option value="">Select Role</option>
      <option value="Patient">Patient</option>
      <option value="Doctor">Doctor</option>
      <option value="Nurse">Nurse</option>

    </select>

    <input type="submit" name="confirm" value="Sign Up">
  </form>
  <div class="role-info">Please select your role in the hospital</div>
</div>

</body>
</html>
<?php
include("connect.php");

if (isset($_POST["confirm"])) {
    // Handle roles: Doctor and Nurse
    if (isset($_POST["role"]) && ($_POST["role"] === "Doctor" || $_POST["role"] === "Nurse")) {
        $sql = "INSERT INTO employees (name, password, address, phone, gender, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $_POST['name'], $_POST['password'], $_POST['address'], $_POST['phone'], $_POST['gender'], $_POST['role']);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div style='text-align: center; font-family: Arial, sans-serif;'>
                        <p style='font-size: 20px; color: #333; margin-bottom: 20px;'>User is now registered</p>
                        <form action='employee_signin.php' method='get' style='display: inline-block; text-align: center; margin-top: 20px;'>
                            <button type='submit' 
                                style='background-color: #4CAF50; color: white; padding: 12px 25px; border: none; 
                                border-radius: 5px; cursor: pointer; font-size: 18px; transition: background-color 0.3s ease;'>
                                Go to Employee Sign In
                            </button>
                        </form>
                        <br>
                        <form action='patient_signin.php' method='get' style='display: inline-block; text-align: center; margin-top: 20px;'>
                            <button type='submit' 
                                style='background-color: #4CAF50; color: white; padding: 12px 25px; border: none; 
                                border-radius: 5px; cursor: pointer; font-size: 18px; transition: background-color 0.3s ease;'>
                                Go to Patient Sign In
                            </button>
                        </form>
                    </div>
                    <style>
                        button:hover {
                            background-color: #45a049;
                        }
                    </style>";
            } else {
                echo "Could not register user: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Statement preparation failed: " . mysqli_error($conn);
        }
    }
    // Handle role: Patient
    else if ($_POST["role"] === "Patient") {
        $sql = "INSERT INTO patient (name, password, address, phone, gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $_POST['name'], $_POST['password'], $_POST['address'], $_POST['phone'], $_POST['gender']);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div style='text-align: center; font-family: Arial, sans-serif;'>
                        <p style='font-size: 20px; color: #333; margin-bottom: 20px;'>User is now registered</p>
                        <form action='patient_signin.php' method='get' style='display: inline-block; text-align: center; margin-top: 20px;'>
                            <button type='submit' 
                                style='background-color: #4CAF50; color: white; padding: 12px 25px; border: none; 
                                border-radius: 5px; cursor: pointer; font-size: 18px; transition: background-color 0.3s ease;'>
                                Go to Patient Sign In
                            </button>
                        </form>
                    </div>
                    <style>
                        button:hover {
                            background-color: #45a049;
                        }
                    </style>";
            } else {
                echo "Could not register user: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Statement preparation failed: " . mysqli_error($conn);
        }
    }
}
?>
