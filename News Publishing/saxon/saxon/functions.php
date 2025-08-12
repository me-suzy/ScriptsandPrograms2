<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: functions.php
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/

// General db connection
function DBConnect ($mhost,$muser,$mpass,$mdb) {
	$connect = mysql_connect($mhost,$muser,$mpass);
	$error="";
	if (!$connect) {
		$error="<p>Unable to connect to the database server at this time.</p>";
	}
	$db_selected = mysql_select_db($mdb,$connect);
	if (!$db_selected) {
		$error ="Unable to use ".$mdb." : ".mysql_error() ;
	}
	return $error;
}

/************************************************************************/
function QuoteSmart($value) {
	// Safe entry of any text into a mysql query. Usage: $name=quote_smart($_POST['name']);

	// Stripslashes
	if (get_magic_quotes_gpc()) $value = stripslashes($value);
	$value = mysql_real_escape_string($value);
	return $value;
}

/************************************************************************/

// Check both username and password supplied on login
function LoginCheck($form,$prefix) {
	$error = "";
	$username = $form["username"];
	if (!preg_match("/^[a-zA-Z0-9]+$/", $username ))  {
		$error = "Login names can only contain letter and numbers.";
		return $error;
	}
	$password = md5($form["password"]);
	if(trim($username) == "" || trim($password) == "") {
		$error = "<p>Please enter a username and password</p>";
		return $error;
	}
	// Do sql query to search for existing record with correct username and password
	$tblname = QuoteSmart($prefix."saxon_users");
	$username = QuoteSmart($username);
	$password = QuoteSmart($password);
	 $result = mysql_query ("SELECT USER_NAME, USER_PWD FROM $tblname WHERE USER_NAME = '$username' AND USER_PWD='$password'");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows==0) {
		$error="Incorrect username or password";
		return $error;
	}
}
/************************************************************************/

// Check user is known and return member_id and full name
function UserLogin($form,$prefix) {
	$username = $form["username"];
	$password = md5($form["password"]);
	/* do the sql query again, but now returning the id of member */
	$tblname = QuoteSmart($prefix."saxon_users");
	$username = QuoteSmart($username);
	$password = QuoteSmart($password);
	$result = mysql_query ("SELECT USER_ID,FULL_NAME,SUPER_USER FROM $tblname WHERE USER_NAME = '$username' AND USER_PWD='$password'");
	if (!$result) die('Invalid query: ' . mysql_error());
	while($row = mysql_fetch_array($result))
	{
		$member_id=$row['USER_ID'];
		$full_name=$row['FULL_NAME'];
		$super_user=$row['SUPER_USER'];
	}
	return array ($member_id,$full_name,$super_user);
}
/************************************************************************/

// Check authentication on each separate page
function Authenticate () {
	if (!isset($_SESSION["member_id"]) || ($_SESSION["member_id"] == "")) 
	{
		echo "<p class=\"error\">You are not authorised to access this area!</p>";
		exit;
	}
}

// Check user status
function UserStatus() {
	if (!isset($_SESSION["super_user"]) || ($_SESSION["super_user"] == "") || ($_SESSION['super_user']!="Y")) 
	{
		echo "<p class=\"error\">You are not authorised to access administrator functions!</p>";
		exit;
	}
}
/************************************************************************/

// Check a given username exists
function CheckUsername($member_login,$prefix) {
	$error = "";
	$tblname = QuoteSmart($prefix."saxon_users");
	$member_login = QuoteSmart($member_login);
	$result = mysql_query ("SELECT * FROM $tblname WHERE USER_NAME = '$member_login'");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows==0) $error="<p class=\"error\">No such user!</p>\n";
	return $error;
}
/************************************************************************/

// Obtain all info on a given user
function GetUser($login_name,$prefix) {
	$tblname = QuoteSmart($prefix."saxon_users");
	$login_name = QuoteSmart($login_name);
	$result = mysql_query ("SELECT * FROM $tblname WHERE USER_NAME = '$login_name'");
	if (!$result) die('Invalid query: ' . mysql_error());
	while($row = mysql_fetch_array($result))
	{
		$user_id=$row['USER_ID'];
		$full_name=$row['FULL_NAME'];
		$user_pwd=$row['USER_PWD'];
		$user_status=$row['SUPER_USER'];
	}
	return array ($user_id,$full_name,$user_pwd,$user_status);
}
/************************************************************************/

