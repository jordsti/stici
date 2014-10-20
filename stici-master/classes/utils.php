<?php

function GetTimeAgo($stamp_end)
{
	if($stamp_end == 0)
	{
		return "";
	}
	
	$stamp = time() - $stamp_end;
	
	$sec = $stamp % 60;
	$min = floor($stamp / 60);
	$h = floor($min / 60);
	$d = floor($h / 24);
	$y = floor($d / 365);
	
	if($stamp < 60)
	{
		return $sec." seconds ago";
	}
	else if($min < 60)
	{
		return $min." minutes ago";
	}
	else if($h < 24)
	{
		return $h." hours ago";
	}
	else if($d < 360)
	{
		return $d." days ago";
	}
	else
	{
		return $y." years ago";
	}
}

function GetFormatedSize($size)
{
	$bytes = $size % 1024;
	$kbs = floor($size / 1024);
	$mbs = floor($kbs / 1024);
		
	if($size < 1024)
	{
		return $size." Byte(s)";
	}
	else if($size < (1024*1024))
	{
		return $kbs.".".str_pad($bytes, 3, '0', STR_PAD_LEFT)." Kilobyte(s)";
	}
	else
	{
		return $mbs.".".str_pad($kbs % 1024, 3, '0', STR_PAD_LEFT)." Megabyte(s)";
	}
}

function GetFormatedTime($stamp, $stamp_end)
{
	if($stamp_end == 0)
	{
		$end = time();
	}
	else
	{
		$end = $stamp_end;
	}
	
	$t = $end - $stamp;
	
	if($t < 60)
	{
		return $t." seconds";
	}
	else
	{
		$min = floor($t/60);
		$sec = $t % 60;
			
		$sec = str_pad($sec, 2, '0', STR_PAD_LEFT);
			
		return $min."m".$sec."s";
	}
}
