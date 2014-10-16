<?php
	require_once("actions/DashboardAction.php");
	$page = new DashboardAction();
	$page->execute();

	require_once("header.php");
	require_once("sidemenu.php");
?>
		
		<!-- page here -->
		<div class="container">
			<h4>Job(s)</h4>
			<table class="table table-hover maintable">
				<tr>
					<th>Name</th>
					<th>Status</th>
					<th>Build Time</th>
					<th>Build Number</th>
					<th>Build On</th>
				</tr>
				<?php
				$jobs = $page->getJobs();
				foreach($jobs as $job)
				{
				?>
					<tr>
						<td><a href="job.php?job_id=<?php echo $job->getId(); ?>"><?php echo $job->getName(); ?></a></td>
						<td><?php echo $job->getStatusId(); ?></td>
						<td><?php echo $job->getName(); ?></td>
						<td><?php echo $job->getBuildNumber(); ?></td>
						<td><?php echo $job->getName(); ?></td>
					</tr>
				<?php
				}
				?>
					
			</table>
		</div>
<?php
	require_once("footer.php");