<?php

$_REQUEST['id'] = intval($_REQUEST['id']);

$Q="SELECT clients.id 
	FROM notes,jobs,clients 
	WHERE jobs.id=notes.job_id 
	AND clients.id=jobs.client_id 
	AND notes.id='".intval($_REQUEST['id'])."'";
list($client_id) = mysql_fetch_row(mysql_query($Q));

$Q="DELETE FROM notes WHERE id='".intval($_REQUEST['id'])."' AND user_id='$_SESSION[id]' LIMIT 1";
mysql_query($Q);

header("Location: index.php?page=editJobs&client_id=$client_id");

?>
