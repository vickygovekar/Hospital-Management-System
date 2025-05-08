<?php
session_start();

include('connect.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM patient WHERE name='$username' AND password='$password'";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);    
    if($row){
      $_SESSION['username'] = $username;
    }
    if ($count == 1) {
            header("Location: patient_dashboard.php");
            exit();
    } else {
        echo '<script> alert("Login failed, invalid username or password."); window.location.href="patient_signin.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient - Sign In</title>
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

    .signin-container {
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
    input[type="password"] {
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

    .signup-link {
      font-size: 12px;
      color: #777;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="signin-container">
  <h2>Patient Sign In</h2>
  <form action="patient_signin.php" method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" name="login" value="Sign In">
  </form>
  <div class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></div>
</div>

</body>
</html>
