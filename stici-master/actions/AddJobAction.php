<?php

require_once("actions/CommonAction.php");

class AddJobAction extends CommonAction
{
	private $showForm;

	public function __construct()
	{
		$this->showForm = false;
		parent::__construct("Add job", Group::$AddJob);
	}
	
	public function execute()
	{
		if(isset($_POST['job_name']) && isset($_POST['remote_git']) && isset($_POST['target']))
		{
			require_once("db/DbJob.php");
			$target = Job::ParseTarget($_POST['target']);
			
			$job_id = DbJob::GetJobByName($_POST['job_name']);
			if($job_id == 0)
			{
				DbJob::AddJob($_POST['job_name'], $_POST['remote_git'], $target);
			}
			else
			{
				$this->errors[] = "This job already exists";
			}
			
		}
		else
		{
			$this->showForm = true;
		}
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function showForm()
	{
		return $this->showForm;
	}
}