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
	
?>

		<div class="sidemenu">
			<h4><?php echo $page->getJob()->getName(); ?></h4>
			<a class="btn btn-default" href="job.php?job_id=<?php echo $page->getJob()->getId(); ?>">Home</a>
			<a class="btn btn-default">Launch build</a>
			<a class="btn btn-default">Delete this job</a>
			<a class="btn btn-default" href="editbuild.php?job_id=<?php echo $page->getJob()->getId(); ?>">Edit build</a>
		</div>

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

<?php
	}

	require_once("footer.php");