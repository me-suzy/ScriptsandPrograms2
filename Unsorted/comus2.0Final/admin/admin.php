<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
	include("../includes/language/lang-english.php");
}
$file_a  = $DOCUMENT_ROOT . "/templates/email_post_accepted.txt";
$file_as = $DOCUMENT_ROOT . "/templates/email_post_accepted_subject.txt";
$file_r  = $DOCUMENT_ROOT . "/templates/email_post_rejected.txt";
$file_rs = $DOCUMENT_ROOT . "/templates/email_post_rejected_subject.txt";
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
	<? echo "<BR><font color=red>$message</center>"; ?>	
	</TD>
</TR>
<form method="post" action="admin/admin.php">
<?

/* Update database with changes you might have made */
if (isset($refresh)) {

	if ($accept) {
		foreach ($accept as $key => $value)
			{
			$Query = "UPDATE tblTgp SET accept='$value', category = '$category[$key]', description = '$newdescription[$key]', newpost='no', vote = '$vote[$key]', mailme='no', numpic='$numpic[$key]' WHERE id = $key";       
			$result = mysql_query($Query, $conn);
			if($value == "yes")
			{
			if($mailme[$key] == "yes")
			{
				/* Subject */
				$f_accepteds = fopen($file_as, "r");
				$subject   = fgets($f_accepteds, 200);
				$subject  = ereg_replace("%sitename%",$sitename,$subject);
				$subject = chop($subject);
				fclose($f_accepteds);			

				/* Message */
				$f_accepted = fopen($file_a, "r");
				$a_dlug = filesize($file_a);
				$message = fread($f_accepted,$a_dlug);
				fclose($f_accepted);
				$message  = ereg_replace("%sitename%",$sitename,$message);
				$message  = ereg_replace("%nick%",$hnick[$key],$message);
				$message  = ereg_replace("%email%",$hemail[$key],$message);
				$message  = ereg_replace("%url%",$hurl[$key],$message);
				$message  = ereg_replace("%category%",$hcat[$key],$message);
				$message  = ereg_replace("%description%",$hdesc[$key],$message);
				$message  = ereg_replace("%sitename%",$sitename,$message);
				$message  = ereg_replace("%tgpemail%",$tgpemail,$message);
				$message  = ereg_replace("%siteowner%",$siteowner,$message);
				if($hmail = 'Yes')
				{
					$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\nContent-type:text/html; charset=iso-8859-2\r\n";
				}
				else
				{
					$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\n";
				}
				mail ($hemail[$key], $subject, $message, $extra);
			}
			}

			if($value == "no")
			{
			if($mailme[$key] == "yes")
			{
				/* Subject */
				$f_rejecteds = fopen($file_rs, "r");
				$subject   = fgets($f_rejecteds, 200);
				$subject  = ereg_replace("%sitename%",$sitename,$subject);
				$subject = chop($subject);
				fclose($f_rejecteds);			

				/* Message */
				$f_rejected = fopen($file_r, "r");
				$r_dlug = filesize($file_r);
				$message = fread($f_rejected,$r_dlug);
				fclose($f_rejected);
				$message  = ereg_replace("%sitename%",$sitename,$message);
				$message  = ereg_replace("%nick%",$hnick[$key],$message);
				$message  = ereg_replace("%email%",$hemail[$key],$message);
				$message  = ereg_replace("%url%",$hurl[$key],$message);
				$message  = ereg_replace("%category%",$hcat[$key],$message);
				$message  = ereg_replace("%description%",$hdesc[$key],$message);
				$message  = ereg_replace("%sitename%",$sitename,$message);
				$message  = ereg_replace("%tgpemail%",$tgpemail,$message);
				$message  = ereg_replace("%siteowner%",$siteowner,$message);
				if($hmail = 'Yes')
				{
					$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\nContent-type:text/html; charset=iso-8859-2\r\n";
				}
				else
				{
					$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\n";
				}
				mail ($hemail[$key], $subject, $message, $extra);
			}
			}
         }
      }
   }

if (isset($yes_all)) {

$Query = "UPDATE tblTgp SET accept='yes', newpost='no' WHERE  newpost='Yes' and recip='Yes'";       
			$result = mysql_query($Query, $conn);


      }

/* GET AND DISPLAY NEW POSTS */
   $query = "SELECT * FROM tblTgp WHERE newpost='yes' order by category";
   $result = mysql_query ($query) or die ("Query failed");

