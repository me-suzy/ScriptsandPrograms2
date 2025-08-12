<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "protection.php";
include "header.php";
$pagetitle = "$sitename $split $txt_editprofile ($logincookie[user])";

include "template.php";

$title = "<font class=\"header\">$txt_editprofile ($logincookie[user])</font>";

if(isset($submit)) {

$users_tablename = "${table_prefix}users";

$username = str_replace ("<?", "&lt;?", $username);
$username = str_replace ('"', '&quot;', $username);
$usercountry = str_replace ("<?", "&lt;?", $usercountry);
$usercountry = str_replace ('"', '&quot;', $usercountry);
$useremail = str_replace ("<?", "&lt;?", $useremail);
$useremail = str_replace ('"', '&quot;', $useremail);
$userprofile = str_replace ("<?", "&lt;?", $userprofile);
$userprofile = str_replace ('"', '&quot;', $userprofile);
$usermsn = str_replace ("<?", "&lt;?", $usermsn);
$usermsn = str_replace ('"', '&quot;', $usermsn);
$useraol = str_replace ("<?", "&lt;?", $useraol);
$useraol = str_replace ('"', '&quot;', $useraol);
$usericq = str_replace ("<?", "&lt;?", $usericq);
$usericq = str_replace ('"', '&quot;', $usericq);
$useryahoo = str_replace ("<?", "&lt;?", $useryahoo);
$useryahoo = str_replace ('"', '&quot;', $useryahoo);
$userhomepage = str_replace ("<?", "&lt;?", $userhomepage);
$userhomepage = str_replace ('"', '&quot;', $userhomepage);
$usersig = str_replace ("<?", "&lt;?", $usersig);
$usersig = str_replace ('"', '&quot;', $usersig);

if($dobmonth == "0" || $dobday == "0" || $dobyear == "0") $userdob = "0000-00-00";
else {

if ($dobmonth < "10") {
$dobmonth = "0".$dobmonth;
}
if ($dobday < "10") {
$dobday = "0".$dobday;
}
$userdob = $dobyear."-".$dobmonth."-".$dobday;
}

$lastq = mysql_query("SELECT lastaccesstime FROM ${table_prefix}users WHERE userid='$logincookie[user]'");
$lastacc = mysql_result($lastq, 0, 0);

$users_table_def = "'$usernumber', '$logincookie[user]', '$userpassword', '$username', '$usercountry', '$useremail', '$userprofile', '$registerdate', '$lastacc', '$usermsn', '$useraol', '$usericq', '$useryahoo', '$userhomepage', '$usersig', '$userdob', '$usersex', '$dispemail', '$imgsig', '$pmnotify', '$timezone'";

if(!mysql_query("REPLACE INTO $users_tablename VALUES($users_table_def)")) die(sql_error());

echo "$title<p class=\"indent\"/>$txt_changessaved $txt_cont";

}

