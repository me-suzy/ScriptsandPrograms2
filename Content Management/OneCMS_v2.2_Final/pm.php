<?php 
$la = "a";
$z = "b";
include ("config.php");

headera();

$result2 = mysql_query("SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."'") or die(mysql_error());
$num2 = mysql_num_rows($result2);

	$per = $num2/$pm;

	echo "<script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script><center><a href=\"pm.php?box=sent\">Sentbox</a> | <a href=\"pm.php?new=msg\">Compose a Message</a></center><br><br>";

if (($_GET['box'] == "in") && ($_GET['del'] == "") && ($_GET['msg'] == "") && ($_GET['confirm'] == "")) {

	echo "<center><b>$num2</b> Messages/<b>$pm</b> Allowed | $per%</center><br><form action='pm.php?del=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>From</b></td><td><b>Date</b></td><td><b>Delete?</b></td></tr>";

$query="SELECT * FROM onecms_pm WHERE jo = '$_COOKIE[username]' ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $id = "$row[id]";
		 $viewed = "$row[viewed]";
		 $subject = "$row[subject]";
		 $date = "$row[date]";
		 $to = "$row[jo]";
		 $userguy = "$row[who]";

		 echo "<tr><td><a href=\"pm.php?msg=$id\">";
		 if ($viewed == "0") {
			 echo "$subject";
		 } else {
			 echo "<b><i>$subject</b></i>";
		 }
		 echo "</a></td><td>$userguy</td><td>".date($dformat, $date)."</td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
	}
echo "<tr><td><input type='submit' value='Delete'></td><td><input type='button' onclick='check(true);' value='Check All'></td><td><input type='button' onclick='check(false);' value='Uncheck All'></td></tr></table>";
}
if (($_GET['box'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "")) {

	$query="SELECT * FROM onecms_pm WHERE id = '" . $_GET['msg'] . "'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$from = "$row[who]";
		$message2 = "$row[message]";
		$date = "$row[date]";
		$subject = "$row[subject]";
		$jo = "$row[jo]";

		$message = stripslashes($message2);
	}

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > $subject</title>";

		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject:</b></td><td>$subject</td></tr>
		<tr><td><b>";
	$query="SELECT * FROM onecms_pm WHERE id = '$id'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$jo = "$row[jo]";
	}
	if ($jo == "$_COOKIE[username]") {
		$resultID = mysql_query("UPDATE onecms_pm SET viewed = '0' WHERE id = '" . $_GET['msg'] . "'") or die(mysql_error());
		echo "From:</b></td><td>$from";
	} else {
		echo "To:</b></td><td>$jo";
	}

		echo " at ".date($dformat, $date)."</td></tr><tr><td><b>Message:</b></td><td>$message</td></tr><tr>";

	if ($jo == "$_COOKIE[username]") {
	  echo "<td><a href=\"pm.php?del=" . $_GET['msg'] . "\">Delete Message?</a></td>
			<td><a href=\"pm.php?new=reply&id=" . $_GET['msg'] . "\">Reply to this Message</a></td><td><a href=\"pm.php?box=in\">Return to Inbox</a></td><td><a href=\"pm.php?box=sent\">Go to Sentbox</a></td></tr></table>";
	} else {

	  echo "<td><a href=\"pm.php?box=sent\">Return to Sentbox</a></td>
		    <td><a href=\"pm.php?box=in\">Go to Inbox</a></td></tr></table>";
	}
}
if ((((($_GET['del'] == "yes") && ($_GET['msg'] == "") && ($_GET['box'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == ""))))) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Delete Message (s)</title>";

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_pm WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "Message(s) have been deleted. <a href='pm.php?box=in'>Return to Inbox</a>";
}
}

