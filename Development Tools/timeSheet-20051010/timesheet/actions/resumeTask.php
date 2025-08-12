<?php

$_REQUEST['id'] = intval($_REQUEST['id']);


$Q="SELECT job_id FROM tasks WHERE id='$_REQUEST[id]' AND user_id='$_SESSION[id]' LIMIT 1";
list($job_id) = mysql_fetch_row(mysql_query($Q));

if ($job_id)
{
	$Q="SELECT punchIn,punchOut 
		FROM tasks 
		WHERE id='$_REQUEST[id]' 
		AND user_id='$_SESSION[id]' LIMIT 1";
	list($in,$out) = mysql_fetch_row(mysql_query($Q));

	$diff = $out - $in;
	$in = time() - $diff;
	$Q="UPDATE tasks 
		SET punchIn='$in',punchOut=0 
		WHERE id='$_REQUEST[id]' 
		AND user_id='$_SESSION[id]' LIMIT 1";
	mysql_query($Q);

	//..... We're resuming, so open job back up
	$Q="UPDATE jobs SET finished=0 WHERE id='$job_id'";
	mysql_query($Q);

}

if ($_REQUEST['REF'])
{
		header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
