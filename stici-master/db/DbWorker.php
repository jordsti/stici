<?php

require_once("db/DbConnection.php");
require_once("db/DbJob.php");
require_once("classes/Worker.php");
require_once("classes/Build.php");

class DbWorker
{
	public static function GetWorkers()
	{
		$list = array();
		DbWorker::CleanWorker();
		
		$con = new DbConnection();
		$query = "SELECT worker_id, worker_hash, worker_status, hostname, remote_addr, last_tick FROM workers ORDER BY last_tick DESC";
		$st = $con->prepare($query);
		$st->bind_result($w_id, $w_hash, $status, $hostname, $remote_addr, $last_tick);
		
		$st->execute();
		
		while($st->fetch())
		{
			$w = new Worker();
			$w->id = $w_id;
			$w->hash = $w_hash;
			$w->status = $status;
			$w->hostname = $hostname;
			$w->remoteAddr = $remote_addr;
			$w->lastTick = $last_tick;
			
			$list[] = $w;
		}
		
		$con->close();
		return $list;
	}

	public static function InsertStepLog($whash, $build_id, $step_id, $stdout, $stderr, $rc)
	{
		$con = new DbConnection();
		$query = "INSERT INTO buildsteps_logs (build_id, step_id, stdout, stderr, return_code) VALUES (?, ?, ?, ?, ?)";
		$st = $con->prepare($query);
		$st->bind_param("iissi", $build_id, $step_id, $stdout, $stderr, $rc);
		$st->execute();
		$con->close();
		DbWorker::TickByHash($whash);
	}

	public static function EndBuild($build_id, $whash, $status)
	{
		
		$stamp = time();
		
		$con = new DbConnection();
		$query = "UPDATE builds SET status = ?, stamp_end = ? WHERE build_id = ? AND worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("iiis", $status, $stamp, $build_id, $whash);
		$st->execute();
		
		$con->close();
		
		$c_id = 0;
		
		$con = new DbConnection();
		$query = "SELECT current_id FROM builds WHERE build_id = ? AND worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("is", $build_id, $whash);
		$st->bind_result($cid);
		$st->execute();
		
		if($st->fetch())
		{
			$c_id = $cid;
		}
		
		$con->close();
		
		if($c_id > 0)
		{
			$stat = CurrentJob::$Ended;
			DbWorker::UpdateCurrentJobStatus($c_id, $stat);
		}
		
		//updating current worker status
		$stat = Worker::$Idle;
		DbWorker::UpdateWorkerStatus($whash, $stat);
	}
	
	public static function UpdateCurrentJobStatus($current_id, $status)
	{
		$con = new DbConnection();
		$query = "UPDATE current_jobs SET status = ? WHERE current_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("ii", $status, $current_id);
		$st->execute();
		$con->close();
	}
	
	public static function UpdateWorkerStatus($whash, $status)
	{
		$con = new DbConnection();
		$query = "UPDATE workers SET worker_status = ? WHERE worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("is", $status, $whash);
		$st->execute();
		$con->close();
	}

	public static function StartBuild($whash, $currentId)
	{
		$job = DbWorker::GetJob($currentId);
		
		if(!is_int($job))
		{
			$bn = $job->getBuildNumber();
			$bn++;
			
			$job_id = $job->getId();
			$stamp = time();
			$stat = Build::$Building;
			
			$con = new DbConnection();
			
			
			$query = "INSERT INTO builds (job_id, current_id, status, stamp, build_number, worker_hash) VALUES(?, ?, ?, ?, ?, ?)";
			
			$st = $con->prepare($query);
			$st->bind_param("iiiiis", $job_id, $currentId, $stat, $stamp, $bn, $whash);
			$st->execute();
			
			$con->close();
			
			//getting build id
			$build_id = 0;
			$con = new DbConnection();
			$query = "SELECT build_id FROM builds WHERE current_id = ? AND worker_hash = ?";
			$st = $con->prepare($query);
			$st->bind_param("is", $currentId, $whash);
			$st->bind_result($b_id);
			$st->execute();
			
			if($st->fetch())
			{
				$build_id = $b_id;
			}
			
			$con->close();
			
			$job->setBuildNumber($bn);
			DbJob::UpdateJob($job);
			$stat = Worker::$Running;
			DbWorker::UpdateWorkerStatus($whash, $stat);
			
			return array('build_id' => $build_id, 'job' => $job);
		}
		
		return 0;
	}

