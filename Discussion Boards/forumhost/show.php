<?
if (!$id || !$mid) exit;
include "./config.php";

$sql = "select lastaccess from $tableonline where ip='$REMOTE_ADDR' and owner='$id'";
$result = mysql_query($sql);
if (mysql_num_rows($result)==0){
	$sql = "insert into $tableonline values('$id', '$REMOTE_ADDR', now())";
	$result = mysql_query($sql);
}
	else
{
	$sql = "update $tableonline set lastaccess=now() where ip='$REMOTE_ADDR' and owner='$id'";
	$result = mysql_query($sql);
}

$purgeseconds = $onlineuserexpireminutes * 60;
$userexpired = strftime("%Y-%m-%d %H:%M:%S", time() - $purgeseconds);
$sql = "delete from $tableonline where lastaccess < '$userexpired'";
$result = mysql_query($sql);



$sql = "select forumtitle,headhtml,foothtml,headtext,bottomtext,mybordercolor,mybordersize,mycellspacing,mycellpadding,bannedipforum,allowimages,profanityfilter,enablesmilies from $table where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$resrow = mysql_fetch_row($result);
$forumtitle = $resrow[0];
$headhtml = $resrow[1];
$foothtml = $resrow[2];
$headtext = $resrow[3];
$bottomtext = $resrow[4];
$mybordercolor = $resrow[5];
$mybordersize = $resrow[6];
$mycellspacing = $resrow[7];
$mycellpadding = $resrow[8];
$bannedipforum = $resrow[9];
$allowimages = $resrow[10];
$profanityfilter = $resrow[11];
$enablesmilies = $resrow[12];
$forumtitle = stripslashes($forumtitle);
$headtext = stripslashes($headtext);
$bottomtext = stripslashes($bottomtext);
$headhtml = stripslashes($headhtml);
$foothtml = stripslashes($foothtml);


$sql = "select indent,author,email,website,msgrname,msgrtype,msgicon,subject,msg,filename,ip,dt from $tableposts where owner='$id' and id='$mid'";
$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
if (mysql_num_rows($result)==0){
	Header("Location: forum.php?id=$id");
	exit;
}
$resrow = mysql_fetch_row($result);
$indent = $resrow[0];
$author = $resrow[1];
$email = $resrow[2];
$website = $resrow[3];
$msgrname = $resrow[4];
$msgrtype = $resrow[5];
$msgicon = $resrow[6];
$subject = $resrow[7];
$msg = $resrow[8];
$filename = $resrow[9];
$ip = $resrow[10];
$dt = $resrow[11];

if ($enablesmilies=="1") {
	$smiliestxt = file("./smilies.txt");
	$msg = convert_smilies($msg,$smiliestxt);
}

if ($profanityfilter=="1") {
	$profanitytxt = file("./profanity.txt");
	$subject = convert_profanity($subject,$profanitytxt);
	$msg = convert_profanity($msg,$profanitytxt);
}
$msg = str_replace("\n", "<br>", $msg);

$adminrights = "";
$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
if (mysql_num_rows($result)!=0) $adminrights = "1";

if ($email) $buttons .= "<a href='email.php?id=$id&mid=$mid'><img src='images/email.gif' border='0'></a>&nbsp;";
if ($msgrname && $msgrtype=="aim") $buttons .= "<a href='aim:goim?screenname=$msgrname'><img src='images/aim.gif' border='0'></a>&nbsp;";
if ($msgrname && $msgrtype=="yahoo") $buttons .= "<a href='ymsgr:sendim?$msgrname'><img src='images/yahoo.gif' border='0'></a>&nbsp;";
if ($msgrname && $msgrtype=="icq") $buttons .= "<a href='http://wwp.icq.com/scripts/contact.dll?msgto=$msgrname'><img src='images/icq.gif' border='0'></a>&nbsp;";
if ($website) $buttons .= "<a href='$website' target='$website'><img src='images/website.gif' border='0'></a>&nbsp;";
if ($bgimgurl) $bgprop = " background='$bgimgurl'";
if ($adminrights=="1") $iptag = "IP: $ip<br>[<a href='forum.php?id=$id&banippost=$ip'>Ban From Posting</a>]<br>[<a href='forum.php?id=$id&banipforum=$ip'>Ban From Forum</a>]<br>[<a href='forum.php?id=$id&deleteid=$mid'>Delete This Post</a>]";

$headhtml = str_replace("[pagetitle]", "$forumtitle - $subject - Powered By $fhtitle", $headhtml);

print $headhtml;
print "<table width='100%' align='center'>
  <tr>
    <td>
      <div align='center'>
        <p><font size='+1' face='Verdana, Arial, Helvetica, sans-serif'>$forumtitle</font><br><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>$headtext</font></p>
        <p align='left'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>";



print "<table width='95%' border='$mybordersize' cellspacing='$mycellspacing' cellpadding='$mycellpadding' align='center' bordercolor='$mybordercolor'>
  <tr align='left' valign='top'> 
    <td colspan='2'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><b>$subject</b></font></td>
  </tr>
  <tr> 
    <td align='left' valign='top' width='27%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$author<br>
      $buttons<br>
      <br>
      $dt<br>$iptag<br>$deltag</font></td>
    <td align='left' valign='top' width='73%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><div align='center'><b>[<a href='post.php?id=$id'>Post 
      New Message</a>] [<a href='post.php?id=$id&mid=$mid'>Reply to Subject</a>]<br>[<a href='forum.php?id=$id'>Back to $forumtitle</a>]</b></div><br>
      <br>
      $msg<br>";
if ($filename && $adminallowimages && $allowimages) print "<br><div align='center'><img src='files/".$filename."'></div><br>";
print "<br><div align='center'><b>[<a href='post.php?id=$id'>Post 
      New Message</a>] [<a href='post.php?id=$id&mid=$mid'>Reply to Subject</a>]<br>[<a href='forum.php?id=$id'>Back to $forumtitle</a>]</b></div></font></td>
  </tr>
</table><br>";



print "<table width='95%' border='$mybordersize' cellspacing='$mycellspacing' cellpadding='$mycellpadding' align='center' bordercolor='$mybordercolor'>
  <tr align='left' valign='top'> 
    <td><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><b>Message 
      Replies</b></font></td>
  </tr>
  <tr> 
    <td align='left' valign='top'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>";
$v = getreplies($mid,$id);
print "</font></td>
  </tr>
</table>";

print "</font></p>
        <p><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>$bottomtext</font></p>
        <p><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'><b>Powered 
          By <a href='http://nukedweb.memebot.com/' target='_other'>ForumHost</a></b></font></p>
      </div>
    </td>
  </tr>
</table>";
print $foothtml;
?>