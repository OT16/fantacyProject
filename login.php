<?php
// Start the session
session_start();

// Include database connection
include("connect.php");
include ("navbar.html");

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- required to handle IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>login</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="activity-styles.css" />
  </head>
  <body>
    <div class="container">
      <div class="child1">
        <h1 class="header">Ready to bet?</h1>
        <h1 class="header">Login to get started</h1>
        <form class="login-form" action="authenticate.php" method="post">
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
            id="pwd "
            name="pwd"
            placeholder="Password"
            required
          />
          <br />
          <input type="submit" value="Login" class="btn login-form" action="authenticate.php" method="post" />
        </form>
        <p style="color: black">
          Don't have an account?
          <a href="register.php" class="btn-link">Register</a>
        </p>
		<p style="color: black">
          Are  you a developer?
          <a href="devlogin.php" class="btn-link">Log in here</a>
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
