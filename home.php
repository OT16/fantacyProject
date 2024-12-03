<?php
session_start();
include 'connect.php';

$status = "";

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$sqlUser = "SELECT userID, fullName FROM users WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);

if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $userID = $rowUser['userID'];
    $fullName = $rowUser['fullName'];
}

$sqlUserLeagues = "
    SELECT 
        leagueID, 
        leagueName, 
        leagueType, 
        maxTeams, 
        draftDate 
    FROM leagues
    WHERE commissioner = '$userID'";
$resultUserLeagues = mysqli_query($conn, $sqlUserLeagues);

$userLeagues = [];
if ($resultUserLeagues && mysqli_num_rows($resultUserLeagues) > 0) {
    while ($rowLeague = mysqli_fetch_assoc($resultUserLeagues)) {
        $userLeagues[] = $rowLeague;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $leagueName = $_POST['leagueName'];
  $leagueType = $_POST['leagueType'];
  $maxTeams = (int)$_POST['maxTeams'];
  $draftDate = $_POST['draftDate'];

  do { // need to get the leagueID to be unique
      $leagueID = random_int(10000000, 99999999);
      $sqlCheck = "SELECT COUNT(*) AS count FROM leagues WHERE leagueID = '$leagueID'";
      $resultCheck = mysqli_query($conn, $sqlCheck);
      $row = mysqli_fetch_assoc($resultCheck);
  } while ($row['count'] > 0);

  if (!empty($leagueName) && in_array($leagueType, ['P', 'R']) && $maxTeams > 0 && $maxTeams <= 99 && !empty($draftDate)) {
      $sql = "INSERT INTO leagues (leagueID, leagueName, leagueType, commissioner, maxTeams, draftDate)
              VALUES ('$leagueID', '$leagueName', '$leagueType', '$userID', '$maxTeams', '$draftDate')";

      if (mysqli_query($conn, $sql)) {
          $status = "League created successfully!";
          header("Location: home.php");
          exit();
      } else {
          $status = "Error creating league: " . mysqli_error($conn);
      }
  }
}


if (isset($_GET['leagueID'])) {
    $leagueID = $_GET['leagueID'];
    header("Location: league.php");
    exit();
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
    <h1>Welcome, 
      <?php echo $fullName; ?>!</h1>
    </h1>
  </div>

  <section id="contests" class="py-5">
    <h2 class="category">
      Your Leagues
    </h2>

    <div class="container">
      <div class="row">
        <?php if (!empty($userLeagues)): ?>
          <?php foreach ($userLeagues as $league): ?>
            <div class="col-md-4 col-lg-2 mb-4">
              <div class="card">
                <div class="card-header">
                  <?php echo htmlspecialchars($league['leagueName']); ?>
                </div>
                <div class="card-body">
                  <p>League ID: <?php echo htmlspecialchars($league['leagueID']); ?></p>
                  <p>Maximum number of teams: <?php echo htmlspecialchars($league['maxTeams']); ?></p>
                  <p>Draft date: <?php echo htmlspecialchars($league['draftDate']); ?></p>
                  
                </div>
                <div class="card-footer text-muted">
                  <div class="select">
                    <a href="league.php?leagueID=<?php echo $league['leagueID']; ?>" class="btn btn-primary">Details</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">You are not managing any leagues yet.</p>
        <?php endif; ?>
      </div>
    </div>


  </section>

  <section id="create-league" class="py-5">
    <h2 class="category">
      Create A New League
    </h2>
    <div class="container">
        <div class="card" style="width: 35%;">

            <div class="card-body-submit">

              <form method="POST" action="home.php">
                <div class="form-section">
                  <label for="leagueName">League Name: </label>
                  <input type="text" id="leagueName" name="leagueName" maxlength="30" required>
                </div>
                <div class="form-section">
                  <label for="leagueType">League Type: </label>
                  <select id="leagueType" name="leagueType" required>
                    <option value="P">Public</option>
                    <option value="R">Private</option>
                  </select>
                </div>
                <div class="form-section">
                  <label for="maxTeams">Maximum Teams: </label>
                  <input type="number" id="maxTeams" name="maxTeams" min="10" max="99" required>
                </div>
                <div class="form-section">
                  <label for="draftDate">Draft Date: </label>
                  <input type="date" id="draftDate" name="draftDate" required>
                </div>

                <div class="select">
                  <button type="submit" class="btn btn-primary">Create League</button>
                </div>
                
              </form>

            </div>
        </div>
    </div>
</section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
