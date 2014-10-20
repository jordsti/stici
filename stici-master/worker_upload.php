<?php 

if(isset($_FILES['fileupload'])
		&& isset($_POST['build_id']) 
		&& isset($_POST['hash'])
		&& isset($_POST['filename']))
{
	
	$filename = $_POST['filename'];
	$dest = 'files/'.$filename;
	//todo
	if(move_uploaded_file($_FILES['fileupload']['tmp_name'], $dest))
	{
		echo 'File upload!';
	}
	
	
	
}
?>