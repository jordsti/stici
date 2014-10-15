<?php
	require_once("db/DbWorker.php");
	if(isset($_GET['current_id']) && isset($_GET['hash']))
	{
		$info = DbWorker::StartBuild($_GET['current_id'], $_GET['hash']);
		
		if(!is_int($info))
		{
			echo $info['job']->getName().':'.$info['build_id']."\n";
			echo 'BuildNumber:'.$info['job']->getBuildNumber()."\n";
			
		}
	}