<?php
require_once("actions/CommonAction.php");
require_once("db/DbCurrentJob.php");
class LaunchBuildAction extends CommonAction
{
	public function __construct()
	{
		parent::__construct("Launch build", Group::$LaunchBuild);
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			DbCurrentJob::LaunchBuild($_GET['job_id']);
			header('location: job.php?job_id='.$_GET['job_id']);
		}
		else
		{
			header('location: index.php');
		}
	}
}