<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	if (!isset($sess_id))
	{
			header("Location: index.php?action=sign_up&error_sign_in=".urlencode("Please login first."));
	}
	$cmember="";
	
	$query="select * from members where id='$sess_id'";
	$q->query($query);
	$q->next_record();
	
	FFileRead("template.member.area.account.info.htm",$cmember);
	$cmember=str_replace("{email}",$q->f("email"),$cmember);
	$cmember=str_replace("{password}",$q->f("password"),$cmember);
	FFileRead("template.member.area.main.htm",$content);
	$content=str_replace("{content}",$cmember,$content);

?>