// Send an email when new item added or edited
function Notify($date_posted,$item_title,$name,$job) {
	include "config.php";
	$toHeader = "SAXON Admin <".$admin_email.">";
	$fromHeader = "From: SAXON <".$server_email.">";
	$subject = "News item added/edited via SAXON";
	$mail_body = "URL: ".$news_url."\n";
	$mail_body .= "Dated: ".$date_posted."\n";
	$mail_body .= "Title: ".$item_title."\n";
	if ($job == "add") $mail_body .= "Posted by: ".$name."\n";
	if ($job == "update") $mail_body .= "Edited by: ".$name."\n";

	// Send the mail
	if(mail($toHeader,$subject,$mail_body,$fromHeader)) echo "<p class=\"success\">Admin notified by email.</p>";
	else echo "<p class=\"error\">Email notification failed. Please contact your SAXON administrator!</p>";
}
/************************************************************************/

// Send an email to notify Admin of an error
function EmailError($content) {
	include "config.php";
	$toHeader = "SAXON Admin <".$admin_email.">";
	$fromHeader = "From: SAXON <".$server_email.">";
	$subject = "SAXON Error";
	$mail_body = "URL: ".$uri.$path."\n\n";
	$mail_body .= $content;
	// Send the mail
	mail($toHeader,$subject,$mail_body,$fromHeader);
}
/************************************************************************/

function PrintForm($pagetitle,$scriptname,$name,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,$submit_value) {
	$monthList=array(
	1 => "January",
	2 => "February",
	3 => "March",
	4 => "April",
	5 => "May",
	6 => "June",
	7 => "July",
	8 => "August",
	9 => "September",
	10 => "October",
	11 => "November",
	12 => "December");
	?>
<h2><?php echo $pagetitle; ?></h2>
<div id="news-form">
<form action="<?php echo $scriptname; ?>" method="post">
<input name="newsid" id="newsid" type="hidden" value="<?php echo $newsid; ?>" />
<input name="name" id="name" type="hidden" value="<?php echo $name; ?>" />
<input name="edited" id="editdate" type="hidden" value="<?php echo $edited; ?>" />
<p class="author">
	<?php
	if ($printmsg !="") echo $printmsg;
	?>
<strong>Author:</strong> <?php echo $name; ?></p>

<p><label for="title">Article Title</label>: 
<input name="title" id="title" type="text" size="25" maxlength="50" value="<?php echo $title; ?>" /></p>

<fieldset><legend>If you want to post-date this item, select the publication date below:</legend>
<p><label for="day" class="dropdown">Day</label>:
<select name="day" id="day">
<?php
for ($i = 1; $i <= 31; $i++)
{
	if ($i == $day) echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
	else echo "<option value=\"$i\">$i</option>\n";
}
?>
</select> 
<label for="month" class="dropdown">Month</label>: 
<select name="month" id="month">
<?php
foreach($monthList as $code => $monthname)
{
	if ($code == $month) echo "<option value=\"$code\" selected=\"selected\">$monthname</option>\n";
	else echo "<option value=\"$code\">$monthname</option>\n";

}
?>
</select> 
<label for="year" class="dropdown">Year</label>: 
<select name="year" id="year">
<?php
$this_year = date("Y");
for ($i = $this_year; $i <= $this_year+10; $i++)
{
	if ($i == $year) echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
	else echo "<option value=\"$i\">$i</option>\n";
}
?>
</select></p>
</fieldset>

<p><span class="details"><label for="news">Details</label>: </span>
<textarea name="news" id="news" cols="50" rows="20"><?php echo $news; ?></textarea></p>

<p><input name="submit" class="submit button" type="submit" value="<?php echo $submit_value; ?>" />
<input name="reset" class="button" type="reset" value="Reset" /></p>
</form>
</div>
	<?php
}
/************************************************************************/

function LimitWords($string,$num) {
	// Limit number of words displayed to limit set in $variable
	$string = explode(' ', $string);
	if (count($string) > $num) $string = implode(' ', array_slice($string, 0, $num)) . "...";
	else $string = implode(' ',$string);
	return $string;
}
/************************************************************************/

