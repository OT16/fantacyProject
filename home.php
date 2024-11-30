<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT fullName FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fullName = $row['fullName'];
} else {
    $fullName = "Guest";
}
?>

ob_start();  // Start output buffering

// Replace or insert any PHP content in the HTML file
include "home.html"; 

$html_content = ob_get_clean(); // Get the content of home.html with PHP variables

// Output the modified content
echo $html_content;
?>


