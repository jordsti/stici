<?php
	require_once("db/DbWorker.php");
	require_once("db/DbEnv.php");
	require_once("db/DbBuildStep.php");
	require_once("db/DbWorkerKey.php");
	
	if(isset($_GET['current_id']) && isset($_GET['hash']) && isset($_GET['key']))
	{
		$key = $_GET['key'];
		if(DbWorkerKey::TestKey($key))
		{
		
			$info = DbWorker::StartBuild($_GET['hash'], $_GET['current_id']);
			
			if(!is_int($info))
			{
				echo "Name:".$info['job']->getName().':'.$info['build_id']."\n";
				echo 'BuildNumber:'.$info['job']->getBuildNumber()."\n";
				echo 'Git='.$info['job']->getRemoteGit()."\n";
				
				$envs = DbEnv::GetEnvs($info['job']->getId());
				$steps = DbBuildStep::GetBuildSteps($info['job']->getId());
				
				foreach($envs as $e)
				{
					echo "ENV+".$e->getName()."=".$e->getValue()."\n";
				}
				
				foreach($steps as $s)
				{
					echo "STEP+".$s->getExecutable()."|".$s->getArgs()."|".$s->getFlags()."|".$s->getId()."\n";
				}
			}
		
		}
	}