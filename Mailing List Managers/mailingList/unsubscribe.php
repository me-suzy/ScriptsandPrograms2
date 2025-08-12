<?php
///verify email address
include 'inc/config.php';
include 'inc/conn.php';
	///ok
	$del = mysql_query("delete from mailList where emailAddress = '".$add."'");
	if($del)
	{
		echo '<html><head><title>Successfully</title></head><body topmargin="60" leftmargin="250">
			  <table width="500" cellpadding="5" cellspacing="0"><tr><td>
			  <font face="verdana" size="2">We had successfully unsubscribe your email from our mailing list<br>
			  Thank you
			  <br>
			  <a href="'.$homePageUrl.'">'.$homePageUrl.'</a></font></td></tr></table></body></html>';
	}
	else
	{
		echo '<html><head><title>Unsuccessful</title></head><body topmargin="60" leftmargin="250">
				  <table width="500" cellpadding="5" cellspacing="0"><tr><td>
				  <font face="verdana" size="2">Please double check your email address. It seems your email is not in our mailing list.
				  <br>
				  <a href="'.$homePageUrl.'">'.$homePageUrl.'</a></font></td></tr></table></body></html>';
	}

mysql_close($conn);
?>