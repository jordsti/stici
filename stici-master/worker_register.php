<?php
	require_once("db/DbWorker.php");
	require_once("db/DbWorkerKey.php");
	
	DbWorker::CleanWorker();
	if(isset($_GET['hash']) && isset($_GET['hostname']) && isset($_GET['os']) && isset($_GET['key']))
	{
		$key = $_GET['key'];
		if(DbWorkerKey::TestKey($key))
		{	
			DbWorker::Register($_GET['hash'], $_SERVER['REMOTE_ADDR'], $_GET['hostname'], $_GET['os']);
			echo "OK!\n";
		}
	}
	else
	{
		echo "FAIL\n";
	}