	public static function GetJob($currentId)
	{

		$con = new DbConnection();
		
		$job_id = 0;
		
		$query = "SELECT job_id FROM current_jobs WHERE current_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $currentId);
		$st->bind_result($j_id);
		$st->execute();
		
		if($st->fetch())
		{
			$job_id = $j_id;
		}
		
		$con->close();
		
		if($job_id > 0)
		{
			$job = DbJob::GetJob($job_id);
			
			return $job;
		}
		
		return 0;
	}

	public static function CleanWorker($timeout=300)
	{
		$stamp = time() - $timeout;
		$con = new DbConnection();
		$query = "DELETE FROM workers WHERE last_tick < ?";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $stamp);
		$st->execute();
		$con->close();
		
	}

	public static function GetWorkerId($hash)
	{
		$id = 0;
		$con = new DbConnection();
		$query = "SELECT worker_id FROM workers WHERE worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("s", $hash);
		$st->bind_result($w_id);
		$st->execute();
		
		if($st->fetch())
		{
			$id = $w_id;
		}
		
		
		$con->close();
		
		return $id;
	}

	public static function ClaimJob($currentId, $hash)
	{
		$stat = CurrentJob::$Pending;
		$good = false;
		$con = new DbConnection();
		$query = "SELECT current_id FROM current_jobs WHERE status = ? AND current_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("ii", $stat, $currentId);
		
		$st->execute();
		
		if($st->fetch())
		{
			$good = true;
		}
		
		$con->close();
		
		$w_id = DbWorker::GetWorkerId($hash);
		
		if($w_id > 0 && $good)
		{
			$stat = CurrentJob::$Running;
			$con = new DbConnection();
			$query = "UPDATE current_jobs SET status = ? , worker_id = ? WHERE current_id = ?";
			$st = $con->prepare($query);
			$st->bind_param("iii", $stat, $w_id, $currentId);
			$st->execute();
			$con->close();
			return true;
		}
		
		return false;
	}
	

	public static function WorkerIsRegistered($hash)
	{
		$exists = false;
		$w_id = 0;
		$con = new DbConnection();
		
		$query = "SELECT worker_id FROM workers WHERE worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("s", $hash);
		$st->bind_result($wid);
		$st->execute();
		
		if($st->fetch())
		{
			$w_id = $wid;
			$exists = true;
		}
		$con->close();
		
		//tick it
		if($exists)
		{
			DbWorker::Tick($w_id);
		}
		
		return $exists;
	}
	
	public static function TickByHash($whash)
	{
		$con = new DbConnection();
		$stamp = time();
		$query = "UPDATE workers SET last_tick = ? WHERE worker_hash = ?";
		$st = $con->prepare($query);
		$st->bind_param("is", $stamp, $worker_hash);
		$st->execute();
		$con->close();
	}
	
	public static function Tick($worker_id)
	{
		$con = new DbConnection();
		$stamp = time();
		$query = "UPDATE workers SET last_tick = ? WHERE worker_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("ii", $stamp, $worker_id);
		$st->execute();
		$con->close();
	}

	public static function PollJob()
	{
		$list = array();
		$con = new DbConnection();
		
		$status = CurrentJob::$Pending;
		
		$query = "SELECT current_id, job_id, worker_id, status, flags FROM current_jobs WHERE status = ? ORDER BY current_id ASC";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $status);
		$st->bind_result($c_id, $j_id, $w_id, $c_status, $j_flags);
		$st->execute();
		
		while($st->fetch())
		{
			$s = new CurrentJob();
			$s->id = $c_id;
			$s->jobId = $j_id;
			$s->workerId = $w_id;
			$s->status = $c_status;
			$s->flags = $j_flags;
			
			$list[] = $s;
			
		}
		
		$con->close();
		return $list;
	}

	public static function Register($hash, $remote_addr, $hostname)
	{
		$con = new DbConnection();
		
		$query = "INSERT INTO workers (worker_hash, worker_status, hostname, remote_addr, last_tick) VALUES(?, 0, ?, ?, ?)";
		
		$st = $con->prepare($query);
		
		$stamp = time();
		
		$st->bind_param("sssi", $hash, $hostname, $remote_addr, $stamp);
		$st->execute();
		
		$con->close();
	}
}