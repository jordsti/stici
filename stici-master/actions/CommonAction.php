<?php

class CommonAction {
	protected $title;


	public function __construct($title = "None")
	{
		$this->title = $title;
	}
	
	public function execute()
	{
	
	}
	
	public function getTitle()
	{
		return $this->title;
	}

}