<?php

include('connect.php'); // This includes your database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $fullName = $_POST['fname'];
    $username = $_POST['uname'];
    $password = $_POST['pwd'];
    $email = $_POST['email'];

    // Hash a new password using SHA-256 when a user logs in
    $hashed_password = hash('sha256', $password);

    $userID = rand(11000020, 99999999); // Starting value


    // Prepare SQL query to insert the new user into the database
    $sql = "INSERT INTO users (userID, fullName, username, password, email, profileSettings) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Set profileSettings to NULL
        $profileSettings = '{"darkMode": true, "notifications": false}';

        // Bind parameters to the statement
        $stmt->bind_param("isssss", $userID, $fullName, $username, $hashed_password, $email, $profileSettings);

        // Execute the statement
        if ($stmt->execute()) {
            echo "User successfully registered!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

}

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
        <h1 class="header">Register new account</h1>
        <form class="login-form" action="register.php" method="post">
          <input
            type="text"
            id="fname"
            name="fname"
            placeholder="Full Name"
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
            type="email"
            id="email"
            name="email"
            placeholder="Email"
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
          <input type="submit" value="Register" class="btn" action="" method="post" />

        </form>
        <p style="color: black">
          Have an account already?
          <a href="login.php" class="btn-link">Login</a>
        </p>
      </div>

      <div class="child2">
        <img src="./images/login.png" />
      </div>
    </div>

  </body>
</html>

