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

$username = $_SESSION['username'];
$sqlUser = "SELECT userID, fullName FROM users WHERE username = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser && $resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $userID = $rowUser['userID'];
    $fullName = $rowUser['fullName'];
} else {
    echo "User not found.";
    exit();
}

$leagueID = $_GET['leagueID'];
$sqlLeague = "SELECT * FROM leagues WHERE leagueID = ?";
$stmtLeague = $conn->prepare($sqlLeague);
$stmtLeague->bind_param("s", $leagueID);
$stmtLeague->execute();
$resultLeague = $stmtLeague->get_result();

if ($resultLeague && $resultLeague->num_rows > 0) {
    $league = $resultLeague->fetch_assoc();
} else {
    echo "League not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teamName = trim($_POST['teamName']);
    $totalPoints = 0;
    $ranking = 100;
    $status = 'A';

    if (!empty($teamName)) {
        do {
            $teamID = random_int(10000000, 99999999);
            $sqlCheck = "SELECT COUNT(*) AS count FROM teams WHERE teamID = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("s", $teamID);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $row = $resultCheck->fetch_assoc();
        } while ($row['count'] > 0);

        $sqlInsertTeam = "INSERT INTO teams (teamID, teamName, owner, leagueID, totalPoints, ranking, status)
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsertTeam);
        $stmtInsert->bind_param(
            "sssiiss", 
            $teamID, $teamName, $userID, $leagueID, 
            $totalPoints, $ranking, $status
        );

        if ($stmtInsert->execute()) {
            echo "Team created successfully!";
            header("Location: league.php?leagueID=" . $leagueID);
            exit();
        } else {
            echo "Error creating team: " . $conn->error;
        }
    } else {
        echo "Team name cannot be empty.";
    }
}

// Fetch teams in the league
$sqlTeams = "SELECT * FROM teams WHERE leagueID = ?";
$stmtTeams = $conn->prepare($sqlTeams);
$stmtTeams->bind_param("i", $leagueID);
$stmtTeams->execute();
$resultTeams = $stmtTeams->get_result();
$teams = $resultTeams->fetch_all(MYSQLI_ASSOC);

include("navbar.html");
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

        <section id="create-league" class="py-5">
            <div class="container">
                <div class="card" style="width: 35%;">
                    <div class="card-body-submit">
                    <form method="POST" action="">
                        <div class="form-section">
                        <h2 class="select">
                            Create A New Team
                        </h2>
                        <br><br><br>
                        <h5 class="select">
                            Team Name
                        </h5>
                        <div class="select">
                            <input type="text" id="teamName" name="teamName" maxlength="25" required>
                        </div>
                        </div>
                        <div class="select">
                            <br><br><br>
                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </section>

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
                                                        <div class="card-footer text-muted">
                            <div class="select">
                                <a href="playerdetails.php?playerID=<?php echo $player['playerID']; ?>" class="btn btn-primary">See Player Statistics</a>
                            </div>
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

    <section style="display:flex; gap: 140px;">
        <h2 class="category">
        Manage League
        </h2>
<p> View teams, see league details, and delete this league </p>
        <div class="select">
            <a href="leaguemanagement.php?leagueID=<?php echo $league['leagueID']; ?>" class="btn btn-primary">Manage League</a>
        </div>
    </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
