<?php

class CurrentJob
{
	public $id;
	public $jobId;
	public $workerId;
	public $status;
	public $target;
	
	public static $Pending = 0;
	public static $Running = 1;
	public static $Ended = 2;
	
	public function __construct($data=array())
	{
		$this->id = 0;
		$this->jobId = 0;
		$this->workerId = 0;
		$this->status = 0;
	
		if(count($data) > 0)
		{
			$this->id = $data['id'];
			$this->jobId = $data['job_id'];
			$this->workerId = $data['worker_id'];
			$this->status = $data['status'];
			$this->target = $data['target'];
		}
	}
	
	public function getTextStatus()
	{
		if($this->status == CurrentJob::$Pending)
		{
			return "Pending";
		}
		else if($this->status == CurrentJob::$Running)
		{
			return "Running";
		}
		else if($this->status == CurrentJob::$Ended)
		{
			return "Ended";
		}
	}
	
}

class Worker
{
	public $id;
	public $hash;
	public $status;
	public $hostname;
	public $remoteAddr;
	public $lastTick;
	public $os;
	
	public static $Idle = 0;
	public static $Running = 1;
	
	public function __construct($data=array())
	{
		$this->id = 0;
		$this->hash = "";
		$this->status = 0;
		$this->hostname = "";
		$this->remoteAddr = "";
		$this->lastTick = 0;
		$this->os = 0;
	
		if(count($data) > 0)
		{
			$this->id = $data['id'];
			$this->hash = $data['hash'];
			$this->status = $data['status'];
			$this->hostname = $data['hostname'];
			$this->remoteAddr = $data['remote_addr'];
			$this->lastTick = $data['last_tick'];
		}
	}
	
	public function getStatusText()
	{
		if($this->status == Worker::$Idle)
		{
			return "Idle";
		}
		else if($this->status == Worker::$Running)
		{
			return "Running";
		}
	}
	
	public function getLastAction()
	{
		$now = time();
		
		$diff = $now - $this->lastTick;
		
		if($diff > 60)
		{
			$min = floor($diff/60);
			return $min." minutes ago";
		}
		else if($diff == 0)
		{
			return "now";
		}
		else
		{
			return $diff." seconds ago";
		}
		
	}
	
}