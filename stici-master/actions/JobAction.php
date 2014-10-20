<?php

require_once("actions/CommonAction.php");

class JobAction extends CommonAction
{
	private $job;
	private $jobs;
	private $builds;
	
	public function __construct()
	{
		$this->builds = array();
		$this->job = 0;
		$this->jobs = array();
		parent::__construct("Job", Group::$ViewJob);
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			require_once("db/DbJob.php");
			require_once("db/DbCurrentJob.php");
			require_once("db/DbBuild.php");
			$job = DbJob::GetJob($_GET['job_id']);
			
			if(is_int($job))
			{
				$this->errors[] = "Invalid Job";
			}
			else
			{
				$this->job = $job;
				$this->jobs = DbCurrentJob::GetCurrentJobs($this->job->getId());
				$this->builds = DbBuild::GetLastBuildByJob($this->job->getId());
				$this->title = $job->getName();
			}
			
		}
	}
	
	public function getBuilds()
	{
		return $this->builds;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function getJobs()
	{
		return $this->jobs;
	}
	
	public function getJob()
	{
		return $this->job;
	}
}