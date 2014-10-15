<?php
class Env
{
	private $id;
	private $jobId;
	private $name;
	private $value;

	public function __construct($data=array())
	{
		$i = count($data);
		if($i > 0)
		{
			$this->id = $data['id'];
			$this->jobId = $data['job_id'];
			$this->name = $data['name'];
			$this->value = $data['value'];
		}
		else
		{
			$this->id = 0;
			$this->jobId = 0;
			$this->name = "";
			$this->value = "";
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
		
	public function getJobId()
	{
		return $this->jobId;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setJobId($jobId)
	{
		$this->jobId = $jobId;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	

}