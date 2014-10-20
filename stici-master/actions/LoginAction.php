<?php
require_once("actions/CommonAction.php");
require_once("db/DbUser.php");
class LoginAction extends CommonAction
{
	public function __construct()
	{
		parent::__construct("Login");
		
		if($this->isLogged())
		{
			header('location: index.php');
		}
	}
	
	public function execute()
	{
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			$user = $_POST['username'];
			$pass = $_POST['password'];
			
			$u = DbUser::GetUserByUsername($user);
			
			if($u->id == 0)
			{
				$this->errors[] = "Invalid username or password !";
			}
			else 
			{
				if($u->testPassword($pass))
				{
					$_SESSION['user_id'] = $u->id;
					$_SESSION['logged'] = true;
					
					header('location: index.php');
				}	
				else
				{
					$this->errors[] = "Invalid username or password !";
				}
				
			}
			
		}
	}
}