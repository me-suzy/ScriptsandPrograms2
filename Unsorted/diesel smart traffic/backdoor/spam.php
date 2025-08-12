<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Email Members</title>
  <link rel="stylesheet" href="../style.css">
 </head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>
<?
  require("../conf/sys.conf");
  require("bots/errbot");
  require("bots/mcbot");
  require("bots/genbot");
  require("bots/grbot");

  $db=con_srv();
?>
<H3>Mailing clients</H3>
<FONT SIZE="-1">
<?
  if($action == "do")
  {
		if($subj == "" && $message == "")
		{
			echo "Invalid parameters entered!";
		}
		else
		{
			$ms .= "\n-------------------------------------\n".
					  "If you wish to unsubscribe, go by this link: ".$ROOT_HOST."index.php?action=unsubscribe&user=";

			if($mail_to != all)
			{
				@mail($mail_to,$subj,$message.$ms.$mail_to."\n","From: $ADMIN_MAIL");
				echo "<b>Mailing done!</b><br><br>";
			}
			else
			{
					$members = _query("select email, login from members order by login");

					while($member = _fetch($members))
					{
						if(_empty(_query("select id from unsubscribers where member_id='$member[id]'")))
						{
							@mail($member[email],$subj,$message.$ms.$member[email]."\n","From: $ADMIN_MAIL");
						}
					}

					echo "<b>Mailing done!</b><br><br>";
			}
		}
  }
?>

<TABLE>
	<FORM ACTION="spam.php?action=do" method=post>
   <TR>
	   <TD><FONT SIZE="-1">Mail to:</FONT></TD>
		<TD>
			 <SELECT NAME="mail_to">
				<option value=all>All
				<?
					$members = _query("select id, email, login from members order by login");

					while($member = _fetch($members))
					{
						if(_empty(_query("select id from unsubscribers where member_id='$member[id]'")))
						{
							echo "<option value='$member[email]'>$member[login]\n";
						}
					}
				?>
			 </SELECT>
		</TD>
   </TR>
	<TR>
		<TD><FONT SIZE="-1">Subject:</FONT></TD>
		<TD><INPUT TYPE="text" NAME="subj" SIZE="30"></TD>
	</TR>
	<TR>
		<TD valign=top><FONT SIZE="-1">Letter's body:</FONT></TD>
		<TD><textarea name=message cols=50 rows=10></textarea></TD>
	</TR>
	<TR>
		<TD></TD>
		<TD><INPUT TYPE="submit" VALUE="Send letters"></TD>
	</TR>
	</FORM>
</TABLE>
<hr noshade align=left size=1>
<a href=index.php><font size=-1>[ < logout ]</font></a> <a href=back.php><font size=-1>[ << main menu ]</font></a>
<? dc_srv($db); ?>
