<?php

class Group
{
	public $id;
	public $name;
	public $flags;
	
	public static $ViewDash = 1;
	public static $AddJob = 2;
	public static $LaunchBuild = 4;
	public static $ViewBuild = 8;
	public static $ViewFile = 16;
	public static $DeleteBuild = 32;
	public static $AddUser = 64;
	public static $AdminUser = 128;
	public static $EditBuild = 256;
	public static $ViewJob = 512;

	public static $All = 255;
	
	public function __construct()
	{
		$this->name = "";
		$this->flags = 0;
		$this->id = 0;
	}
	
	public function addFlags($flag)
	{
		$this->flags = $flag | $this->flags;
	}
	
	public function removeFlags($flag)
	{
		if($this->testFlags($flag))
		{
			$this->flags = $flag ^ $this->flags;
		}
	}
	
	public function testFlags($flag)
	{
		$if = $this->flags & $flag;
		return ($if == $flag);
	
	}
	
}