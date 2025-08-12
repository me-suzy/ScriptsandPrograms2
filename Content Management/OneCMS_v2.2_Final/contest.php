<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck1 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the site...now go away!";
} else {

headera();

if (($_GET['id']) && ($_GET['show'] == "")) {

$query="SELECT * FROM onecms_templates WHERE name = 'Contest Manager'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$contest = stripslashes($row[template]);
}

$query="SELECT * FROM onecms_contest WHERE id = '".$_GET['id']."'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$postpone = "$row2[email]";

	$find[] = "/{rules}/";
	$find[] = "/{des}/";
	$find[] = "/{name}/";
	$find[] = "/{priv}/";
	$find[] = "/{posts}/";
	$find[] = "/{login}/";
	$find[] = "/{register}/";
	$find[] = "/{forums}/";
	$find[] = "/{enter}/";
	$repl[] = "".stripslashes($row2[rules])."";
	$repl[] = "".stripslashes($row2[des])."";
	$repl[] = "".$row2[name]."";
	$repl[] = "".$row2[priv]."";
	$repl[] = "".$row2[posts]."";
	$repl[] = "members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."";
	$repl[] = "members.php?action=register";
	$repl[] = "boards.php";
	$repl[] = "contest.php?id=".$_GET['id']."&show=enter";

if ($postpone > date("YmdHi")) {
eval (" ?>" . preg_replace($find, $repl, $contest) . " <?php ");
} else {

    $query="SELECT * FROM onecms_contest WHERE type = 'entry' AND cid = '".$_GET['id']."' ORDER BY RAND() LIMIT 1";
	$result=mysql_query($query);
	while($rowb = mysql_fetch_array($result)) {
		$win = "$rowb[username]";
	}

	$update = mysql_query("UPDATE onecms_contest SET type = 'winner' WHERE username = '".$win."' AND cid = '".$_GET['id']."'");
}
	$queraey=mysql_query("SELECT * FROM onecms_contest WHERE cid = '".$_GET['id']."' AND type = 'winner'");
	$countn = mysql_num_rows($queraey);
	if ($countn == "1") {
	$queraeya="SELECT * FROM onecms_contest WHERE cid = '".$_GET['id']."' AND type = 'winner'";
	$result=mysql_query($queraeya);
	while($row = mysql_fetch_array($result)) {
		$winner = "$row[username]";
	}
	echo "Sorry, but this contest is over. The winner is: <b>".$winner."</b>";
	$queraeye="SELECT * FROM onecms_profile WHERE id = '".$winner."'";
	$resulta=mysql_query($queraeye);
	while($rowe = mysql_fetch_array($resulta)) {
		print $rowe[username];
	}
	echo "</a>";
	}
	}
}

if (($_GET['id']) && ($_GET['show'] == "enter")) {
	$query="SELECT * FROM onecms_contest WHERE id = '".$_GET['id']."'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$priv = "$row2[priv]";
		$posts = "$row2[posts]";
		$name = "$row2[name]";
	}
	echo '<SCRIPT LANGUAGE="JavaScript">
function validateZIP(field) {
var valid = "0123456789-";
var hyphencount = 0;

if (field.length!=5 && field.length!=10) {
alert("Please enter your 5 digit or 5 digit+4 zip code.");
return false;
}
for (var i=0; i < field.length; i++) {
temp = "" + field.substring(i, i+1);
if (temp == "-") hyphencount++;
if (valid.indexOf(temp) == "-1") {
alert("Invalid characters in your zip code.  Please try again.");
return false;
}
if ((hyphencount > 1) || ((field.length==10) && ""+field.charAt(5)!="-")) {
alert("The hyphen character should be used with a properly formatted 5 digit+four zip code, like 12345-6789.   Please try again.");
return false;
   }
}
return true;
}
</script>';

