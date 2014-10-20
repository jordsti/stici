<?php

require_once("db/DbConnection.php");
require_once("classes/Group.php");

class DbGroup
{
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
}