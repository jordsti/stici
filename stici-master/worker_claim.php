<?php

	require_once("db/DbWorker.php");
	require_once("db/DbWorkerKey.php");
	
	if(isset($_GET['hash']) && isset($_GET['current_id']) && isset($_GET['key']))
	{
		$key = $_GET['key'];
		if(DbWorkerKey::TestKey($key))
		{
			$hash = $_GET['hash'];
			$current_id = $_GET['current_id'];
			
			if(DbWorker::ClaimJob($current_id, $hash))
			{
				echo "OK!\n";
			}
			else
			{
				echo "FAIL\n";
			}
		}
	}