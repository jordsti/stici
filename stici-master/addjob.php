<?php
	require_once("actions/AddJobAction.php");
	$page = new AddJobAction();
	$page->execute();
	require_once("header.php");	
	
	
	if($page->showForm())
	{
		require_once("addjob_form.html");
	}
	else if(count($page->getErrors()) > 0)
	{
		//redirect to index or error message
		$errors = $page->getErrors();
	}
	else
	{
		header('location: index.php');
	}
?>



<?php
	require_once("footer.php");