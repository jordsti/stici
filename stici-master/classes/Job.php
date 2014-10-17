<?php

class Job
{
	public $id;
	public $name;
	public $status;
	public $buildNumber;
	public $remoteGit;
	public $stamp;
	public $flags;
	
	
	public static $Win32Build = 1;
	public static $LinuxBuild = 2;
	
	public static $Building = 0;
	public static $Success = 1;
	public static $Failed = 2;
	
	public function __construct($data)
	{
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->status = $data['status'];
		$this->buildNumber = $data['build_number'];
		$this->remoteGit = $data['remote_git'];
		$this->stamp = 0;
		$this->flags = $data['flags'];
	}
	
	public function addFlags($flag)
	{
		$this->flags = $this->flags | $flag;
	}
	
	public function removeFlags($flag)
	{
		$this->flags = $this->flags ^ $flag;
	}
	
	public function testFlags($flag)
	{
		$if = $this->flags & $flag;
		return ($if == $flag);
		
	}
	
	public function getStatusText()
	{
		if($this->status == Job::$Building)
		{
			return "Not Builded";
		}
		else if($this->status == Job::$Success)
		{
			return "Success";
		}
		else if($this->status == Job::$Failed)
		{
			return "Failed";
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getBuildNumber()
	{
		return $this->buildNumber;
	}
	
	public function setBuildNumber($bn)
	{
		$this->buildNumber = $bn;
	}
	
	public function getRemoteGit()
	{
		return $this->remoteGit;
	}
	
	public function getBuildTimeAgo()
	{
		if($this->stamp == 0)
		{
			return "";
		}
	
		$stamp = time() - $this->stamp;
	
		$sec = $stamp % 60;
		$min = floor($stamp / 60);
		$h = floor($min / 60);
		$d = floor($h / 24);
		
		if($stamp < 60)
		{
			return $sec." seconds ago";
		}
		else if($min < 60)
		{
			return $min." minutes ago";
		}
		else if($h < 24)
		{
			return $h." hours ago";
		}
		else
		{
			return $d." days ago";
		}
		
	}
}