if (((($_GET['msg'] == "") && ($_GET['box'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "")))) {
if ($_GET['del'] == "yes") {
} else {
		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Delete Message</title>";

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

$delete = mysql_query("DELETE FROM onecms_pm WHERE id = '".$_GET['del']."'") or die(mysql_error());

if ($delete == TRUE) {
	echo "Message has been deleted. <a href='pm.php?box=in'>Return to Inbox</a>";
}
}
}
if (($_GET['box'] == "sent") && ($_GET['del'] == "") && ($_GET['msg'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "")) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Sentbox</title>";

	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>To</b></td><td><b>Date</b></td></tr>";

$query="SELECT * FROM onecms_pm WHERE who = '$_COOKIE[username]' ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $id = "$row[id]";
		 $viewed = "$row[viewed]";
		 $subject = "$row[subject]";
		 $date = "$row[date]";
		 $to = "$row[jo]";
		 $userguy = "$row[jo]";

		 echo "<tr><td><a href=\"pm.php?msg=$id\">$subject</a></td><td>$userguy</td><td>".date($dformat, $date)."</td></tr>";
	}

echo "<tr><td><a href=\"pm.php?box=in\">Inbox</a></td><td><a href=\"pm.php?new=msg\">Compose a Message</a></td></tr></table>";
}

if (($_GET['box'] == "") && ($_GET['msg'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "reply") && ($_GET['sent'] == "")) {

	$query="SELECT * FROM onecms_pm WHERE id = '" . $_GET['id'] . "'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $id = "$row[id]";
		 $subject = "$row[subject]";
		 $jo = "$row[who]";
		 $message2 = "$row[message]";
		 $message = stripslashes($message2);
	}

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Reply > $subject</title>";

if (!$_POST['submit']) {
if ($pm-$num2 > 1) {

		echo "<form action=\"pm.php?new=reply&id=".$id."\" method=\"post\"><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject2\" value=\"Re: $subject\"></td></tr>
		<tr><td>To:</td><td><input type=\"hidden\" name=\"tto\" value=\"$jo\">$jo</td></tr><tr><td><b>Message:</b></td><td>
<textarea name=\"messagea2\" cols=\"36\" rows=\"10\">

-- Original Message: $message --</textarea></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Send Reply\"></td><td><a href=\"pm.php?box=in\">Return to Inbox</a></td></tr></table></form>";
} else {

echo "Sorry $_COOKIE[username], but you have used up all the allotted messages. Please delete at least (1) message before replying to '$subject'.";
}
}

if ($_POST['submit']) {

$messageaa2 = addslashes($_POST['messagea2']);

$sent = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', '".$_POST['subject2']."', '$messageaa2', '$_COOKIE[username]', '".$_POST['tto']."', '".time()."')") or die(mysql_error());

if ($sent == TRUE) {
	echo "The reply has been sent to ".$_POST['tto'].". <a href=\"pm.php?box=in\">Return to Inbox</a>";
} else {
	echo "The reply could not be sent to ".$_POST['tto'].". Please click 'Back' on your browser and try again";
}
}
}

if (($_GET['msg'] == "") && ($_GET['new'] == "msg") && ($_GET['box'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['sent'] == "")) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Compose Message</title>";

if (!$_POST['submit']) {

		echo "<form action=\"pm.php?new=msg\" method=\"post\"><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject\"></td></tr>
		<tr><td><b>To:</b></td><td><select name=\"jo\">";

	if ($_GET['user']) {
	echo "<option value='".$_GET['user']."' selected'>-- ".$_GET['user']." --</option>";
	}
	$query="SELECT * FROM onecms_users";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$userguyr = "$row[username]";
        
		if ($userguyr == $_COOKIE[username]) {
		} else {
		echo "<option value=\"$userguyr\">$userguyr</option>";
		}
	}
	   echo "</select></td></tr><tr><td><b>Message:</b></td><td><textarea name=\"message\" cols=\"36\" rows=\"10\"></textarea></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Send Message\"></td><td><a href=\"pm.php?box=in\">Return to Inbox</a></td></tr></table></form>";
}
	if ($pm-$num2 > 1) {

if ($_POST['submit']) {

$message2 = addslashes($_POST['message']);

$sent = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', '".$_POST['subject']."', '$message2', '$_COOKIE[username]', '".$_POST['jo']."', '".time()."')") or die(mysql_error());

if ($sent == TRUE) {
	echo "Message has been sent. <a href=\"pm.php?box=in\">Return to Inbox</a>";
} else {
	echo "Message could not be sent. Please click 'Back' on your browser and try again";
}
}
} else {

echo "Sorry $_COOKIE[username], but ".$_POST['jo']." has used up all there allotted messages. Please tell them to delete at least (1) message.";
}

}

footera();
?>