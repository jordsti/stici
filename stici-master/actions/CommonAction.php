<?php
require_once("db/DbUser.php");
require_once("db/DbGroup.php");

session_start();

class CommonAction {
	protected $title;
	public $errors;
	public $userId;
	public $user;
	public $groups;
	private $logged;

	public function __construct($title = "None", $requiredFlags = 0)
	{
		$this->logged = false;
		$this->userId = 0;
		$this->user = new User();
		$this->title = $title;
		$this->errors = array();
		$this->groups = array();
		
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
	
	public function redirectError($errors)
	{
		$_SESSION['errors'] = $errors;
		header("location: error_page.php");
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