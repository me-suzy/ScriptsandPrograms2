<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/config.fnc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
    include("../includes/language/lang-english.php");
}
$file_p  = $DOCUMENT_ROOT . "/templates/email_partner.txt";
$file_ps = $DOCUMENT_ROOT . "/templates/email_partner_subject.txt";
$session = SessionID(5);
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<? printabout(2); ?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	<A HREF="admin/index.php"><? echo GTGP_SET_RETURN; ?></A>
	</TD>
</TR>
<form method="post" action="admin/preferred.php">
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_PARTNER; ?></B>
	</TD>
</TR>
<TR>
	<TD ALIGN="RIGHT" WIDTH="30%"><input type=text name="preferred_email"></TD>
	<TD ALIGN="LEFT"  WIDTH="70%"><? echo GTGP_ADMIN_PARTNER_EMAIL; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT" WIDTH="30%">&nbsp;</TD>
	<TD ALIGN="LEFT"  WIDTH="70%"><input type=submit name=preferred value="<? echo GTGP_ADMIN_PARTNER_ADD; ?>"></TD>
</TR>
</FORM>
<?
/* Delete Preferred Submitter */
if (isset($zap)) {
	$Query = "DELETE FROM tblPreferred WHERE id='$id'";
	$result = mysql_query($Query, $conn);
	$message3 = GTGP_ADMIN_PARTNER_DEL_B . "&nbsp;<B>$id</B>&nbsp;" . GTGP_ADMIN_PARTNER_DEL_A;
}

/* ADD Preferred submitter */

if (isset($preferred)) {

        /* update someone to the preferred list */
mysql_query("INSERT into tblPreferred (email, pass) VALUES ('$preferred_email', '$session')");
$message3 = GTGP_ADMIN_PARTNER_ADD_B . "&nbsp;<B>$preferred_email</B>&nbsp;" . GTGP_ADMIN_PARTNER_ADD_A;

/* Send confirmation email to submitter	*/

	$recipient = "$preferred_email";
    /*  Subject */
   $f_partner_s = fopen($file_ps, "r");
   $subject   = fgets($f_partner_s, 200);
   $subject  = ereg_replace("%sitename%",$sitename,$subject);
   $subject = chop($subject);
   fclose($f_partner_s);
	/* Message */
   $f_partner = fopen($file_p, "r");
   $p_dlug = filesize($file_p);
   $message = fread($f_partner,$p_dlug);
   fclose($f_partner);
   $message  = ereg_replace("%sitename%",$sitename,$message);
   $message  = ereg_replace("%email%",$email,$message);
   $message  = ereg_replace("%url%",$url,$message);
   $message  = ereg_replace("%category%",$category,$message);
   $message  = ereg_replace("%description%",$description,$message);
   $message  = ereg_replace("%sitename%",$sitename,$message);
   $message  = ereg_replace("%tgpemail%",$tgpemail,$message);
   $message  = ereg_replace("%siteowner%",$siteowner,$message);
   $message  = ereg_replace("%session%",$session,$message);
	if($hmail = 'Yes')
	{
		$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\nContent-type:text/html; charset=iso-8859-2\r\n";
	}
	else
	{
		$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\n";
	}
	mail ($recipient, $subject, $message, $extra);
}

/* View all of Preferred */

	$query = "SELECT * FROM tblPreferred order by id DESC";
	$result = mysql_query ($query)
        or die ("Query failed");

	if ($result) {
	while ($r = mysql_fetch_array($result)) { 

	$id = $r["id"];
	$email1 = $r["email"];

	echo "
	<tr>
	<TD ALIGN=\"RIGHT\" WIDTH=\"30%\">$email1</TD>
	<TD ALIGN=\"LEFT\"  WIDTH=\"70%\"><a href=\"admin/preferred.php?view=all&zap=yes&id=$id\">" . GTGP_ADMIN_PARTNER_DEL . "</a></td>
	</tr>";
		} /* end of while loop */
	}	/* end of result */

?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	<font color=red><? echo $message3; ?></font>
	</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>