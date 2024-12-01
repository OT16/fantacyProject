<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$sqlUser = "SELECT userID, fullName FROM users WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);

if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $userID = $rowUser['userID'];
    $fullName = $rowUser['fullName'];
}

// Check if the leagueID is set in the URL
if (isset($_GET['leagueID'])) {
    $leagueID = $_GET['leagueID'];

    // Query to get the league details based on leagueID
    $sqlLeague = "SELECT leagueName, leagueType, maxTeams, draftDate FROM leagues WHERE leagueID = '$leagueID' AND commissioner = '$userID'";
    $resultLeague = mysqli_query($conn, $sqlLeague);

    if ($resultLeague && mysqli_num_rows($resultLeague) > 0) {
        $league = mysqli_fetch_assoc($resultLeague);
    } else {
        echo "League not found or you don't have permission to manage it.";
        exit();
    }

    // Query to get all teams for this league
    $sqlTeams = "SELECT teamID, teamName FROM teams WHERE leagueID = '$leagueID'";
    $resultTeams = mysqli_query($conn, $sqlTeams);
} else {
    echo "No league selected.";
    exit();
}
?>
