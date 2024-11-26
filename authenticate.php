<?php
session_start(); // Start session to use $_SESSION

include "connect.php"; // Ensure this file defines $conn

// Get the values from the form
$un = mysqli_real_escape_string($conn, $_POST['uname']);
$pass = $_POST['pwd'];

// Query to fetch user details
$sql = "SELECT * FROM users WHERE username = '$un'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    // password_verify? Hashing passwords?
    // Verify password using password_verify() (assuming passwords are hashed)
    echo $pass;
    echo $row['password'];
    if ($pass == $row['password']) {
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
?>
