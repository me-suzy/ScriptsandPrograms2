<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
$pagetitle = "$sitename $split $txt_search";
include "template.php";
include "timeconvert.php";
include "msg.php";

$allowed = 0;

echo "<font class=\"header\">$txt_search</font><p class=\"indent\"/>";

if(isset($q) || isset($u)) {
$q = str_replace("<", "&lt;", $q);
$q = str_replace(">", "&gt;", $q);


if(isset($q) && $q != "" && strlen($q) < 3) {
echo "$txt_stoshort $txt_goback";
}
else {

echo "<font class=\"subhead\">$txt_sfor <font class=\"emph\">";
if($q != "") {
$qprint = str_replace("\\", "", $q);
echo $qprint;
}
else {
echo "$txt_sallterms";
}
echo "</font> ";

if($x == "y") {
echo "$txt_sexact ";
}

echo "$txt_in ";
if($s == "y") {
echo "$txt_ssubmsg ";
}
else {
echo "$txt_smsg ";
}
echo "$txt_by <font class=\"emph\">";
if($u != "") {
echo $u;
}
else {
echo "$txt_sall";
}
echo "</font> $txt_in <font class=\"emph\">";
if($f != "") {
echo $f;
}
else {
echo "$txt_sallforums";
}
echo "</font>.";

if ($admincheck == 1) {
$allowed = 1;
}

if ($x != "y") {
$q = str_replace(" ", "%", $q);
}

if($q != "" && $s == "y" && $u == "") {
$results = mysql_query("SELECT * FROM ${table_prefix}public WHERE message LIKE '%$q%' OR subject LIKE '%$q%' ORDER BY posttime DESC");
}
elseif($q != "" && $s != "y" && $u == "") {
$results = mysql_query("SELECT * FROM ${table_prefix}public WHERE message LIKE '%$q%' ORDER BY posttime DESC");
}
elseif($q != "" && $s == "y" && $u != "") {
$results = mysql_query("SELECT * FROM ${table_prefix}public WHERE (message LIKE '%$q%' OR subject LIKE '%$q%') AND userfrom = '$u' ORDER BY posttime DESC");
}
elseif($q != "" && $s != "y" && $u != "") {
$results = mysql_query("SELECT * FROM ${table_prefix}public WHERE message LIKE '%$q%' AND userfrom = '$u' ORDER BY posttime DESC");
}
elseif($q == "" && $u != "") {
$results = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom = '$u' ORDER BY posttime DESC");
}

if($f != "all") {
$tot = 0;
for ($i = 0; $i < mysql_num_rows($results); $i++) {

$isreply = mysql_result($results, $i, "reply");
if($isreply == 0) $themsg = mysql_result($results, $i, "msgnumber");
else $themsg = mysql_result($results, $i, "reply");

$forumq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$themsg'");

if(mysql_result($forumq, 0, "forum") == $f) $tot++;

}
}
else {
$tot = mysql_num_rows($results);
}

echo " $tot $txt_sresults</font><p/><div id=\"central\">";

for($i = 0; $i < mysql_num_rows($results); $i++) {

$msgnumber = mysql_result($results, $i, "msgnumber");
$subject = mysql_result($results, $i, "subject");
$message = mysql_result($results, $i, "message");
$userfrom = mysql_result($results, $i, "userfrom");
$reply = mysql_result($results, $i, "reply");

$posttimeq = mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$msgnumber'");
$posttime = mysql_result($posttimeq, 0, 0);

if($reply == 0) {
$msgid = $msgnumber;
}
else {
$msgid = $reply;
}

$threadq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid = '$msgid'");
$forum = mysql_result($threadq, 0, "forum");

$forumq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno = '$forum'");
$forumname = mysql_result($forumq, 0, "forumname");

$restricted = mysql_result($forumq, 0, "restricted");

if($restricted == "1") {
$rights = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$rights = mysql_result($rights, 0, "access");

if(strstr($rights, " $forum,")) {
$allowed = 1;
}
else {
$allowed = 0;
}
}

if ($restricted != "1" || $allowed == 1) {

echo "<div class=\"boxes\"><table cellspacing=\"0\" width=\"100%\"><tr><td valign=\"top\" rowspan=\"2\" class=\"box\" width=\"20%\"><font class=\"useremph\">$userfrom</font><br/>$subject</td><td valign=\"top\" width=\"80%\" class=\"boxrt\">";

replacestuff($message);

echo "<p/></td></tr><tr><td class=\"boxrt\">$txt_sposted ";
echo date($timeform, $posttime + (3600 * $zone));
echo " $split <a href=\"view.php?forum=$forum&amp;msgid=$msgid\">$txt_sgotopic</a></td></tr></table></div>";
}
}
echo "</div>";
}
}
else {
echo "$txt_schoose<p/>

<div id=\"central\"><div class=\"boxes\"><form action=\"search.php\" method=\"post\"><table width=\"100%\" cellspacing=\"0\">
<tr><td class=\"boxhd\" width=\"20%\">$txt_stext:</td><td class=\"boxrt\"><input type=\"text\" size=\"40\" name=\"q\"/> <input type=\"checkbox\" name=\"x\" value=\"y\" class=\"noborder\"/> $txt_sexactq</td></tr>
<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"radio\" class=\"noborder\" name=\"s\" value=\"y\" checked=\"checked\"/> $txt_ssubmsg <input type=\"radio\" class=\"noborder\" name=\"s\" value=\"n\"/> $txt_smsg</td></tr>
<tr><td class=\"boxhd\">$txt_username:</td><td class=\"boxrt\"><input type=\"text\" name=\"u\"/></td></tr>
<tr><td class=\"boxhd\">$txt_forumname:</td><td class=\"boxrt\"><select name=\"f\"><option value=\"all\">$txt_sallforums</option>";

$forums = mysql_query("SELECT * FROM ${table_prefix}forums ORDER BY forumno ASC");
for ($i = 0; $i < mysql_num_rows($forums); $i++) {

$restricted = mysql_result($forums, $i, "restricted");
$rights = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$rights = mysql_result($rights, 0, "access");
$j = $i + 1;
if(strstr($rights, " $j,")) {
$allowed = 1;
}
else {
$allowed = 0;
}

$forumno = $i + 1;
$forumname = mysql_result($forums, $i, "forumname");

if($restricted == 0 || ($admincheck == 1 || $allowed == 1)) {

echo "<option value=\"$forumno\">$forumname</option>";

}
}

echo "</select></td></tr>

<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><input type=\"submit\" value=\"$txt_search\"/></td></tr></table></form></div></div>";

}

include "footer.php";
?>