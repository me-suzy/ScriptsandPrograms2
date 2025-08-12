<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include( "settings.inc.php");
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo"<h2>".$lng[849]."</h2>";

extract( $_GET );
extract( $_POST );

if( $dump )
{
	echo $lng[848];
	$sql_email = "select distinct `email` from ".$prefix."store_customer where email <> '' and perm <>'N'";
	$result = mysql_query ($sql_email);
	
	$nem = 0;
	
	while ($row = mysql_fetch_array($result))
	{
		$email = $row["email"];
		
		// send e-mail
		echo "$email;<br>";
		
		$nem++;
		
	}
	
	if ( !$nem )
		echo "<br>" . $lng[927];
	
}
else {
	
	
	// If there are users waiting to be activated show reminder
	$sql_count_unactive = "select * from ".$prefix."store_customer";
	$result_unactive = mysql_query ($sql_count_unactive);
	$customers = mysql_num_rows($result_unactive);

	// content for no users
	if( $customers == '0' )
		abcPageExit( "<br><br><p align=\"center\">".$lng[850]."</p>" );

	// If submit is clicked, send email
	if ($submit && !$_SESSION['demo'] )  {
	
		$sql_email = "select distinct email from ".$prefix."store_customer where email <> '' and perm <>'N'";
		$result = mysql_query ($sql_email);
		$num_email =  mysql_num_rows($result);

		while ($row = mysql_fetch_array($result))
		{
			$email = $row["email"];
			$sendto = "$email";
			$subject = "$subject_field";
			$message = "$message_field";
			
			// send e-mail
			mail($sendto, $subject, $message, "From: $site_email");
		}
		
		eval('$_msg="'.$lng[851].'";');
		echo "<p>".$_msg."</p>";
	}
	else if ( !$submit )
	{
?>
 
<form method="post" action="email.php">
<p><?=$lng[852];?></p>

<table border="0" cellpadding="4" cellspacing="0">
<tr> 
<td valign="top"><b><?=$lng[853];?>:</b></td>
<td valign="top"><input type="text" class="textbox" name="subject_field" size="40"></td>
</tr>

<tr> 
<td valign="top"><b><?=$lng[854];?>:</b></td>
<td valign="top"><textarea name="message_field" rows="8" cols="62" wrap="physical"></textarea></td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><input type="submit" class="submit" name="submit" value="<?=$lng[855];?>"></td>
</tr>
</table>
</form>

<?
	}
}

if ($submit && $_SESSION['demo'] )  {
	include('_guest_access.php');
	
}

include("footer.inc.php");

?>