<?php
require_once("actions/CommonAction.php");

class UsersAction extends CommonAction
{
	public $users;
	public $groups;

	public function __construct()
	{
		$this->users = array();
		$this->groups = array();
		parent::__construct("Users admin", Group::$AdminUsers);
	}
	
	public function execute()
	{
		$this->groups = DbGroup::GetGroups();
	
		if(isset($_GET['add']))
		{
			if(!isset($_POST['username']) ||
				!isset($_POST['password']) ||
				!isset($_POST['password2']) ||
				!isset($_POST['group'])	)
			{
				return;
			}
		
			//adding user
			$username = strtolower($_POST['username']);
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$grp = $_POST['group'];
		
			if(strlen($username) < $this->setting("username_min_char") || strlen($username) > $this->setting("username_max_char"))
			{
				$this->errors[] = "Invalid username, must be between 5 and 32 characters";
				return;
			}
			else if(strcmp($password, $password2) != 0)
			{
				$this->errors[] = "The two password didn't matches";
				return;
			}
			else
			{
				//checking if this user already exists
				$u = DbUser::GetUserByUsername($username);
				if($u->id != 0)
				{
					$this->errors[] = "This username already exists";
					return;
				}
			}
			
			//this is good
			$hash_type = "sha256";
			$salt = User::GenerateSalt();
			$pw_hash = hash($hash_type, $password.$salt);
			$email = "";
			
			DbUser::AddUser($username, $pw_hash, $salt, $hash_type, $email);
			
			$user = DbUser::GetUserByUsername($username);
			
			foreach($this->groups as $g)
			{
				if(strcmp($g->name, $grp) == 0)
				{
					DbGroup::AssignGroup($user, $g);
					break;
				}
			}
		}
	
		$this->users = DbUser::GetUsers();	
	}
}