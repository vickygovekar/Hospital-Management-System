<?php 
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        main {
            padding: 20px;
            text-align: center;
            background-image: url('bgimg01_walkdocs.jpg'); 
            background-size: cover;
            background-position: center;
            color: #fff; 
            min-height: 60vh; 
        }
        .content {
            background-color: rgba(0, 0, 0, 0.5); 
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <?php include 'header.html'; ?>

    <main>
        <div class="content">
            <h2>Welcome to Our Hospital Management System</h2>
            <p>This is the main content area where information about the system will be displayed.</p>
        </div>
    </main>

    <?php include 'footer.html'; ?>
</body>
</html>

