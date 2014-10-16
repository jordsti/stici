<?php
require_once("actions/CommonAction.php");
require_once("db/DbWorker.php");

class WorkersAction extends CommonAction
{
	public $workers;

	public function __construct()
	{
		$this->workers = array();
		parent::__construct("Workers");
	}
	
	public function execute()
	{
		$this->workers = DbWorker::GetWorkers();
	}
}