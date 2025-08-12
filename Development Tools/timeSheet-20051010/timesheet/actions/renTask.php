<?php


foreach ($_REQUEST['task'] as $key=>$value)
{
	//...... This allows us to reset the punch tasks
	$taskTime = $_REQUEST['taskTime'];

	//...... Parse the time fields
	$pt = $ts->parseTime($taskTime[$key]);

	//...... If we alread have a punchout time
	$Q="SELECT punchIn,punchOut from tasks WHERE id='".intval($key)."'";
	list($pi,$po) = mysql_fetch_row(mysql_query($Q));

	if ($po)
	{
		$pt = $po - $pt;
	}
	elseif ($pi == 0 && $po == 0)
	{
		$pt = 0;
	}
	else
	{
		$pt = time() - $pt;
	}
	//.........................................

	//...... Update the task tasks
	$Q="UPDATE tasks 
		SET punchDesc='".addslashes($value)."',
		punchIn='".$pt."'
		WHERE id='".intval($key)."' 
		AND user_id='$_SESSION[id]' LIMIT 1";

	mysql_query($Q);
}

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
