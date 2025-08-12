<?
include "./config.php";

if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}
if ($deleteid){
	$sql = "delete from $msgstable where id='$deleteid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "select entries from $table where id='$owner'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$entries = $resrow[0];
	$entries--;
	$sql = "update $table set entries='$entries' where id='$owner'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: adminmsgs.php?pass=$pass&id=$owner");
	exit;
}


$sql = "select * from $msgstable where owner='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);

if ($numrows==0){
	print "There are no entries in this user's guestbook.";
	exit;
}

for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$msgid = $resrow[0];
	$poster = $resrow[2];
	$email = $resrow[3];
	$message = $resrow[4];
	$ip = $resrow[5];
	$dt = $resrow[6];
	$dt = "<i>[$dt]</i>";
	$ip = "<i>[$ip]</i>";
	if (!$poster) $poster = "Guest";
	if ($email) $wholink = "<a href='mailto:$email'>$poster</a>";
	if (!$email) $wholink = $poster;
	$wholink = "[<a href='adminmsgs.php?deleteid=$msgid&owner=$id&pass=$pass'>X</a>] ".$wholink;
	print "<font face='$tablefontname' size='$tablefontsize'>$wholink - $message $dt $ip</font<center><hr></center>";
}
?>