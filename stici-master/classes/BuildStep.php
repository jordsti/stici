<?php
class BuildStep
{
	private $id;
	private $jobId;
	private $order;
	private $executable;
	private $args;
	private $flags;
	
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