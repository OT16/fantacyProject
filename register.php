<?php
include "connect.php";
// Get the values from the form
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$username = $_POST['uname'];
$email = $_POST['email'];
$password = $_POST['pwd'];
// preparing and executing statment
// EDIT NOT DONE *****
$sql = "INSERT into users(userID, fullname, email, username, password)
values('$firstname','$lastname','$username','$password')";
$result = mysqli_query($conn, $sql);
if($result){
echo $firstname. " is registred succesfully!";
}
$conn->close();
?>
