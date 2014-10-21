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
		if(isset($_GET['save']))
		{
			$save = false;
			$settings = $this->settings();
			
			foreach($settings as $k => $n)
			{
				if(isset($_POST[$k]))
				{
					$settings[$k] = $_POST[$k];
					$save = true;
				}
			}
			
			//save settings
			if($save)
			{
				$this->overwriteSettings($settings);
			}
			
		}
	}
}