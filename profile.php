<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT fullName FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fullName = $row['fullName'];
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newOtherDetails = mysqli_real_escape_string($conn, $_POST['otherDetails']);

    $updateSql = "UPDATE users SET fullName = '$newFullName', email = '$newEmail' WHERE username = '$username'";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <title>login</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Inconsolata:wght@200..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<link rel="stylesheet" href="activity-styles.css" /> 

<script>function lti_launch( vars, target ) {
						var query = '';
						var new_tab = false;

						for(var key in vars) {
							if(query.length == 0) {
								query += '?' + key + '=' + encodeURIComponent(vars[key]);
							}
							else {
								query += '&' + key + '=' + encodeURIComponent(vars[key]);
							}
						}

						var url = '/d2l/customization/pearsonlti/6606/Launch' + query;(target == '_blank') ? window.open( url, '_blank' ) : location.replace( url );}</script><script src="https://s.brightspace.com/lib/bsi/2024.10.209/unbundled/embeds.js" type="module"></script><script>document.addEventListener('DOMContentLoaded', function() {
					window.D2L.EmbedRenderer.renderEmbeds(document.body);
				});</script><script src="https://s.brightspace.com/lib/bsi/2024.10.209/unbundled/mathjax.js" type="module"></script><script>document.addEventListener('DOMContentLoaded', function() {
						if (document.querySelector('math') || /\$\$|\\\(|\\\[|\\begin{|\\ref{|\\eqref{/.test(document.body.innerHTML)) {
							document.querySelectorAll('mspace[linebreak="newline"]').forEach(elm => {
								elm.setAttribute('style', 'display: block; height: 0.5rem;');
							});

							window.D2L.MathJax.loadMathJax({
								outputScale: 1.5,
								renderLatex: true,
								enableMML3Support: false
							});
						}
					});</script><script src="https://s.brightspace.com/lib/bsi/2024.10.209/unbundled/prism.js" type="module"></script><script>document.addEventListener('DOMContentLoaded', function() {
					document.querySelectorAll('.d2l-code').forEach(code => {
						window.D2L.Prism.formatCodeElement(code);
					});
				});</script><script src="https://s.brightspace.com/lib/bsi/2024.10.209/unbundled/creator.js" type="module"></script><script>document.addEventListener('DOMContentLoaded', async () => {
					const activities = [];
					const practices = [];
					const allPractices = document.querySelectorAll('.d2l-practice');
					const elements = document.querySelectorAll('.d2l-element');
					let layouts = [...document.querySelectorAll('.d2l-cplus-layout')];
					const tables = document.querySelectorAll('table');
					const contentStylerStyleLinks = document.querySelectorAll('head > link[href="https://templates.lcs.brightspace.com/lib/assets/css/styles.min.css"]');
					const contentStylerOverides = document.querySelectorAll('head > link[href^="/d2l/le/contentstyler/"][data-override="override"]');

					layouts = layouts.filter((layout) => layout.closest('.d2l-element.mceNonEditable') === null);

					for (const practice of allPractices) {
						const activityContainer = practice.hasAttribute('data-d2l-activity-href');
						if (activityContainer) {
							activities.push(practice);
						}
						else {
							practices.push(practice);
						}
					}

					if (layouts.length > 0) {
						window.D2L.Creator.convertLayouts(layouts);
					}
					if (elements.length > 0) {
						window.D2L.Creator.convertElements(elements, 6606, '/content/', 'True');
					}
					if (activities.length > 0) {
						window.D2L.Creator.convertPracticeActivities(activities, 6606, '/content/');
					}
					if (practices.length > 0) {
						window.D2L.Creator.convertPractices(practices, 6606, '/content/');
					}
					if (contentStylerStyleLinks.length > 0 && contentStylerOverides.length > 0 && tables.length > 0) {
						window.D2L.Creator.wrapTables(tables);
					}
				});</script><script>window.addEventListener('message', function(event) { 
					if( !event.data ) {
						return;
					}

					let params;
					try {
						params = JSON.parse( event.data );
					}
					catch {
						return;
					}
					if( !params.subject || params.subject !== 'lti.frameResize' ) {
						return;
					}

					const MAX_FRAME_HEIGHT = 10000;
					if( !params.height || params.height < 1 || params.height > MAX_FRAME_HEIGHT ) {
						console.warn( 'Invalid height value received, aborting' );
						return;
					}
					const el = document.getElementsByTagName( 'iframe' );
					for ( let i=0; i < el.length; i++ ) {
						if( el[i].contentWindow === event.source ) {
							el[i].style.height = params.height + 'px';

							console.info( 'Setting iFrame height to ' + params.height );

							if( el[i].getAttribute('lockwidth') !== 'true'){
								el[i].style.width = '100%';
								console.info( 'Setting iFrame width to 100%' );
							}
						}
					}
				});</script></head>
<body>  

	<!-- Navigation Bar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
		  <a class="navbar-brand" href="#">
			<img src="./images/logo.png" style="height: 50px;">
		  </a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto">
			  <li class="nav-item">
				<a class="nav-link active" href="home.php">Home</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="profile.php">Profile</a>
			  </li>
			</ul>
		  </div>
		</div>
	  </nav>

    <div class="hero">
        <h1>Profile Settings</h1>
    </div>

	<div class="container">
		<div class="child1">
			<h3 style="text-align: center;">Update Details</h3>
			
			<form class="form" action="profile.php" method="post">  
				<input type="text" id="uname" name="fullName" placeholder="Full Name" value="" required>
				<input type="email" id="email" name="email"  placeholder="Email" value="" required>
				<input type="submit" value="Save changes" class="btn"/>
			</form>
		</div>
	</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>
