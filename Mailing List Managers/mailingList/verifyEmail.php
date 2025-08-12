<?php
///verify email address
include 'inc/config.php';
include 'inc/conn.php';
$q = mysql_query("select * from temp where verifyKey = '".$code."'");
if(!$q)
{
	echo '<html><head><title>Unsuccessful</title></head><body topmargin="60" leftmargin="250">
			  <table width="500" cellpadding="5" cellspacing="0"><tr><td>
			  <font face="verdana" size="2">Please double check your verification and email address combination. We 
			  fail to verify it!
			  <br>
			  <a href="'.$homePageUrl.'">'.$homePageUrl.'</a></font></td></tr></table></body></html>';
	exit();
}
while($result = mysql_fetch_array($q))
{
	$name = $result['name'];
	$emailAddress = $result['emailAddress'];
	$verifyKey = $result['verifyKey'];
}
$code = $HTTP_GET_VARS['code'];
$add = $HTTP_GET_VARS['add'];
if(@$code == @$verifyKey && @$add == @$emailAddress)
{
	///ok
	$values = 'values ("'.$name.'", "'.$emailAddress.'", "'.$REMOTE_ADDR.'", "'.date("d M Y").'")';
	$insert = mysql_query("insert into mailList(name, emailAddress, IPaddress, subTime) ".$values);
	if($insert)
	{
		echo '<html><head><title>Successfully</title></head><body topmargin="60" leftmargin="250">
			  <table width="500" cellpadding="5" cellspacing="0"><tr><td>
			  <font face="verdana" size="2">We had successfully add your email to our mailing list<br>
			  Thank you
			  <br>
			  <a href="'.$homePageUrl.'">'.$homePageUrl.'</a></font></td></tr></table></body></html>';
		///remove from temporary table
		$q = mysql_query("delete from temp where emailAddress = '".$emailAddress."'");
	}
}
else
{
	echo '<html><head><title>Unsuccessful</title></head><body topmargin="60" leftmargin="250">
			  <table width="500" cellpadding="5" cellspacing="0"><tr><td>
			  <font face="verdana" size="2">Please double check your verification and email address combination. We 
			  fail to verify it!
			  <br>
			  <a href="'.$homePageUrl.'">'.$homePageUrl.'</a></font></td></tr></table></body></html>';
}
mysql_free_result($q);
mysql_close($conn);
?>