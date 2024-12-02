<?php
// To use PDO to connect to a database, you need the following information:
$servname = "localhost";
$username = "root";
$password = "";
$dbname = "fantasy_db";
// database connection
$conn = mysqli_connect($servname, $username, $password, $dbname);
if(!$conn){
echo "Connection failed";
}

// if ($conn) {
//     // Fetch and display the current database name
//     $db_query = $conn->query("SELECT DATABASE()");
//     if ($db_query) {
//         $db_name = $db_query->fetch_row()[0];
//         echo "Connected to Database: " . $db_name . "<br>";
//     } else {
//         echo "Could not fetch database name.<br>";
//     }

//     // Fetch and display all tables in the database
//     $tables_query = $conn->query("SHOW TABLES");
//     if ($tables_query->num_rows > 0) {
//         echo "Tables in Database:<br>";
//         while ($row = $tables_query->fetch_row()) {
//             echo "- " . $row[0] . "<br>";
//         }
//     } else {
//         echo "No tables found in the database.";
//     }
// } else {
//     echo "Connection failed";
// }





?>
