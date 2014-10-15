<?php

class Job
{
	private $id;
	private $name;
	private $status_id;
	private $buildNumber;
	private $remoteGit;
	
	public function __construct($data)
	{
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->status_id = $data['status'];
		$this->buildNumber = $data['build_number'];
		$this->remoteGit = $data['remote_git'];
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getStatusId()
	{
		return $this->status_id;
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
}