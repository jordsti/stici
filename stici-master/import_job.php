<?php
require_once("db/DbJob.php");
require_once("db/DbEnv.php");
require_once("db/DbBuildStep.php");

if(file_exists($_FILES["job"]["tmp_name"]))
{
	//file uploaded
	$fp = fopen($_FILES["job"]["tmp_name"], 'r');
	
	$data = "";
	$buffer = fread($fp, 1024);
	
	while(strlen($buffer) > 0)
	{
		$data .= $buffer;
		$buffer = fread($fp, 1024);
	}
	
	fclose($fp);
	
	//parsing file
	$lines = explode("\n", $data);
	$ia = count($lines);
	$i = 0;
	$name = "";
	$buildNumber = 0;
	$git = "";
	
	$envs = array();
	$steps = array();
	
	$c_order = 1;
	
	while($i < $ia)
	{
		if($i == 0)
		{
			//name
			$name = $lines[$i];
		}
		else if($i == 1)
		{
			//build number
			$buildNumber = $lines[$i];
		}
		else if($i == 2)
		{
			$git = $lines[$i];
		}
		else if($i == 3)
		{
			$target = $lines[$i];
		}
		else
		{
			//steps or envs vars
			$vars = explode('+', $lines[$i], 2);
			
			if(strcmp($vars[0], "ENV") == 0)
			{
				$v = explode('=', $vars[1]);
				$e = new Env();
				$e->setName($v[0]);
				$e->setValue($v[1]);
				
				$envs[] = $e;
			}
			else if(strcmp($vars[0], "STEP") == 0)
			{
				$v = explode('|', $vars[1]);
				$bs = new BuildStep();
				
				$bs->flags = $v[2];
				$bs->args = $v[1];
				$bs->executable = $v[0];
				$bs->order = $c_order;
				$c_order++;
				$steps[] = $bs;
			}
			
		}
		$i++;
		
	}
	
		/*echo $name."\n";
		echo $buildNumber."\n";
		echo $git."\n";
		
		foreach($envs as $e)
		{
			echo $e->getName().'='.$e->getValue()."\n";
		}
		
		foreach($steps as $s)
		{
			echo $s->executable.' '.$s->args.' '.$s->flags."\n";
		}*/
		
	//importing job
	DbJob::AddJob($name, $git, $target);
	$id = DbJob::GetJobId($name, $git, $target);
	
	$job_obj = DbJob::GetJob($id);
	
	$job_obj->setBuildNumber($buildNumber);
	
	DbJob::UpdateJob($job_obj);
	
	//adding envs
	foreach($envs as $e)
	{
		$e->setJobId($id);
	}
	
	foreach($steps as $s)
	{
		$s->setJobId($id);
	}
	
	
	DbBuildStep::SaveBuildSteps($steps);
	DbEnv::SaveEnvs($envs);
	
	
	header('location: index.php');
}