<?php
require_once("db/DbConnection.php");
require_once("classes/Job.php");
/*

fields:

job_id
job_name
status
build_number


*/

class DbJob
{
	public static function AddJob($job_name)
	{
		$con = new DbConnection();
		$query = "INSERT INTO jobs (job_name, job_status, build_number) VALUES(?,0,0)";
		$st = $con->prepare($query);
		
		$st->bind_param("s", $job_name);
		$st->execute();
		
		$con->close();
	}
	
	public static function UpdateJob($job)
	{
		$con = new DbConnection();
		$query = "UPDATE jobs SET build_number = ? WHERE job_id = ?";
		$st = $con->prepare($query)
		
		$j_id = $job->getId();
		$j_bn = $job->getBuildNumber();
		
		$st->bind_param("ii", $j_bn, $j_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function GetJob($job_id)
	{
		$job = 0;
		$con = new DbConnection();
		$query = "SELECT job_id, job_name, job_status, build_number FROM jobs WHERE job_id = ?";
		$st = $con->prepare($query);
		
		$st->bind_param('i', $job_id);
		$st->bind_result($j_id, $j_name, $j_status, $j_number);
		
		$st->execute();
		
		if($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number
			);
			
			$job = new Job($data);
		}
		
		$con->close();
		
		return $job;
	}
	
	public static function GetJobs()
	{
		$list = array();
		$con = new DbConnection();
		$query = "SELECT job_id, job_name, job_status, build_number FROM jobs ORDER BY job_id";
		$st = $con->prepare($query);
		
		$st->bind_result($j_id, $j_name, $j_status, $j_number);
		
		$st->execute();
		
		while($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number
			);
			
			$j = new Job($data);
			
			$list[] = $j;
		}
		
		$con->close();
		
		return $list;
	}
}