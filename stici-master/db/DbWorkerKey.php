<?php
require_once("db/DbConnection.php");
require_once("classes/WorkerKey.php");
class DbWorkerKey
{
	public static function AddKey($key, $status)
	{
		$con = new DbConnection();
		$query = "INSERT INTO worker_keys (worker_key, status, stamp) VALUES(?, ?, ?)";
		$st = $con->prepare($query);
		$stamp = time();
		$st->bind_param("sii", $key, $status, $stamp);
		$st->execute();
		$con->close();
	}
	
	public static function ChangeStatus($key_id, $status)
	{
		$con = new DbConnection();
		$query = "UPDATE worker_keys SET status = ? WHERE key_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("ii", $status, $key_id);
		$st->execute();
		$con->close();
	}
	
	public static function TestKey($key)
	{
		$status = WorkerKey::$Active;
		$valid = false;
		
		$con = new DbConnection();
		$query = "SELECT worker_key FROM worker_keys WHERE worker_key = ? and status = ?";
		$st = $con->prepare($query);
		$st->bind_param("si", $key, $status);
		$st->execute();
		
		if($st->fetch())
		{
			$valid = true;
		}
		
		$con->close();
		
		return $valid;
	}
	
	public static function GetKeys()
	{
		$list = array();
		
		$con = new DbConnection();
		$query = "SELECT key_id, worker_key, status, stamp FROM worker_keys ORDER BY key_id DESC";
		$st = $con->prepare($query);
		$st->bind_result($k_id, $w_key, $status, $stamp);
		$st->execute();
		
		while($st->fetch())
		{
			$k = new WorkerKey();
			$k->id = $k_id;
			$k->key = $w_key;
			$k->status = $status;
			$k->stamp = $stamp;
			$list[] = $k;
		}
		
		$con->close();
		
		return $list;
	}
}