<?php

//Test this with: http://localhost/fantacyProject/tradedetails.php?tradeID=3000000001

// Connect to the database
include ("connect.php");

// Get trade ID from the GET request
$tradeID = isset($_GET['tradeID']) ? $_GET['tradeID'] : null;

if ($tradeID) {
    // Fetch trade details
    $trade_query = $conn->prepare("SELECT * FROM trades WHERE tradeID = ?");
    $trade_query->bind_param("i", $tradeID);
    $trade_query->execute();
    $trade_result = $trade_query->get_result();
    $trade_data = $trade_result->fetch_assoc();

    if ($trade_data) {
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Details</title>
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
        .trade-card, .team-card, .player-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php if ($trade_data): ?>
        <div class="sidebar">
            <h3>Trade Details</h3>
            <p><strong>Trade ID:</strong> <?php echo htmlspecialchars($trade_data['tradeID']); ?></p>
            <p><strong>Trade Date:</strong> <?php echo htmlspecialchars($trade_data['tradeDate']); ?></p>

            <h4>Teams Involved</h4>
            <div class="team-card">
                <p><strong>Team 1:</strong> <?php echo htmlspecialchars($team1_data['teamName']); ?></p>
                <p><strong>Team 1 ID:</strong> <?php echo htmlspecialchars($team1_data['teamID']); ?></p>
            </div>
            <div class="team-card">
                <p><strong>Team 2:</strong> <?php echo htmlspecialchars($team2_data['teamName']); ?></p>
                <p><strong>Team 2 ID:</strong> <?php echo htmlspecialchars($team2_data['teamID']); ?></p>
            </div>

            <h4>Players Traded</h4>
            <div class="player-card">
                <p><strong>Traded Player 1:</strong> <?php echo htmlspecialchars($player1_data['fullName']); ?></p>
                <p><strong>Player 1 ID:</strong> <?php echo htmlspecialchars($player1_data['playerID']); ?></p>
                <p><strong>Sport:</strong> <?php echo htmlspecialchars($player1_data['sport']); ?></p>
                <p><strong>Position:</strong> <?php echo htmlspecialchars($player1_data['position']); ?></p>
                <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player1_data['realTeam']); ?></p>
                <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player1_data['fantasyPoints']); ?></p>
                <p><strong>Availability:</strong> <?php echo htmlspecialchars($player1_data['availability']); ?></p>
            </div>

            <div class="player-card">
                <p><strong>Traded Player 2:</strong> <?php echo htmlspecialchars($player2_data['fullName']); ?></p>
                <p><strong>Player 2 ID:</strong> <?php echo htmlspecialchars($player2_data['playerID']); ?></p>
                <p><strong>Sport:</strong> <?php echo htmlspecialchars($player2_data['sport']); ?></p>
                <p><strong>Position:</strong> <?php echo htmlspecialchars($player2_data['position']); ?></p>
                <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player2_data['realTeam']); ?></p>
                <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player2_data['fantasyPoints']); ?></p>
                <p><strong>Availability:</strong> <?php echo htmlspecialchars($player2_data['availability']); ?></p>
            </div>
        </div>
        <div class="main-content">
            <!-- You can add more content here if necessary -->
        </div>
    <?php else: ?>
        <div>
            <p>Sorry, no trade found or invalid trade ID provided.</p>
        </div>
    <?php endif; ?>

    <?php
    // Close connections
    $trade_query->close();
    $team1_query->close();
    $team2_query->close();
    $player1_query->close();
    $player2_query->close();
    $conn->close();
    ?>
</body>
</html>
