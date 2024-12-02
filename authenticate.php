<?php
session_start();
include "connect.php"; // Ensure this file defines $conn

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the form and sanitize
    $un = mysqli_real_escape_string($conn, $_POST['uname']);
    $pass = $_POST['pwd'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $un); // 's' denotes string type for $un

    // Execute the query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Direct password comparison (not recommended)
        if ($pass == $row['password']) {  // Directly compare the passwords
            echo "Logged in";
            $_SESSION['username'] = $un; // Set session variable
            header("Location: home.php"); // Redirect to home page
            exit();
        } else {
            echo "Incorrect username or password"; // Password mismatch
            exit();
        }
    } else {
        echo "No User Found :("; // No matching user
        exit();
    }
}

?>
