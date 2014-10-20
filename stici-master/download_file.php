<?php

if(isset($_GET['file_id']))
{
	require_once("db/DbBuildFile.php");
	
	$f = DbBuildFile::GetFile($_GET['file_id']);
	
	if(!is_int($f))
	{

		header('Content-Type: application/octet-stream');
		header("Content-length: " . filesize($f->filepath));
		header('Content-Disposition: attachment; filename="' . $f->filename . '"');
		header('Content-Transfer-Encoding: binary');

		
		readfile($f->filepath);
	}
}