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
						<?php 
						if($page->testGroupFlags(Group::$ViewDash))
						{
						?>
						<li><a href="index.php">Dashboard</a></li>
						<?php
						}
						
						if($page->testGroupFlags(Group::$EditSettings))
						{
						?>
						<li><a href="settings.php">Settings</a></li>
						<?php 
						}
						
						if($page->isLogged())
						{
						?>
						<li><a href="logout.php">Log out</a></li>
						<?php
						}
						else 
						{
						?>
						<li><a href="login.php">Sign in</a></li>
						<?php
						}
						?>
					</ul>
				</div>
				
			</div>
		</div>