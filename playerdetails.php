<?php

// Connect to the database
include ("connect.php");



// Get playerID from the GET request
$playerID = isset($_GET['playerID']) ? $_GET['playerID'] : null;

if ($playerID) {
    // Fetch player name from the players table
    $player_name_query = $conn->prepare("SELECT fullName FROM players WHERE playerID = ?");
    $player_name_query->bind_param("i", $playerID);
    $player_name_query->execute();
    $player_name_result = $player_name_query->get_result();
    $player_name_data = $player_name_result->fetch_assoc();

    // Fetch player statistics from the playerStatistics table
    $player_stats_query = $conn->prepare("SELECT * FROM playerStatistics WHERE playerID = ?");
    $player_stats_query->bind_param("i", $playerID);
    $player_stats_query->execute();
    $player_stats_result = $player_stats_query->get_result();
    $player_stats_data = $player_stats_result->fetch_all(MYSQLI_ASSOC);
}

// Add navbar bit
include("navbar.html");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="activity-styles.css" />
</head>
<body>
    <div class="hero">
        <h1>Player Details: <?php echo htmlspecialchars($player_name_data['fullName']); ?></h1>
    </div>
    <div class="main-container">
        <?php if ($player_stats_data): ?>
            <div class="player-details">
                <h3>Player Statistics</h3>
                <?php foreach ($player_stats_data as $stat): ?>
                    <div class="player-card">
                        <p><strong>Game Date:</strong> <?php echo htmlspecialchars($stat['gameDate']); ?></p>
                        <p><strong>Performance Stats:</strong> <?php echo htmlspecialchars($stat['performanceStats']); ?></p>
                        <p><strong>Injury Status:</strong> <?php echo htmlspecialchars($stat['injuryStatus']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div>
                <p>Sorry, no player statistics found or invalid player ID provided.</p>
            </div>
        <?php endif; ?>


        <div class="select">
            <a href="home.php" class="btn btn-primary">Back to Home</a>
        </div>
        
        <?php


        // Close connections
        $player_name_query->close();
        $player_stats_query->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
