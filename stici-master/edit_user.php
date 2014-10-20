<?php
require_once("actions/EditUserAction.php");
$page = new EditUserAction();
$page->execute();
require_once("header.php");
require_once("settings_sidemenu.php");
require_once("errors.php");
?>

<div class="container">
	<h4>Edit User</h4>
	<div class="row">
		<div class="col-sm-4">
		<strong>Username</strong>
		</div>
		<div class="col-sm-8">
		<?php echo $page->_user->username; ?>
		</div>
		<div class="col-sm-4">
		<strong>Email</strong>
		</div>
		<div class="col-sm-8">
		<?php echo $page->_user->email; ?>
		</div>
	</div>
	<div class="row">
		<form role="role" method="post" action="edit_user.php?user_id=<?php echo $page->_user->id; ?>&addgrp">
		<div class="col-sm-2">
		Assign new group
		</div>
		<select name="add_group" class="form-control">
			<?php
			foreach($page->all_groups as $g)
			{
			?>
			<option value="<?php echo $g->name; ?>"><?php echo $g->name; ?></option>
			<?php
			}
			?>
		</select>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset2">
				<button type="submit" class="btn btn-default">Add</button>
			</div>
		</div>
	</div>
	
	<div class="row">
		<h4>User groups</h4>
		
		<?php
		foreach($page->_groups as $g)
		{
		?>
		<div class="row">
			<div class="col-sm-4">
			<?php echo $g->name; ?>
			</div>
			<div class="col-sm-4">
			<a href="edit_user.php?user_id=<?php echo $page->_user->id; ?>&group=<?php echo $g->name; ?>&remgrp" class="btn btn-default">Remove</a>
			</div>
		</div>
		<?php
		}
		?>
		
	</div>
</div>

<?php
require_once("footer.php");