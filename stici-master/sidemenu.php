	<div class="sidemenu">
		<h4>Menu</h4>
		<a class="btn btn-default" href="addjob.php">Add job</a>
		<a class="btn btn-default" href="workers.php">View Workers</a>
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
	</div>