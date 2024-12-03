<?php
// authenticate.php
include('connect.php'); // This includes your database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST['uname'];
    $password = $_POST['pwd'];

    // Validate the username and password against the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If user exists, check password
    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // Hash a new password using SHA-256 when a user logs in
        $hashed_password = hash('sha256', $password);

        if ($hashed_password == $row['password']) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['fullName'] = $row['fullName'];

            $profileSettings = json_decode($row['profileSettings'], true); // Decode JSON to an associative array
            if (isset($profileSettings['isDeveloper']) && $profileSettings['isDeveloper'] === true) {
                // User is a developer, can export data
                header("Location: devhome.php");
            } else {
                // echo "User is not a developer.";
                header("Location: home.php");
                exit();
            }
        } else {
            // Incorrect password
            echo "Invalid username or password";
            header("Location: register.php");
        }
    } else {
        // User doesn't exist
        echo "User does not exist";
        header("Location: register.php");
    }
}
?>
