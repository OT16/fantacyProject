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

// get
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fullName = $row['fullName'];
	$email = $row['email'];
}

// change
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo $fullName;
    echo $username;
    $newFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    echo $newFullName ;
    echo $newEmail;
    $updateSql = "UPDATE users SET fullName = '$newFullName', email = '$newEmail' WHERE username = '$username'";
    $updateSqlResult = $conn->query($updateSql);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>login</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<link rel="stylesheet" href="activity-styles.css" /> 

</head>
<body>

	<!-- Navigation Bar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
		  <a class="navbar-brand" href="#">
			<img src="./images/logo.png" style="height: 50px;">
		  </a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto">
			  <li class="nav-item">
				<a class="nav-link active" href="home.php">Home</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="profile.php">Profile</a>
			  </li>
			</ul>
		  </div>
		</div>
	</nav>

    <div class="hero">
        <h1>Profile Settings</h1>
    </div>

			<h3 style="text-align: center;">Update Profile Details</h3>

			<form class="login-form" action="" method="post">
				<div class-"profile-section"></div>
					<h5>Change profile fields.</h5>
					<p>Enter name</p>
					<input type="text" id="fullName" name="fullName" placeholder="Full Name" value="" required>

					<p>Enter email</p>
					<input type="email" id="email" name="email"  placeholder="Email" value="" required>
				</div>
				
				<div class-"profile-section">
					<input type="submit" value="Save changes" class="btn"/>
				</div>

			</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
