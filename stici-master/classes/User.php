<?php

class User
{
	public $id;
	public $username;
	public $email;
	public $hashType;
	public $password;
	public $groups;
	public $stamp;
	public $salt;
	public $pwdStamp;
	
	public static function GenerateSalt()
	{
		$salt = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890|!$%?&*()";
		$salt = str_shuffle($salt);
		
		$salt = substr($salt, 0, 32);
		return $salt;
	}
	
	public function __construct()
	{
		$this->id = 0;
		$this->username = "";
		$this->email = "";
		$this->hashType = "clear";
		$this->password = "";
		$this->stamp = 0;
		$this->groups = array();
		$this->salt = "";
		$this->pwdStamp = 0;
	}
	
	public function createdOn()
	{
		require_once("classes/utils.php");
		return GetTimeAgo($this->stamp);
	}
	
	public function testFlags($flag)
	{
		foreach($this->groups as $g)
		{
			if($g->testFlags($flag))
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function testPassword($password)
	{
		if(strcmp($this->hashType, 'clear') == 0)
		{
			//clear text password
			//not taking care of salt, cause THIS IS FUCKIN CLEAR TEXT
			return (strcmp($this->password, $password) == 0);
		}
		else 
		{
			if(strlen($this->salt) == 0)
			{
				return (strcmp($this->password, hash($this->hashType, $password)) == 0);
			}
			else
			{
				return (strcmp($this->password, hash($this->hashType, $password.$this->salt)) == 0);
			}
		}
			
	}
	
	
}