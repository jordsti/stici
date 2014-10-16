<?php
class BuildStepLog
{
	public $id;
	public $stepId;
	public $buildId;
	public $duration;
	public $stdout;
	public $stderr;
	public $returnCode;
	
	public function __construct()
	{
		$this->id = 0;
		$this->stepId = 0;
		$this->buildId = 0;
		$this->duration = 0;
		$this->stdout = "";
		$this->stderr = "";
		$this->returnCode = 0;
	}
}

class BuildStep
{
	public $id;
	public $jobId;
	public $order;
	public $executable;
	public $args;
	public $flags;
	
	public static $IgnoreReturn = 1;
	
	public function __construct($data=array())
	{
		$this->id = 0;
		$this->jobId = 0;
		$this->order = 1;
		$this->executable = "";
		$this->args = "";
		$this->flags = 0;
	
		if(count($data) > 0)
		{
			$this->id = $data['id'];
			$this->jobId = $data['job_id'];
			$this->order = $data['order'];
			$this->executable = $data['executable'];
			$this->args = $data['args'];
			$this->flags = $data['flags'];
		}
	}
	
	public function addFlags($flag)
	{
		$this->flags = $flag | $this->flags;
	}
	
	public function removeFlags($flag)
	{
		$this->flags = $flag ^ $this->flags;
	}
	
	public function testFlags($flag)
	{
		$if = $this->flags & $flag;
		return ($if == $flag);
		
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setJobId($jobId)
	{
		$this->jobId = $jobId;
	}
	
	public function setOrder($order)
	{
		$this->order = $order;
	}
	
	public function setExecutable($exe)
	{
		$this->executable = $exe;
	}
	
	public function setArgs($args)
	{
		$this->args = $args;
	}
	
	public function setFlags($flags)
	{
		$this->flags = $flags;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getJobId()
	{
		return $this->jobId;
	}
	
	public function getOrder()
	{
		return $this->order;
	}
	
	public function getExecutable()
	{
		return $this->executable;
	}
	
	public function getArgs()
	{
		return $this->args;
	}
	
	public function getFlags()
	{
		return $this->flags;
	}
}