<?php
	require_once("actions/WorkersAction.php");
	$page = new WorkersAction();
	$page->execute();

	require_once("header.php");
	require_once("sidemenu.php");
	$workers = $page->workers;
	?>
		
		<div class="container">
		<?php if(count($workers) == 0)
		{
		?>
			<h3>No worker are connected at the moment</h3><br>
		<?php
		}
		?>
			<h4>Workers(s)</h4>
			<table class="table table-hover maintable">
				<tr>
					<th>Hostname</th>
					<th>IP</th>
					<th>Hash</th>
					<th>Status</th>
					<th>Last Action</th>
				</tr>
				<?php
				foreach($workers as $w)
				{
				?>
					<tr>
						<td><?php echo $w->hostname; ?></td>
						<td><?php echo $w->remoteAddr; ?></td>
						<td><?php echo $w->hash; ?></td>
						<td><?php echo $w->getStatusText(); ?></td>
						<td><?php echo $w->getLastAction(); ?></td>
					</tr>
				<?php
				}
				?>
					
			</table>
		</div>
<?php
	require_once("footer.php");