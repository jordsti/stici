<?php
	require_once("db/DbWorker.php");
	
	if(isset($_GET['hash']))
	{
		$hash = $_GET['hash'];
		
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