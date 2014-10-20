<?php
require_once("actions/CommonAction.php");

class EditUserAction extends CommonAction
{
	public $_groups;
	public $_user;
	public $all_groups;

	public function __construct()
	{
		$this->_groups = array();
		$this->_user = 0;
		parent::__construct("Edit user", Group::$AdminUsers);
	}
	
	public function execute()
	{
		if(isset($_GET['user_id']))
		{
			$this->_user = DbUser::GetUserById($_GET['user_id']);
			if($this->_user->id == 0)
			{
				$this->errors[] = "This user doesn't exists";
				return;
			}
			
			if(isset($_GET['addgrp']) && isset($_POST['add_group']))
			{
				$grp_name = $_POST['add_group'];
				$g = DbGroup::GetGroupByName($grp_name);
				
				DbGroup::AssignGroup($this->_user, $g);
			}
			else if(isset($_GET['remgrp']) && isset($_GET['group']))
			{
				$grp_name = $_GET['group'];
				$g = DbGroup::GetGroupByName($grp_name);
				DbGroup::UnassignGroup($this->_user, $g);
			}
			
			$this->all_groups = DbGroup::GetGroups();
			
			DbUser::fillGroup($this->_user);
			$this->_groups = $this->_user->groups;
		}
		else
		{
			header('location: users.php');
		}
	}
}