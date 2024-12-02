<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) { 
    header("Location: login.html");
    exit();
}

if (!isset($_GET['leagueID'])) {
    echo "No league selected.";
    exit();
}

// user info
$username = $_SESSION['username'];
$sqlUser = "SELECT userID, fullName FROM users WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);

if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $userID = $rowUser['userID'];
    $fullName = $rowUser['fullName'];
}

// leagues
$leagueID = $_GET['leagueID'];
$sqlLeague = "SELECT * FROM leagues WHERE leagueID = '$leagueID'";
$resultLeague = mysqli_query($conn, $sqlLeague);

if ($resultLeague && mysqli_num_rows($resultLeague) > 0) {
    $league = mysqli_fetch_assoc($resultLeague);
} else {
    echo "League not found.";
    exit();
}

// teams
$sqlTeams = "SELECT * FROM teams WHERE leagueID = '$leagueID'";
$resultTeams = mysqli_query($conn, $sqlTeams);

$teams = [];
if ($resultTeams && mysqli_num_rows($resultTeams) > 0) {
    while ($rowTeam = mysqli_fetch_assoc($resultTeams)) {
        $teams[] = $rowTeam;
    }
}

// matches
$sqlMatches = "SELECT * FROM matches WHERE leagueID = '$leagueID'";
$resultMatches = mysqli_query($conn, $sqlMatches);

$matches = [];
if ($resultMatches && mysqli_num_rows($resultMatches) > 0) {
    while ($rowMatch = mysqli_fetch_assoc($resultMatches)) {
        $matches[] = $rowMatch;
    }
}

include ("navbar.html");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fantasy Sports Betting</title>
  <link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="activity-styles.css" /> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="hero">
        <h1><?php echo htmlspecialchars($league['leagueName']); ?></h1>
    </div>

    <section id="teams" class="py-5">
    <h2 class="category">Teams</h2>
    
        <?php if (!empty($teams)): ?>
            <div class="container">
                <?php foreach ($teams as $team): ?>
                        <div class="card" style="width: 25%;">
                            <div class="card-header">
                                <h5>
                                    Team ID: <?php echo htmlspecialchars($team['teamID']); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Team: <?php echo htmlspecialchars($team['teamName']); ?></h5>
                                <h5 class="card-title">Owner: <?php echo htmlspecialchars($username); ?></h5>
                                <h5 class="card-title">LeagueID: <?php echo htmlspecialchars($leagueID) ; ?></h5>
                            </div>
                            <div class="card-footer text-muted">
                                <div class="select">
                                    <a href="league.php?leagueID=<?php echo $league['leagueID']; ?>" class="btn btn-primary">See More</a>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No teams in this league.</p>
        <?php endif; ?>
    </section>

    <section id="matches" class="py-5">
    <h2 class="category">Matches</h2>

    <?php if (!empty($matches)): ?>
        <div class="container">
            <?php foreach ($matches as $match): ?>
                <div class="card my-3" style="width: 50%;">
                    <div class="card-header">
                        Match ID: <?php echo htmlspecialchars($match['matchID']); ?>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p>Team 1: <?php echo htmlspecialchars($match['team1']); ?></p>
                                <p>Score: <?php echo htmlspecialchars($match['score1']); ?></p>
                            </div>

                            <div class="col-md-4 text-center">
                                <p>Date: <?php echo htmlspecialchars($match['matchDate']); ?></p>
                                <p>Final Score: <?php echo htmlspecialchars($match['finalScore']); ?></p>
                                <p>Winner: <?php echo htmlspecialchars($match['winner']); ?></p>
                            </div>

                            <div class="col-md-4">
                                <p>Team 2: <?php echo htmlspecialchars($match['team2']); ?></p>
                                <p>Score: <?php echo htmlspecialchars($match['score2']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center">No matches yet.</p>
        <?php endif; ?>
    </section>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
