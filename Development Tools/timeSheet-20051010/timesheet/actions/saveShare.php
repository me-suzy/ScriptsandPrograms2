<?php

$Q="DELETE 
	FROM clientShare 
	WHERE client_id='$_REQUEST[client_id]' 
	AND owner_id='$_SESSION[id]'";
	mysql_query($Q);

	foreach($_REQUEST['share'] as $soid)
	{
		$Q="INSERT INTO clientShare SET client_id='$_REQUEST[client_id]',owner_id='$_SESSION[id]',share_owner_id='$soid'";
		mysql_query($Q);
	}

print_r($_REQUEST);
header("Location: index.php?page=editJobs&client_id=$_REQUEST[client_id]");

?>
