<?php

require_once("actions/CommonAction.php");
require_once("db/DbJob.php");
require_once("db/DbCurrentJob.php");
require_once("db/DbBuild.php");
require_once("db/DbBuildFile.php");

class JobAction extends CommonAction
{
	private $job;
	private $jobs;
	private $builds;
	public $files;
	
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
				$this->files = DbBuildFile::GetJobFiles($this->job->id);
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