?php
// Database connection details
$servername = "127.0.0.1"; // Localhost because we're using an SSH tunnel
$username = "root"; // Cloud SQL username
$password = ""; // Cloud SQL password
$dbname = "fantasy_db"; // Database name
$port = 3306; // The port Cloud SQL proxy is listening on

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname, $port);
session_start(); // Start session if not already started

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
