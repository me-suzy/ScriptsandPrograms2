<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();
include ("config.php");
include ("settings.inc.php");
include ("loginheader.inc.php");

/*
if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
{
	echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
	exit;
}  
*/

// print header

echo "<a name=\"toppage\"></a>
<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
<tr bgcolor=\"#cccccc\">
<td width=\"100%\"><a href=\"$site_url\" target=\"_blank\"><img src=\"images/admin_logo_250.gif\" width=\"250\" height=\"50\" border=\"0\" hspace=\"0\" vspace=\"0\"></a></td>
</tr>
</table>
<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td height=\"2\"><img src=\"images/z.gif\" width=\"1\" height=\"1\" border=\"0\" hspace=\"0\" vspace=\"0\"></td></tr>
<tr><td height=\"2\" bgcolor=\"#000000\"><img src=\"images/z.gif\" width=\"1\" height=\"1\" border=\"0\" hspace=\"0\" vspace=\"0\"></td></tr>
</table>
\n";



$submit = false;
if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	extract( $_POST );
	$submit = true;
}

if( $submit )
{
	// Generate random password
	$new_pass = abcGenPassword();

	// Check username address is held in database
	$result = mysql_query ("select * from ".$prefix."store_config where user = '$user_name'");
	$count_results = mysql_num_rows($result);

	if( $count_results == 0 )
		echo "<br><br><p align=\"center\"><b> ".$lng[590]."</b>
		<Br><br>".$lng[591]." <b>$user_name</b>".$lng[592]."
		<Br><Br>".$lng[593]."
		<br><br><a href=\"forgot_pass.php\" target=\"_self\">".$lng[594]."</a>
		</p>";
	else
	{
		// If all is ok encrypt password in database and send new password to the users e-mail address
		$passwd = md5($new_pass);
		$res = mysql_query ("update ".$prefix."store_config set pass='$passwd' where user = '$user_name'");
		if ($res)
		{
			echo "<br><br><p align=\"center\"><b>".$lng[590]."</b><br><br>
				".$lng[595].": <b>$site_email</b><br><br>
				".$lng[596]."</p>
				<p align=\"center\"><a href=\"$site_url/admin\">".$lng[597]."</a></p>";
			$sendto = $site_email;
			$from = $site_email;
			$subject = $lng[856];
			eval('$message = "'.$lng[857].'";');
	
			$headers = "From: $site_email\r\n";
			
			// send e-mail
			mail($sendto, $subject, $message, $headers);
		} 
	}
}
//end if submit

if( !$submit )
{
	echo "<br><br><br>
	<form method=\"post\" enctype=\"multipart/form-data\" action=\"forgot_pass.php\">
	
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"400\">
	<tr>
	<td colspan=\"2\" width=\"400\"><h2>".$lng[598]."</h2></td>
	</tr>
	
	<tr> 
	<td colspan=\"2\" width=\"400\"><p>".$lng[599]."</p><p>".$lng[600]."</p></td>
	</tr>
	
	<tr> 
	<td width=\"200\"><b>".$lng[601]."</b>:</td>
	<td width=\"200\"> <input type=\"text\" class=\"textbox\" name=\"user_name\">
	</td>
	</tr>
	
	<tr> 
	<td width=\"200\">&nbsp;</td>
	<td width=\"200\"> <input type=\"submit\" name=\"submit\" class=\"submit\" value=\"".$lng[602]."\">
	</td>
	</tr>

	<tr>
	<td colspan=\"2\" width=\"400\"><a href=\"login.php\">".$lng[603]."</a></td>
	</tr>
	
	</table>
	</form>
";

}// end if submit

include( "loginfooter.inc.php");
?>