<?php

class BuildFile
{
	public $id;
	public $buildId;
	public $jobId;
	public $stamp;
	public $filename;
	public $filepath;
	public $size;
	public $hash;
	
	//add some methods to get file size and posted ago
	
	public function __construct()
	{
		$this->id = 0;
		$this->buildId = 0;
		$this->jobId = 0;
		$this->stamp =  0;
		$this->filepath = "";
		$this->filename = "";
		$this->size = 0;
		$this->hash = "";
	}
	
	public function timeAgo()
	{
		require_once("classes/utils.php");
		return GetTimeAgo($this->stamp);
	}
	
	public function size()
	{
		require_once("classes/utils.php");
		return GetFormatedSize($this->size);
	}
	
}