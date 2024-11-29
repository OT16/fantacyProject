<?php
include "connect.php";

// Get the values from the form
$username = $_POST['uname']; // Retrieve the username
$email = $_POST['email'];
$password = $_POST['pwd'];

// Combine firstname and lastname for fullname
$fullname = $_POST['fname'] . ' ' . $_POST['lname'];

// Prepare and execute the statement (using prepared statements for security)
$sql = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Bind parameters and execute
$stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);

if ($stmt->execute()) {
    // Registration successful
    $welcome_message = "Welcome to " . htmlspecialchars($username) . "!";
} else {
    // Registration failed
    $welcome_message = "Error registering user: " . $conn->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
