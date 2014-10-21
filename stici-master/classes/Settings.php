<?php

class Settings
{
	protected $filepath;
	protected $settings;
	
	public function __construct($filepath = 'conf/settings.data')
	{
		$this->settings = array();
		$this->filepath = $filepath;
		if(!file_exists($filepath))
		{
			$this->defaultSettings();
			$this->save();
		}
		else
		{
			$this->load();
		}
	}
	
	public function get($set_name)
	{
		return $this->settings[$set_name];
	}
	
	public function defaultSettings()
	{
		$this->settings['password_hash'] = "sha256";
		$this->settings['username_max_char'] = 32;
		$this->settings['username_min_char'] = 5;
		$this->settings['password_min_char'] = 5;
	}
	
	public function save()
	{
		$fp = fopen($this->filepath, 'w');
		fwrite($fp, serialize($this->settings));
		fclose($fp);
	}
	
	public function load()
	{
		$fp = fopen($this->filepath, 'r');
		$this->settings = unserialize(fread($fp, 4096));
		fclose($fp);
	}
}