<?php
// To use PDO to connect to a database, you need the following information:
$servname = "localhost";
$username = "root";
$password = "";
$dbname = "fantasy_db";
// database connection
$conn = mysqli_connect($servname, $username, $password, $dbname);
if($conn){
echo "Connected";
}
if(!$conn){
echo "Connection failed";
}
?>
