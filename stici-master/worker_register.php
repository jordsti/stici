<?php
	require_once("db/DbWorker.php");
	DbWorker::CleanWorker();
	if(isset($_GET['hash']) && isset($_GET['hostname']))
	{
		DbWorker::Register($_GET['hash'], $_SERVER['REMOTE_ADDR'], $_GET['hostname'] );
		echo "OK!\n";
	}
	else
	{
		echo "FAIL\n";
	}