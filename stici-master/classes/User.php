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
	
	public function __construct()
	{
		$this->id = 0;
		$this->username = "";
		$this->email = "";
		$this->hashType = "clear";
		$this->password = "";
		$this->stamp = 0;
		$this->groups = array();
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
			return (strcmp($this->password, $password) == 0);
		}
		else 
		{
			return (strcmp($this->password, hash($this->hashType, $password)) == 0);
		}
			
	}
	
	
}