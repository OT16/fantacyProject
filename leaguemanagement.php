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

// add navbar bit
include ("navbar.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="activity-styles.css" /> 		
</head>
<body>
    <div class="main">
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
    </div>
</body>
</html>
