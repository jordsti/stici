<?php

require_once("actions/CommonAction.php");

class AddJobAction extends CommonAction
{
	private $showForm;
	private $errors;

	public function __construct()
	{
		$this->errors = array();
		$this->showForm = false;
		parent::__construct("Add job");
	}
	
	public function execute()
	{
		if(isset($_POST['job_name']))
		{
			require_once("db/DbJob.php");
			
			DbJob::AddJob($_POST['job_name']);
			
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