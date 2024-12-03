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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = $_POST['fullName'];
    $newEmail = $_POST['email'];
    $password = $_POST['pwd'];

    $sqlVerify = "SELECT userID, password FROM users WHERE username = ?";
    $stmtVerify = mysqli_prepare($conn, $sqlVerify);
    mysqli_stmt_bind_param($stmtVerify, "s", $username);
    mysqli_stmt_execute($stmtVerify);
    $resultVerify = mysqli_stmt_get_result($stmtVerify);

    if ($resultVerify && mysqli_num_rows($resultVerify) > 0) {
        $row = mysqli_fetch_assoc($resultVerify);

        if (password_verify($password, $row['password'])) {
            $userID = $row['userID'];
            $sqlUpdate = "UPDATE users SET fullName = ?, email = ? WHERE userID = ?";
            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "ssi", $newName, $newEmail, $userID);

            if (mysqli_stmt_execute($stmtUpdate)) {
                $status = "Profile updated successfully!";
            } else {
                $status = "Error updating profile: " . mysqli_error($conn);
            }
        } else {
            $status = "Invalid password.";
        }
    } else {
        $status = "Invalid username.";
    }
}

// add navbar bit
include ("navbar.html");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>Login</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="activity-styles.css" /> 		
</head>
<body>  

    <div class="hero">
        <h1>Profile Settings</h1>
    </div>

	<div class="container">
		<div class="child1">
			<h3 style="text-align: left;">Update Profile Details</h3>
			
			<form class="login-form" action="profile.php" method="post">
                
					<h5>Change profile fields.</h5>
                    <br>
					<p>Enter name</p>
					<input type="text" id="uname" name="fullName" placeholder="Full Name" required>
                    <br><br>

					<p>Enter email</p>
					<input type="email" id="email" name="email"" placeholder="Email" required>
                    
                    <br><br><br><br>                 

					<h5>Confirm Username and password to save changes.</h5>
                    <br>
					<p>Password</p>
					<input type="password" id="pwd "name="pwd" placeholder="Password"  required /> <br/>
				
                    <div class="select">
                        <input type="submit" value="Save changes" class="btn"/>
                    </div>
			</form>

            
		</div>
	</div>

    <br>
	<div class="container">
		<a href="logout.php" class="btn btn-primary">Logout</a>
	</div>
</body>
</html>
