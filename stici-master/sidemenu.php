	<div class="sidemenu">
		<h4>Menu</h4>
		<?php if($page->isLogged())
		{
		?>
		<a class="btn btn-default" href="my_account.php">My account</a>
		<?php		
		}		
		if($page->testGroupFlags(Group::$AddJob))
		{
		?>
		<a class="btn btn-default" href="addjob.php">Add job</a>
		<?php 
		}
		?>
		
		<?php 
		if($page->testGroupFlags(Group::$ViewWorker))
		{
		?>
		<a class="btn btn-default" href="workers.php">View Workers</a>
		<?php 
		}
		
		?>
		<?php 
		if($page->testGroupFlags(Group::$AddJob))
		{
		?>
		<a class="btn btn-default" onclick="import_job();">Import job</a>
		<div id="import_form">
			<form role="form" action="import_job.php" method="post" enctype="multipart/form-data">
					<label>
					Job File
					</label>
					<input type="file" name="job" class="form-control" width="160px">
					<button type="submit" class="btn btn-default">Import</button>
			</form>
		</div>
		<?php 
		}
		?>
	</div>