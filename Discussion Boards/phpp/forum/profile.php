<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

include "header.php";
$pagetitle = "$sitename $split $txt_profile ($user)";
include "template.php";
include "timeconvert.php";

if(!mysql_select_db($dbname)) die(sql_error());

$records = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$user'");

$posts = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$user'");

$userpostcount = mysql_num_rows($posts);

echo "<font class=\"header\">$txt_profile ($user)</font><p/>";
$username = mysql_result($records, 0, "username");
$dispemail = mysql_result($records, 0, "dispemail");
$useremail = mysql_result($records, 0, "useremail");
$registerdate = mysql_result($records, 0, "registerdate");
$usermsn = mysql_result($records, 0, "usermsn");
$useraol = mysql_result($records, 0, "useraol");
$usericq = mysql_result($records, 0, "usericq");
$useryahoo = mysql_result($records, 0, "useryahoo");
$userhomepage = mysql_result($records, 0, "userhomepage");
$userdob = mysql_result($records, 0, "userdob");
$usersex = mysql_result($records, 0, "usersex");
if ($usersex == 1) {
$usersex = "$txt_male";
}
elseif ($usersex == 2) {
$usersex = "$txt_female";
}
else {
$usersex = "<font class=\"emph\">$txt_notspec</font>";
}
$userprofile = mysql_result($records, 0, "userprofile");
$usercountry = mysql_result($records, 0, "usercountry");

if ($userhomepage != "" && !strstr($userhomepage, "http://")) {
$userhomepage = "http://".$userhomepage;
}

if(mysql_num_rows($records) == 1 && $registerdate != "0000-00-00") {

echo "<div id=\"central\"><div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_realname:</td><td class=\"boxrt\">$username</td></tr>
<tr><td class=\"boxhd\">$txt_registertm:</td><td class=\"boxrt\">";

converttime($registerdate, 1);

echo "</td></tr>
<tr><td class=\"boxhd\">$txt_totposts:</td><td class=\"boxrt\">$userpostcount <a href=\"search.php?q=&amp;s=y&amp;u=$user&amp;f=all\">($txt_allfromuser)</a></td></tr>
<tr><td class=\"boxhd\">$txt_email:</td><td class=\"boxrt\">";

if ($dispemail == 1) {
echo "<a href=\"mail.php?user=$user\">$txt_pmsend</a>";
}

else {
echo "<font class=\"emph\">$txt_private</font>";
}

echo " <a href=\"pm.php?s=o&amp;replyuser=$user\">($txt_sendpm)</a></td></tr>
<tr><td class=\"boxhd\">$txt_homepage:</td><td class=\"boxrt\">";

if ($userhomepage != "") {
echo "<a href=\"$userhomepage\" target=\"_blank\">$userhomepage</a>";
}
else {
echo "&nbsp;";
}

$userprofile = str_replace("\n", "<br/>", $userprofile);
echo "</td></tr>
<tr><td class=\"boxhd\">$txt_aol:</td><td class=\"boxrt\">";
if($useraol != "") echo "<a href=\"aim:goim?screenname=$useraol&message=Hello...+Are+you+there?\">$useraol</a>";
else echo "&nbsp;";
echo "</td></tr>
<tr><td class=\"boxhd\">$txt_msn:</td><td class=\"boxrt\">$usermsn</td></tr>
<tr><td class=\"boxhd\">$txt_icq:</td><td class=\"boxrt\">$usericq</td></tr>
<tr><td class=\"boxhd\">$txt_yahoo!:</td><td class=\"boxrt\">";
if($useryahoo != "") echo "<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=$useryahoo&.src=pg\">$useryahoo</a>";
else echo "&nbsp;";
echo "</td></tr>
<tr><td class=\"boxhd\">$txt_sex:</td><td class=\"boxrt\">$usersex</td></tr>
<tr><td class=\"boxhd\">$txt_location:</td><td class=\"boxrt\">$usercountry</td></tr>
<tr><td class=\"boxhd\">$txt_dob:</td><td class=\"boxrt\">";

if ($userdob == "0000-00-00" || empty($userdob)) {
echo "<font class=\"emph\">$txt_notspec</font>";
}
else {
converttime($userdob, 3);
}

echo "</td></tr>
<tr><td class=\"boxhd\" valign=\"top\">$txt_profile:</td><td class=\"boxrt\"	>";
if(file_exists("gfx/avatars/$user.gif") && $avatars == "1") echo "<img src=\"gfx/avatars/$user.gif\" align=\"left\" class=\"avatar\" alt=\"$txt_userposted\"/><p/>";
echo "$userprofile</td></tr>

</table></div>
</div>
";

}

else {
echo "$txt_userguest";
}

include "footer.php";
?>