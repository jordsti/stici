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
	
	if($page->testGroupFlags(Group::$ViewBuild))
	{
	
?>

		<div class="container maintable">
			<h4>Build(s)</h4>
			<table class="table table-hover">
				<tr class="active">
					<th>Status</th>
					<th>Build Time</th>
					<th>Build Number</th>
					<th>-</th>
				</tr>
			<?php
				$builds = $page->getBuilds();
				foreach($builds as $b)
				{

				$row_class = "normal_row";
				
				if($b->status == Build::$Building)
				{
					$row_class = "info";	
				}
				else if($b->status == Build::$Success)
				{
					$row_class = "success";	
				}
				else if($b->status == Build::$Failed)
				{
					$row_class = "danger";	
				}
				
			?>
				<tr class="<?php echo $row_class; ?>">
					<td><?php echo $b->getStatusText(); ?></td>
					<td><?php echo $b->getBuildTime(); ?></td>
					<td><?php echo $b->buildNumber; ?></td>
					<td><a class="btn btn-default" href="viewbuild.php?build_id=<?php echo $b->id; ?>">View Build</a></td>
				</tr>
			
			<?php
				}
			?>
				
				
			</table>
		</div>
		
		<?php 
		}
		
		if($page->testGroupFlags(Group::$ViewFile))
		{
		?>
		<div class="container maintable">
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
		</div>
		<?php 
		}
		?>
		
		
		<div class="container maintable">
			<h4>Last job(s)</h4>
			<table class="table table-hover">
				<tr class="active">
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