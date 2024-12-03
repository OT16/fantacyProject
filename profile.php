<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT fullName, email FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// get
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fullName = $row['fullName'];
	$email = $row['email'];
}

// change
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $newFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);

    $updateSql = "UPDATE users SET fullName = '$newFullName', email = '$newEmail' WHERE username = '$username'";
    // Execute the query
    $updateSqlResult = $conn->query($updateSql);
}


include ('navbar.html');
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



    <div class="hero">
        <h1>Profile Settings</h1>
    </div>
	<div class="main-container">
			<h3>Update Profile Details</h3>

			<form class="login-form" action="" method="post">

					<h5>Change profile fields.</h5>
										<br>
					<p>Enter name</p>
					<input type="text" id="fullName" name="fullName" placeholder="Full Name" value="" required>
					<br>
					<p>Enter email</p>
					<input type="email" id="email" name="email"  placeholder="Email" value="" required>

				

					<input type="submit" value="Save changes" class="btn"/>


			</form>
								<br>
		<h3>Log out</h3>
							<br>
		<div class="">
            <a href="logout.php" class="btn btn-primary">Log out</a>
        </div>


	</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
