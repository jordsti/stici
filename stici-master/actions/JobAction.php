<?php

require_once("actions/CommonAction.php");

class JobAction extends CommonAction
{
	private $errors;
	private $job;
	public function __construct()
	{
	
		$this->errors = array();
		parent::__construct("Job");
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			require_once("db/DbJob.php");
			
			$job = DbJob::GetJob($_GET['job_id']);
			
			if(is_int($job))
			{
				$this->errors[] = "Invalid Job";
			}
			else
			{
				$this->job = $job;
				$this->title = $job->getName();
			}
			
		}
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function getJob()
	{
		return $this->job;
	}
}