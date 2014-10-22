<?php

require_once("db/DbConnection.php");

class DbLoginAttempt
{
	public function Add($username)
	{
		$con = new DbConnection();
		$query = "INSERT INTO login_attempts (username, stamp) VALUES (?, ?)";
		$stamp = time();
		$st = $con->prepare($query);
		$st->bind_param("si", $username, $stamp);
		$st->execute();
		$con->close();
	}
	
	public function GetAttemptCount($username, $offset)
	{
		
		$stamp = time() - $offset;
		
		$con = new DbConnection();
		
		$query = "SELECT attempt_id FROM login_attempts WHERE username = ? and stamp > ?";
		$st = $con->prepare($query);
		$st->bind_param("si", $username, $offset);
		$st->bind_result($a_id);
		
		$st->execute();
		$i = 0;
		while($st->fetch())
		{
			$i += 1;
		}
		
		$con->close();
		
		return $i;
	}
}