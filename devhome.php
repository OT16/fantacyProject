<?php
// Start session (if needed) to validate user or handle access control
session_start();

// Check if user is authorized (if necessary)
if (!isset($_SESSION['fullName'])) {
    die('You need to be logged in to download the CSV.');
}

// Include your database connection file
include("connect.php");

// Check if the form is submitted to download the CSV
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_csv'])) {
    // Fetch all leagues from the database
    $query = "SELECT * FROM leagues"; // Adjust the table and column names as needed
    $result = $conn->query($query);

    // Check if there are any leagues to export
    if ($result->num_rows > 0) {
        // Set the headers to trigger a CSV download in the browser
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="leagues.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream to the browser
        $output = fopen('php://output', 'w');

        // Output the column headers as the first row (optional, but helpful for CSVs)
        $columns = $result->fetch_fields();
        $headers = [];
        foreach ($columns as $column) {
            $headers[] = $column->name; // Get column names (field names)
        }
        fputcsv($output, $headers);

        // Output each row of data as a CSV row
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row); // Writes each row as a CSV line
        }

        // Close the output stream (it will be closed automatically at the end of the script)
        fclose($output);
        exit(); // Make sure to stop further code execution after the CSV is sent
    } else {
        echo "No leagues found to export.";
    }

    // Close database connection
    $conn->close();
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
  <div class="hero">
    <h1>Welcome, 
      <?php echo $_SESSION['fullName']; ?>!</h1>
    </h1>
</div>
    <div class="container">
        <h1>Developer Portal</h1>
        <!-- Form to trigger CSV download -->
        <form action="" method="post">
            <button type="submit" name="download_csv" class="btn btn-success">Download Leagues CSV</button>
        </form>
    </div>



</body>
