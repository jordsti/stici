		<div class="sidemenu">
			<h4><?php echo $page->getJob()->getName(); ?></h4>
			<a class="btn btn-default" href="job.php?job_id=<?php echo $page->getJob()->getId(); ?>">Home</a>
			
			<?php 
			if($page->testGroupFlags(Group::$LaunchBuild))
			{
			?>
			<a class="btn btn-default" href="launch_build.php?job_id=<?php echo $page->getJob()->getId(); ?>">Launch build</a>
			<?php 
			}
			?>
			<a class="btn btn-default">Delete this job</a>
			<?php 
			if($page->testGroupFlags(Group::$EditBuild))
			{
			?>
			<a class="btn btn-default" href="editbuild.php?job_id=<?php echo $page->getJob()->getId(); ?>">Edit build</a>
			<?php 
			}
			?>
			
			<a class="btn btn-default" href="export_job.php?job_id=<?php echo $page->getJob()->getId(); ?>">Export</a>
		</div>