<?
if (!$id) exit;
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



if ($postmsg && $id){
	if (!$msg) $status .= "Error: No Message Entered.<br>";
	if (!$subject) $status = "Error: No Subject Entered.<br>";
	if (!$author) $author = "Anonymous";
	if ($adminallowimages && $file && $file_name){
		if ($_FILES['file']['size']>$maxsize) $status = "Error: Picture size too large. Max file size is $maxsize bytes.<br>";
		if (($_FILES['file']['type']!="image/gif") && ($_FILES['file']['type']!="image/jpeg") && ($_FILES['file']['type']!="image/jpg") && ($_FILES['file']['type']!="image/pjpeg")) $status .= "Error: Wrong file type. Must be JPG or GIF only.<br>";
		$picext = substr($file_name,-3);
		$picext = strtolower($picext);
		if ((!$status) && ($picext!="gif") && ($picext!="jpg") && ($picext!="peg")) $status .= "Error: Wrong file type. Must be JPG or GIF only.<br>";
	}
	if (!$status){
		$sql = "select indent from $tableposts where owner='$id' and id='$mid'";
		$result = mysql_query($sql);
		$resrow = mysql_fetch_row($result);
		$indent = $resrow[0];
		$indent++;
		$subject = strip_tags($subject);
		$author = strip_tags($author);
		$msg = strip_tags($msg, $allowedtags);
		$sql = "insert into $tableposts values('', '$id', '$mid', '$indent', '$author', '$email', '$website', '$msgrname', '$msgrtype', '$msgicon', '$subject', '$msg', '', '$REMOTE_ADDR', now())";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
		if ($adminallowimages && $file && $file_name){
			$sql = "select max(id) from $tableposts";
			$result = mysql_query($sql);
			$resrow = mysql_fetch_row($result);
			$picname = $resrow[0];
			$picext = substr($file_name,-3);
			$picext = strtolower($picext);
			@copy($file, "./files/".$picname.".".$picext);
			$sql = "update $tableposts set filename = '$picname.".$picext."' where id='$picname' and owner='$id'";
			$result = mysql_query($sql);
		}
		setcookie ("ckForumAuthor", "$author", "315360000");
		setcookie ("ckForumWebsite", "$website", "315360000");
		setcookie ("ckForumEmail", "$email", "315360000");
		setcookie ("ckForumMsgr", "$msgrname", "315360000");
		setcookie ("ckForumMType", "$msgrtype", "315360000");
		Header("Location: forum.php?id=$id");
		exit;
	}
}

$author = $ckForumAuthor;
$website = $ckForumWebsite;
$email = $ckForumEmail;
$msgrname = $ckForumMsgr;
$msgrtype = $ckForumMType;
if ($msgrtype=="aim") $aselected = " selected";
if ($msgrtype=="yahoo") $yselected = " selected";
if ($msgrtype=="icq") $iselected = " selected";


$sql = "select forumtitle,headhtml,foothtml,headtext,bottomtext,mybordercolor,mybordersize,mycellspacing,mycellpadding,bannedippost,allowimages,enablesmilies from $table where id='$id'";
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
$bannedippost = $resrow[9];
$allowimages = $resrow[10];
$enablesmilies = $resrow[11];
$forumtitle = stripslashes($forumtitle);
$headtext = stripslashes($headtext);
$bottomtext = stripslashes($bottomtext);
$headhtml = stripslashes($headhtml);
$foothtml = stripslashes($foothtml);


$bannedippost = str_replace("\r", "", $bannedippost);
$iparray = explode("\n", $bannedippost);
for($v=0;$v<count($iparray);$v++){
	$ip = $iparray[$v];
	if (@stristr($REMOTE_ADDR, $ip)) exit;
}

