<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
$pagetitle = "$sitename $split $txt_register";
include "template.php";

echo "<font class=\"header\">$txt_register</font><p class=\"indent\"/>";

if(isset($submit)) {

if($userpassword != $userpassword2) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_passnomatch $txt_goback";
}
elseif(empty($userid)) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_enteruser $txt_goback";
}
elseif(strlen($userpassword) < 6 || strlen($userpassword) > 15) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_passlen $txt_goback";
}
elseif(strstr($userid, "&")) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_noamp $txt_goback";
}
elseif(strstr($userpassword, " ")) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_nospaces $txt_goback";
}
elseif(!strstr($useremail, "@")) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_validemail $txt_goback";
}
elseif($userid == "Guest" || $userid == "guest" || $userid == "admin" || $userid == "Admin" || $userid == "administrator" || $userid == "Administrator") {
echo "<b>$txt_error</b><p class=\"indent\"/>$txt_resuser $txt_goback";
}
else {

$user_tablename = "${table_prefix}users";
$user_table_def = "NULL, '$userid', password('$userpassword'), '$username', '', '$useremail', '', curdate(), NULL, '', '', '', '', '', '', '', '', '$dispemail', '0', '0', '0'";

if(!mysql_query("INSERT INTO $user_tablename VALUES($user_table_def)")) {

$errorno = mysql_errno();

if ($errorno == 1062) {
$already = mysql_query("SELECT userpassword FROM $user_tablename WHERE userid = '$userid'");
$theirpass = mysql_result($already, 0, 0);
if($theirpass == "") {
if(!mysql_query("REPLACE INTO $user_tablename VALUES($user_table_def)")) echo "<font class=\"subhead\">$txt_error</font>";
}
else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_userexists $txt_goback";
die();
}
}
}
elseif ($errorno != 0) die(sql_error());

echo "$txt_useradded <a href=\"login.php\">$txt_login</a>.<p class=\"indent\"/>
$txt_oncein";

include "mails/signup.txt";

if(!@mail($useremail,$mailsubject,$mailmessage, "From: $siteemail\r\n"));
}
}
else {

echo "<div id=\"central\">
<div class=\"boxes\"><form action=\"register.php\" method=\"post\">
<table cellspacing=\"0\" width=\"100%\">
<tr><td width=\"20%\" class=\"box\">$txt_username: <font color=\"ff0000\">*</font></td><td class=\"boxrt\"><input type=\"text\" name=\"userid\"/></td></tr>
<tr><td class=\"box\">$txt_password: <font color=\"ff0000\">*</font></td><td class=\"boxrt\"><input type=\"password\" name=\"userpassword\"/></td></tr>
<tr><td class=\"box\">$txt_confpass: <font color=\"ff0000\">*</font></td><td class=\"boxrt\"><input type=\"password\" name=\"userpassword2\"/></td></tr>
<tr><td class=\"box\">$txt_realname:</td><td class=\"boxrt\"><input type=\"text\" name=\"username\"/></td></tr>
<tr><td class=\"box\">$txt_email: <font color=\"ff0000\">*</font></td><td class=\"boxrt\"><input type=\"text\" name=\"useremail\"/></td></tr>
<tr><td class=\"box\">$txt_dispemail <font color=\"ff0000\">*</font></td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"dispemail\" value=\"1\" checked=\"checked\"/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"dispemail\" value=\"0\"/> $txt_no</td></tr>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_register\"/><input type=\"hidden\" name=\"submit\" value=\"yes\"/>
</td></tr>
</table>
</form></div></div><p class=\"indent\"/>
<font color=\"ff0000\">*</font> = $txt_reqfield";

}

include "footer.php";
?>