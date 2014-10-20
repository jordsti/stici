<?php

require_once("actions/CommonAction.php");
require_once("db/DbBuild.php");
require_once("db/DbBuildStep.php");
require_once("db/DbJob.php");
require_once("db/DbBuildFile.php");

class ShowBuildAction extends CommonAction
{
	public $logs;
	public $build;
	public $job;
	public $files;
	
	public function __construct()
	{
		$this->logs = 0;
		$this->build = 0;
		$this->job = 0;
		$this->files = array();
		parent::__construct("Build Logs", Group::$ViewBuild);
	}
	
	public function execute()
	{
		if(isset($_GET['build_id']))
		{
			$this->build = DbBuild::GetBuild($_GET['build_id']);
			
			if(is_int($this->build))
			{
				//build no found
				//need to implement a general error handling
				//todo
			}
			else
			{
				$this->job = DbJob::GetJob($this->build->jobId);
				
				$this->title = $this->job->getName(). " Build #".$this->build->buildNumber;
				
				$this->logs = DbBuildStep::GetStepLogs($this->build->id);
				
				if($this->testGroupFlags(Group::$ViewFile))
				{
				
					$this->files = DbBuildFile::GetFiles($this->build->id);
				
				}
			}	
		}
	}
}