$smiliestxt = file("./smilies.txt");
$cnt = count($smiliestxt);
for($x=0;$x<$cnt;$x++){
	$lyn = $smiliestxt[$x];
	if (substr($lyn,0,1)!="#"){
		$lsta = explode("|", $lyn);
		$from = $lsta[0];
		$to = $lsta[1];
		$to = str_replace("\r", "", $to);
		$to = str_replace("\n", "", $to);
		$msgiconhtml .= "<input type='radio' name='msgicon' value='$to'><img src='$to'> ";
	}
}
$msgiconhtml .= "<input type='radio' name='msgicon' value=''>None";


if ($mid) {
	$sql = "select subject from $tableposts where owner='$id' and id='$mid'";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	if (mysql_num_rows($result)==0){
		Header("Location: forum.php?id=$id");
		exit;
	}
	$resrow = mysql_fetch_row($result);
	$subject = $resrow[0];
	$boxtitle = "Reply to $subject";
}

if (substr($subject, 0, 3)!="Re:" && $mid) $subject = "Re: ".$subject;
if (!$mid) $boxtitle = "Post New Message";
if ($bgimgurl) $bgprop = " background='$bgimgurl'";
if ($enablesmilies=="1") $smilieslink = "<br><a href=\"#\" onClick=\"window.open('".$thurl."forum.php?showsmilies=1','pop_smilies','height=370,width=230,top=50,left=50,resizable=no,scrollbars=yes')\" style=\"color:$othertxtcolor;\">[smilies]</a>&nbsp;&nbsp;&nbsp;";

$headhtml = str_replace("[pagetitle]", "$forumtitle - $boxtitle - Powered By $fhtitle", $headhtml);

print $headhtml;

print "<div align='center'><font color='red' size='-1'><b>$status</b></font></div>
<table width='100%' align='center'>
  <tr>
    <td>
      <div align='center'>
        <p><font size='+1' face='Verdana, Arial, Helvetica, sans-serif'>$forumtitle</font><br><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>$headtext</font></p>
        <p align='left'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='$textcolor'>";



print "<form name='form1' method='post' action='post.php' enctype='multipart/form-data'>
  <table width='514' border='$mybordersize' cellspacing='$mycellspacing' cellpadding='$mycellpadding' align='center' bordercolor='$mybordercolor'>
    <tr align='left' valign='top'> 
      <td colspan='2'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><b>$boxtitle</b></font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Name:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='author' size='35' value='$author'>
        </font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Email:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='email' size='35' value='$email'>
        </font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Messenger:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='msgrname' size='35' value='$msgrname'>
        <select name='msgrtype'>
          <option value='aim'$aselected>AIM</option>
          <option value='yahoo'$yselected>Yahoo</option>
          <option value='icq'$iselected>ICQ</option>
        </select>
        </font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Website:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='website' size='35' value='$website'>
        </font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Subject:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='subject' size='35' maxlength='100' value='$subject'>
        </font></td>
    </tr>";

if (!$mid || $mid==0) print "    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Message 
        Icon:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$msgiconhtml 
        </font> </td>
    </tr>";

if ($adminallowimages && $allowimages) print "<tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Image 
        Attachment:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='hidden' name='MAX_FILE_SIZE' value='$maxfilesize'><input type='file' name='file'>
        </font></td>
    </tr>";

print "<tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Message:<br>&nbsp;&nbsp;&nbsp;$smilieslink</font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <textarea name='msg' cols='35' rows='6'>".stripslashes($msg)."</textarea>
        </font></td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Options:&nbsp;&nbsp;&nbsp;</font></td>
      <td align='left' valign='top' width='65%'>&nbsp;</td>
    </tr>
    <tr> 
      <td align='right' valign='top' width='35%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='hidden' name='id' value='$id'>
        <input type='hidden' name='mid' value='$mid'>
        <input type='reset' value='Cancel' onClick='history.go(-1)'>
        </font></td>
      <td align='left' valign='top' width='65%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='hidden' name='postmsg' value='1'><input type='submit' value='Post Message'>
        </font></td>
    </tr>
  </table>
</form>";

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