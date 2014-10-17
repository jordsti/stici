<?php
require_once("db/DbConnection.php");
require_once("classes/Job.php");

class DbJob
{
	public static function AddJob($job_name, $remote_git)
	{
		$con = new DbConnection();
		$query = "INSERT INTO jobs (job_name, job_status, build_number, remote_git, flags) VALUES(?,0,0,?,0)";
		$st = $con->prepare($query);
		
		$st->bind_param("ss", $job_name, $remote_git);
		$st->execute();
		
		$con->close();
	}
	
	public static function GetJobId($job_name, $remote_git)
	{
		$id = 0;
		$con = new DbConnection();
		$query = "SELECT job_id FROM jobs WHERE job_name = ? AND remote_git = ?";
		$st = $con->prepare($query);
		$st->bind_param("ss", $job_name, $remote_git);
		$st->bind_result($job_id);
		$st->execute();
		
		if($st->fetch())
		{
			$id = $job_id;
		}
		
		$con->close();
		return $id;
	}
	
	public static function UpdateJob($job)
	{
		$con = new DbConnection();
		$query = "UPDATE jobs SET build_number = ? WHERE job_id = ?";
		$st = $con->prepare($query);
		
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
		$query = "SELECT job_id, job_name, job_status, build_number, remote_git, flags FROM jobs WHERE job_id = ?";
		$st = $con->prepare($query);
		
		$st->bind_param('i', $job_id);
		$st->bind_result($j_id, $j_name, $j_status, $j_number, $j_git, $j_flags);
		
		$st->execute();
		
		if($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number,
				'remote_git' => $j_git,
				'flags' => $j_flags
			);
			
			$job = new Job($data);
		}
		
		$con->close();
		
		return $job;
	}
	
	public static function SaveFlags($job)
	{
		if($job->getId() != 0)
		{
			$job_id = $job->getId();
			$flags = $job->flags;
			$con = new DbConnection();
			$query = "UPDATE jobs SET flags = ? WHERE job_id = ?";
			$st = $con->prepare($query);
			$st->bind_param("ii", $flags, $job_id);
			$st->execute();
			$con->close();
		}
	}
	
	public static function GetJobs()
	{
		$list = array();
		$con = new DbConnection();
		$query = "SELECT * FROM (SELECT j.job_id, j.job_name, b.status, j.build_number, j.remote_git, b.stamp_end, j.flags FROM jobs j LEFT JOIN builds b ON b.job_id = j.job_id ORDER BY b.build_id DESC) t1 GROUP BY job_id ORDER BY stamp_end DESC";
		$st = $con->prepare($query);
		
		$st->bind_result($j_id, $j_name, $j_status, $j_number, $j_git, $stamp, $j_flags);

		$st->execute();
		
		while($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number,
				'remote_git' => $j_git,
				'flags' => $j_flags
			);
			
			$j = new Job($data);
			$j->stamp = $stamp;
			
			$list[] = $j;
		}
		
		$con->close();
		
		return $list;
	}
}