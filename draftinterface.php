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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Details</title>
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
        .draft-card, .league-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
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
</body>
</html>
