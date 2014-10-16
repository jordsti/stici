<?php
	require_once("actions/JobAction.php");
	$page = new JobAction();
	$page->execute();
	require_once("header.php");
	
	if(count($page->getErrors()) > 0)
	{
		$errors = $page->getErrors();
		foreach($errors as $e)
		{
			echo $e;
		}
	}
	else
	{
	
	require_once("job_sidemenu.php");
?>

		<div class="container">
			<h4>Build(s)</h4>
			<table class="table table-hover">
				<tr>
					<th>Status</th>
					<th>Build Time</th>
					<th>Build Number</th>
					<th>Build On</th>
				</tr>
			</table>
		</div>
		
		<div class="container">
			<h4>Last job(s)</h4>
			<table class="table table-hover">
				<tr>
					<th>Job Id</th>
					<th>Worker Id</th>
					<th>Status</th>
				</tr>
				
				<?php
				$jobs = $page->getJobs();
				foreach($jobs as $j)
				{
				?>
				<tr>
					<td><?php echo $j->id; ?></td>
					<td><?php echo $j->workerId; ?></td>
					<td><?php echo $j->getTextStatus(); ?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
		
		

<?php
	}

	require_once("footer.php");