<?php
require_once("actions/CommonAction.php");

class SettingsAction extends CommonAction
{
	public function __construct()
	{
		parent::__construct("Settings", Group::$EditSettings);
	}
	
	public function execute()
	{
	
	}
}