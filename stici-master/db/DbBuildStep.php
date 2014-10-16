<?php
require_once("db/DbConnection.php");
require_once("classes/BuildStep.php");

class DbBuildStep
{
	public static function GetStepLogs($build_id)
	{
		$list = array();
		$con = new DbConnection();
		
		$query = "SELECT l.log_id, l.step_id, l.build_id, l.duration, l.stdout, l.stderr, l.return_code, s.flags FROM buildsteps_logs l INNER JOIN buildsteps s ON s.buildstep_id = l.step_id WHERE l.build_id = ? ORDER BY s.step_order";
		$st = $con->prepare($query);
		$st->bind_param("i", $build_id);
		$st->bind_result($l_id, $s_id, $b_id, $duration, $stdout, $stderr, $rc, $flags);
		$st->execute();
		
		while($st->fetch())
		{
			$log = new BuildStepLog();
			$log->id = $l_id;
			$log->stepId =  $s_id;
			$log->buildId = $b_id;
			$log->duration = $duration;
			$log->stdout = $stdout;
			$log->stderr = $stderr;
			$log->returnCode = $rc;
			
			$list[] = $log;
		}
		
		$con->close();
		return $list;
	}

	public static function GetBuildSteps($job_id)
	{
		$list = array();
		$con = new DbConnection();
		
		$query = "SELECT buildstep_id, job_id, step_order, executable, args, flags FROM buildsteps WHERE job_id = ? ORDER BY step_order";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $job_id);
		$st->bind_result($bs_id, $j_id, $bs_order, $bs_exe, $bs_args, $bs_flags);
		
		$st->execute();
		
		while($st->fetch())
		{
			$data = array(
			'id' => $bs_id,
			'job_id' => $j_id,
			'order' => $bs_order,
			'executable' => $bs_exe,
			'args' => $bs_args,
			'flags' => $bs_flags,
			);
			
			$bs = new BuildStep($data);
			$list[] = $bs;
		}
		
		$con->close();
		return $list;
	}
	
	public static function SaveBuildSteps($listBs)
	{
		$con = new DbConnection();
		
		foreach($listBs as $bs)
		{
			$b_id = $bs->getId();
			$b_job_id = $bs->getJobId();
			$b_order = $bs->getOrder();
			$b_exe = $bs->getExecutable();
			$b_args = $bs->getArgs();
			$b_flags = $bs->getFlags();
			
			if($bs->getId() == 0)
			{
			
				$query = "INSERT INTO buildsteps (job_id, step_order, executable, args, flags) VALUES(?, ?, ?, ?, ?)";
				$st = $con->prepare($query);
				$st->bind_param("iissi", $b_job_id, $b_order, $b_exe, $b_args, $b_flags);
				$st->execute();
			}
			else
			{
				$query = "UPDATE buildsteps SET step_order = ?, executable = ?, args = ?, flags = ? WHERE buildstep_id = ?";
				$st = $con->prepare($query);
				$st->bind_param("issii", $b_order, $b_exe, $b_args, $b_flags, $b_id);
				$st->execute();
			}
		}
		
		$con->close();
	}
	
	public static function Delete($bs_id)
	{
		$con = new DbConnection();
		
		$query = "DELETE FROM buildsteps WHERE buildstep_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $bs_id);
		$st->execute();
		
		$con->close();
	}
}