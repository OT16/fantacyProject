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

$sqlUserLeagues = "
    SELECT 
        leagueID, 
        leagueName, 
        leagueType, 
        maxTeams, 
        draftDate 
    FROM leagues
    WHERE commissioner = '$userID'";
$resultUserLeagues = mysqli_query($conn, $sqlUserLeagues);

$userLeagues = [];
if ($resultUserLeagues && mysqli_num_rows($resultUserLeagues) > 0) {
    while ($rowLeague = mysqli_fetch_assoc($resultUserLeagues)) {
        $userLeagues[] = $rowLeague;
    }
}

ob_start();
include "home.html";
$html_content = ob_get_clean();

echo $html_content;
?>
