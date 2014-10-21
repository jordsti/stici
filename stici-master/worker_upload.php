<?php 
require_once("db/DbBuildFile.php");
require_once("db/DbWorkerKey.php");

if(isset($_FILES['fileupload'])
		&& isset($_POST['build_id']) 
		&& isset($_POST['hash'])
		&& isset($_POST['filename'])
		&& isset($_POST['key'])
		)
{
	$key = $_POST['key'];
	if(DbWorkerKey::TestKey($key))
	{
		$filename = $_POST['filename'];
		$dest = 'files/'.$filename;
		//todo
		if(move_uploaded_file($_FILES['fileupload']['tmp_name'], $dest))
		{
			DbBuildFile::AddFile($_POST['build_id'], $dest, $filename, $_POST['hash']);
			echo 'File upload!';
		}
	}
	
}
