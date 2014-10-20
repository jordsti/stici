<?php


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sti::CI - <?php echo $page->getTitle(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/stici.css" rel="stylesheet">
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="nagivation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
					</button>
				
					<a class="navbar-brand" href="index.php">Sti::CI</a>
				</div>
				
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="index.php">Dashboard</a></li>
						<li><a href="settings.php">Settings</a></li>
					</ul>
				</div>
				
			</div>
		</div>
		
		<div class="spacer">
		</div>
		<!-- <div class="footer">
			<a href="https://github.com/jordsti/stici">Sti::CI</a>
		</div> -->
		<script src="js/stici.js"></script>
	</body>
</html>