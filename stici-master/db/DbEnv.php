<?php
require_once("db/DbConnection.php");
require_once("classes/Env.php");

class DbEnv
{
	public static function AddEnv($env_obj)
	{
		$con = new DbConnection();
		
		$job_id = $env_obj->getJobId();
		$env_name = $env_obj->getName();
		$env_value = $env_obj->getValue();
		
		$query = "INSERT INTO envs (job_id, env_name, env_value) VALUES (?, ?, ?)";
		$st = $con->prepare($query);
		
		$st->bind_param("iss", $job_id, $env_name, $env_value);
		
		$st->execute();
		
		$con->close();
	}
	
	public static function SaveEnvs($listEnvs)
	{
		$con = new DbConnection();
		foreach($listEnvs as $env)
		{
			$e_name = $env->getName();
			$e_value = $env->getValue();
			$e_id = $env->getId();
			
			
			if($e_id == 0)
			{	
				$e_jobId = $env->getJobId();
				$query = "INSERT INTO envs (job_id,env_name,env_value) VALUES(?,?,?)";
				$st = $con->prepare($query);
				$st->bind_param("iss", $e_jobId, $e_name, $e_value);
				$st->execute();
			
			}
			else
			{
				$query = "UPDATE envs SET env_name = ?, env_value = ? WHERE env_id = ?";
				$st = $con->prepare($query);
				$st->bind_param("ssi", $e_name, $e_value, $e_id);
				$st->execute();
			}
			
		}
		
		$con->close();
	}
	
	public static function DeleteEnv($env_id)
	{
		$con = new DbConnection();
		
		$query = "DELETE FROM envs WHERE env_id = ?";
		$st = $con->prepare($query);
		$st->bind_param('i', $env_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function GetEnvs($job_id)
	{
		$list = array();
		$con = new DbConnection();
		$query = "SELECT env_id, job_id, env_name, env_value FROM envs WHERE job_id = ? ORDER BY env_id";
		
		$st = $con->prepare($query);
		
		$st->bind_param("i", $job_id);
		$st->bind_result($e_id, $j_id, $e_name, $e_value);
		$st->execute();
		
		while($st->fetch())
		{
			$data = array(
				'id' => $e_id,
				'job_id' => $j_id,
				'name' => $e_name,
				'value' => $e_value
			);
			
			$e = new Env($data);
			$list[] = $e;
		}
	
	
		$con->close();
		
		return $list;
	}

}