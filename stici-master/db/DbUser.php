<?php

require_once("db/DbConnection.php");
require_once("classes/User.php");
require_once("classes/Group.php");

class DbUser
{
	public static function GetUserById($user_id)
	{
		$user = new User();
		//we dont need groups for login
		$con = new DbConnection();
	
		$query = "SELECT user_id, username, password, hash_type, email, stamp FROM users WHERE user_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $user_id);
		$st->bind_result($u_id, $u_name, $u_pass, $u_hash, $u_email, $u_stamp);
		$st->execute();
	
		if($st->fetch())
		{
			$user->id = $u_id;
			$user->username = $u_name;
			$user->password = $u_pass;
			$user->hashType = $u_hash;
			$user->email = $u_email;
			$user->stamp = $u_stamp;
		}
	
		$con->close();
	
		return $user;
	}
	
	
	public static function GetUserByUsername($username)
	{
		$user = new User();
		//we dont need groups for login
		$con = new DbConnection();
		
		$query = "SELECT user_id, username, password, hash_type, email, stamp FROM users WHERE username = ?";
		$st = $con->prepare($query);
		$st->bind_param("s", $username);
		$st->bind_result($u_id, $u_name, $u_pass, $u_hash, $u_email, $u_stamp);
		$st->execute();
		
		if($st->fetch())
		{
			$user->id = $u_id;
			$user->username = $u_name;
			$user->password = $u_pass;
			$user->hashType = $u_hash;
			$user->email = $u_email;
			$user->stamp = $u_stamp;
		}
		
		$con->close();
		
		return $user;
	}
	
	public static function fillGroup($user)
	{
		if($user->id != 0)
		{
			$con = new DbConnection();
			
			$query = "SELECT g.group_id, g.group_name, g.flags FROM groups g JOIN users_groups ug ON ug.group_id = g.group_id WHERE ug.user_id = ?";
			
			$st = $con->prepare($query);
			$user_id = $user->id;
			$st->bind_param("i", $user_id);
			$st->bind_result($g_id, $g_name, $flags);
			$st->execute();
			
			while($st->fetch())
			{
				$g = new Group();
				$g->id = $g_id;
				$g->name = $g_name;
				$g->flags = $flags;
				
				$user->groups[] = $g;
			}
			
			$con->close();
		}
	}
	
}