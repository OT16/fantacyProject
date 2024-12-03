<?php


// Test with: http://localhost/fantacyProject/draftinterface.php?draftID=1
// Connect to the database
include ("connect.php");

// Get draft ID from the GET request
$draftID = isset($_GET['draftID']) ? $_GET['draftID'] : null;

if ($draftID) {
    // Fetch draft details
    $draft_query = $conn->prepare("SELECT * FROM drafts WHERE draftID = ?");
    $draft_query->bind_param("i", $draftID);
    $draft_query->execute();
    $draft_result = $draft_query->get_result();
    $draft_data = $draft_result->fetch_assoc();

    if ($draft_data) {
        // Fetch league details
        $league_query = $conn->prepare("SELECT * FROM leagues WHERE leagueID = ?");
        $league_query->bind_param("i", $draft_data['leagueID']);
        $league_query->execute();
        $league_result = $league_query->get_result();
        $league_data = $league_result->fetch_assoc();
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
    <title>Draft Details</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="activity-styles.css" /> 		
</head>
<body>
                <div class="hero">
        <h1>Draft Interface</h1>
    </div>
    <div class="main">

    <?php if ($draft_data): ?>
        <div class="sidebar">
            <h3>Draft Details</h3>
            <p><strong>Draft ID:</strong> <?php echo htmlspecialchars($draft_data['draftID']); ?></p>
            <p><strong>Draft Date:</strong> <?php echo htmlspecialchars($draft_data['draftDate']); ?></p>
            <p><strong>Draft Order:</strong> <?php echo htmlspecialchars($draft_data['draftOrder']); ?></p>
            <p><strong>Draft Status:</strong> <?php echo htmlspecialchars($draft_data['draftStatus']); ?></p>

            <h4>League Involved</h4>
            <div class="league-card">
                <p><strong>League ID:</strong> <?php echo htmlspecialchars($league_data['leagueID']); ?></p>
                <p><strong>League Name:</strong> <?php echo htmlspecialchars($league_data['leagueName']); // Assuming 'leagueName' is a column in the leagues table ?></p>
            </div>
        </div>
        <div class="main-content">
            <!-- Additional content for the draft can be added here if needed -->
        </div>
    <?php else: ?>
        <div>
            <p>Sorry, no draft found or invalid draft ID provided.</p>
        </div>
    <?php endif; ?>

    <?php
    // Close connections
    $draft_query->close();
    $league_query->close();
    $conn->close();
    ?>
    </div>
</body>
</html>
