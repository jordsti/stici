<?php

class WorkerKey
{
	public $id;
	public $key;
	public $status;
	public $stamp;
	
	public static $Active = 1;
	public static $Revoked = 2;
	
	public function __construct()
	{
		
		$this->id = 0;
		$this->key = "";
		$this->status = 0;
		$this->stamp = 0;
	}
	
	public function timeAgo()
	{
		require_once("classes/utils.php");
		return GetTimeAgo($this->stamp);
	}
	
}