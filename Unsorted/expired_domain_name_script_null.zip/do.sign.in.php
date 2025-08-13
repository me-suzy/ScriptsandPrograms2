<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	$query="select id from members where email='$email' and password='$password' limit 0,1";
	$q->query($query);
	if ($q->nf()!=0)
	{
		$q->next_record();
		$sess_id=$q->f("id");
		session_register("sess_id");
		header("Location: index.php?action=member_area");
	}
	else
	{
			header("Location: index.php?action=sign_up&error_sign_in=".urlencode("Your login does not exist. Sign up first."));
	}

?>