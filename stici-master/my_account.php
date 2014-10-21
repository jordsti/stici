<?php
require_once("actions/MyAccountAction.php");
$page = new MyAccountAction();
$page->execute();

require_once("header.php");
require_once("sidemenu.php");
require_once("errors.php");
?>

<div class="container">
	<h4>My Account, <?php echo $page->user->username; ?></h4>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">
			Change password
			</div>
			<div class="panel-body">
			<form role="form" method="post" action="my_account.php?chpass">
			
			<div class="form-group">
				<label for="actual" class="col-sm-4 col-sm-offset-1">
					Current password
				</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" name="actual" id="actual">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-sm-4 col-sm-offset-1">
					New password
				</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" name="password" id="password">
				</div>
			</div>
			
			<div class="form-group">
				<label for="actual" class="col-sm-4 col-sm-offset-1">
					New password confirm
				</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" name="password2" id="password2">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Change</button>
					</div>
			</div>
			
			</form>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">
				Change your email
			</div>
			<div class="panel-body">
			<form role="form" action="my_account.php?chemail" method="post">
				
			<div class="form-group">
				<label for="email" class="col-sm-4 col-sm-offset-1">
					Email
				</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="email" id="email" value="<?php echo $page->user->email; ?>">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Change</button>
					</div>
			</div>
			</form>
			
			</div>
		</div>
	</div>
</div>

<?php 

require_once("footer.php");