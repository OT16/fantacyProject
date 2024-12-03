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



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- required to handle IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Registration</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="activity-styles.css" />
  </head>
  <body>

    <div class="container">
      <div class="child1">
        <h1 class="header">Register</h1>
        <form class="form" action="register.php" method="post">
          <input
            type="text"
            id="fname"
            name="fname"
            placeholder="First Name"
            required
          />
          <br />
          <input
            type="text"
            id="lname"
            name="lname"
            placeholder="Last Name"
            required
          />
          <br />
          <input
            type="text"
            id="uname"
            name="uname"
            placeholder="Username"
            required
          />
          <br />
          <input
            type="password"
            id="pwd"
            name="pwd"
            placeholder="Password"
            required
          />
          <br />
          <input type="submit" value="Submit" class="btn" />
        </form>
        <p style="color: black">
          Have an account already?
          <a href="login.html" class="btn-link">Login</a>
        </p>
      </div>

      <div class="child2">
        <img src="./images/login.png" />
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

