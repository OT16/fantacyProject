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
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newOtherDetails = mysqli_real_escape_string($conn, $_POST['otherDetails']);

    $updateSql = "UPDATE users SET fullName = '$newFullName', email = '$newEmail' WHERE username = '$username'";
}
?>
