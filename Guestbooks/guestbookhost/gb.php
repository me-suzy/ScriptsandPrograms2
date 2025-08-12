<?
include "./config.php";
if ($purgedays>0){
	$purgeseconds = $purgedays * 86400;
	$olddate = strftime("%Y-%m-%d %H:%M:%S", time() - $purgeseconds);
	$sql = "delete from $table where created < '$olddate' and entries='0'";
	$result = mysql_query($sql) or die("Failed: $sql");
}

if ($owner && $message && $post=="1"){
	$today = strftime("%Y-%m-%d", time());
	$sql = "select id from $msgstable where dt rlike '$today' and owner='$owner' and ip='$REMOTE_ADDR'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows!=0){
		print "Error: You may only post once a day to this guestbook.";
		exit;
	}
	$poster = strip_tags($poster);
	$email = strip_tags($email);
	$message = strip_tags($message);
	$sql = "insert into $msgstable values('', '$owner', '$poster', '$email', '$message', '$REMOTE_ADDR', now())";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "select entries from $table where id='$owner'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$entries = $resrow[0];
	$entries++;
	$sql = "update $table set entries='$entries' where id='$owner'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: ".$guestbook_filename."?id=$owner");
	exit;
}

if (!$id && !$owner){
	print "Error: No ID was given.";
	exit;
}

$sql = "select sitetitle,siteurl,headtext,bgcolor,textcolor,linkcolor,uniquehits,pageviews from $table where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
if ($numrows==0){
	print "Error: Invalid ID";
	exit;
}
$resrow = mysql_fetch_row($result);
$sitetitle = $resrow[0];
$siteurl = $resrow[1];
$headtext = $resrow[2];
$bgcolor = $resrow[3];
$textcolor = $resrow[4];
$linkcolor = $resrow[5];
$uniquehits = $resrow[6];
$pageviews = $resrow[7];
if (!$preview && !$edit){
	$pageviews++;
	$sql = "update $table set pageviews='$pageviews' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$viewed = $HTTP_COOKIE_VARS["viewed".$id];
	if (!$viewed){
		$uniquehits++;
		$sql = "update $table set uniquehits='$uniquehits' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		setcookie("viewed".$id, "1", time()+313560000);
	}
}

print "<html><head><title>$sitetitle Guestbook</title></head><body bgcolor='$bgcolor' text='$textcolor' link='$linkcolor' alink='$linkcolor' vlink='$linkcolor'>";
if ($id && $sign=="1"){
	print "<form name='form1' method='post' action='$guestbook_filename'><font face='$tablefontname'>Sign Guestbook</font><font size='$tablefontsize' face='$tablefontname'><br><br>Name:<br><input type='text' name='poster' size='40' maxlength='255'><br>Email:<br><input type='text' name='email' maxlength='255' size='40'><br>Message:<br><textarea name='message' cols='40' rows='4'></textarea><br><input type='submit' value='Post'><input type='hidden' name='owner' value='$id'><input type='hidden' name='post' value='1'></font></form>";
	print "</body></html>";
	exit;
}

if (!$edit){
	$sql = "select html from $adstable order by rand() limit 0,1";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$bannerhtml = $resrow[0];
	if ($bannerhtml) print "<center>$bannerhtml</center><br>";
	print "<font face='$tablefontname' size='$tablefontsize'>$headtext</font>";
	print "<center><font face='$tablefontname' size='$tablefontsize'><a href='".$guestbook_filename."?id=$id&sign=1'>Sign Guestbook</a><br><br></font></center>";
}

if ($edit && $adminemail && $adminpassword){
	$sql = "select id from $table where id='$id' and email='$adminemail' and password='$adminpassword'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows>0) $editvalidated = "1";
}

if ($id && $deleteid && $adminemail && $adminpassword){
	$sql = "select id from $table where id='$id' and email='$adminemail' and password='$adminpassword'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows>0) {
		$sql = "delete from $msgstable where owner='$id' and id='$deleteid'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$sql = "select entries from $table where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$entries = $resrow[0];
		$entries--;
		$sql = "update $table set entries='$entries' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$editvalidated = "1";
	}
}

$sql = "select id,poster,email,message,ip,dt from $msgstable where owner='$id' order by dt desc";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$msgid = $resrow[0];
	$poster = $resrow[1];
	$email = $resrow[2];
	$message = $resrow[3];
	$poster = strip_tags($poster);
	$email = strip_tags($email);
	$message = strip_tags($message);
	$ip = $resrow[4];
	$dt = $resrow[5];
	$dt = "<i>[$dt]</i>";
	if (!$poster) $poster = "Guest";
	if ($email) $wholink = "<a href='mailto:$email'>$poster</a>";
	if (!$email) $wholink = $poster;
	if ($editvalidated=="1") $wholink = "[<a href='".$guestbook_filename."?id=$id&deleteid=$msgid&adminemail=$adminemail&adminpassword=$adminpassword'>X</a>] ".$wholink;
	print "<font face='$tablefontname' size='$tablefontsize'>$wholink - $message $dt</font<center><hr></center>";
}
print "<center><font face='$tablefontname' size='$tablefontsize'>[<a href='javascript:window.close();'>Close Guestbook</a>]<br>Powered By <a href='http://nukedweb.memebot.com/' target='_nuked'>GuestBookHost</a></font></center></body></html>";
?>