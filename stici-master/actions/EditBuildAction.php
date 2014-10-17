<?php

require_once("actions/CommonAction.php");

require_once("db/DbJob.php");
require_once("db/DbEnv.php");
require_once("db/DbBuildStep.php");

require_once("classes/Env.php");

class EditBuildAction extends CommonAction
{
	public $errors;
	public $job;
	public $steps;
	public $envs;
	
	public function __construct()
	{
	
		$this->errors = array();
		$this->steps = array();
		parent::__construct("Edit build");
	}
	
	public function execute()
	{
		if(isset($_GET['job_id']))
		{
			$job = DbJob::GetJob($_GET['job_id']);
		
			if(is_int($job))
			{
				$this->errors[] = "Invalid Job";
				return;
			}
			else
			{
				$this->job = $job;
				$this->title = "Edit Build : ".$job->getName();
			}
		
			if(isset($_GET['save']))
			{
				$this->job->flags = 0;
				if(isset($_POST['win32build']))
				{
					$this->job->addFlags(Job::$Win32Build);
				}
				
				if(isset($_POST['linuxbuild']))
				{
					$this->job->addFlags(Job::$LinuxBuild);
				}


				//saving flags
				DbJob::SaveFlags($this->job);
			
				if(isset($_POST['delete_envs']))
				{
					$ids = explode(';', $_POST['delete_envs']);
					foreach($ids as $eid)
					{
						if(strlen($eid) > 0)
						{
							DbEnv::DeleteEnv($eid);
						}
					}
				}
				$_envs = array();
				
				$ie = 0;
				
				while(isset($_POST['env_name_'.$ie]))
				{
					$e = new Env();
					
					if(isset($_POST['env_id_'.$ie]))
					{
						$e->setId($_POST['env_id_'.$ie]);
					}
					
					$e->setJobId($this->job->getId());
					$e->setName($_POST['env_name_'.$ie]);
					$e->setValue($_POST['env_value_'.$ie]);
	
					$_envs[] = $e;
					
					$ie++;
				}
				
				DbEnv::SaveEnvs($_envs);
				
				
				if(isset($_POST['delete_steps']))
				{
					$ids = explode(';', $_POST['delete_steps']);
					foreach($ids as $eid)
					{
						if(strlen($eid) > 0)
						{
							DbBuildStep::Delete($eid);
						}
					}
				}
				
				$_steps = array();
				
				$ie = 0;
				
				while(isset($_POST['step_exe_'.$ie]))
				{
					$bs = new BuildStep();
					
					if(isset($_POST['step_id_'.$ie]))
					{
						$bs->setId($_POST['step_id_'.$ie]);
					}
					
					$bs->setJobId($this->job->getId());
					$bs->setExecutable($_POST['step_exe_'.$ie]);
					$bs->setArgs($_POST['step_args_'.$ie]);
					$bs->setOrder($_POST['step_order_'.$ie]);
					
					if(isset($_POST['step_flags_ignore_return_'.$ie]))
					{
						$bs->addFlags(BuildStep::$IgnoreReturn);
					}
	
					$_steps[] = $bs;
					
					$ie++;
				}
				
				DbBuildStep::SaveBuildSteps($_steps);
				
			}
			
			$envs = DbEnv::GetEnvs($this->job->getId());
			$steps = DbBuildStep::GetBuildSteps($this->job->getId());
			
			$this->envs = $envs;
			$this->steps = $steps;
		}
		
	}
	
	public function getEnvs()
	{
		return $this->envs;
	}
	
	public function getSteps()
	{
		return $this->steps;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function getJob()
	{
		return $this->job;
	}
}