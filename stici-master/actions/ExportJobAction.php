<?php
require_once("actions/CommonAction.php");
require_once("db/DbJob.php");
require_once("db/DbEnv.php");
require_once("db/DbBuildStep.php");

class ExportJobAction extends CommonAction
{
	public function __construct()
	{
		parent::__construct("Export job", Group::$ViewJob);
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			$job = DbJob::GetJob($_GET['job_id']);
			
			if(!is_int($job))
			{
				$name = $job->getName();
				$buildNumber = $job->getBuildNumber();
				$git = $job->getRemoteGit();
				$target = $job->target;
				
				$envs = DbEnv::GetEnvs($job->getId());
				$steps = DbBuildStep::GetBuildSteps($job->getId());
				
				$out = "";
				$out .= $name."\n";
				$out .= $buildNumber."\n";
				$out .= $git."\n";
				$out .= $target."\n";
				
				foreach($envs as $e)
				{
					$out .= 'ENV+'.$e->getName().'='.$e->getValue()."\n";
				}
				
				foreach($steps as $s)
				{
					$out .= 'STEP+'.$s->getExecutable().'|'.$s->getArgs().'|'.$s->getFlags()."\n";
				}
				
				
				header("Content-Type:text/plain");
				header("Content-length: " . strlen($out)); 
				header('Content-Disposition: attachment; filename="' . $job->getName() . '.job"'); 
				
				echo $out;
				exit();
				
			}
		}
	}
}