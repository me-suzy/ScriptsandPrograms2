<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	if (!isset($sess_id))
	{
			header("Location: index.php?action=sign_up&error_sign_in=".urlencode("Please login first."));
	}
	
	foreach ($check as $x => $value)
	{	
		$query="delete from monitor where member_id='$sess_id' and domain_id='$x'";
		$q->query($query);
	}

	header("Location: index.php?action=monitor_domains");
?>