<?
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

if ($showsmilies=="1"){
	print "<html><head><title>Smilies List</title><STYLE>.nounder{text-decoration:none;}</STYLE><SCRIPT lang=\"Javascript\" type=\"text/javascript\">function add_smilie(a_smilie){if(window.opener.document.form1!=window.undef){window.opener.document.form1.msg.value+=\" \"+a_smilie+\" \";window.opener.document.form1.msg.focus();window.close();};};</SCRIPT><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'></head><body bgcolor='#FFFFFF' text='#000000' link='#000000' alink='#000000' vlink='#000000'><table width='100%' border='1' cellspacing='0' cellpadding='0' align='center'><tr bgcolor='#CCCCCC'><td width='50%'><b><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><center>Smilie Text</center></font></b></td><td width='50%'><b><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><center>Image</center></font></b></td></tr>";
	$smiliestxt = file("./smilies.txt");
	$cnt = count($smiliestxt);
	$rowcolor = "#DDDDDD";
	for($x=0;$x<$cnt;$x++){
		$lyn = $smiliestxt[$x];
		$lsta = explode("|", $lyn);
		$from = $lsta[0];
		$to = $lsta[1];
		$to = str_replace("\r", "", $to);
		$to = str_replace("\n", "", $to);
		$to = "<a href=\"javascript:add_smilie('".$from."');\"><img src='$to' border='0'></a>";
		if ($rowcolor=="#EEEEEE"){
			$rowcolor = "#DDDDDD";
		}
			else
		{
			$rowcolor = "#EEEEEE";
		}
		print "<tr bgcolor='#EEEEEE'><td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><center><a class=\"nounder\" href=\"javascript:add_smilie('".$from."');\">$from</a></center></font></td><td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><center>$to</center></font></td></tr>";
	}
	print "</table></body></html>";
	exit;
}

if ($banippost && $ckForumAdminEmail && $ckForumAdminPassword){
	$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	if (mysql_num_rows($result)!=0){
		$sql = "select bannedippost from $table where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$bannedippost = $resrow[0];
		$bannedippost .= $banippost."\n";
		$sql = "update $table set bannedippost='$bannedippost' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	}
	Header("Location: forum.php?id=$id");
	exit;
}

if ($banipforum && $ckForumAdminEmail && $ckForumAdminPassword){
	$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	if (mysql_num_rows($result)!=0){
		$sql = "select bannedipforum from $table where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$bannedipforum = $resrow[0];
		$bannedipforum .= $banipforum."\n";
		$sql = "update $table set bannedipforum='$bannedipforum' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	}
	Header("Location: forum.php?id=$id");
	exit;
}

if ($deleteid && $ckForumAdminEmail && $ckForumAdminPassword){
	$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	if (mysql_num_rows($result)!=0){
		$sql = "select filename from $tableposts where id='$deleteid' and owner='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$filename = $resrow[0];
		$sql = "delete from $tableposts where id='$deleteid' and owner='$id'";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
		if ($filename) $v = @unlink("./files/".$filename);
	}
	Header("Location: forum.php?id=$id");
	exit;
}

$sql = "select forumtitle,headhtml,foothtml,headtext,bottomtext,mybordercolor,mybordersize,mycellspacing,mycellpadding,bannedipforum,allowimages from $table where id='$id'";
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
$forumtitle = stripslashes($forumtitle);
$headtext = stripslashes($headtext);
$bottomtext = stripslashes($bottomtext);
$headhtml = stripslashes($headhtml);
$foothtml = stripslashes($foothtml);

$bannedipforum = str_replace("\r", "", $bannedipforum);
$iparray = explode("\n", $bannedipforum);
for($v=0;$v<count($iparray);$v++){
	$ip = $iparray[$v];
	if (@stristr($REMOTE_ADDR, $ip)) exit;
}


$sql = "select count(*) from $tableonline where owner='$id'";
$result = mysql_query($sql);
$resrow = mysql_fetch_row($result);
$onlinecnt = $resrow[0];


$headhtml = str_replace("[pagetitle]", "$forumtitle - Powered By $fhtitle", $headhtml);

print $headhtml;

print "<table width='95%' align='center' border='$mybordersize' cellspacing='$mycellspacing' cellpadding='$mycellpadding' bordercolor='$mybordercolor'>
  <tr>
    <td>
      <div align='center'>
        <p><font size='+1' face='Verdana, Arial, Helvetica, sans-serif'>$forumtitle</font><br><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>$headtext</font></p>
        <p align='left'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'><b>[<a href='post.php?id=$id'>Post New Thread</a>]</b><br>";

$adminrights = "";
$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
if (mysql_num_rows($result)!=0) $adminrights = "1";

$sql = "select id,author,msgicon,subject,msg,filename from fh_msgs where pid='0' and owner='$id' order by dt desc";
$result = mysql_query($sql);
$numrows = mysql_num_rows($result);
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$msgicohtml = "";
	$mid = $resrow[0];
	$author = $resrow[1];
	$msgicon = $resrow[2];
	$subject = $resrow[3];
	$msg = $resrow[4];
	$filename = $resrow[5];
	if ($msgicon) $msgicohtml = "<img src='$msgicon'>";
	if (!$msgicon) $msgicohtml = "<img src='images/blankico.gif'>";
	if ($adminrights=="1") $delhtml = "[<a href='forum.php?id=$id&deleteid=$mid'>X</a>] ";
	$pichtml = "";
	if ($filename && $allowimages && $adminallowimages) $pichtml = "&nbsp;<img src='images/attachment.gif'>";
	print "<br>$msgicohtml&nbsp;$delhtml<a href='show.php?id=$id&mid=$mid'>$subject</a>$pichtml By $author<br>";
	flush();
	$z = getreplies($mid,$id);
}

print "<br><br>There are $onlinecnt users browsing this forum.</font></p>
        <p><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>$bottomtext</font></p>
        <p><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'><b>Powered 
          By <a href='http://nukedweb.memebot.com/' target='_other'>ForumHost</a></b></font></p>
      </div>
    </td>
  </tr>
</table>";
print $foothtml;
?>