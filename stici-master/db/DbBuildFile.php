<?php

require_once("db/DbConnection.php");
require_once("classes/BuildFile.php");


class DbBuildFile
{
	
	public static function GetFile($file_id)
	{
		$bf = 0;
		
		$con = new DbConnection();
		
		$query = "SELECT bf.file_id, bf.build_id, bf.filepath, bf.filename, bf.filesize, bf.file_hash, bf.stamp, b.job_id FROM build_files bf JOIN builds b ON b.build_id = bf.build_id WHERE bf.file_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $file_id);
		$st->bind_result($f_id, $b_id, $f_path, $f_name, $f_size, $f_hash, $f_stamp, $j_id);
		$st->execute();
		
		if($st->fetch())
		{
			$bf = new BuildFile();
				
			$bf->id = $f_id;
			$bf->buildId = $b_id;
			$bf->filepath = $f_path;
			$bf->filename = $f_name;
			$bf->size = $f_size;
			$bf->hash = $f_hash;
			$bf->stamp = $f_stamp;
			$bf->jobId = $j_id;
				
		}
		
		$con->close();
		return $bf;

	}
	
	
	public static function GetFiles($build_id)
	{
		$list = array();
		
		$con = new DbConnection();
		
		$query = "SELECT bf.file_id, bf.build_id, bf.filepath, bf.filename, bf.filesize, bf.file_hash, bf.stamp, b.job_id FROM build_files bf JOIN builds b ON b.build_id = bf.build_id WHERE bf.build_id = ? ORDER BY bf.stamp DESC";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $build_id);
		$st->bind_result($f_id, $b_id, $f_path, $f_name, $f_size, $f_hash, $f_stamp, $j_id);
		$st->execute();
		
		while($st->fetch())
		{
			$bf = new BuildFile();
			
			$bf->id = $f_id;
			$bf->buildId = $b_id;
			$bf->filepath = $f_path;
			$bf->filename = $f_name;
			$bf->size = $f_size;
			$bf->hash = $f_hash;
			$bf->stamp = $f_stamp;
			$bf->jobId = $j_id;
			
			$list[] = $bf;
		}
		
		$con->close();
		return $list;
	}
	
	public static function AddFile($build_id, $filepath, $filename, $worker_hash)
	{
		$found = false;
		$con = new DbConnection();
		
		$query = "SELECT worker_hash FROM builds WHERE build_id = ? AND worker_hash = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("is", $build_id, $worker_hash);
		$st->bind_result($w_hash);
		
		$st->execute();
		
		if($st->fetch())
		{
			$found = true;
		}
		
		$con->close();
		
		if($found)
		{
			//this buils exists and worker match!
			$con = new DbConnection();
			
			$size = filesize($filepath);
			$md5 = md5_file($filepath);
			$stamp = time();
			
			$query = "INSERT INTO build_files (build_id, filepath, filename, filesize, file_hash, stamp) VALUES(?, ?, ?, ?, ?, ?)";
			$st = $con->prepare($query);
			$st->bind_param("issisi", $build_id, $filepath, $filename, $size, $md5, $stamp);
			$st->execute();
			
			$con->close();
		
		}
		
	}
}