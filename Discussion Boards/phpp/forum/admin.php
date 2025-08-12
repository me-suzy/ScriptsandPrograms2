<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "protection.php";
include "header.php";
$pagetitle = "$sitename $split $txt_admincentre";
include "template.php";
include "timeconvert.php";

if($overalladmin == 1) {

echo "<font class=\"header\"><a href=\"admin.php\" class=\"head\">$txt_admincentre</a>";

if($p == "user") {

echo " $split $txt_useradmin</font><p class=\"indent\"/>";

if($s == "users") {

if(isset($u)) {

echo "<font class=\"subhead\">$u</font> ";

$usq = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$u'");
$us2q = mysql_query("SELECT userbanned FROM ${table_prefix}users WHERE userid='$u'");

$overalladmin = mysql_result($usq, 0, "overalladmin");
$admin = mysql_result($usq, 0, "admin");
$mod = mysql_result($usq, 0, "mod");
$access = mysql_result($usq, 0, "access");
$banned = mysql_result($us2q, 0, 0);

if(!isset($a)) {

if($banned == 1) {
echo $txt_hasbeenbanned;
}
else {
if($overalladmin == 1) {
echo $txt_overalladmin;
}
elseif($admin == 1) {
echo $txt_issiteadmin;
}
elseif($mod != "") {
echo $txt_moderator;

$modar = explode(" ", $mod);

$mods = count($modar);

for ($i = 1; $i < $mods; $i++) {
$modar[$i] = str_replace(",", "", $modar[$i]);

$forname = mysql_query("SELECT forumname FROM ${table_prefix}forums WHERE forumno='$modar[$i]'");
$forname = mysql_result($forname, 0, 0);

echo "<br/>- $forname";

}
echo "<br/>$txt_and ";
}
if($access != "" && $admin != 1) {
echo $txt_restaccess;

$accar = explode(" ", $access);

$acc = count($accar);

for ($i = 1; $i < $acc; $i++) {
$accar[$i] = str_replace(",", "", $accar[$i]);

$forname = mysql_query("SELECT forumname FROM ${table_prefix}forums WHERE forumno='$accar[$i]'");
$forname = mysql_result($forname, 0, 0);

echo "<br/>- $forname";
}
}
}
}

if($overalladmin != 1) {

if(isset($t)) {
if($t == "y") {
$userbans_table_def = "NULL, '$u'";
if(!mysql_query("INSERT INTO ${table_prefix}userbans VALUES($userbans_table_def)")) die(sql_error());
echo "<p class=\"indent\"/><font class=\"emph\">$txt_statuschanged</font> - $u $txt_banned. <a href=\"admin.php?p=user&s=users&u=$u\">$txt_useradmin</a><p class=\"indent\"/>";
}
elseif ($t == "n") {
if(!mysql_query("DELETE FROM ${table_prefix}userbans WHERE user='$u'")) die(sql_error());
echo "<p class=\"indent\"/><font class=\"emph\">$txt_statuschanged</font> - $u $txt_unbanned. <a href=\"admin.php?p=user&s=users&u=$u\">$txt_useradmin</a><p class=\"indent\"/>";
}
}
elseif($a == "profile") {

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

if ($dobmonth < "10") {
$dobmonth = "0".$dobmonth;
}
if ($day < "10") {
$dobday = "0".$dobday;
}
$userdob = $dobyear."-".$dobmonth."-".$dobday;

$users_table_def = "'$usernumber', '$u', '$userpassword', '$username', '$usercountry', '$useremail', '$userprofile', '$registerdate', curdate(), '$usermsn', '$useraol', '$usericq', '$useryahoo', '$userhomepage', '$usersig', '$userdob', '$usersex', '$dispemail', '$imgsig', '$pmnotify', '$timezone'";

if(!mysql_query("REPLACE INTO $users_tablename VALUES($users_table_def)")) die(sql_error());

echo "<p class=\"indent\"/>$txt_changessaved <a href=\"admin.php?p=user\">$txt_useradmin</a>";

}

else {
$record = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$u'");

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

$timetosort = $userdob;
$dobyear = substr($timetosort, 0, 4);
$dobmonth = substr($timetosort, 5, 2);
$dobday = substr($timetosort, 8, 2);

echo "<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">
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
}
elseif ($a == "rights") {

if(isset($submit)) {

$currentq = mysql_query("SELECT autonumber FROM ${table_prefix}userrights WHERE userid='$u'");
$newautonumber = mysql_result($currentq, 0, 0);

$newaccess = "";
$newmoder = "";

$forumsq = mysql_query("SELECT forumno FROM ${table_prefix}forums");

for ($i = 0; $i < mysql_num_rows($forumsq); $i++) {

$forumno = mysql_result($forumsq, $i, 0);

if($newacc[$forumno] == 1) $newaccess .= " $forumno,";
if($newmod[$forumno] == 1) $newmoder .= " $forumno,";

}

$userrights_tablename = "${table_prefix}userrights";
$userrights_table_def = "'$newautonumber', '$u', '$newaccess', '$newmoder', '$newadmin', '0'";

if(!mysql_query("REPLACE INTO $userrights_tablename VALUES($userrights_table_def)")) die(sql_error());


echo "<p class=\"indent\"/>$txt_changessaved <a href=\"admin.php?p=user\">$txt_useradmin</a>";
}

else {
echo "<div id=\"central\"><div class=\"boxes\"><form action=\"admin.php?p=user&amp;s=users&&amp;u=$u&&amp;a=rights\" method=\"post\"><table cellspacing=\"0\" width=\"100%\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_siteadminq</td><td class=\"box\" width=\"80%\"><input type=\"radio\" class=\"noborder\" name=\"newadmin\" value=\"1\"";

if ($admin == 1) echo " checked=\"checked\"";
echo "> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"newadmin\" value=\"0\"";
if ($admin != 1) echo " checked=\"checked\"";
echo "> $txt_no</td></tr>
<tr><td class=\"boxhdrt\">&nbsp;</td><td class=\"boxhdrt\">$txt_modrights</td></tr>";

$catsq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

for($i = 0; $i < mysql_num_rows($catsq); $i++) {

$catnm = mysql_result($catsq, $i, "catname");
$catorder = mysql_result($catsq, $i, "catorder");

echo "<tr><td class=\"boxhdrt\">&gt; $catnm</td><td class=\"boxrt\">&nbsp;</td></tr>";

$forsq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$catorder' ORDER BY fororder ASC");

for ($j = 0; $j < mysql_num_rows($forsq); $j++) {

$fornm = mysql_result($forsq, $j, "forumname");
$forno = mysql_result($forsq, $j, "forumno");

echo "<tr><td class=\"box\">&nbsp; &nbsp;- $fornm</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"newmod[$forno]\" value=\"1\"";

if(strstr($mod, " $forno,")) echo " checked=\"checked\"";

echo "> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"newmod[$forno]\" value=\"0\"";

if(!strstr($mod, " $forno,")) echo " checked=\"checked\"";

echo "> $txt_no</td></tr>";

}

}

echo "<tr><td class=\"boxhdrt\">&nbsp;</td><td class=\"boxhdrt\">$txt_accessrights</td></tr>";

$forsq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE restricted='1' ORDER BY fororder ASC");

for ($j = 0; $j < mysql_num_rows($forsq); $j++) {

$fornm = mysql_result($forsq, $j, "forumname");
$forno = mysql_result($forsq, $j, "forumno");

echo "<tr><td class=\"box\">&nbsp; &nbsp;- $fornm</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"newacc[$forno]\" value=\"1\"";
if(strstr($access, " $forno,")) echo " checked=\"checked=\"";
echo "/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"newacc[$forno]\" value=\"0\"";
if(!strstr($access, " $forno,")) echo " checked=\"checked\"";
echo "/> $txt_no</td></td></tr>";

}

echo "<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"hidden\" name=\"submit\" value=\"y\"/><input type=\"submit\" value=\"$txt_savechanges\"/></td></tr>";

echo "</table></form>";
}
}
else {
echo "<p class=\"indent\"/><a href=\"admin.php?p=user&amp;s=users&&amp;u=$u&a&amp;=profile\">$txt_editprofile</a> $split <a href=\"admin.php?p=user&amp;s=users&amp;u=$u&amp;a=rights\">$txt_editrights</a> $split ";
$banq = mysql_query("SELECT * FROM ${table_prefix}userbans WHERE user='$u'");
if(mysql_num_rows($banq) > 0) echo "<a href=\"admin.php?p=user&amp;s=users&amp;u=$u&amp;t=n\">$txt_unban</a>";
else echo "<a href=\"admin.php?p=user&amp;s=users&amp;u=$u&amp;t=y\">$txt_ban</a>";

}

}
}

else {

$usersq = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid LIKE '%$username%' ORDER BY userid ASC");

echo "$txt_found ".mysql_num_rows($usersq);
if (mysql_num_rows($usersq) != 1) echo " $txt_results";
else echo " $txt_result";

echo ".<p class=\"indent\"/>";

for ($i = 0; $i < mysql_num_rows($usersq); $i++) {

$userid = mysql_result($usersq, $i, "userid");

echo "<a href=\"admin.php?p=user&amp;s=users&amp;u=$userid\">$userid</a><br/>";
}
}

}
elseif($s == "ip") {

if(isset($t)) {
if($t == "y") {
$ipbans_table_def = "NULL, '$ip'";
if(!mysql_query("INSERT INTO ${table_prefix}ipbans VALUES($ipbans_table_def)")) die(sql_error());
}
elseif ($t == "n") {
if(!mysql_query("DELETE FROM ${table_prefix}ipbans WHERE ip='$ip'")) die(sql_error());
}
echo "<font class=\"emph\">$txt_statuschanged</font><p class=\"indent\"/>";

}

echo "<font class=\"subhead\">$txt_ipstatus</font><p class=\"indent\"/>

$txt_address <font class=\"emph\">$ip</font>";

$ipchk = mysql_query("SELECT * FROM ${table_prefix}ipbans WHERE ip='$ip'");

if (mysql_num_rows($ipchk) > 0) {
echo " $txt_bannedacc <a href=\"admin.php?p=user&s=ip&amp;ip=$ip&amp;t=n\">$txt_unban</a>";
}
else {
echo " $txt_allowedacc <a href=\"admin.php?p=user&amp;s=ip&amp;ip=$ip&amp;t=y\">$txt_ban</a>";
}

echo "<p class=\"indent\"/>$txt_postedfromip<br/>";

$postsq = mysql_query("SELECT * FROM ${table_prefix}public WHERE ip='$ip' ORDER BY userfrom ASC");

for ($i = 0; $i < mysql_num_rows($postsq); $i++) {

$lastuser = $user;

$user = mysql_result($postsq, $i, "userfrom");

if(isset($posted[$user])) $posted[$user]++;
else $posted[$user] = 1;

if($i != 0 && $lastuser != $user) {
echo "$lastuser - $posted[$lastuser]";

if($posted[$lastuser] > 1) echo " $txt_posts";
else echo " $txt_post";
echo "<br/>";
}
if($i == (mysql_num_rows($postsq) - 1)) {
echo "<a href=\"admin.php?p=user&amp;s=users&amp;u=$lastuser\">$lastuser</a> - $posted[$lastuser]";

if($posted[$lastuser] > 1) echo " $txt_posts";
else echo " $txt_post";
$userbanstat = mysql_query("SELECT * FROM ${table_prefix}userbans WHERE user='$lastuser'");
if(mysql_num_rows($userbanstat) > 0) echo " - <a href=\"admin.php?p=user&amp;s=users&amp;u=$lastuser&amp;t=n\">$txt_unban</a>";
else echo " - <a href=\"admin.php?p=user&amp;s=users&amp;u=$lastuser&amp;t=y\">$txt_ban</a>";
echo "<br/>";
}

}

}

else {

echo "$txt_enterusername<br/>$txt_enterip<p class=\"indent\"/>

<div id=\"central\"><div class=\"infobox\">
<table>
<tr><td>$txt_username:</td><td><form method=\"post\" action=\"admin.php?p=user&amp;s=users\"><input type=\"text\" name=\"username\"/></td><td><input type=\"submit\" value=\"$txt_search\"/></form></td></tr>
<tr><td>$txt_ip:</td><td><form method=\"post\" action=\"admin.php?p=user&amp;s=ip\"><input type=\"text\" name=\"ip\"></td><td><input type=\"submit\" value=\"$txt_checkstatus\"/></td></tr>
</table></form></div></div>";

}

}
elseif($p == "forum") {
echo " $split $txt_forumadmin</font><p class=\"indent\">";


if($s == "deletef") {

if(isset($confirm)) {

if(!mysql_query("DELETE FROM ${table_prefix}forums WHERE forumno='$no'")) die(sql_error());

$postsremq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$no'");

for($i = 0; $i < mysql_num_rows($postsremq); $i++) {
$postsrem = mysql_result($postsremq, $i, "threadid");
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE msgnumber='$postsrem'")) die(sql_error());
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE reply='$postsrem'")) die(sql_error());
}

if(!mysql_query("DELETE FROM ${table_prefix}threads WHERE forum='$no'")) die(sql_error());

echo "$txt_forumdel <a href=\"admin.php?p=forum\">$txt_forumadmin</a>";
}
else {
echo "<font class=\"subhead\">$txt_warning</font><p class=\"indent\"/>

$txt_forumremwarn<p class=\"indent\"/>
<a href=\"admin.php?p=forum&amp;s=deletef&amp;no=$no&amp;confirm=yes\">$txt_yes</a> $split <a href=\"admin.php?p=forum\">$txt_no</a>";
}

}
elseif($s == "deletec") {

if(isset($confirm)) {

$getcatpos = mysql_query("SELECT catorder FROM ${table_prefix}categories WHERE catno='$no'");
$catpos = mysql_result($getcatpos, 0, 0);

$getcats = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$catpos'");

for ($j = 0; $j < mysql_num_rows($getcats); $j++) {

$curfor = mysql_result($getcats, $j, "forumno");

if(!mysql_query("DELETE FROM ${table_prefix}forums WHERE forumno='$curfor'")) die(sql_error());

$postsremq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$curfor'");

for($i = 0; $i < mysql_num_rows($postsremq); $i++) {
$postsrem = mysql_result($postsremq, $i, "threadid");
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE msgnumber='$postsrem'")) die(sql_error());
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE reply='$postsrem'")) die(sql_error());
}

if(!mysql_query("DELETE FROM ${table_prefix}threads WHERE forum='$curfor'")) die(sql_error());


}

if(!mysql_query("DELETE FROM ${table_prefix}categories WHERE catno='$no'")) die(sql_error());

echo "$txt_catdel <a href=\"admin.php?p=forum\">$txt_forumadmin</a>";
}
else {
echo "<font class=\"subhead\">$txt_warning</font><p class=\"indent\">

$txt_catremwarn<p class=\"indent\"/>

<a href=\"admin.php?p=forum&amp;s=deletec&amp;no=$no&amp;confirm=yes\">$txt_yes</a> $split <a href=\"admin.php?p=forum\">$txt_no</a>";

}

}
elseif($s == "addf") {
echo "<font class=\"subhead\">$txt_addforum</font><p class=\"indent\"/>";

if(isset($forname) && isset($fordesc) && $forname != "") {

$checkq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$forcat' ORDER BY fororder DESC");
$cur = mysql_result($checkq, 0, "fororder");
$fororder = $cur + 1;

for ($checking = 0; $checking < mysql_num_rows($checkq); $checking++) {

$forumname = mysql_result($checkq, $checking, "forumname");

if ($forumname == $forname) $already = 1;

}

$forums_tablename = "${table_prefix}forums";
$forums_table_def = "NULL, '$forname', '$fordesc', '$forrest', 0, '$fororder', '$forcat'";

if ($already == 1) {
echo "$txt_forumexists $txt_goback";

}
else {

if(!mysql_query("INSERT INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "$txt_forumcreated <a href=\"admin.php?p=forum\">$txt_forumadmin</a>";
}
}

else {

echo "<div id=\"central\"><div class=\"boxes\"><form action=\"admin.php?p=forum&amp;s=addf\" method=\"post\"><table cellspacing=\"0\" width=\"100%\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_forumname:</td><td class=\"boxrt\"><input type=\"text\" name=\"forname\"/></td></tr>
<tr><td class=\"boxhd\">$txt_forumdesc:</td><td class=\"boxrt\"><input type=\"text\" name=\"fordesc\"/></td></tr>
<tr><td class=\"boxhd\">$txt_restaccessq</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"forrest\" value=\"1\"/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"forrest\" value=\"0\" checked=\"checked\"/> $txt_no</td></tr>
<tr><td class=\"boxhd\">$txt_cat:</td><td class=\"boxrt\"><select name=\"forcat\">";

$catsq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

for($i = 0; $i < mysql_num_rows($catsq); $i++) {

$catorder = mysql_result($catsq, $i, "catorder");
$catname = mysql_result($catsq, $i, "catname");

echo "<option value=\"$catorder\">$catname</option>";

}

echo "</select></td></tr>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_createforum\"/></td></tr>
</table></form></div></div>";
}
}

elseif($s == "addc") {
echo "<font class=\"subhead\">$txt_addcat</font><p class=\"indent\"/>";

if(isset($catname) && $catname != "") {

$checkq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder DESC");
$cur = mysql_result($checkq, 0, "catorder");
$catorder = $cur + 1;

for ($checking = 0; $checking < mysql_num_rows($checkq); $checking++) {

$categname = mysql_result($checkq, $checking, "catname");

if ($categname == $catname) $already = 1;

}

$categories_tablename = "${table_prefix}categories";
$categories_table_def = "NULL, '$catname', '$catorder'";

if ($already == 1) {
echo "$txt_catexists $txt_goback";

}
else {

if(!mysql_query("INSERT INTO $categories_tablename VALUES($categories_table_def)")) die(sql_error());

echo "$txt_catcreated <a href=\"admin.php?p=forum\">$txt_forumadmin</a>";
}
}

else {

$catnameq = mysql_query("SELECT catname FROM ${table_prefix}categories WHERE catorder = '$no'");
$catname = mysql_result($catnameq, 0, 0);

echo "<div id=\"central\"><div class=\"boxes\"><form action=\"admin.php?p=forum&amp;s=addc\" method=\"post\"><table cellspacing=\"0\" width=\"100%\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_catname:</td><td class=\"boxrt\" width=\"80%\"><input type=\"text\" name=\"catname\" value=\"$catname\"/></td></tr>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_catcreate\"/></td></tr>
</table></form></div></div>";
}
}

elseif($s == "editc") {
echo "<font class=\"subhead\">$txt_editcat</font><p class=\"indent\"/>";

if(isset($catname) && $catname != "") {

$checkq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder DESC");

for ($checking = 0; $checking < mysql_num_rows($checkq); $checking++) {

$categname = mysql_result($checkq, $checking, "catname");

if ($categname == $catname) $already = 1;

}

$categories_tablename = "${table_prefix}categories";
$categories_table_def = "'$catno', '$catname', '$catorder'";

if ($already == 1) {
echo "$txt_catexists $txt_goback";

}
else {

if(!mysql_query("REPLACE INTO $categories_tablename VALUES($categories_table_def)")) die(sql_error());

echo "$txt_catedited <a href=\"admin.php?p=forum\">$forumadmin</a>";
}
}
else {

$catnameq = mysql_query("SELECT * FROM ${table_prefix}categories WHERE catorder = '$no'");
$catno = mysql_result($catnameq, 0, "catno");
$catname = mysql_result($catnameq, 0, "catname");
$catorder = mysql_result($catnameq, 0, "catorder");

echo "<div id=\"central\"><div class=\"boxes\"><form action=\"admin.php?p=forum&amp;s=editc\" method=\"post\"><table cellspacing=\"0\" width=\"100%\">
<tr><td width=\"20%\" class=\"boxhd\">$txt_catname:</td><td class=\"boxrt\"><input type=\"text\" name=\"catname\" value=\"$catname\"/></td></tr>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_editcat\"/><input type=\"hidden\" name=\"catno\" value=\"$catno\"/><input type=\"hidden\" name=\"catorder\" value=\"$catorder\"/>
</td></tr>
</table></form></div></div>";
}
}

elseif($s == "editf") {
echo "<font class=\"subhead\">$txt_editforum</font><p class=\"indent\"/>";

if(isset($forname) && $forname != "") {

$checkq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$forcat'");

for ($checking = 0; $checking < mysql_num_rows($checkq); $checking++) {

$forumname = mysql_result($checkq, $checking, "forumname");

if ($forumname == $forname && $forname != $oldforname) $already = 1;

}

$forums_tablename = "${table_prefix}forums";
$forums_table_def = "'$forno', '$forname', '$fordesc', '$forrest', '$forlocked', '$fororder', '$forcat'";

if ($already == 1) {
echo "$txt_forumexists $txt_goback";

}
else {

if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "$txt_forumedited <a href=\"admin.php?p=forum\">$txt_forumadmin</a>";
}
}
else {

$forumq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$no'");
$forumname = mysql_result($forumq, 0, "forumname");
$forumdesc = mysql_result($forumq, 0, "forumdesc");
$restricted = mysql_result($forumq, 0, "restricted");
$locked = mysql_result($forumq, 0, "locked");
$fororder = mysql_result($forumq, 0, "fororder");
$cat = mysql_result($forumq, 0, "cat");

echo "<div id=\"central\"><div class=\"boxes\"><form action=\"admin.php?p=forum&amp;s=editf\" method=\"post\"><table cellspacing=\"0\" width=\"100%\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_forumname:</td><td class=\"boxrt\" width=\"80%\"><input type=\"text\" name=\"forname\" value=\"$forumname\"/></td></tr>
<tr><td class=\"boxhd\">$txt_forumdesc:</td><td class=\"boxrt\"><input type=\"text\" name=\"fordesc\" value=\"$forumdesc\"/></td></tr>
<tr><td class=\"boxhd\">$txt_restaccessq</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"forrest\" value=\"1\"";

if($restricted == 1) echo " checked=\"checked\"";

echo "/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"forrest\" value=\"0\"";

if($restricted == 0) echo " checked=\"checked\"";

echo "/> $txt_no</td></tr>
<tr><td class=\"boxhd\">$txt_cat:</td><td class=\"boxrt\"><select name=\"forcat\">";

$catsq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

for($i = 0; $i < mysql_num_rows($catsq); $i++) {

$catorder = mysql_result($catsq, $i, "catorder");
$catname = mysql_result($catsq, $i, "catname");

echo "<option value=\"$catorder\"";

if($catorder == $cat) echo " selected=\"selected\"";

echo ">$catname</option>";

}

echo "</select></td></tr>


<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_editforum\"/><input type=\"hidden\" name=\"fororder\" value=\"$fororder\"/><input type=\"hidden\" name=\"forno\" value=\"$no\"/><input type=\"hidden\" name=\"locked\" value=\"$forlocked\"/><input type=\"hidden\" name=\"oldforname\" value=\"$forumname\"/>
</td></tr>
</table></form></div></div>";
}
}


else {
if($s == "moveupc") {

$catsq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

$oldcat = $no - 1;
$newcat = $no - 2;

$oldcatno = mysql_result($catsq, $oldcat, "catno");
$oldcatname = mysql_result($catsq, $oldcat, "catname");
$newcatno = mysql_result($catsq, $newcat, "catno");
$newcatname = mysql_result($catsq, $newcat, "catname");
$oldcatname = str_replace("'", "\'", $oldcatname);
$newcatname = str_replace("'", "\'", $newcatname);

$categories1_table_def = "'$oldcatno', '$oldcatname', '$oldcat'";
$categories2_table_def = "'$newcatno', '$newcatname', '$no'";

$categories_tablename = "${table_prefix}categories";
if(!mysql_query("REPLACE INTO $categories_tablename VALUES($categories1_table_def)")) die(sql_error());
if(!mysql_query("REPLACE INTO $categories_tablename VALUES($categories2_table_def)")) die(sql_error());

$forumsq = mysql_query("SELECT * FROM ${table_prefix}forums");

for ($i = 0; $i < mysql_num_rows($forumsq); $i++) {

$forumno = mysql_result($forumsq, $i, "forumno");
$forumname = mysql_result($forumsq, $i, "forumname");
$forumdesc = mysql_result($forumsq, $i, "forumdesc");
$restricted = mysql_result($forumsq, $i, "restricted");
$locked = mysql_result($forumsq, $i, "locked");
$fororder = mysql_result($forumsq, $i, "fororder");
$cat = mysql_result($forumsq, $i, "cat");

$forumname = str_replace("'", "\'", $forumname);
$forumdesc = str_replace("'", "\'", $forumdesc);

if($cat == $no) {

$forums_table_def = "'$forumno', '$forumname', '$forumdesc', '$restricted', '$locked', '$fororder', '$oldcat'";

$forums_tablename = "${table_prefix}forums";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

}

elseif($cat == $oldcat) {

$forums_table_def = "'$forumno', '$forumname', '$forumdesc', '$restricted', '$locked', '$fororder', '$no'";

$forums_tablename = "${table_prefix}forums";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

}

}


echo "<font class=\"emph\">$txt_catmoved</font><p class=\"indent\"/>";
}
elseif($s == "movedownc") {

$catsq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

$oldcat = $no - 1;
$newcat = $no;
$new = $no + 1;

$oldcatno = mysql_result($catsq, $oldcat, "catno");
$oldcatname = mysql_result($catsq, $oldcat, "catname");
$newcatno = mysql_result($catsq, $newcat, "catno");
$newcatname = mysql_result($catsq, $newcat, "catname");
$oldcatname = str_replace("'", "\'", $oldcatname);
$newcatname = str_replace("'", "\'", $newcatname);

$categories1_table_def = "'$oldcatno', '$oldcatname', '$new'";
$categories2_table_def = "'$newcatno', '$newcatname', '$no'";

$categories_tablename = "${table_prefix}categories";
if(!mysql_query("REPLACE INTO $categories_tablename VALUES($categories1_table_def)")) die(sql_error());
if(!mysql_query("REPLACE INTO $categories_tablename VALUES($categories2_table_def)")) die(sql_error());

$forumsq = mysql_query("SELECT * FROM ${table_prefix}forums");

for ($i = 0; $i < mysql_num_rows($forumsq); $i++) {

$forumno = mysql_result($forumsq, $i, "forumno");
$forumname = mysql_result($forumsq, $i, "forumname");
$forumdesc = mysql_result($forumsq, $i, "forumdesc");
$restricted = mysql_result($forumsq, $i, "restricted");
$locked = mysql_result($forumsq, $i, "locked");
$fororder = mysql_result($forumsq, $i, "fororder");
$cat = mysql_result($forumsq, $i, "cat");

$forumname = str_replace("'", "\'", $forumname);
$forumdesc = str_replace("'", "\'", $forumdesc);

if($cat == $no) {

$forums_table_def = "'$forumno', '$forumname', '$forumdesc', '$restricted', '$locked', '$fororder', '$new'";

$forums_tablename = "${table_prefix}forums";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

}

elseif($cat == $new) {

$forums_table_def = "'$forumno', '$forumname', '$forumdesc', '$restricted', '$locked', '$fororder', '$no'";

$forums_tablename = "${table_prefix}forums";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

}

}


echo "<font class=\"emph\">$txt_catmoved</font><p class=\"indent\"/>";
}
elseif($s == "moveupf") {

$curforq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$no'");
$forcat = mysql_result($curforq, 0, "cat");

$curforumno = mysql_result($curforq, 0, "forumno");
$curforumname = mysql_result($curforq, 0, "forumname");
$curforumdesc = mysql_result($curforq, 0, "forumdesc");
$currestricted = mysql_result($curforq, 0, "restricted");
$curlocked = mysql_result($curforq, 0, "locked");
$curfororder = mysql_result($curforq, 0, "fororder");

$curforumname = str_replace("'", "\'", $curforumname);
$curforumdesc = str_replace("'", "\'", $curforumdesc);

$newfororder = $curfororder - 1;

$newforq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$forcat' AND fororder='$newfororder'");

$newforumno = mysql_result($newforq, 0, "forumno");
$newforumname = mysql_result($newforq, 0, "forumname");
$newforumdesc = mysql_result($newforq, 0, "forumdesc");
$newrestricted = mysql_result($newforq, 0, "restricted");
$newlocked = mysql_result($newforq, 0, "locked");

$newforumname = str_replace("'", "\'", $newforumname);
$newforumdesc = str_replace("'", "\'", $newforumdesc);

$forums_tablename = "${table_prefix}forums";

$forums_table_def = "'$curforumno', '$curforumname', '$curforumdesc', '$currestricted', '$curlocked', '$newfororder', '$forcat'";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

$forums_table_def = "'$newforumno', '$newforumname', '$newforumdesc', '$newrestricted', '$newlocked', '$curfororder', '$forcat'";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "<font class=\"emph\">$txt_forummoved</font><p class=\"indent\"/>";
}

elseif($s == "movedownf") {

$curforq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$no'");
$forcat = mysql_result($curforq, 0, "cat");

$curforumno = mysql_result($curforq, 0, "forumno");
$curforumname = mysql_result($curforq, 0, "forumname");
$curforumdesc = mysql_result($curforq, 0, "forumdesc");
$currestricted = mysql_result($curforq, 0, "restricted");
$curlocked = mysql_result($curforq, 0, "locked");
$curfororder = mysql_result($curforq, 0, "fororder");

$curforumname = str_replace("'", "\'", $curforumname);
$curforumdesc = str_replace("'", "\'", $curforumdesc);

$newfororder = $curfororder + 1;

$newforq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$forcat' AND fororder='$newfororder'");

$newforumno = mysql_result($newforq, 0, "forumno");
$newforumname = mysql_result($newforq, 0, "forumname");
$newforumdesc = mysql_result($newforq, 0, "forumdesc");
$newrestricted = mysql_result($newforq, 0, "restricted");
$newlocked = mysql_result($newforq, 0, "locked");

$newforumname = str_replace("'", "\'", $newforumname);
$newforumdesc = str_replace("'", "\'", $newforumdesc);

$forums_tablename = "${table_prefix}forums";

$forums_table_def = "'$curforumno', '$curforumname', '$curforumdesc', '$currestricted', '$curlocked', '$newfororder', '$forcat'";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

$forums_table_def = "'$newforumno', '$newforumname', '$newforumdesc', '$newrestricted', '$newlocked', '$curfororder', '$forcat'";
if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "<font class=\"emph\">$txt_forummoved</font><p class=\"indent\"/>";
}

$catq = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder");

echo "<a href=\"admin.php?p=forum&amp;s=addc\">$txt_addcat</a>";
if(mysql_num_rows($catq) > 0) echo "<br/><a href=\"admin.php?p=forum&s=addf\">$txt_addforum</a>";
echo "<p class=\"indent\">";


for ($i = 0; $i < mysql_num_rows($catq); $i++) {

$catname = mysql_result($catq, $i, "catname");
$catno = mysql_result($catq, $i, "catno");
$catorder = mysql_result($catq, $i, "catorder");

echo "<font class=\"subhead\">$catname $split <a href=\"admin.php?p=forum&amp;s=editc&amp;no=$catno\">$txt_edit</a> $split ";

if($catorder != 1) {
echo "<a href=\"admin.php?p=forum&amp;s=moveupc&amp;no=$catorder\">$txt_moveup</a>";
}
else {
echo "<font class=\"emph\">$txt_moveup</font>";

}

echo " $split ";

if(mysql_num_rows($catq) - $i == 1) {
echo "<font class=\"emph\">$txt_movedown</font>";
}
else {
echo "<a href=\"admin.php?p=forum&amp;s=movedownc&amp;no=$catorder\">$txt_movedown</a>";
}

echo " $split <a href=\"admin.php?p=forum&amp;s=deletec&amp;no=$catno\">$txt_del</a></font><br/>";

$cat = $i + 1;

$forumsq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$cat' ORDER BY fororder");

for ($j = 0; $j < mysql_num_rows($forumsq); $j++) {

$forumno = mysql_result($forumsq, $j, "forumno");
$fororder = mysql_result($forumsq, $j, "fororder");
$forumname = mysql_result($forumsq, $j, "forumname");
echo "&nbsp;&nbsp;$forumname $split <a href=\"admin.php?p=forum&amp;s=editf&amp;no=$forumno\">$txt_edit</a> $split ";

if($fororder != 1) {
echo "<a href=\"admin.php?p=forum&amp;s=moveupf&amp;no=$forumno\">$txt_moveup</a>";
}
else {
echo "<font class=\"emph\">$txt_moveup</font>";
}
echo " $split ";

if(mysql_num_rows($forumsq) - $j == 1) {
echo "<font class=\"emph\">$txt_movedown</font>";
}
else {
echo "<a href=\"admin.php?p=forum&amp;s=movedownf&amp;no=$forumno\">$txt_movedown</a>";
}

echo " $split <a href=\"admin.php?p=forum&amp;s=deletef&amp;no=$forumno\">$txt_del</a><br/>";
}

echo "<br/>";

}
}

}
elseif($p == "site") {
echo " $split $txt_siteadmin</font><p class=\"indent\"/>";

if(isset($submit)) {

$filecontents = "<?

\$language = \"$newlanguage\";
\$sitename = \"$newsitename\";
\$template = \"$newtemplate\";
\$menutemplate = \"$newmenutemplate\";
\$deftimezone = \"$newdeftimezone\";
\$table_prefix = \"$table_prefix\";
\$dbhost = \"$dbhost\";
\$dbusername = \"$dbusername\";
\$dbuserpassword = \"$dbuserpassword\";
\$dbname = \"$dbname\";
\$postsperpage = \"$newpostsperpage\";
\$threadsperpage = \"$newthreadsperpage\";
\$hottopic = \"$newhottopic\";
\$guestpost = \"$newguestpost\";
\$siteemail = \"$newsiteemail\";
\$forumurl = \"$newforumurl\";
\$dateform = \"$newdateform\";
\$timeform = \"$newtimeform\";
\$split = \"$newsplit\";
\$avatars = \"$newavatars\";
\$gd = \"$newgd\";

?>";

$handle= fopen("settings.php","w");
fputs($handle, $filecontents);
fclose($handle);

echo "$txt_changessaved <a href=\"admin.php\">$txt_admincentre</a>";


}
else {

$sitename = str_replace("<","&lt;",$sitename);
$sitename = str_replace(">","&gt;",$sitename);
$sitename = str_replace("\"","&quot;",$sitename);
$siteemail = str_replace("<","&lt;",$siteemail);
$siteemail = str_replace(">","&gt;",$siteemail);
$siteemail = str_replace("\"","&quot;",$siteemail);
$menutemplate = str_replace("<","&lt;",$menutemplate);
$menutemplate = str_replace(">","&gt;",$menutemplate);
$menutemplate = str_replace("\"","&quot;",$menutemplate);
$split = str_replace("<","&lt;",$split);
$split = str_replace(">","&gt;",$split);
$split = str_replace("\"","&quot;",$split);

echo "<form action=\"admin.php?p=site\" method=\"post\"><div id=\"central\"><div class=\"boxes\"><table cellspacing=\"0\" width=\"100%\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_language:</td><td class=\"boxrt\" width=\"80%\"><select name=\"newlanguage\">";
$dirpos = "languages/";
$dir = opendir($dirpos);
while (false !== ($file = readdir($dir))) {
$dispfile = str_replace(".php", "", $file);
if($file != "." && $file != ".." && $file != "index.htm") {
echo "<option value=\"$dispfile\"";
if($dispfile == $language) echo " selected=\"selected\"";
echo">$dispfile</option>";
}
}
echo "</select></td></tr>
<tr><td class=\"boxhd\">$txt_sitename:</td><td class=\"boxrt\"><input type=\"text\" name=\"newsitename\" value=\"$sitename\"/></td></tr>
<tr><td class=\"boxhd\">$txt_forumurl:</td><td class=\"boxrt\"><input type=\"text\" name=\"newforumurl\" value=\"$forumurl\"/></td></tr>
<tr><td class=\"boxhd\">$txt_adminemail:</td><td class=\"boxrt\"><input type=\"text\" name=\"newsiteemail\" value=\"$siteemail\"/></td></tr>
<tr><td class=\"boxhd\">$txt_menutemplate:</td><td class=\"boxrt\"><input type=\"text\" name=\"newmenutemplate\" value=\"$menutemplate\"/></td></tr>
<tr><td class=\"boxhd\">$txt_splitter:</td><td class=\"boxrt\"><input type=\"text\" name=\"newsplit\" value=\"$split\"/></td></tr>
<tr><td class=\"boxhd\">$txt_template:</td><td class=\"boxrt\"><select name=\"newtemplate\">";

$handle = opendir('templates/');
while (false !== ($file = readdir($handle))) {
$file = str_replace("_h.php", "", $file);
if (!stristr($file,".css") && !stristr($file,".php")) {
if ($file != "index.htm" && $file != "." && $file != "..") {

echo "<option value=\"$file\"";

if ($template == $file) {
echo " selected=\"selected\"";
}

echo ">$file</option>";

}
}
}
closedir($handle);


echo "</select>
</td></tr>
<tr><td class=\"boxhd\">$txt_timedef:</td><td class=\"boxrt\"><select name=\"newdeftimezone\">";

for ($i = -24; $i <= 24; $i++) {

echo "<option value=\"$i\"";

if ($i == $deftimezone) echo " selected=\"selected\"";

echo ">$i</option>";

}

$current = date($timeform);

echo "</select></td></tr><tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\">($txt_currenttime $current)</td></tr>
<tr><td class=\"boxhd\">$txt_dateformat:</td><td class=\"boxrt\"><input type=\"text\" name=\"newdateform\" value=\"$dateform\"/></td></tr>
<tr><td class=\"boxhd\">$txt_timeformat:</td><td class=\"boxrt\"><input type=\"text\" name=\"newtimeform\" value=\"$timeform\"/></td></tr>
<tr><td class=\"boxhd\">$txt_postspage:</td><td class=\"boxrt\"><select name=\"newpostsperpage\"/>";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == $postsperpage) echo " selected=\"selected\"";

echo ">$i</option>";

}

echo "</select></td></tr>
<tr><td class=\"boxhd\">$txt_topicspage:</td><td class=\"boxrt\"><select name=\"newthreadsperpage\">";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == $threadsperpage) echo " selected=\"selected\"";

echo ">$i</option>";

}

echo "</select></td></tr>
<tr><td class=\"boxhd\">$txt_postshot:</td><td class=\"boxrt\"><select name=\"newhottopic\">";

for ($i = 1; $i <= 100; $i++) {
echo "<option value=\"$i\"";

if ($i == $hottopic) echo " selected=\"selected=\"";

echo ">$i</option>";

}

echo "</select></td></tr>
<tr><td class=\"boxhd\">$txt_guestspost</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"newguestpost\" value=\"1\"";
if($guestpost == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"newguestpost\" value=\"0\"";
if($guestpost == 0) {
echo " checked=\"checked\"";
}

echo "/> $txt_no</td></tr>
<tr><td class=\"boxhd\">$txt_avatarsshow</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"newavatars\" value=\"1\"";
if($avatars == 1) {
echo " checked=\"checked\"";
}
echo "/> $txt_yes <input type=\"radio\" class=\"noborder\" name=\"newavatars\" value=\"0\"";
if($avatars == 0) {
echo " checked=\"checked\"";
}

echo "/> $txt_no</td></tr>
<tr><td class=\"boxhd\">$txt_gdver:</td><td class=\"boxrt\"><select name=\"newgd\">
<option value=\"0\"";
if($gd == 0) echo " selected=\"selected\"";
echo ">$txt_none</option>
<option value=\"1\"";
if($gd == 1) echo " selected=\"selected\"";
echo ">1</option>
<option value=\"2\"";
if($gd == 2) echo " selected=\"selected\"";
echo ">2</option>
</select></td></tr>
<input type=\"hidden\" name=\"submit\" value=\"1\"/>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_savechanges\"/></td></tr>
</table></form></div></div>";
}
}
else {

echo "</font><p class=\"indent\"/><font class=\"subhead\">$txt_adminwelcome</font><p class=\"indent\"/>

<a href=\"admin.php?p=user\">$txt_useradmin</a><br/>
<a href=\"admin.php?p=forum\">$txt_forumadmin</a><br/>
<a href=\"admin.php?p=site\">$txt_siteadmin</a>";

}

}

else {
echo "<font class=\"header\"> $txt_error</font><p class=\"indent\"/>

$txt_adminonly";
}

include "footer.php";

?>