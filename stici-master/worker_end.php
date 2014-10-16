<?php
require_once("db/DbWorker.php");

if(isset($_GET['build_id']) && isset($_GET['hash']) && isset($_GET['status']))
{
	$build_id = $_GET['build_id'];
	$whash = $_GET['hash'];
	$status = $_GET['hash'];
	DbWorker::EndBuild($build_id, $whash, $status);
}