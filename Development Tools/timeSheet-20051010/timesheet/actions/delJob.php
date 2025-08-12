<?php
$_REQUEST['id'] = intval($_REQUEST['id']);

$Q="SELECT client_id,id FROM jobs WHERE id='$_REQUEST[id]' AND user_id='$_SESSION[id]' LIMIT 1";
list($client_id,$job_id) = mysql_fetch_row(mysql_query($Q));

if ($job_id)
{
	$Q="DELETE FROM jobs WHERE id='$job_id'";
	mysql_query($Q);

	$Q="DELETE FROM tasks WHERE job_id='$job_id'";
	mysql_query($Q);

	$Q="DELETE FROM notes WHERE job_id='$job_id'";
	mysql_query($Q);
}

header("Location: index.php?page=editJobs&client_id=$client_id");

?>
