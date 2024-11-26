<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Optionally store the username for further use
$username = $_SESSION['username'];
?>

<?php
ob_start();  // Start output buffering

// Replace or insert any PHP content in the HTML file
include "home.html"; 

$html_content = ob_get_clean(); // Get the content of home.html with PHP variables

// Output the modified content
echo $html_content;
?>


