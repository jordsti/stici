<?php

require_once("db/DbConnection.php");
require_once("classes/Worker.php");

class DbCurrentJob
{
	public static function GetCurrentJobs($job_id, $limit=25)
	{
		$list = array();
		$con = new DbConnection();
		
		$query = "SELECT c.current_id, c.job_id, c.worker_id, c.status, j.target FROM current_jobs c JOIN jobs j ON j.job_id = c.job_id WHERE c.job_id = ? ORDER BY current_id DESC LIMIT ?";
		
		$st = $con->prepare($query);
		$st->bind_param("ii", $job_id, $limit);
		$st->bind_result($c_id, $j_id, $w_id, $status, $target);
		$st->execute();
		
		while($st->fetch())
		{
			$cj = new CurrentJob();
			$cj->id = $c_id;
			$cj->jobId = $j_id;
			$cj->workerId = $w_id;
			$cj->status = $status;
			$cj->target = $target;
			
			$list[] = $cj;
		}
		
		$con->close();
		
		return $list;
	}

	public static function LaunchBuild($job_id)
	{
		$launch = true;
		
		$pending = CurrentJob::$Pending;
		$running = CurrentJob::$Running;
		$con = new DbConnection();
		
		$query = "SELECT current_id FROM current_jobs WHERE job_id = ? AND (status = ? OR status = ?)";
		//if there is one already pending, dont launch a new build or building
		$st = $con->prepare($query);
		$st->bind_param("iii", $job_id, $pending, $running);
		$st->bind_result($c_id);
		$st->execute();
		
		if($st->fetch())
		{
			$launch = false;
		}
		
		$con->close();
		
		if($launch)
		{
			$con = new DbConnection();
			
			$query = "INSERT INTO current_jobs (job_id, status) VALUES (?, ?)";
			$st = $con->prepare($query);
			$st->bind_param("ii", $job_id, $pending);
			$st->execute();
			$con->close();
		}
	}
}