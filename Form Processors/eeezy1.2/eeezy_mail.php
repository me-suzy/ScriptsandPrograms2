<html>
<head>
<title>EEEzy Feedback Form V 1.2</title>
</head>
<body>
<?
$form_block = "
<form method=\"post\" action=\"$PHP_SELF\">
<p><strong>Your name:</strong><br>
<input type=\"text\" name=\"name\" size=30 value=\"$_POST[name]\"</p>
<p><strong>Your email:</strong><br>
<input type=\"text\" name=\"email\" value=\"$_POST[email]\"size=30</p>
<p><strong>Comments:</strong><br>
<textarea name=\"comments\" cols=30 rows=5 wrap=virtual>$_POST[comments]</textarea></p>
<input type=\"hidden\" name=\"op\" value=\"ds\">
<p><input type=\"submit\" name=\"submit\" value=\"Send Form\"></p><br>
<a href=\"http://www.nafwa.org/\"><font size=\"1\"><b>Powered By The Nutrition and Food Web Archive
</b></font>
</form>";

if ($_POST[op] != "ds") {
echo "$form_block";

} else if ($_POST[op] =="ds") {

if ($_POST[name] == "") {
	$name_err = "<font color=blue>Please enter your name!</font><br>";
	$send="no";
}
if ($_POST[email] == "") {
	$email_err = "<font color=blue>Please enter your email!</font><br>";
	$send="no";
}
if ($_POST[comments] == "") {
	$message_err = "<font color=blue>Please enter a comment!</font><br>";
	$send="no";
}
if ($send !="no") {
$msg = "email sent from mywebsite.com\n";
$msg .="Name: $_POST[name]\n";
$msg .="Email: $_POST[email]\n";
$msg .="Comments: $_POST[comments]\n";
$to = "admin@mydomain.com";
$subject = "Feedback Form";
$mailheaders = "From: My Website <admin@mydomain.com>\n";
$mailheaders .= "Reply-To: $_POST[email]\n";
mail ($to, $subject, $msg, $mailheaders);
	echo "<p>Mail has been sent!</p>";

} else if ($send == "no") {
	echo "$name_err";
	echo "$email_err";
	echo "$message_err";
	echo "$form_block";
     }

}
?>

</body>
</html>