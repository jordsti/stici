<?php
	require_once("db/DbWorker.php");
	DbWorker::CleanWorker();
	if(isset($_GET['hash']) && isset($_GET['hostname']) && isset($_GET['os']))
	{
		DbWorker::Register($_GET['hash'], $_SERVER['REMOTE_ADDR'], $_GET['hostname'], $_GET['os']);
		echo "OK!\n";
	}
	else
	{
		echo "FAIL\n";
	}