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

// get all teams
$sqlTeams = "SELECT * FROM teams WHERE leagueID = '$leagueID'";
$resultTeams = mysqli_query($conn, $sqlTeams);

$teamIDs = [];
if ($resultTeams && mysqli_num_rows($resultTeams) > 0) {
    while ($rowTeam = mysqli_fetch_assoc($resultTeams)) {
        $teams[] = $rowTeam;
        $teamIDs[] = $rowTeam['teamID'];
    }
}

if (!empty($teamIDs)) {
    $teamIDsList = implode(',', array_map('intval', $teamIDs)); // Ensure safety with intval

    $sqlMatches = "
    SELECT 
        matches.*,
        t1.teamName AS team1Name,
        t2.teamName AS team2Name,
        winnerTeams.teamName AS winnerTeamName
    FROM matches
        LEFT JOIN teams t1 ON matches.team1ID = t1.teamID
        LEFT JOIN teams t2 ON matches.team2ID = t2.teamID
        LEFT JOIN teams winnerTeams ON matches.winner = winnerTeams.teamID
    WHERE matches.team1ID IN ($teamIDsList) 
       OR matches.team2ID IN ($teamIDsList)";

    $resultMatches = mysqli_query($conn, $sqlMatches);

    $matches = [];
    if ($resultMatches && mysqli_num_rows($resultMatches) > 0) {
        while ($rowMatch = mysqli_fetch_assoc($resultMatches)) {
            $matches[] = $rowMatch;
        }
    }
}

$sqlAvailablePlayers = "
    SELECT playerID, fullName, sport, position, realTeam, fantasyPoints, availabilityStatus 
    FROM players 
    WHERE availabilityStatus = 'A'";
$resultAvailablePlayers = mysqli_query($conn, $sqlAvailablePlayers);

$availablePlayers = [];
if ($resultAvailablePlayers && mysqli_num_rows($resultAvailablePlayers) > 0) {
    while ($rowPlayer = mysqli_fetch_assoc($resultAvailablePlayers)) {
        $availablePlayers[] = $rowPlayer;
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
                                    <a href="teamdetails.php?teamID=<?php echo $team['teamID']; ?>" class="btn btn-primary">See More</a>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No teams in this league.</p>
        <?php endif; ?>

    </section>

    <section id="contests" class="py-5">
    <h2 class="category">
      Matches
    </h2>

    <div class="container">
      <div class="row">
        <?php if (!empty($matches)): ?>
            <?php foreach ($matches as $match): ?>
            <div class="col-md-4 col-lg-2 mb-4" style="width: 50%">
                <div class="card-matches">
                    <div class="card-header">Match ID: <?php echo htmlspecialchars($match['matchID']); ?></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <p>Team: <?php echo htmlspecialchars($match['team1Name']); ?></p>
                                    <p>ID: <?php echo htmlspecialchars($match['team1ID']); ?></p>
                                </div>
                                <div class="col-md-4" >
                                    <div class="text-center">
                                        <p>Date: <?php echo htmlspecialchars($match['matchDate']); ?></p>
                                        <p>Final Score: <?php echo htmlspecialchars($match['finalScore']); ?></p>
                                        <p>- Winner -</p>
                                        <p style="font-weight:bold"><?php echo htmlspecialchars($match['winnerTeamName']); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p>Team: <?php echo htmlspecialchars($match['team2Name']); ?></p>
                                    <p>ID: <?php echo htmlspecialchars($match['team2ID']); ?></p>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                   
                <?php endif; ?>
            </div>
        </div>
    </section>


    <section id="waiver-pool" class="py-5">
    <h2 class="category">Waiver Pool</h2>

    <?php if (!empty($availablePlayers)): ?>
        <div class="container">
            <div class="row">
                <?php foreach ($availablePlayers as $player): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Player: <?php echo htmlspecialchars($player['fullName']); ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Sport:</strong> <?php echo htmlspecialchars($player['sport']); ?></p>
                                <p><strong>Position:</strong> <?php echo htmlspecialchars($player['position']); ?></p>
                                <p><strong>Real Team:</strong> <?php echo htmlspecialchars($player['realTeam']); ?></p>
                                <p><strong>Fantasy Points:</strong> <?php echo htmlspecialchars($player['fantasyPoints']); ?></p>
                                <p><strong>Availability:</strong> <?php echo htmlspecialchars($player['availabilityStatus']); ?></p>
                            </div>
                            <div class="select">
                                <a href="playerdetails.php?playerID=<?php echo $player['playerID']; ?>" class="btn btn-primary">See Player Statistics</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="text-center">No players available in the waiver pool.</p>
    <?php endif; ?>
</section>


    <div class="browse-more">
        <a href="leaguemanagement.php?leagueID=<?php echo $league['leagueID']; ?>" class="btn btn-primary">Manage League</a>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
