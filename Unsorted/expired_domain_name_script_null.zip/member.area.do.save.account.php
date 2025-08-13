<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	if (!isset($sess_id))
	{
			header("Location: index.php?action=sign_up&error_sign_in=".urlencode("Please login first."));
	}

	$query="update members set password='$password' where id='$sess_id'";
	$q->query($query);	

	header("Location: index.php?action=member_area");
?>