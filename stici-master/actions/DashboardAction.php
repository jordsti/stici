<?php

require_once("actions/CommonAction.php");
require_once("db/DbBuild.php");
require_once("db/DbJob.php");

class DashboardAction extends CommonAction
{
	private $jobs;
	public $builds;

	public function __construct()
	{
		$this->builds = array();
		parent::__construct("Dashboard", Group::$ViewDash);
	}
	
	public function execute()
	{
		$this->builds = DbBuild::GetLastBuild();
		$this->jobs = DbJob::GetJobs();
	}
	
	public function getJobs()
	{
		return $this->jobs;
	}
	
}