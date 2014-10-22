<?php
require_once("db/DbUser.php");
require_once("db/DbGroup.php");
require_once("classes/Settings.php");
session_start();

class CommonAction {
	protected $title;
	public $errors;
	public $userId;
	public $user;
	public $groups;
	private $settings;
	private $logged;

	public function __construct($title = "None", $requiredFlags = 0)
	{
		$this->logged = false;
		$this->userId = 0;
		$this->user = new User();
		$this->title = $title;
		$this->errors = array();
		$this->groups = array();
		$this->settings = new Settings();
		
		if(isset($_SESSION['errors']))
		{
			$errors = $_SESSION['errors'];
			if(count($errors) > 0)
			{
				$this->errors = $errors;
				$_SESSION['errors'] = array();
			}
		}
		
		if(isset($_SESSION['user_id']) && isset($_SESSION['logged']))
		{
			$this->logged = true;
			$this->userId = $_SESSION['user_id'];
			
			//retrieving user
			$user = DbUser::GetUserById($this->userId);
			
			if($user->id == 0)
			{
				//errors there
				$this->errors[] = "This user is invalid !";
				
			}
			else
			{
				$this->user = $user;
				DbUser::fillGroup($user);
				$this->groups = $user->groups;

				$page_name = "my_account.php";
				
				if($this->setting("password_expire_days") > 0 && strcmp( substr( $_SERVER['SCRIPT_NAME'], strlen($_SERVER['SCRIPT_NAME']) - strlen($page_name), strlen($page_name)), $page_name) != 0)
				{
					if($this->user->pwdStamp == 0)
					{
						//must change now ! this password was assigned by default or by the admin
						$this->passwordExpired();
					}
					else
					{
						$diff = time() - $this->user->pwdStamp;
						$secs_expire = $this->setting("password_expire_days")*3600*24;
						
						if($diff > $secs_expire)
						{
							$this->passwordExpired();
						}
						
					}
				}
			}
			
		}
		else
		{
			//guest
			$this->groups[] = DbGroup::GetGroupByName('Guest');
		}
		
		if(!$this->testGroupFlags($requiredFlags) && $requiredFlags != 0)
		{
			$this->redirectError(array('You don\'t got the right to peform this action'));
		}
	}
	
	private function passwordExpired()
	{
		$this->redirectError(array("Your password is expired ! You must change it now !"), 'my_account.php');
	}
	
	public function overwriteSettings($settings)
	{
		$this->settings->overwrite($settings);
	}
	
	public function setting($setting)
	{
		return $this->settings->get($setting);
	}
	
	public function settings()
	{
		return $this->settings->getall();
	}
	
	public function redirectError($errors , $page = 'error_page.php')
	{
		$_SESSION['errors'] = $errors;
		header("location: ".$page);
		exit();
	}
	
	public function testGroupFlags($flag)
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
	
	public function errorsExists()
	{
		return (count($this->errors) > 0);
	}
	
	public function isLogged()
	{
		return $this->logged;
	}
	
	public function execute()
	{
	
	}
	
	public function getTitle()
	{
		return $this->title;
	}

}