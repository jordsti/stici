<?php
require_once("actions/GroupsAction.php");
$page = new GroupsAction();
$page->execute();
require_once("header.php");
require_once("settings_sidemenu.php");

$flags = Group::GetFlags();
?>

<div class="container">

	<h3> New Group </h3>
	<div class="new_group">
		<form method="post" role="form" action="groups.php?add">
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="name" name="name">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Add</button>
				</div>
			</div>
		</form>
	</div>
	<div class="groups">
	<h3>Group(s)</h3>
		<form role="form" method="post" action="groups.php?save">
		<?php
		$g_i = 0;
		foreach($page->groups as $g)
		{
		?>
		<div class="row">
			<h4><?php echo $g->name; ?></h4>
			<input type="hidden" name="group_id_<?php echo $g_i; ?>" value="<?php echo $g->id; ?>">
			<?php
			foreach($flags as $f)
			{
			?>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="flag_<?php echo $g_i; ?>_<?php echo $f->name; ?>" <?php if($g->testFlags($f->value)){ echo 'checked'; } ?>>
					<?php echo $f->description; ?>
				</label>
			</div>
			<?php
			}
			?>
		</div>
		<a class="btn btn-default" href="groups.php?delete&group_id=<?php echo $g->id; ?>">Delete</a>
		<?php
		$g_i++;
		}
		?>
		<div class="row">
			<button type="submit" class="btn btn-default">Save</button>
		</div>
		</form>
	</div>

</div>

<?php
require_once("footer.php");