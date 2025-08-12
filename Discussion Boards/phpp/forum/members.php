<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
if (!isset($sort)) {
$sort = userid;
}
if (!isset($order)) {
$order = ASC;
}
$records = mysql_query("SELECT * FROM ${table_prefix}users WHERE registerdate != '0000-00-00' ORDER BY $sort $order");
$total = mysql_num_rows($records);
if ($total == 1) {
$memtxt = $txt_member;
}
else {
$memtxt = $txt_members;
}
$pagetitle= "$sitename $split $txt_memberlist ($total $memtxt)";

include "template.php";
include "timeconvert.php";


echo "<font class=\"header\">Member list ($total $memtxt)</font><p/>";

echo "<div id=\"central\">

<div class=\"infobox\"><form method=\"get\" action=\"members.php\"><table><tr><td align=\"right\">$txt_sortby: 
<select name=\"sort\">
<option value=\"userid\">$txt_username</option>
<option value=\"username\">$txt_realname</option>
<option value=\"usersex\">$txt_sex</option>
<option value=\"userdob\">$txt_dob</option>
</select>
<input type=\"radio\" class=\"noborder\" name=\"order\" value=\"ASC\" checked=\"checked\"/>$txt_asc <input type=\"radio\" class=\"noborder\" name=\"order\" value=\"DESC\"/> $txt_desc <input type=\"submit\" value=\"$txt_sort\"/></td></tr></table></form></div>

<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">
<tr><td class=\"boxhd\">$txt_username</td><td class=\"boxhd\">$txt_realname</td><td class=\"boxhd\">$txt_email</td><td class=\"boxhd\">$txt_sex</td><td  class=\"boxhd\">$txt_dob</td><td class=\"boxhdrt\">$txt_totposts</td></tr>";

for ($i = 0; $i < $total; $i++) {

$userid = mysql_result($records, $i, "userid");
$username = mysql_result($records, $i, "username");
$useremail = mysql_result($records, $i, "useremail");
$dispemail = mysql_result($records, $i, "dispemail");
$usersex = mysql_result($records, $i, "usersex");
$userdob = mysql_result($records, $i, "userdob");

$posts = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$userid'");
$userposts = mysql_num_rows($posts);

if ($usersex == "0" || empty($usersex)) {
$usersex = "<font class=\"emph\">$txt_notspec</font>";
}
elseif ($usersex == "1") {
$usersex = "$txt_male";
}
elseif ($usersex == "2") {
$usersex = "$txt)female";
}
if ($dispemail == "1") {
$useremail = "<a href=\"mail.php?user=$userid\">$txt_pmsend</a>";
}
else {
$useremail = "<font class=\"emph\">$txt_private</font>";
}
if($username == "") $username = "&nbsp;";

echo "<tr><td class=\"box\"><a href=\"profile.php?user=$userid\">$userid</a></td><td class=\"box\">$username</td><td class=\"box\">$useremail</td><td  class=\"box\">$usersex&nbsp;</td><td class=\"box\">";
if ($userdob == "0000-00-00" || empty($userdob)) {
echo "<font class=\"emph\">$txt_notspec</font>";
}
else {
converttime($userdob, 3);
}
echo "</td><td class=\"boxrt\">$userposts</td></tr>";
}

echo "</table></div></div>";

include "footer.php";
?>