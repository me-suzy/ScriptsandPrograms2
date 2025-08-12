<?php

$_REQUEST['client_id'] = intval($_REQUEST['client_id']);
$_REQUEST['rate'] = floatval($_REQUEST['rate']);


$Q="INSERT INTO jobs 
	SET jobDesc='".addslashes($_REQUEST['jobDesc'])."',
	rate='$_REQUEST[rate]',
	user_id='$_SESSION[id]',
	client_id='$_REQUEST[client_id]',
	start=NOW()";
mysql_query($Q);

header("Location: index.php?page=editJobs&client_id=$_REQUEST[client_id]");
	

?>
