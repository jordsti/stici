<?php

class Build
{
	public static $Building = 0;
	public static $Success = 1;
	public static $Failed = 2;

	public $id;
	public $jobId;
	public $buildNumber;
	public $stamp;
	public $stampEnd;
	public $status;
	public $workerHash;

	public function __construct($data=array())
	{
		$this->id = 0;
		$this->jobId = 0;
		$this->buildNumber = 0;
		$this->stamp = 0;
		$this->status = 0;
		$this->stampEnd = 0;
		$this->workerHash = "";
	}
	
	public function getStatusText()
	{
		if($this->status == Build::$Building)
		{
			return "Building";
		}
		else if($this->status == Build::$Success)
		{
			return "Success";
		}
		else if($this->status == Build::$Failed)
		{
			return "Failed";
		}
		
		return "";
	}
	
	public function getBuildTime()
	{
		if($this->stampEnd == 0)
		{
			$end = time();
		}
		else
		{
			$end = $this->stampEnd;
		}
		
		$t = $end - $this->stamp;
		
		if($t < 60)
		{
			return $t." seconds";
		}
		else
		{
			$min = floor($t/60);
			$sec = $t % 60;
			
			$sec = str_pad($sec, 2, '0', STR_PAD_LEFT);
			
			return $min."m".$sec."s";
		}
		
	}
}