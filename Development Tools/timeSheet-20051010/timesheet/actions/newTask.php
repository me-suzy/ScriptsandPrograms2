<?php

$_REQUEST['job_id'] = intval($_REQUEST['job_id']);

print "<pre>";
print_r($_REQUEST);

$Q="INSERT INTO tasks 
	SET user_id='$_SESSION[id]',
	job_id='$_REQUEST[job_id]',
	punchDesc='".addslashes($_REQUEST['punchDesc'])."'";
mysql_query($Q);

//..... this job is now "Unfinished"
$Q="UPDATE jobs SET finished='0' WHERE id='$_REQUEST[job_id]' AND user_id='$_SESSION[id]'";
mysql_query($Q);

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
