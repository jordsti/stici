<?php

require_once("actions/CommonAction.php");
require_once("db/DbBuild.php");
require_once("db/DbBuildFile.php");
require_once("db/DbJob.php");

class DashboardAction extends CommonAction
{
	private $jobs;
	public $files;
	public $builds;

	public function __construct()
	{
		$this->files = array();
		$this->builds = array();
		parent::__construct("Dashboard", Group::$ViewDash);
	}
	
	public function execute()
	{
		$this->builds = DbBuild::GetLastBuild();
		$this->jobs = DbJob::GetJobs();
		$this->files = DbBuildFile::GetLastFiles();
	}
	
	public function getJobs()
	{
		return $this->jobs;
	}
	
}