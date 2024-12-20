<?php

// Connect to the database
include ("connect.php");


// Fetch all trades from the database
$trades_query = $conn->prepare("SELECT * FROM trades");
$trades_query->execute();
$trades_result = $trades_query->get_result();

$trades = [];
while ($trade = $trades_result->fetch_assoc()) {
    $trades[] = $trade;
}

// add navbar bit
include ("navbar.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Trades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="activity-styles.css" />         
</head>
<body>
    <div class="hero">
        <h1>All Trades</h1>
    </div>
    <div class="main-container" style="display: flex; gap: 40px; flex-wrap: wrap;">
        <?php if ($trades): ?>
            <?php foreach ($trades as $trade_data): ?>
                <div class="player-card">
                    <h4>Trade ID: <?php echo htmlspecialchars($trade_data['tradeID']); ?></h4>
                    <p><strong>Trade Date:</strong> <?php echo htmlspecialchars($trade_data['tradeDate']); ?></p>

                    <?php
                    // Fetch team details for team1 and team2
                    $team1_query = $conn->prepare("SELECT * FROM teams WHERE teamID = ?");
                    $team1_query->bind_param("i", $trade_data['team1ID']);
                    $team1_query->execute();
                    $team1_result = $team1_query->get_result();
                    $team1_data = $team1_result->fetch_assoc();

                    $team2_query = $conn->prepare("SELECT * FROM teams WHERE teamID = ?");
                    $team2_query->bind_param("i", $trade_data['team2ID']);
                    $team2_query->execute();
                    $team2_result = $team2_query->get_result();
                    $team2_data = $team2_result->fetch_assoc();

                    // Fetch player details for traded players
                    $player1_query = $conn->prepare("SELECT * FROM players WHERE playerID = ?");
                    $player1_query->bind_param("i", $trade_data['tradedPlayer1ID']);
                    $player1_query->execute();
                    $player1_result = $player1_query->get_result();
                    $player1_data = $player1_result->fetch_assoc();

                    $player2_query = $conn->prepare("SELECT * FROM players WHERE playerID = ?");
                    $player2_query->bind_param("i", $trade_data['tradedPlayer2ID']);
                    $player2_query->execute();
                    $player2_result = $player2_query->get_result();
                    $player2_data = $player2_result->fetch_assoc();
                    ?>
<br>
                    <h5>Teams Involved</h5>
                    <div class="team-card">
                        <p><strong>Team 1:</strong> <?php echo htmlspecialchars($team1_data['teamName']); ?></p>
                        <p><strong>Team 1 ID:</strong> <?php echo htmlspecialchars($team1_data['teamID']); ?></p>
                    </div>
                    <div class="team-card">
                        <p><strong>Team 2:</strong> <?php echo htmlspecialchars($team2_data['teamName']); ?></p>
                        <p><strong>Team 2 ID:</strong> <?php echo htmlspecialchars($team2_data['teamID']); ?></p>
                    </div>
<br>
                    <h5>Players Traded</h5>
                    <div class="player-card">
                        <p><strong>Traded Player 1:</strong> <?php echo htmlspecialchars($player1_data['fullName']); ?></p>
                        <p><strong>Player 1 ID:</strong> <?php echo htmlspecialchars($player1_data['playerID']); ?></p>
                        <p><strong>Sport:</strong> <?php echo htmlspecialchars($player1_data['sport']); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($player1_data['position']); ?></p>
                        <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player1_data['realTeam']); ?></p>
                        <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player1_data['fantasyPoints']); ?></p>
                        <p><strong>Availability:</strong> <?php echo htmlspecialchars($player1_data['availabilityStatus']); ?></p>
                    </div>

                    <div class="player-card">
                        <p><strong>Traded Player 2:</strong> <?php echo htmlspecialchars($player2_data['fullName']); ?></p>
                        <p><strong>Player 2 ID:</strong> <?php echo htmlspecialchars($player2_data['playerID']); ?></p>
                        <p><strong>Sport:</strong> <?php echo htmlspecialchars($player2_data['sport']); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($player2_data['position']); ?></p>
                        <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player2_data['realTeam']); ?></p>
                        <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player2_data['fantasyPoints']); ?></p>
                        <p><strong>Availability:</strong> <?php echo htmlspecialchars($player2_data['availabilityStatus']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div>
                <p>No trades found.</p>
            </div>
        <?php endif; ?>

        <?php
        // Close connections
        $trades_query->close();
        $team1_query->close();
        $team2_query->close();
        $player1_query->close();
        $player2_query->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