if ($result) {
?>
</TABLE>
<BR clear="all">
<TABLE ALIGN="LEFT" VALIGN="TOP" WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
<?
while ($r = mysql_fetch_array($result)) {
   $id		= $r["id"];
   $nickname	= $r["nickname"]; 
   $recip	= $r["recip"];
   $url		= $r["url"]; 
   $email	= $r["email"];
   $category	= $r["category"];
   $description = $r["description"];
   $numpic	= $r["numpic"];
   $mailme	= $r["mailme"];
   $vote	= $r["vote"];
   $date	= $r["date"];
echo "
<TABLE ALIGN=CENTER VALIGN=TOP WIDTH=100% CELLPADDING=2 BORDER=1 BORDERCOLOR=#000000 BORDERCOLORLIGHT=#000000 BORDERCOLORDARK=#000000>
<TR>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_URL ."</B>&nbsp;&nbsp;".GTGP_ADMIN_POST_DATE."&nbsp;-&nbsp;".$date."&nbsp;&nbsp;&nbsp;&nbsp;".GTGP_ADMIN_POST_SUBMITER. "&nbsp;-&nbsp;<A class=\"smalllink\" HREF=\"mailto:". $email ."\">". $nickname ."</A></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_PIC ."</B></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_ACC ."</B></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_CAT ."</B></FONT></TD>
</TR>
<tr>
	<input type=hidden name=\"hnick[$id]\" value=\"$nickname\">
	<input type=hidden name=\"hurl[$id]\" value=\"$url\">
	<input type=hidden name=\"hemail[$id]\" value=\"$email\">
	<input type=hidden name=\"hcat[$id]\" value=\"$category\">
	<input type=hidden name=\"hdesc[$id]\" value=\"$description\">
    <input type=hidden name=\"tempid[$id]\" value=\"$id\">
 	<input type=hidden name=\"mailme[$id]\" value=\"$mailme\">
 	<td align=left valign=top class=tabelek>
    <a href=\"admin/ck.gallery.php?send=$url\" target=\"check\">$url</a>
    </td>
	<td class=tabelek>
	<INPUT TYPE=\"TEXT\" NAME=\"numpic[$id]\" value=\"$numpic\" SIZE=\"3\">
	</td>
    <td ALIGN=LEFT VALIGN=TOP class=tabelek> 
      Y:<input class=inputek type=radio name=accept[$id] value=\"yes\">&nbsp;&nbsp;N:<input class=inputek type=radio name=accept[$id] value=\"no\">
    </td>
    <td align=left valign=top class=tabelek>
       <select name=category[$id]>
                     <option selected>$category";
   $query2 = "SELECT * FROM tblCategories ORDER BY Category";
   $result2 = mysql_query ($query2)
        or die ("Query failed");

   if ($result) {

   while ($r = mysql_fetch_array($result2)) { 

   $Category = $r["Category"];
                              
      echo"<option>$Category";

      }
   } 
echo"</select>
</td>
</tr>
<tr>
		 <td class=tabelek width=\"70%\"> 
              <input type=text size=65 name=\"newdescription[$id]\" value=\"$description\">
         </td>
		 <td width=\"10%\" class=tabelek>
		 &nbsp;
		 </td>
         <td width=\"10%\" class=tabelek> 
             " . tslb($recip) ."
         </td>
         <td width=\"10%\" class=tabelek> 
         <select name=\"vote[$id]\">
         <option value=\"1\">1</option>
         <option value=\"2\">2</option>
         <option value=\"3\">3</option>
         <option value=\"4\">4</option>
         <option value=\"5\" selected>5</option>
         <option value=\"6\">6</option>
         <option value=\"7\">7</option>
         <option value=\"8\">8</option>
         <option value=\"9\">9</option>
         <option value=\"10\">10</option>
         </select>
            </td>
          </tr>
<TR>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_DES ."</B></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>&nbsp;</B></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_REC ."</B></FONT></TD>
<TD class=tabelek><FONT SIZE=1><B>". GTGP_ADMIN_POST_VOT ."</B></FONT></TD>
</TR>

		  </table><BR clear=ALL>";
} 
?>

	</TD>
</TR>
<TR>
	<TD ALIGN="CENTER" COLSPAN="1">
	<input type="submit" name="refresh" value="<? echo GTGP_ADMIN_POST_SUBMIT; ?>">
	</TD>
		<TD ALIGN="CENTER" COLSPAN="1">
	<input type="submit" name="yes_all" value="Yes All">
	</TD>
</TR>
<?
}
else
{ 
echo "No data."; 
}
?>
</form>
</TABLE>
<BR CLEAR="ALL"><BR>
</TD>
</TR>
</TABLE>
</body>
</html>