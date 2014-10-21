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
	public $jobName;
	public $target;

	public function __construct($data=array())
	{
		$this->id = 0;
		$this->jobId = 0;
		$this->buildNumber = 0;
		$this->stamp = 0;
		$this->status = 0;
		$this->stampEnd = 0;
		$this->workerHash = "";
		$this->jobName = "";
		$this->target = 0;
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
		require_once("classes/utils.php");
		return GetFormatedTime($this->stamp, $this->stampEnd);
		
	}
	
	public function getBuildTimeAgo()
	{	
		require_once("classes/utils.php");
		if($this->stampEnd == 0)
			return GetTimeAgo($this->stamp);
		return GetTimeAgo($this->stampEnd);	
	}
}