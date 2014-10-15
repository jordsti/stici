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
	public $status;
	public $workerHash;

	public function __construct($data=array())
	{
		$this->id = 0;
		$this->jobId = 0;
		$this->buildNumber = 0;
		$this->stamp = 0;
		$this->status = 0;
		$this->workerHash = "";
	}
}