$findba = mysql_query("SELECT * FROM onecms_contest WHERE username = '".$_COOKIE[username]."' AND type = 'entry' AND cid = '".$_GET['id']."'");
$check2 = mysql_num_rows($findba);

$find2ba = mysql_query("SELECT * FROM onecms_contest WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND type = 'entry' AND cid = '".$_GET['id']."'");
$check3 = mysql_num_rows($find2ba);

$findbae = mysql_query("SELECT * FROM onecms_contest WHERE type = 'winner' AND cid = '".$_GET['id']."'");
$check2e = mysql_num_rows($findbae);

if ($check2e > "0") {
	$queraeya="SELECT * FROM onecms_contest WHERE cid = '".$_GET['id']."' AND type = 'winner'";
	$result=mysql_query($queraeya);
	while($row = mysql_fetch_array($result)) {
		$winner = "$row[username]";
	}
	echo "Sorry, but this contest is over. The winner is: <b><a href='members.php?action=profile&id=".$winner."'>";
	$queraeye="SELECT * FROM onecms_profile WHERE id = '".$winner."'";
	$resulta=mysql_query($queraeye);
	while($rowe = mysql_fetch_array($resulta)) {
		print $rowe[username];
	}
	echo "</a>";
} else {


if (($check2 == "0") or ($check3 == "0")) {
	echo "<form action='contest.php?show=enter2&id=".$_GET['id']."' method='post' onSubmit='return validateZIP(this.zip.value)'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Your Name</b></td><td><input type='text' name='name'></td></tr>";
	
	if (($priv == "yes") && ($username)) {
		echo "<tr><td><b>Your Username</b></td><td>".$username."</td></tr><tr><td><b>Your E-mail Address</b></td><td><input type='hidden' name='email' value='".$email."'>".$email."</td></tr>";
	}
	
	if (($priv == "yes") && ($username == "")) {
		echo "<tr><td><b>Your Username</b></td><td><font color='red'>Sorry, but you must be a member of ".$sitename." in order to enter. Please <a href='members.php?action=register'>Register</a> or <a href='members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."'>Login</a></td></tr>";
	}

	if (($priv == "no") && ($username == "")) {
		echo "<tr><td><b>Your E-mail Address</b></td><td><input type='text' name='email'></td></tr>";
	}

	if (($priv == "no") && ($username)) {
		echo "<tr><td><b>Your E-mail Address</b></td><td><input type='hidden' name='email' value='".$email."'>".$email."</td></tr>";
	}

	echo "<tr><td><b>Street Address</b></td><td><input type='text' name='addy'></td></tr><tr><td><b>City</b></td><td><input type='text' name='city'></td></tr><tr><td><b>State</b></td><td><input type='text' name='state'></td></tr><tr><td><b>Zip Code</b></td><td><input type='text' name='zip' size='5'></td></tr><tr><td><input type='checkbox' name='check' checked></td><td>I agree to the official rules and all information entered is not false.</td></tr><tr><td><input type='submit' value='Submit Entry'></td></tr>";
} else {
	echo "Sorry, but you have already entered into the <b>".$name."</b> contest";
}
}
echo "</table>";
}

if (($_GET['id']) && ($_GET['show'] == "enter2")) {
	if ($_POST['check'] == "") {
		echo "Sorry, but you must agree to the rules. Please go back and try again.";
	} else {
	$enter = mysql_query("INSERT INTO onecms_contest VALUES ('null', '".$_POST["name"]."', '".$_POST["addy"]."', '".$_POST["email"]."', 'entry', '".$_POST["city"]."', '".$_POST["state"]."', '".$_POST["zip"]."', '".$username."', '".$_SERVER['REMOTE_ADDR']."', '".$_GET['id']."')") or die(mysql_error());

	if ($enter == TRUE) {
		echo "Congratulations ".$_POST["name"].", you have successfully entered the contest. You will be notified if you win. Thank you.";
	}
	}
}

}
}
}
footera();
?>