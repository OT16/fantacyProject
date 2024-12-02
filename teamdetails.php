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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Details</title>
      <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 25%;
            padding: 20px;
            background-color: #f4f4f4;
            border-right: 1px solid #ddd;
        }
        .main-content {
            padding: 20px;
            width: 75%;
        }
        .player-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php if ($team_data): ?>
        <div class="sidebar">
            <h3>Team Stats</h3>
            <p><strong>Team ID:</strong> <?php echo htmlspecialchars($team_data['teamID']); ?></p>
            <p><strong>Team Name:</strong> <?php echo htmlspecialchars($team_data['teamName']); ?></p>
            <p><strong>League ID:</strong> <?php echo htmlspecialchars($team_data['leagueID']); ?></p>
            <p><strong>Total Points:</strong> <?php echo htmlspecialchars($team_data['totalPoints']); ?></p>
            <p><strong>Ranking:</strong> <?php echo htmlspecialchars($team_data['ranking']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($team_data['status']); ?></p>
        </div>
        <div class="main-content">
            <h3>Players</h3>
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
</body>
</html>
