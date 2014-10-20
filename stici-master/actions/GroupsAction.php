<?php
require_once("actions/CommonAction.php");

class GroupsAction extends CommonAction
{
	public $groups;

	public function __construct()
	{
		$this->groups = array();
		parent::__construct("Groups admin", Group::$AdminUsers);
	}
	
	public function execute()
	{
		$flags = Group::GetFlags();
		if(isset($_GET['save']))
		{
			$g_i = 0;
			$var_pre = "group_id_";
			while(true)
			{
				$var = $var_pre.$g_i;
				
				if(!isset($_POST[$var]))
				{
					break;
				}
				
				$g_id = $_POST[$var];
				$g_flags = 0;
				
				foreach($flags as $f)
				{
					$flag_var = "flag_".$g_i."_".$f->name;
					if(isset($_POST[$flag_var]))
					{
						$g_flags = $g_flags | $f->value;
					}
				}
				DbGroup::UpdateFlags($g_id, $g_flags);
				
				$g_i++;
			}
		}
		else if(isset($_GET['delete']))
		{
			if(isset($_GET['group_id']))
			{
				$g_id = $_GET['group_id'];
				DbGroup::RemoveGroup($g_id);
				DbGroup::UnassignAll($g_id);
			}
		}
		else if(isset($_GET['add']))
		{
			if(isset($_POST['name']))
			{
				DbGroup::AddGroup($_POST['name']);
			}
		}
	
		$this->groups = DbGroup::GetGroups();
	}
}