<?php

class GroupFlag
{
	public $name;
	public $description;
	public $value;
	
	public function __construct($name, $description, $value)
	{
		$this->name = $name;
		$this->description = $description;
		$this->value = $value;
	}
}

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
	public static $EditBuild = 64;
	public static $ViewJob = 128;
	public static $AdminUsers = 256;
	public static $EditSettings = 512;
	public static $ViewWorker = 1024;
	public static $AdminWorkers = 2048;

	public static $All = 4095;
	
	public static function GetFlags()
	{
		$flags = array();
		
		$flags[] = new GroupFlag("ViewDash", "View Dashboard", Group::$ViewDash);
		$flags[] = new GroupFlag("AddJob", "Add a new job", Group::$AddJob);
		$flags[] = new GroupFlag("LaunchBuild", "Launch a build", Group::$LaunchBuild);
		$flags[] = new GroupFlag("ViewBuild", "View Build", Group::$ViewBuild);
		$flags[] = new GroupFlag("ViewFile", "View builded files", Group::$ViewFile);
		$flags[] = new GroupFlag("DeleteBuild", "Delete build", Group::$DeleteBuild);
		$flags[] = new GroupFlag("EditBuild", "Edit build configuration", Group::$EditBuild);
		$flags[] = new GroupFlag("ViewJob", "View job", Group::$ViewJob);
		$flags[] = new GroupFlag("AdminUsers", "Administrate users", Group::$AdminUsers);
		$flags[] = new GroupFlag("EditSettings", "Edit your settings", Group::$EditSettings);
		$flags[] = new GroupFlag("ViewWorker", "View current worker", Group::$ViewWorker);
		$flags[] = new GroupFlag("AdminWorkers", "Administrate workers", Group::$AdminWorkers);
		
		return $flags;
	}
	
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