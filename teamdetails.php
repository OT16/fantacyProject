<?php

//Test this with: http://localhost/fantacyProject/teamdetails.php?teamID=1

// Connect to the database

include ("connect.php");

// Get team ID from the GET request
$teamID = isset($_GET['teamID']) ? $_GET['teamID'] : null;

// $teamID = 1;
// echo "Team ID: " . $teamID . "<br>";

if ($teamID) {
    // Fetch team stats
    $team_query = $conn->prepare("SELECT * FROM teams WHERE teamID = ?");
    $team_query->bind_param("i", $teamID);
    $team_query->execute();
    $team_result = $team_query->get_result();
    $team_data = $team_result->fetch_assoc();

    if ($team_data) {
        // Get the teamName from the team stats
        $teamName = $team_data['teamName'];

        // Fetch players for the matching realTeam
        $player_query = $conn->prepare("SELECT * FROM players WHERE realTeam = ?");
        $player_query->bind_param("s", $teamName);
        $player_query->execute();
        $player_result = $player_query->get_result();
    }
}

$action = $_POST['action'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $fullName = $_POST['fullName'];
        $sport = $_POST['sport'];
        $position = $_POST['position'];
        $realTeam = $_POST['realTeam'];

        $query = $conn->prepare("INSERT INTO players (fullName, sport, position, realTeam) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $fullName, $sport, $position, $realTeam);
        $query->execute();
    } elseif ($action === 'update') {
        $playerID = $_POST['playerID'];
        $fullName = $_POST['fullName'];
        $fantasyPoints = $_POST['fantasyPoints'];

        $query = $conn->prepare("UPDATE players SET fullName = ?, fantasyPoints = ? WHERE playerID = ?");
        $query->bind_param("sii", $fullName, $fantasyPoints, $playerID);
        $query->execute();
    } elseif ($action === 'delete') {
        $playerID = $_POST['playerID'];

        $query = $conn->prepare("DELETE FROM players WHERE playerID = ?");
        $query->bind_param("i", $playerID);
        $query->execute();
    }
}


// add navbar bit
include ("navbar.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="activity-styles.css" /> 		
</head>
<body>
                <div class="hero">
        <h1>Team Details</h1>
    </div>
    <div class="main">
    <?php if ($team_data): ?>
        <div class="sidebar">
            <h3>Team Stats</h3>
            <p><strong>Team ID:</strong> <?php echo htmlspecialchars($team_data['teamID']); ?></p>
            <p><strong>Team Name:</strong> <?php echo htmlspecialchars($team_data['teamName']); ?></p>
            <p><strong>League ID:</strong> <?php echo htmlspecialchars($team_data['leagueID']); ?></p>
            <p><strong>Total Points:</strong> <?php echo htmlspecialchars($team_data['totalPoints']); ?></p>
            <p><strong>Ranking:</strong> <?php echo htmlspecialchars($team_data['ranking']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($team_data['status']); ?></p>
            <div class="select">
                <a href="tradedetails.php" class="btn btn-primary">View Trades</a>
            </div>
        </div>
        <div class="main-content">
            <h3><br>Players</h3>
            <?php while ($player = $player_result->fetch_assoc()): ?>
                <div class="player-card">
                    <p><strong>Player ID:</strong> <?php echo htmlspecialchars($player['playerID']); ?></p>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($player['fullName']); ?></p>
                    <p><strong>Sport:</strong> <?php echo htmlspecialchars($player['sport']); ?></p>
                    <p><strong>Position:</strong> <?php echo htmlspecialchars($player['position']); ?></p>
                    <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player['realTeam']); ?></p>
                    <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player['fantasyPoints']); ?></p>
                    <p><strong>Availability:</strong> <?php echo htmlspecialchars($player['availability']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div>
            <p>Sorry, no team found or invalid team ID provided.</p>
        </div>
    <?php endif; ?>
    <?php
    // Close connections
    $team_query->close();
    $player_query->close();
    $conn->close();
    ?>
    </div>




</body>
</html>
