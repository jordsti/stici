<?php
require_once("db/DbWorker.php");
require_once("db/DbWorkerKey.php");

if(isset($_GET['build_id']) && isset($_GET['hash']) && isset($_GET['status']) && isset($_GET['key']))
{
	$key = $_GET['key'];
	
	if(DbWorkerKey::TestKey($key))
	{
	
		$build_id = $_GET['build_id'];
		$whash = $_GET['hash'];
		$status = $_GET['status'];
		DbWorker::EndBuild($build_id, $whash, $status);
	
	}
}