<?php

require_once("db/DbCurrentJob.php");

if(isset($_GET['job_id']))
{
	DbCurrentJob::LaunchBuild($_GET['job_id']);
	header('location: job.php?job_id='.$_GET['job_id']);
}
else
{
	header('location: index.php');
}