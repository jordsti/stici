<?php
	require_once("actions/DashboardAction.php");
	$page = new DashboardAction();
	$page->execute();

	require_once("header.php");
	require_once("sidemenu.php");
?>
		
		<!-- page here -->
		<div class="container maintable">
			<h4>Job(s)</h4>
			<table class="table table-hover">
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
						<td><?php echo $job->getStatusText(); ?></td>
						<td><?php echo $job->getName(); ?></td>
						<td><?php echo $job->getBuildNumber(); ?></td>
						<td><?php echo $job->getBuildTimeAgo(); ?></td>
					</tr>
				<?php
				}
				?>
					
			</table>
			
			<h4>Last builds</h4>
			<table class="table table-hover">
				<tr>
					<th>Build Number</th>
					<th>Job</th>
					<th>Build Time</th>
					<th>Status</th>
					<th>-</th>
				</tr>
			<?php
			foreach($page->builds as $b)
			{
			?>
				<tr>
					<td><a href="viewbuild.php?build_id=<?php echo $b->id; ?>"><?php echo $b->buildNumber; ?></a></td>
					<td><a href="job.php?job_id=<?php echo $b->jobId; ; ?>"><?php echo $b->jobName; ?><a></td>
					<td><?php echo $b->getBuildTime(); ?></td>
					<td><?php echo $b->getStatusText(); ?></td>
					<td><?php echo $b->getBuildTimeAgo(); ?></td>
				</tr>
			
			<?php
			}
			?>			
			</table>
			
		</div>
<?php
	require_once("footer.php");