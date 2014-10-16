<?php
require_once("db/DbWorker.php");

if(
	isset($_POST['build_id']) &&
	isset($_POST['step_id']) &&
	isset($_POST['hash']) &&
	isset($_POST['stdout']) &&
	isset($_POST['stderr']) &&
	isset($_POST['return_code'])
)
{
	$build_id = $_POST['build_id'];
	$step_id = $_POST['step_id'];
	$whash = $_POST['hash'];
	$stdout = $_POST['stdout'];
	$stderr = $_POST['stderr'];
	$rc = $_POST['return_code'];

	DbWorker::InsertStepLog($whash, $build_id, $step_id, $stdout, $stderr, $rc);
}