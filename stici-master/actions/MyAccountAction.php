<?php
require_once("actions/CommonAction.php");

class MyAccountAction extends CommonAction
{
	public function __construct()
	{
		parent::__construct("My Account");
	}
	
	public function execute()
	{
		if(!$this->isLogged())
		{
			header('location: index.php');
		}
		
		if(isset($_GET['chpass']))
		{
			$actual = $_POST['actual'];
			$pw = $_POST['password'];
			$pw2 = $_POST['password2'];
			
			if(!$this->user->testPassword($actual))
			{
				$this->errors[] = "Password mismatch";
				return;
			}
			
			if(strcmp($pw, $pw2) != 0)
			{
				$this->errors[] = "Password mismatch";
				return;
			}
			
			if(strlen($pw) < $this->setting("password_min_char"))
			{
				$this->errors[] = "Your password must have at least ".$this->setting("password_min_char")." characters";
				return;
			}
			
			$user = $this->user;
			
			$user->hashType = $this->setting("password_hash");
			$user->password = hash($user->hashType, $pw);
			
			DbUser::UpdatePassword($user);
			
		}
		else if(isset($_GET['chemail']))
		{
			if(isset($_POST['email']))
			{
				$user = $this->user;
				$user->email = $_POST['email'];
				DbUser::UpdateEmail($user);
			}
		}
	}
}