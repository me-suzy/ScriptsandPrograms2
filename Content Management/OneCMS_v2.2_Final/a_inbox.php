<?php 
	include ("config.php");
	if ($ipbancheck3 == "0") {if ($numv == "0") {
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

$result2 = mysql_query("SELECT * FROM onecms_pm WHERE jo = '$username'") or die(mysql_error());
$num2 = mysql_num_rows($result2);

	$per = $num2/$pm;

echo '<script language="javascript">
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>';

	echo "<center><a href=\"a_inbox.php?box=sent\">Sentbox</a> | <a href=\"a_inbox.php?new=msg\">Compose a Message</a></center><br><br>";

if (($_GET['box'] == "in") && ($_GET['del'] == "") && ($_GET['msg'] == "") && ($_GET['confirm'] == "")) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox</title>";

	echo "<center><b>$num2</b> Messages/<b>$pm</b> Allowed | $per%</center><br><form action='a_inbox.php?del=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>From</b></td><td><b>Date</b></td><td><b>Delete?</b></td></tr>";

$query="SELECT * FROM onecms_pm WHERE jo = '$username' ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $id = "$row[id]";
		 $viewed = "$row[viewed]";
		 $subject = "$row[subject]";
		 $date = date($dformat, $row[date]);
		 $to = "$row[jo]";
		 $userguy = "$row[who]";

		 echo "<tr><td><a href=\"a_inbox.php?msg=$id\">";
		 if ($viewed == "0") {
			 echo "$subject";
		 } else {
			 echo "<b><i>$subject</b></i>";
		 }
		 echo "</a></td><td>$userguy</td><td>$date</td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
	}
echo "<tr><td><input type='submit' value='Delete'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></table>";
}
if (($_GET['box'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "")) {

	$query="SELECT * FROM onecms_pm WHERE id = '" . $_GET['msg'] . "'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$from = "$row[who]";
		$message2 = "$row[message]";
		$date = date($dformat, $row[date]);
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
	if ($jo == "$username") {
		$resultID = mysql_query("UPDATE onecms_pm SET viewed = '0' WHERE id = '" . $_GET['msg'] . "'") or die(mysql_error());
		echo "From:</b></td><td>$from";
	} else {
		echo "To:</b></td><td>$jo";
	}

		echo " at $date</td></tr><tr><td><b>Message:</b></td><td>$message</td></tr><tr>";

	if ($jo == "$username") {
	  echo "<td><a href=\"a_inbox.php?del=" . $_GET['msg'] . "\">Delete Message?</a></td>
			<td><a href=\"a_inbox.php?new=reply&id=" . $_GET['msg'] . "\">Reply to this Message</a></td><td><a href=\"a_inbox.php?box=in\">Return to Inbox</a></td><td><a href=\"a_inbox.php?box=sent\">Go to Sentbox</a></td></tr></table>";
	} else {

	  echo "<td><a href=\"a_inbox.php?box=sent\">Return to Sentbox</a></td>
		    <td><a href=\"a_inbox.php?box=in\">Go to Inbox</a></td></tr></table>";
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
	echo "Message(s) have been deleted. <a href='a_inbox.php?box=in'>Return to Inbox</a>";
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
	echo "Message has been deleted. <a href='a_inbox.php?box=in'>Return to Inbox</a>";
}
}
}
if (($_GET['box'] == "sent") && ($_GET['del'] == "") && ($_GET['msg'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "")) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Sentbox</title>";

	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>To</b></td><td><b>Date</b></td></tr>";

$query="SELECT * FROM onecms_pm WHERE who = '$username' ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $id = "$row[id]";
		 $viewed = "$row[viewed]";
		 $subject = "$row[subject]";
		 $date = date($dformat, $row[date]);
		 $to = "$row[jo]";
		 $userguy = "$row[jo]";

		 echo "<tr><td><a href=\"a_inbox.php?msg=$id\">$subject</a></td><td>$userguy</td><td>$date</td></tr>";
	}

echo "<tr><td><a href=\"a_inbox.php?box=in\">Inbox</a></td><td><a href=\"a_inbox.php?new=msg\">Compose a Message</a></td></tr></table>";
}

if (($_GET['box'] == "") && ($_GET['msg'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['new'] == "reply") && ($_GET['sent'] == "")) {

	$query="SELECT * FROM onecms_pm WHERE id = '" . intval($_GET['id']) . "'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		 $subject = "$row[subject]";
		 $jo = "$row[who]";
		 $message2 = "$row[message]";
		 $message = stripslashes($message2);
	}

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Reply > $subject</title>";

if (!$_POST['submit']) {
if ($pm-$num2 > 1) {

		echo "<form action=\"a_inbox.php?new=reply&id=$id\" method=\"post\"><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject2\" value=\"Re: $subject\"></td></tr>
		<tr><td>To:</td><td><input type=\"hidden\" name=\"tto\" value=\"$jo\">$jo</td></tr><tr><td><b>Message:</b></td><td>
<textarea name=\"messagea2\" cols=\"36\" rows=\"10\">

-- Original Message: $message --</textarea></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Send Reply\"></td><td><a href=\"a_inbox.php?box=in\">Return to Inbox</a></td></tr></table></form>";
} else {

echo "Sorry $username, but you have used up all the allotted messages. Please delete at least (1) message before replying to '$subject'.";
}
}

if ($_POST['submit']) {

$messageaa2 = addslashes($_POST['messagea2']);

$sent = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', '".$_POST['subject2']."', '$messageaa2', '$username', '".$_POST['tto']."', '".time()."')") or die(mysql_error());

if ($sent == TRUE) {
	echo "The reply has been sent to ".$_POST['tto'].". <a href=\"a_inbox.php?box=in\">Return to Inbox</a>";
} else {
	echo "The reply could not be sent to ".$_POST['tto'].". Please click 'Back' on your browser and try again";
}
}
}

if (($_GET['msg'] == "") && ($_GET['new'] == "msg") && ($_GET['box'] == "") && ($_GET['del'] == "") && ($_GET['confirm'] == "") && ($_GET['sent'] == "")) {

		echo "<title>OneCMS - www.insanevisions.com/onecms > Inbox > Compose Message</title>";

if (!$_POST['submit']) {

		echo "<form action=\"a_inbox.php?new=msg\" method=\"post\"><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject\"></td></tr>
		<tr><td>To:</td><td><select name=\"jo\">";

	$query="SELECT * FROM onecms_users";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$userguyr = "$row[username]";

		echo "<option value=\"$userguyr\">$userguyr</option>";
	}
	   echo "</select></td></tr><tr><td><b>Message:</b></td><td><textarea name=\"message\" cols=\"36\" rows=\"10\"></textarea></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Send Message\"></td><td><a href=\"a_inbox.php?box=in\">Return to Inbox</a></td></tr></table></form>";
}
	if ($pm-$num2 > 1) {

if ($_POST['submit']) {

$message2 = addslashes($_POST['message']);

$sent = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', '".$_POST['subject']."', '$message2', '$username', '".$_POST['jo']."', '".time()."')") or die(mysql_error());

if ($sent == TRUE) {
	echo "Message has been sent. <a href=\"a_inbox.php?box=in\">Return to Inbox</a>";
} else {
	echo "Message could not be sent. Please click 'Back' on your browser and try again";
}
}
} else {

echo "Sorry $username, but ".$_POST['jo']." has used up all there allotted messages. Please tell them to delete at least (1) message.";
}
}
}
	}
}include ("a_footer.inc");
?>