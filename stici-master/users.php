<?php
require_once("actions/UsersAction.php");
$page = new UsersAction();
$page->execute();

require_once("header.php");
require_once("settings_sidemenu.php");
require_once("errors.php");
?>

<div class="container users">
	<div class="add_user">
		<div class="add_user_form" id="add_user_form">
		<form role="form" method="post" action="users.php?add">
			
			<div class="form-group">
				<label for="username" class="col-sm-2 control-label">Username</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="username" name="username" placeholder="username">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" id="password" name="password" placeholder="password">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password2" class="col-sm-2 control-label">Password (Confirm)</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" id="password2" name="password2" placeholder="password">
				</div>
			</div>	

			<div class="form-group col-sm-10 col-sm-offset-2">
				<label for="group" class="col-sm-2 control-label">Group</label>
	
				<select class="form-control" id="group" name="group">
				
				<?php
				foreach($page->groups as $g)
				{
				?>
					<option value="<?php echo $g->name; ?>"><?php echo $g->name; ?></option>
				<?php
				}
				?>
				
				</select>

			</div>					
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Save</button>
				</div>
			</div>
		</form>
		</div>
	</div>

	<div class="users_list">
	
	<h4>Users</h4>
	
	<table class="table table-hover">
		<tr class="active">
			<th>User Id</th>
			<th>Username</th>
			<th>Email</th>
			<th>Created On</th>
			<th>-</th>
		</tr>
		
		<?php
		foreach($page->users as $u)
		{
		?>
		<tr>
			<td><?php echo $u->id; ?></td>
			<td><?php echo $u->username; ?></td>
			<td><?php echo $u->email; ?></td>
			<td><?php echo $u->createdOn(); ?></td>
			<td><a class="btn btn-default" href="edit_user.php?user_id=<?php echo $u->id; ?>">Edit</a></td>
		</tr>
		<?php
		}
		?>
		
	</table>
	
	</div>
	
</div>

<?php
require_once("footer.php");
