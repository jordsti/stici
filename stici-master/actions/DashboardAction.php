<?php

require_once("actions/CommonAction.php");

class DashboardAction extends CommonAction
{
	private $jobs;

	public function __construct()
	{
		parent::__construct("Dashboard");
	}
	
	public function execute()
	{
		require_once("db/DbJob.php");
			
		$this->jobs = DbJob::GetJobs();
	}
	
	public function getJobs()
	{
		return $this->jobs;
	}
	
}