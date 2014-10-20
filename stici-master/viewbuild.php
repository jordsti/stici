<?php
	require_once("actions/ShowBuildAction.php");
	$page = new ShowBuildAction();
	$page->execute();
	require_once("header.php");
	
?>
	<div class="sidemenu">
		<h4>Menu</h4>
		<a class="btn btn-default" href="job.php?job_id=<?php echo $page->job->id; ?>">View job</a>
		<a class="btn btn-default" href="workers.php">Delete this build</a>
	</div>


		<div class="container maintable">
			<h4>Build(s)</h4>
			<table class="table table-hover">
				<tr>
					<th>Status</th>
					<th>Build Time</th>
					<th>Build Number</th>
				</tr>
				
				<tr>
					<td><?php echo $page->build->getStatusText(); ?></td>
					<td><?php echo $page->build->getBuildTime(); ?></td>
					<td><?php echo $page->build->buildNumber; ?></td>
				</tr>
			</table>
			
			<h4>File(s)</h4>
			<?php 
			if($page->testGroupFlags(Group::$ViewFile))
			{
			
			?>
			<table class="table table-hover">
				<tr>
					<th>Filename</th>
					<th>Size</th>
					<th>Hash</th>
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
					<td><?php echo $f->timeAgo(); ?></td>
					<td><a class="btn btn-default" href="download_file.php?file_id=<?php echo $f->id; ?>">Download</a></td>
				</tr>
			<?php 
			}
			?>
			</table>
			
			<?php 
			}
			?>
		
			<h4>Steps</h4>
			
			<?php
			foreach($page->logs as $l)
			{
			?>
			<div class="row">
				<div class="col-md-6">
					<?php echo nl2br($l->stdout); ?>
				</div>
				<div class="col-md-6 errtxt">
					<?php echo nl2br($l->stderr); ?>
				</div>
			</div>
			<?php
			}
			?>
			
		</div>
		
		
		

<?php


	require_once("footer.php");