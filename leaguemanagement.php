<?php
// Test with: http://localhost/fantacyProject/leaguemanagement.php?leagueID=1
// Connect to the database
include("connect.php");

// Get league ID from the GET request
$leagueID = isset($_GET['leagueID']) ? $_GET['leagueID'] : null;

if ($leagueID) {
    // Fetch league details
    $league_query = $conn->prepare("SELECT * FROM leagues WHERE leagueID = ?");
    $league_query->bind_param("i", $leagueID);
    $league_query->execute();
    $league_result = $league_query->get_result();
    $league_data = $league_result->fetch_assoc();

    if ($league_data) {
        // Fetch all teams in this league
        $teams_query = $conn->prepare("SELECT * FROM teams WHERE leagueID = ?");
        $teams_query->bind_param("i", $leagueID);
        $teams_query->execute();
        $teams_result = $teams_query->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Details</title>
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
        .team-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php if ($league_data): ?>
        <div class="sidebar">
            <h3>League Details</h3>
            <p><strong>League ID:</strong> <?php echo htmlspecialchars($league_data['leagueID']); ?></p>
            <p><strong>League Name:</strong> <?php echo htmlspecialchars($league_data['leagueName']); // Assuming 'leagueName' is a column in the leagues table ?></p>
            <p><strong>League Type:</strong> <?php echo htmlspecialchars($league_data['leagueType']); ?></p>
            <p><strong>League Status:</strong> <?php echo htmlspecialchars($league_data['leagueStatus']); ?></p>
            <p><strong>Founded:</strong> <?php echo htmlspecialchars($league_data['foundedDate']); ?></p>
        </div>
        <div class="main-content">
            <h3>Teams in this League</h3>
            <?php while ($team = $teams_result->fetch_assoc()): ?>
                <div class="team-card">
                    <p><strong>Team ID:</strong> <?php echo htmlspecialchars($team['teamID']); ?></p>
                    <p><strong>Team Name:</strong> <?php echo htmlspecialchars($team['teamName']); ?></p>
                    <p><strong>Ranking:</strong> <?php echo htmlspecialchars($team['ranking']); ?></p>
                    <p><strong>Total Points:</strong> <?php echo htmlspecialchars($team['totalPoints']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($team['status']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div>
            <p>Sorry, no league found or invalid league ID provided.</p>
        </div>
    <?php endif; ?>

    <?php
    // Close connections
    $league_query->close();
    $teams_query->close();
    $conn->close();
    ?>
</body>
</html>