else {

$record = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$logincookie[user]'");

$usernumber = mysql_result($record, 0, "usernumber");
$username = mysql_result($record, 0, "username");
$userpassword = mysql_result($record, 0, "userpassword");
$useremail = mysql_result($record, 0, "useremail");
$usercountry = mysql_result($record, 0, "usercountry");
$userprofile = mysql_result($record, 0, "userprofile");
$registerdate = mysql_result($record, 0, "registerdate");
$usermsn = mysql_result($record, 0, "usermsn");
$useraol = mysql_result($record, 0, "useraol");
$usericq = mysql_result($record, 0, "usericq");
$useryahoo = mysql_result($record, 0, "useryahoo");
$userhomepage = mysql_result($record, 0, "userhomepage");
$usersig = mysql_result($record, 0, "usersig");
$userdob = mysql_result($record, 0, "userdob");
$usersex = mysql_result($record, 0, "usersex");
$dispemail = mysql_result($record, 0, "dispemail");
$imgsig = mysql_result($record, 0, "imgsig");
$pmnotify = mysql_result($record, 0, "pmnotify");
$timezone = mysql_result($record, 0, "timezone");
$userbanned = mysql_result($record, 0, "userbanned");

$timetosort = $userdob;
$dobyear = substr($timetosort, 0, 4);
$dobmonth = substr($timetosort, 5, 2);
$dobday = substr($timetosort, 8, 2);

echo "$title<p/>

<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">
<form action=\"editprofile.php\" method=\"post\">
<table>
<tr><td>$txt_realname:</td><td><input type=\"text\" name=\"username\" value=\"$username\"/></td></tr>
<tr><td>$txt_email:</td><td><input type=\"text\" name=\"useremail\" value=\"$useremail\"/></td></tr>
<tr><td>$txt_location:</td><td><input type=\"text\" name=\"usercountry\" value=\"$usercountry\"/></td></tr>
<tr><td>$txt_aol:</td><td><input type=\"text\" name=\"useraol\" value=\"$useraol\"/></td></tr>
<tr><td>$txt_msn:</td><td><input type=\"text\" name=\"usermsn\" value=\"$usermsn\"/></td></tr>
<tr><td>$txt_icq:</td><td><input type=\"text\" name=\"usericq\" value=\"$usericq\"/></td></tr>
<tr><td>$txt_yahoo:</td><td><input type=\"text\" name=\"useryahoo\" value=\"$useryahoo\"/></td></tr>
<tr><td>$txt_homepage:</td><td><input type=\"text\" name=\"userhomepage\" value=\"$userhomepage\"/></td></tr>
<tr><td>$txt_dob:</td><td>";

echo "<select name=\"dobday\">";
echo "<option value=\"0\">00</option>";
for($i = 1; $i <= 31; $i++) {
echo "<option value=\"$i\"";

if ($i == $dobday) {
echo " selected=\"selected\"";
}

echo ">$i</option>";
}
echo "</select>";

echo "<select name=\"dobmonth\">";
echo "<option value=\"0\">00</option>";
for($i = 1; $i <= 12; $i++) {
echo "<option value=\"$i\"";

if ($i == $dobmonth) {
echo " selected=\"selected\"";
}

echo ">$i</option>";
}
echo "</select>";

$curyr = date(Y);

echo "<select name=\"dobyear\">";
echo "<option value=\"0000\">0000</option>";
for($i = 1940; $i <= $curyr; $i++) {
echo "<option value=\"$i\"";

if ($i == $dobyear) {
echo " selected=\"selected\"";
}

echo ">$i</option>";
}
echo "</select>";


echo "</td></tr>
<tr><td>$txt_sex:</td><td>
<input type=\"radio\" class=\"noborder\" name=\"usersex\" value=\"1\"";
if ($usersex == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_male
<input type=\"radio\" class=\"noborder\" name=\"usersex\" value=\"2\"";
if ($usersex == 2) {
echo " checked=\"checked\"";
}
echo "/> $txt_female
</td></tr>
<tr><td valign=\"top\">$txt_profile:</td><td><textarea name=\"userprofile\" cols=\"60\" rows=\"15\">$userprofile</textarea></td></tr>
<tr><td valign=\"top\">$txt_signature:</td><td><textarea name=\"usersig\" cols=\"60\" rows=\"5\">$usersig</textarea></td></tr>";
if($avatars == 1) {
echo "<tr><td valign=\"top\">$txt_avatar:</td><td>";
if(file_exists("gfx/avatars/$logincookie[user].gif")) echo "<img src=\"gfx/avatars/$logincookie[user].gif\" alt=\"$txt_userposted\"/><br/>
<a href=\"javascript:window.open('avatar.php?s=n','','status=no,width=300,height=200,left=20,top=20,scrollbars=yes');void('w');\">$txt_avatardelete</a><br/>";
echo "<a href=\"javascript:window.open('avatar.php','','status=no,width=300,height=200,left=20,top=20,scrollbars=yes');void('w');\">$txt_avatarupload</a><br/>
</td></tr>";
}
echo "<tr><td valign=\"top\">$txt_dispemail</td><td>
<input type=\"hidden\" name=\"userpassword\" value=\"$userpassword\"/>
<input type=\"hidden\" name=\"registerdate\" value=\"$registerdate\"/>
<input type=\"hidden\" name=\"userbanned\" value=\"$userbanned\"/>
<input type=\"radio\" class=\"noborder\" name=\"dispemail\" value=\"1\"";
if ($dispemail == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_yes
<input type=\"radio\" class=\"noborder\" name=\"dispemail\" value=\"0\"";
if ($dispemail == 0) {
echo " checked=\"checked\"";
}
echo "/> $txt_no
</td></tr>
<tr><td valign=\"top\">$txt_imgsig</td><td>
<input type=\"radio\" class=\"noborder\" name=\"imgsig\" value=\"1\"";
if ($imgsig == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_yes
<input type=\"radio\" class=\"noborder\" name=\"imgsig\" value=\"0\"";
if ($imgsig == 0) {
echo " checked=\"checked\"";
}
echo "/> $txt_no
</td></tr>
<tr><td valign=\"top\">$txt_pmnotify</td><td>
<input type=\"radio\" class=\"noborder\" name=\"pmnotify\" value=\"1\"";
if ($pmnotify == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_yes
<input type=\"radio\" class=\"noborder\" name=\"pmnotify\" value=\"0\"";
if ($pmnotify == 0) {
echo " checked=\"checked\"";
}
echo "/> $txt_no
</td></tr>
<tr><td valign=\"top\">$txt_timezone</td><td><select name=\"timezone\">";

for ($i = -12; $i < 13; $i++) {

echo "<option value=\"$i\"";

if ($i == $timezone) echo " selected=\"selected\"";

echo ">$i</option>";

}

$current = date($timeform);

echo "</select> ($txt_currenttime $current)</td></tr>
<tr><td colspan=\"2\"><input type=\"submit\" value=\"$txt_savechanges\"/><input type=\"hidden\" name=\"submit\" value=\"yes\"/>
</td></tr>
</table>
</form>
</div></div></div>";

}

include "footer.php";
?>