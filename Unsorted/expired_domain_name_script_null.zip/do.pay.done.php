<?php
	if (!eregi("index.php", $PHP_SELF)) 
	{
    	die ("You can't access this file directly...");
	}
	if (isset($payer_email))
	{
		$email=$payer_email;
		$password=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));
		$query="insert into members (id, email, password) values (NULL, '$email', '$password')";
		$q->query($query);
		$body=get_setting("welcome_email");
		$subject=get_setting("welcome_subject");
		$body=str_replace("{sitename}",$sitename,$body);
		$body=str_replace("{password}",$password,$body);
		$body=str_replace("{email}",$email,$body);
		$subject=str_replace("{sitename}",$sitename,$subject);
		mail($email, $subject, $body, "From: $sitename <$webmasteremail>");
		header("Location: index.php?action=pay_message");
	}
	else echo "Something is wrong with payment !!!";

?>