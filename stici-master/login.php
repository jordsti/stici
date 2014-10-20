<?php
require_once("actions/LoginAction.php");
$page = new LoginAction();
$page->execute();
require_once("header.php");
require_once("errors.php"); 
?>

		<div class="container loginform">
			<div class="panel panel-primary">
				<div class="panel-heading">
				Login
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post">
						<div class="form-group">
							<label for="username" class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="username" name="username">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="password" name="password" placeholder="password">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default">Sign in</button>
							</div>
						</div>
					</form>
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