<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	$query="select password from members where email='$email' limit 0,1";
	$q->query($query);
	if ($q->nf()!=0)
	{
		$q->next_record();
		$body=get_setting("forgot_pass_email");
		$subject=get_setting("forgot_pass_subject");
		$body=str_replace("{sitename}",$sitename,$body);
		$body=str_replace("{password}",$q->f("password"),$body);
		$subject=str_replace("{sitename}",$sitename,$subject);
		mail($email, $subject, $body, "From: $sitename <$webmasteremail>");
		header("Location: index.php?action=sign_up&error_forget_password=".urlencode("Your password has been sent."));
	}
	else
	{
		header("Location: index.php?action=sign_up&error_forget_password=".urlencode("Your email does not exist. Sign up first."));
	}

?>