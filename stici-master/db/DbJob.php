<?php
require_once("db/DbConnection.php");
require_once("classes/Job.php");

class DbJob
{
	public static function AddJob($job_name, $remote_git, $target)
	{
		$con = new DbConnection();
		$query = "INSERT INTO jobs (job_name, job_status, build_number, remote_git, target) VALUES(?,0,0,?,?)";
		$st = $con->prepare($query);
		
		$st->bind_param("ssi", $job_name, $remote_git, $target);
		$st->execute();
		
		$con->close();
	}
	
	public static function GetJobByName($job_name)
	{
		$id = 0;
		$con = new DbConnection();
		$query = "SELECT job_id FROM jobs WHERE job_name = ?";
		$st = $con->prepare($query);
		$st->bind_param("s", $job_name);
		$st->bind_result($job_id);
		$st->execute();
		
		if($st->fetch())
		{
			$id = $job_id;
		}
		
		$con->close();
		return $id;
	}
	
	public static function GetJobId($job_name, $remote_git, $target)
	{
		$id = 0;
		$con = new DbConnection();
		$query = "SELECT job_id FROM jobs WHERE job_name = ? AND remote_git = ? AND target = ?";
		$st = $con->prepare($query);
		$st->bind_param("ssi", $job_name, $remote_git, $target);
		$st->bind_result($job_id);
		$st->execute();
		
		if($st->fetch())
		{
			$id = $job_id;
		}
		
		$con->close();
		return $id;
	}
	
	public static function UpdateTarget($job)
	{
		$con = new DbConnection();
		$query = "UPDATE jobs SET target = ? WHERE job_id = ?";
		$st = $con->prepare($query);
		
		$j_id = $job->getId();
		$target = $job->target;
		
		$st->bind_param("ii", $target, $j_id);
		$st->execute();
		
		$con->close();
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
		$query = "SELECT job_id, job_name, job_status, build_number, remote_git, target FROM jobs WHERE job_id = ?";
		$st = $con->prepare($query);
		
		$st->bind_param('i', $job_id);
		$st->bind_result($j_id, $j_name, $j_status, $j_number, $j_git, $target);
		
		$st->execute();
		
		if($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number,
				'remote_git' => $j_git,
				'target' => $target
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
		$query = "SELECT * FROM (SELECT j.job_id, j.job_name, b.status, j.build_number, j.remote_git, b.stamp_end, j.target FROM jobs j LEFT JOIN builds b ON b.job_id = j.job_id ORDER BY b.build_id DESC) t1 GROUP BY job_id ORDER BY stamp_end DESC";
		$st = $con->prepare($query);
		
		$st->bind_result($j_id, $j_name, $j_status, $j_number, $j_git, $stamp, $target);

		$st->execute();
		
		while($st->fetch())
		{
			$data = array(
				'id' => $j_id,
				'name' => $j_name,
				'status' => $j_status,
				'build_number' => $j_number,
				'remote_git' => $j_git,
				'target' => $target
			);
			
			$j = new Job($data);
			$j->stamp = $stamp;
			
			$list[] = $j;
		}
		
		$con->close();
		
		return $list;
	}
}