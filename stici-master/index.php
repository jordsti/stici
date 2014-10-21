<?php
	require_once("actions/DashboardAction.php");
	$page = new DashboardAction();
	$page->execute();

	require_once("header.php");
	require_once("sidemenu.php");
?>
		
		<!-- page here -->
		<div class="container maintable">
		
			<?php 
			if($page->testGroupFlags(Group::$ViewJob))
			{
			?>
		
			<h4>Job(s)</h4>
			<table class="table table-hover">
				<tr class="active">
					<th>Name</th>
					<th>Status</th>
					<th>Build Time</th>
					<th>Build Number</th>
					<th>Build On</th>
					<th>Target</th>
				</tr>
				<?php
				$jobs = $page->getJobs();
				foreach($jobs as $job)
				{
					$row_class = "normal_row";
					
					if($job->status == Build::$Building)
					{
						$row_class = "info";
					}
					else if($job->status == Build::$Failed)
					{
						$row_class = "danger";
					}
					
				?>
					<tr class="<?php echo $row_class; ?>">
						<td><a href="job.php?job_id=<?php echo $job->getId(); ?>"><?php echo $job->getName(); ?></a></td>
						<td><?php echo $job->getStatusText(); ?></td>
						<td><?php echo $job->getName(); ?></td>
						<td><?php echo $job->getBuildNumber(); ?></td>
						<td><?php echo $job->getBuildTimeAgo(); ?></td>
						<td><img src="<?php echo Job::GetTargetIcon($job->target); ?>"></td>
					</tr>
				<?php
				}
				?>
					
			</table>
			
			<?php 
			}
			
			if($page->testGroupFlags(Group::$ViewFile))
			{
			?>
			
			<table class="table table-hover">
				<tr>
					<th>Filename</th>
					<th>Size</th>
					<th>Hash</th>
					<th>Job</th>
					<th>Uploaded</th>
					<th>-</th>
				</tr>
			<?php 
			foreach($page->files as $f)
			{
			?>
				<tr>
					<td><?php echo $f->filename; ?></td>
					<td><?php echo $f->size(); ?></td>
					<td><?php echo $f->hash; ?></td>
					<td><a href="job.php?job_id=<?php echo $f->jobId; ?>"><?php echo $f->jobName; ?></a></td>
					<td><?php echo $f->timeAgo(); ?></td>
					<td><a class="btn btn-default" href="download_file.php?file_id=<?php echo $f->id; ?>">Download</a></td>
				</tr>
			<?php 
			}
			?>
			</table>
			
			<?php 
			}
			
			
			if($page->testGroupFlags(Group::$ViewBuild))
			{
			?>
			
			<h4>Last builds</h4>
			<table class="table table-hover">
				<tr class="active">
					<th>Build Number</th>
					<th>Job</th>
					<th>Build Time</th>
					<th>Status</th>
					<th>-</th>
					<th>Target</th>
				</tr>
			<?php
			foreach($page->builds as $b)
			{
				$row_class = "normal_row";
					
				if($b->status == Build::$Building)
				{
					$row_class = "info";
				}
				else if($b->status == Build::$Failed)
				{
					$row_class = "danger";
				}
			?>
				<tr class="<?php echo $row_class; ?>">
					<td><a href="viewbuild.php?build_id=<?php echo $b->id; ?>"><?php echo $b->buildNumber; ?></a></td>
					<td><a href="job.php?job_id=<?php echo $b->jobId; ?>"><?php echo $b->jobName; ?></a></td>
					<td><?php echo $b->getBuildTime(); ?></td>
					<td><?php echo $b->getStatusText(); ?></td>
					<td><?php echo $b->getBuildTimeAgo(); ?></td>
					<td><img src="<?php echo Job::GetTargetIcon($b->target); ?>"></td>
				</tr>
			
			<?php
			}
			?>			
			</table>
			<?php
			}
			?>		
		</div>
<?php
	require_once("footer.php");