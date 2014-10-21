<?php
require_once("actions/CommonAction.php");
require_once("db/DbBuildFile.php");
class DownloadFileAction extends CommonAction
{
	
	public function __construct()
	{
		parent::__construct("", Group::$ViewFile);
	}
	
	public function execute()
	{
		if(isset($_GET['file_id']))
		{
		
			$f = DbBuildFile::GetFile($_GET['file_id']);
		
			if(!is_int($f))
			{
		
				header('Content-Type: application/octet-stream');
				header("Content-length: " . filesize($f->filepath));
				header('Content-Disposition: attachment; filename="' . $f->filename . '"');
				header('Content-Transfer-Encoding: binary');
		
		
				readfile($f->filepath);
			}
		}
	}
	
}