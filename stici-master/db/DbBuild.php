<?php
require_once("db/DbConnection.php");
require_once("classes/Build.php");

class DbBuild
{

	public static function GetBuild($build_id)
	{
		$bo = 0;
		$con = new DbConnection();
		
		$query = "SELECT build_id, current_id, job_id, status, stamp, stamp_end, build_number, worker_hash FROM builds WHERE build_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $build_id);
		$st->bind_result($b_id, $c_id, $j_id, $status, $stamp, $stamp_end, $b_number, $w_hash);
		$st->execute();
		
		if($st->fetch())
		{
			$b = new Build();
			$b->id = $b_id;
			$b->currentId = $c_id;
			$b->jobId = $j_id;
			$b->status = $status;
			$b->stamp = $stamp;
			$b->stampEnd = $stamp_end;
			$b->buildNumber = $b_number;
			$b->workerHash = $w_hash;
			
			$bo = $b;
		}
		$con->close();
		
		return $bo;
	}
	
	public static function GetLastBuild($limit=25)
	{
		$list_b = array();
		$con = new DbConnection();
		
		$query = "SELECT b.build_id, b.current_id, b.job_id, b.status, b.stamp, b.stamp_end, b.build_number, b.worker_hash, j.job_name, j.target FROM builds b INNER JOIN jobs j ON j.job_id = b.job_id ORDER BY build_id DESC LIMIT ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $limit);
		$st->bind_result($b_id, $c_id, $j_id, $status, $stamp, $stamp_end, $b_number, $w_hash, $j_name, $target);
		$st->execute();
		
		while($st->fetch())
		{
			$b = new Build();
			$b->id = $b_id;
			$b->currentId = $c_id;
			$b->jobId = $j_id;
			$b->status = $status;
			$b->stamp = $stamp;
			$b->stampEnd = $stamp_end;
			$b->buildNumber = $b_number;
			$b->workerHash = $w_hash;
			$b->jobName = $j_name;
			$b->target = $target;
			
			$list_b[] = $b;
		}
		$con->close();
		
		return $list_b;
	}

	public static function GetLastBuildByJob($job_id, $limit=25)
	{
		$list = array();
		$con = new DbConnection();
		
		$query = "SELECT b.build_id, b.current_id, b.job_id, b.status, b.stamp, b.stamp_end, b.build_number, b.worker_hash, j.target FROM builds b JOIN jobs j ON j.job_id = b.job_id WHERE b.job_id = ? ORDER BY b.build_id DESC LIMIT ?";
		$st = $con->prepare($query);
		$st->bind_param("ii", $job_id, $limit);
		$st->bind_result($b_id, $c_id, $j_id, $status, $stamp, $stamp_end, $b_number, $w_hash, $target);
		$st->execute();
		
		while($st->fetch())
		{
			$b = new Build();
			$b->id = $b_id;
			$b->currentId = $c_id;
			$b->jobId = $j_id;
			$b->status = $status;
			$b->stamp = $stamp;
			$b->stampEnd = $stamp_end;
			$b->buildNumber = $b_number;
			$b->workerHash = $w_hash;
			$b->target = $target;
			
			$list[] = $b;
		}
		$con->close();
		
		return $list;
	}
}
