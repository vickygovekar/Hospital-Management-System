<?php
include('connect.php');

$query = "SELECT id, password FROM employees";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $password = $row['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the hashed password in the database
    $updateQuery = "UPDATE employees SET hashed_password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'si', $hashedPassword, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
echo "Passwords have been hashed and updated.";
?>