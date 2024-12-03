<?php
// Connect to the database
include("connect.php");


// Fetch all drafts
$draft_query = $conn->prepare("SELECT * FROM drafts");
$draft_query->execute();
$draft_result = $draft_query->get_result();

$drafts = [];
if ($draft_result) {
    while ($row = $draft_result->fetch_assoc()) {
        $drafts[] = $row;
    }
}

// Close the draft query
$draft_query->close();

// add navbar bit
include("navbar.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Drafts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="activity-styles.css" /> 		
</head>
<body>

    <div class="hero">
        <h1>All Drafts</h1>
    </div>
    <div class="main mt-2">
        
        <?php if (!empty($drafts)): ?>
            <div class="row">
                <?php foreach ($drafts as $draft): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Draft ID: <?php echo $draft['draftID']; ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Draft Date:</strong> <?php echo ($draft['draftDate']); ?></p>
                                <p><strong>Draft Order:</strong> <?php echo ($draft['draftOrder']); ?></p>
                                <p><strong>Draft Status:</strong> <?php echo $draft['draftStatus']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No drafts found.</p>
        <?php endif; ?>

        <?php
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
