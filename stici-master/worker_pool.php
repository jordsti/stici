<?php
	require_once("db/DbWorker.php");
	require_once("db/DbWorkerKey.php");
	
	if(isset($_GET['hash']) && isset($_GET['key']))
	{
		$hash = $_GET['hash'];
		$key = $_GET['key'];
		
		if(DbWorkerKey::TestKey($key))
		{
			if(DbWorker::WorkerIsRegistered($hash))
			{
				$pools = DbWorker::PollJob();
				
				foreach($pools as $p)
				{
					echo "JOB_ID:".$p->jobId.",CURRENT_ID:".$p->id.",TARGET:".$p->target."\n";
				}
				
				if(count($pools) == 0)
				{
					echo "None\n";
				}
				
				
			}
			else
			{
				echo "Not registered\n";
			}
		}
	}