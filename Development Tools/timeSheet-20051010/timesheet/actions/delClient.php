<?php

$_REQUEST['id'] = intval($_REQUEST['id']);

$Q="SELECT COUNT(*) FROM clients WHERE id='$_REQUEST[id]' AND user_id='$_SESSION[id]' LIMIT 1";
list($doDel) = mysql_fetch_row(mysql_query($Q));

if ($doDel)
{
	$Q="DELETE FROM clients WHERE id='$_REQUEST[id]' LIMIT 1";
	mysql_query($Q);

	$Q="SELECT id FROM jobs WHERE client_id='$_REQUEST[id]'";
	$res = mysql_query($Q);
	print mysql_error();

	while(list($jobs) = mysql_fetch_row($res))
	{
		$Q="DELETE FROM jobs WHERE id='$jobs'";
		mysql_query($Q);

        $Q="DELETE FROM tasks WHERE job_id='$jobs'";
        mysql_query($Q);

		$Q="DELETE FROM notes WHERE job_id='$jobs'";
		mysql_query($Q);

	}
}

header("Location: index.php");

?>
