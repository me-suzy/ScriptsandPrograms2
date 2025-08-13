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
		$query="insert into monitor (id, member_id, domain_id) values(NULL, '$sess_id', '$x')";
		$q->query($query);
	}

	header("Location: index.php?action=monitor_domains");
?>