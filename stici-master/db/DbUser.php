<?php

require_once("db/DbConnection.php");
require_once("classes/User.php");
require_once("classes/Group.php");

class DbUser
{
	public static function AddUser($username, $password, $hashtype, $email)
	{
		$con = new DbConnection();
		$query = "INSERT INTO users (username, password, hash_type, email, stamp) VALUES (?, ?, ?, ?, ?)";
		$stamp = time();
		$st = $con->prepare($query);
		$st->bind_param("ssssi", $username, $password, $hashtype, $email, $stamp);
		
		$st->execute();
		
		$con->close();
	}
	
	public static function UpdateEmail($user)
	{
		if($user->id != 0)
		{
			$con = new DbConnection();
				
			$query = "UPDATE users SET email = ? WHERE user_id = ?";
			$st = $con->prepare($query);
			$email = $user->email;
			$u_id = $user->id;
			$st->bind_param("si", $email, $u_id);
			$st->execute();
				
			$con->close();
		}
	}
	
	
	public static function UpdatePassword($user)
	{
		if($user->id != 0)
		{
			$con = new DbConnection();
			
			$query = "UPDATE users SET hash_type = ?, password= ? WHERE user_id = ?";
			$st = $con->prepare($query);
			$hash = $user->hashType;
			$pw = $user->password;
			$u_id = $user->id;
			$st->bind_param("ssi", $hash, $pw, $u_id);
			$st->execute();
			
			$con->close();
		}
	}

	public static function GetUsers()
	{
		$list = array();
		//we dont need groups for login
		$con = new DbConnection();
		
		$query = "SELECT user_id, username, password, hash_type, email, stamp FROM users ORDER BY user_id ASC";
		$st = $con->prepare($query);
		$st->bind_result($u_id, $u_name, $u_pass, $u_hash, $u_email, $u_stamp);
		$st->execute();
		
		while($st->fetch())
		{
			$user = new User();
			$user->id = $u_id;
			$user->username = $u_name;
			$user->password = $u_pass;
			$user->hashType = $u_hash;
			$user->email = $u_email;
			$user->stamp = $u_stamp;
			$list[] = $user;
		}
		
		$con->close();
		
		return $list;
	}

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