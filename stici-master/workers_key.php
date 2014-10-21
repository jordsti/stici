<?php
require_once("actions/WorkerKeyAction.php");
$page = new WorkerKeyAction();
$page->execute();
require_once("header.php");
require_once("settings_sidemenu.php");
?>

<div class="container">

	<div class=row>
		<div class="col-sm-10 col-sm-offset-1">
			<a href="workers_key.php?generate" class="btn btn-default">Generate new Key</a>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<table class="table table-hover">
				<tr>
					<th>Key</th>
					<th>Status</th>
					<th>Created</th>
					<th>-</th>
				</tr>
				
				<?php 
				foreach($page->keys as $k)
				{
				?>
				<tr>
					<td><?php echo $k->key; ?></td>
					<td><?php echo $k->status; ?></td>
					<td><?php echo $k->timeAgo(); ?></td>
					<td>
					<?php 
					if($k->status == WorkerKey::$Active)
					{
					?>
					<a class="btn btn-default" href="workers_key.php?revoke&id=<?php echo $k->id; ?>">Revoke</a>
					<?php 
					}
					?>
					</td>
				</tr>
				
				<?php 
				}
				
				?>
			
			</table>
			
		</div>
	</div>

</div>

<?php
require_once("footer.php");