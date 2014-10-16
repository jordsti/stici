<?php

require_once("actions/CommonAction.php");

class JobAction extends CommonAction
{
	private $errors;
	private $job;
	private $jobs;
	
	public function __construct()
	{
		$this->job = 0;
		$this->jobs = array();
		$this->errors = array();
		parent::__construct("Job");
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			require_once("db/DbJob.php");
			require_once("db/DbCurrentJob.php");
			$job = DbJob::GetJob($_GET['job_id']);
			
			if(is_int($job))
			{
				$this->errors[] = "Invalid Job";
			}
			else
			{
				$this->job = $job;
				$this->jobs = DbCurrentJob::GetCurrentJobs($this->job->getId());
				$this->title = $job->getName();
			}
			
		}
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