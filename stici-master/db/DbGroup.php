<?php

require_once("db/DbConnection.php");
require_once("classes/Group.php");

class DbGroup
{
	public static function UpdateFlags($group_id, $flags)
	{
		$con = new DbConnection();
		
		$query = "UPDATE groups SET flags = ? WHERE group_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("ii", $flags, $group_id);
		$st->execute();
		
		$con->close();
	}

	public static function GetGroupByName($group_name)
	{
		$group = new Group();
		$con = new DbConnection();
		
		$query = "SELECT group_id, group_name, flags FROM groups WHERE group_name = ?";
		$st = $con->prepare($query);
		$st->bind_param("s", $group_name);
		$st->bind_result($g_id, $g_name, $g_flags);
		$st->execute();
		
		if($st->fetch())
		{
			$group->id = $g_id;
			$group->name = $g_name;
			$group->flags = $g_flags;
		}
		
		$con->close();
		
		return $group;
	}
	
	public static function GetGroups()
	{
		$list = array();
		$con = new DbConnection();
		
		$query = "SELECT group_id, group_name, flags FROM groups";
		$st = $con->prepare($query);
		$st->bind_result($g_id, $g_name, $g_flags);
		$st->execute();
		
		while($st->fetch())
		{
			$group = new Group();
			$group->id = $g_id;
			$group->name = $g_name;
			$group->flags = $g_flags;
			$list[] = $group;
		}
		
		$con->close();
		
		return $list;
	}
	
	public static function UnassignAll($group_id)
	{
		$con = new DbConnection();
		$query = "DELETE FROM users_groups WHERE group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $group_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function RemoveGroup($group_id)
	{
		$con = new DbConnection();
		$query = "DELETE FROM groups WHERE group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $group_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function AddGroup($name, $flag = 0)
	{
		//name must not be already existing
		$grp = DbGroup::GetGroupByName($name);
		
		if($grp->id != 0)
		{
			return;
		}
	
		$con = new DbConnection();
		$query = "INSERT INTO groups (group_name, flags) VALUES (?, ?)";
		
		$st = $con->prepare($query);
		$st->bind_param("si", $name, $flag);
		$st->execute();
		$con->close();
	}
	
	public static function UnassignGroup($user, $group)
	{
		if($user->id == 0 || $group->id == 0)
		{
			return;
		}
		
		$con = new DbConnection();
		$u_id = $user->id;
		$g_id = $group->id;
		
		$query = "DELETE FROM users_groups WHERE user_id = ? AND group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("ii", $u_id, $g_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function AssignGroup($user, $group)
	{
		if($user->id != 0 && $group->id != 0)
		{
			foreach($user->groups as $g)
			{
				if($g->id == $group->id)
				{
					return;
				}
			}
		}
		else
		{
			return;
		}
		
		$con = new DbConnection();
		$query = "INSERT INTO users_groups (group_id, user_id) VALUES (?, ?)";
		$u_id = $user->id;
		$g_id = $group->id;
		
		$st = $con->prepare($query);
		$st->bind_param("ii", $g_id, $u_id);
		$st->execute();
		$con->close();
	}
}