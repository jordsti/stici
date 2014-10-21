<?php
require_once("actions/CommonAction.php");
require_once("db/DbWorkerKey.php");

class WorkerKeyAction extends CommonAction
{
	public $keys;
	
	public function __construct()
	{
		$this->keys = array();
		parent::__construct('Workers Key admin', Group::$AdminWorkers);
	}
	
	public function execute()
	{
		if(isset($_GET['generate']))
		{
			$str = "qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM";
			$str = str_shuffle($str);
			//todo setting here
			$key = substr($str, 12);
			$status = WorkerKey::$Active;
			DbWorkerKey::AddKey($key, $status);
		}
		else if(isset($_GET['revoke']) && isset($_GET['id']))
		{
			$k_id = $_GET['id'];
			$status = WorkerKey::$Revoked;
			DbWorkerKey::ChangeStatus($k_id, $status);
		}
		
		$this->keys = DbWorkerKey::GetKeys();
	}
}