function PrepText($string,$allowed) {
	// remove any slashes inserted by magic_quotes_gpc
	$string = stripslashes ($string);
	//If HTML isn't allowed
	if ($allowed == 0) $string = htmlentities($string);
	$new_string = Markup($string);
	$new_string = EncodeChrs ($new_string);
	return $new_string;
}
/************************************************************************/

function Markup($text, $br = 1) {
// Transforms input into properly marked up text with paragraph and line break tags 
// whilst being mindful of block-level HTML tags
    $text = $text . "\n"; // just to make things a little easier, pad the end
    $text = preg_replace('|<br />\s*<br />|', "\n\n", $text);
    $text = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $text); // Space things out a little
    $text = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $text); // Space things out a little
    $text = preg_replace("/(\r\n|\r)/", "\n", $text); // cross-platform newlines
    $text = preg_replace("/\n\n+/", "\n\n", $text); // take care of duplicates
    $text = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $text); // make paragraphs, including one at the end
    $text = preg_replace('|<p>\s*?</p>|', '', $text); // under certain strange conditions it could create a P of entirely whitespace
    $text = preg_replace("|<p>(<li.+?)</p>|", "$1", $text); // problem with nested lists
    $text = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $text);
    $text = str_replace('</blockquote></p>', '</p></blockquote>', $text);
    $text = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $text);
    $text = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $text);
    if ($br) $text = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $text); // optionally make line breaks
    $text = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $text);
    $text = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $text);
    
    return $text;
}
/************************************************************************/

function EncodeChrs ($string){
    $string = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $string);

	$trans_tbl = array() ;
	$trans_tbl[chr(128)] = '&#8364;' ;	// 	euro
	$trans_tbl[chr(130)] = '&#8218;' ; 	//	low quote
	$trans_tbl[chr(131)] = '&#402;' ; 	//	florin
	$trans_tbl[chr(132)] = '&#8222;' ; 	// 	double low quote
	$trans_tbl[chr(133)] = '&#8230;' ; 	//	ellipsis
	$trans_tbl[chr(134)] = '&#8224;' ;	//	dagger
	$trans_tbl[chr(135)] = '&#8225;' ; 	//	double dagger
	$trans_tbl[chr(136)] = '&#710;' ; 	//	circumflex
	$trans_tbl[chr(137)] = '&#8240;' ; 	//	per thousand
	$trans_tbl[chr(138)] = '&#352;' ; 	//	S caron
	$trans_tbl[chr(140)] = '&#338;' ; 	//	OE ligature
	$trans_tbl[chr(142)] = '&#381;' ; 	//	Z caron
	$trans_tbl[chr(145)] = '&#8216;' ; 	//	left single curly quote
	$trans_tbl[chr(146)] = '&#8217;' ; 	//	right single curly quote
	$trans_tbl[chr(147)] = '&#8220;' ; 	//	left double curly quote
	$trans_tbl[chr(148)] = '&#8221;' ; 	//	right double curly quote
	$trans_tbl[chr(149)] = '&#8226;' ; 	//	bullet
	$trans_tbl[chr(150)] = '&#8211;' ; 	//	en dash
	$trans_tbl[chr(151)] = '&#8212;' ; 	//	em dash
	$trans_tbl[chr(152)] = '&#732;' ; 	//	small tilde
	$trans_tbl[chr(153)] = '&#8482;' ; 	//	trademark
	$trans_tbl[chr(154)] = '&#353;' ; 	//	small s caron
	$trans_tbl[chr(156)] = '&#339;' ; 	//	oe ligature
	$trans_tbl[chr(158)] = '&#382;' ; 	// 	small z caron
	$trans_tbl[chr(159)] = '&#376;' ; 	//	Y with diaeresis
	$trans_tbl[chr(162)] = '&#162;' ;	// 	cent
	$trans_tbl[chr(163)] = '&#163;' ;	// 	£
	$trans_tbl[chr(169)] = '&#169;' ;	// 	copyright

	for ( $i=160; $i<=255; $i++ ) {
		$trans_tbl[chr($i)] = '&#' . $i . ';' ;
	}

	return strtr ( $string , $trans_tbl ) ;
}
/************************************************************************/

function DisplayDate($date) {
	$displaydate = date("l, d F Y",strtotime($date));
	return $displaydate;
}
/************************************************************************/

?>