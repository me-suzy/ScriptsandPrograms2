<?php

$_REQUEST['id'] = intval($_REQUEST['id']);


$Q="SELECT job_id FROM tasks WHERE id='$_REQUEST[id]' AND user_id='$_SESSION[id]' LIMIT 1";
list($job_id) = mysql_fetch_row(mysql_query($Q));

if ($job_id)
{
	$Q="SELECT punchIn,punchOut FROM tasks WHERE id='$_REQUEST[id]' AND job_id='$job_id' LIMIT 1";
	list($in,$out) = mysql_fetch_row(mysql_query($Q));

	if (!$in)
	{
		//...... Punching IN
		$Q="UPDATE tasks SET punchIn=UNIX_TIMESTAMP() WHERE id='$_REQUEST[id]' AND job_id='$job_id' ";
		mysql_query($Q);
	}
	else
	{
		//...... Punching OUT
		$Q="UPDATE tasks SET punchOut=UNIX_TIMESTAMP() WHERE id='$_REQUEST[id]' AND job_id='$job_id' ";
		mysql_query($Q);

		$Q="SELECT COUNT(*) FROM tasks WHERE job_id='$job_id' AND punchOut=0";
		list($isOpen) = mysql_fetch_row(mysql_query($Q));

		if (!$isOpen)
		{
			//..... All tasks complete
			$Q="UPDATE jobs SET finished=NOW() WHERE id='$job_id'";
			mysql_query($Q);
		}
		else 
		{
			//..... All tasks complete
			$Q="UPDATE jobs SET finished=0 WHERE id='$job_id'";
			mysql_query($Q);
		}
	}
}


		
if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");



?>
