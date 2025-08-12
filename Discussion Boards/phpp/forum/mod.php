<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "protection.php";
include "header.php";
$forumname = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumname, 0, "forumname");
$pagetitle = "$sitename $split $forumname $split ";
if($t == "move") $pagetitle .= "$txt_movetopic";
elseif($t == "delete") $pagetitle .= "$txt_deltopic";
elseif ($t == "ip") {
if ($mode == "ban") $pagetitle .= "$txt_bancontrol";
else $pagetitle .= "$txt_iplookup";
}
elseif($t == "lock") $pagetitle .= "$txt_locktopic";
elseif($t == "lockforum") $pagetitle .= "$txt_lockforum";
elseif($t == "unlock") $pagetitle .= "$txt_unlocktopic";
elseif($t == "unlockforum") $pagetitle .= "$txt_unlockforum";
else $pagetitle .= "$txt_error";

include "template.php";
include "timeconvert.php";

$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$theaccess = mysql_result($access, 0, "mod");

if(strstr($theaccess, " $forum,")) {
$mod = 1;
}
else {
$mod = 0;
}

if($t == "ip") {
if ($theaccess != "") $mod = 1;
}

if ($mod == 1 || $admincheck == 1) {


$topicname = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$msgid'");
$topicname = mysql_result($topicname, 0, "subject");

if ($t == "move") {
echo "<font class=\"header\"><a href=\"view.php?forum=$forum\>$forumname</a> $split $txt_movetopic</font><p class=\"indent\"/>";

echo "#$msgid - $topicname<p class=\"indent\"/>";

if ($submit == "yes") {

$posttomove = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid' AND forum='$forum'");
if(mysql_num_rows($posttomove) != 1) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_postnotexist";
}
else {

if(strstr($theaccess, " $forum,") AND strstr($theaccess, " $newforum,")) {
$modallowed = 1;
}

if ($modallowed == 1 || $admincheck == 1) {
$lastrepid = mysql_result($posttomove, 0, "lastrepid");
$lastreptime = mysql_result($posttomove, 0, "lastreptime");
$number = mysql_result($posttomove, 0, "number");
$locked = mysql_result($posttomove, 0, "locked");
$type = mysql_result($posttomove, 0, "type");

$threads_tablename = "${table_prefix}threads";
$threads_table_def = "'$msgid', '$newforum', '$lastrepid', '$lastreptime', '$number', '$locked', '$type'";

if(!mysql_query("REPLACE INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());

echo "<font class=\"subhead\">$txt_changessaved</font><p class=\"indent\"/>$txt_postmoved <a href=\"view.php?forum=$newforum&msgid=$msgid\">$txt_viewpost</a>";
}

else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_movenorights";
}
}
}

else {

echo "$txt_selectforum

<form method=\"post\" action=\"mod.php?t=move\">
<select name=\"newforum\">";

$forums = mysql_query("SELECT * FROM ${table_prefix}forums");

for ($i = 0; $i < mysql_num_rows($forums); $i++) {

$j = $i + 1;
$forumname = mysql_result($forums, $i, "forumname");

if(strstr($theaccess, " $j,")) {
$display = 1;
}
else {
$display = 0;
}

if ($display == 1 || $admincheck == 1) {
echo "<option value=\"$j\">$forumname</option>";
}
}
echo "</select><br/><input type=\"submit\" value=\"$txt_movetopic\"/>
<input type=\"hidden\" name=\"submit\" value=\"yes\"/>
<input type=\"hidden\" name=\"msgid\" value=\"$msgid\"/>
<input type=\"hidden\" name=\"forum\" value=\"$forum\"/>
</form>";
}
}
elseif ($t == "delete") {
echo "<font class=\"header\"><a href=\"view.php?forum=$forum\">$forumname</a> $split $txt_deltopic</font><p class=\"indent\"/>";

if ($admincheck == 1) {

$posttodel = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid' AND forum='$forum'");
if(mysql_num_rows($posttodel) != 1) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_postnotexist";
}
else {
if(!mysql_query("DELETE FROM ${table_prefix}threads WHERE threadid='$msgid'")) die(sql_error());
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE msgnumber='$msgid'")) die(sql_error());
if(!mysql_query("DELETE FROM ${table_prefix}public WHERE reply='$msgid'")) die(sql_error());
echo "$txt_topicdel <a href=\"view.php?forum=$forum\">$txt_forumreturn</a>";
}
}
else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_delnorights <a href=\"view.php?forum=$forum&msgid=$msgid\">$txt_topicreturn</a>";
}

}

elseif ($t == "ip") {
echo "<font class=\"header\">";
if ($mode == "ban") {
echo "$txt_bancontrol";
}
else {
echo "$txt_iplookup";
}
echo "</font><p class=\"indent\"/>";

if ($mode == "ban") {

if ($admincheck == 1) {

if(isset($ip)) {

$checknotset = mysql_query("SELECT * FROM ${table_prefix}ipbans WHERE ip='$ip'");
if(mysql_num_rows($checknotset) > 0) {
echo "IP <font class=\"emph\">$ip</font> $txt_bannedalready";
}
else {
$ipbans_tablename = "${table_prefix}ipbans";
$ipbans_table_def = "NULL, '$ip'";
if(!mysql_query("INSERT INTO $ipbans_tablename VALUES($ipbans_table_def)")) die(sql_error());
echo "$txt_ipban<p class=\"indent\"/>$txt_cont";
}
}

elseif(isset($user)) {
$adminq = mysql_query("SELECT admin FROM ${table_prefix}userrights WHERE userid='$user'");
$isadmin = mysql_result($adminq, 0, 0);
if($isadmin != "1") {
$record = mysql_query("SELECT * FROM ${table_prefix}userbans WHERE user='$user'");
if(mysql_num_rows($record) > 0) echo "<font class=\"emph\">$user</font> $txt_bannedalready";
else {
$userbans_tablename = "${table_prefix}userbans";
$userbans_table_def = "NULL, '$user'";
if(!mysql_query("INSERT INTO $userbans_tablename VALUES($userbans_table_def)")) die(sql_error());
echo "$txt_userbanned";
}
}
else {
echo "$txt_cantbanadmin";
}

}

}
else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_mustbeadmin";
}
}

else {

if ($a == "ip") {

$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$msgid'");

$ip = mysql_result($records, 0, "ip");
$topic = mysql_result($records, 0, "subject");
$userpost = mysql_result($records, 0, "userfrom");

echo "#$msgid - $topic - $txt_postedby $userpost $txt_from $ip.<p class=\"indent\">

<a href=\"mod.php?t=ip&amp;ip=$ip\">$txt_allfromip</a> $split <a href=\"mod.php?t=ip&amp;user=$userpost\">$txt_allfromuser</a>";

if ($admincheck == 1) {
echo "<br/>
<a href=\"mod.php?t=ip&amp;ip=$ip&amp;mode=ban\">$txt_banip</a> $split <a href=\"mod.php?t=ip&amp;user=$userpost&amp;mode=ban\">$txt_banuser</a>";
}

}

elseif (isset($ip)) {

$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE ip='$ip' ORDER BY userfrom ASC");

echo "<font class=\"subhead\">$txt_fromip $ip:</font><p class=\"indent\"/>

<div id=\"central\"><div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">
<tr><td class=\"boxhd\">$txt_username</td><td class=\"boxhd\">$txt_forumname</td><td class=\"boxhd\">$txt_subject</td><td class=\"boxhdrt\">$txt_time</td></tr>";

for ($i = 0; $i < mysql_num_rows($records); $i++) {

$msgid = mysql_result($records, $i, "msgnumber");
$topic = mysql_result($records, $i, "subject");
$userpost = mysql_result($records, $i, "userfrom");
$reply = mysql_result($records, $i, "reply");

$result=mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE ip='$ip' ORDER by userfrom ASC"); 
$posttime = mysql_result ($result, $i, 0);

if($reply != "0") $thread = $reply;
else $thread = $msgid;

$threadcheck = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$thread'");
$forum = mysql_result($threadcheck, 0, "forum");

$forumcheck = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumcheck, 0, "forumname");

echo "<tr><td class=\"box\"><a href=\"mod.php?t=ip&amp;user=$userpost\">$userpost</a></td><td class=\"box\"><a href=\"view.php?forum=$forum\">$forumname</a></td><td class=\"box\"><a href=\"view.php?forum=$forum&amp;msgid=$thread\">$topic</a></td><td class=\"boxrt\">";

converttime($posttime, 0, $zone);
echo "</td></tr>";

}

echo "</table></center>";

}
elseif (isset($user)) {
echo "$txt_ipused $user:<p class=\"indent\"/>";

$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$user' ORDER BY ip ASC");

echo "<center><table width=85% class=border cellspacing=0 cellpadding=3 border=0>
<tr><td class=msghd>$txt_ip</td><td class=msghd>$txt_forumname</td><td class=msghd>$txt_subject</td><td><b>$txt_time</b></td></tr>";

for ($i = 0; $i < mysql_num_rows($records); $i++) {

$msgid = mysql_result($records, $i, "msgnumber");
$topic = mysql_result($records, $i, "subject");
$ip = mysql_result($records, $i, "ip");
$reply = mysql_result($records, $i, "reply");

$result=mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE userfrom='$user' ORDER BY ip ASC"); 
$posttime = mysql_result ($result, $i, 0);

if($reply != "0") $thread = $reply;
else $thread = $msgid;

$threadcheck = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$thread'");
$forum = mysql_result($threadcheck, 0, "forum");

$forumcheck = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumcheck, 0, "forumname");

echo "<tr><td class=msg><a href=\"mod.php?t=ip&ip=$ip\">$ip&nbsp;</a></td><td class=msg><a href=\"view.php?forum=$forum\">$forumname&nbsp;</a></td><td class=msg><a href=\"view.php?forum=$forum&msgid=$thread\">$topic&nbsp;</a></td><td class=msgrt>";

converttime($posttime, 0, $zone);

echo "</td></tr>";

}

echo "</table></center>";

}
}
}

elseif ($t == "lock") {
echo "<font class=\"header\"><a href=\"view.php?forum=$forum\">$forumname</a> $split $txt_locktopic</font><p class=\"indent\"/>";

$posttolock = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid' AND forum='$forum'");
if(mysql_num_rows($posttolock) != 1) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_postnotexist";
}
else {

if(strstr($theaccess, " $forum,")) {
$modallowed = 1;
}

if ($modallowed == 1 || $admincheck == 1) {
$lastrepid = mysql_result($posttolock, 0, "lastrepid");
$lastreptime = mysql_result($posttolock, 0, "lastreptime");
$number = mysql_result($posttolock, 0, "number");
$type = mysql_result($posttolock, 0, "type");

$threads_tablename = "${table_prefix}threads";
$threads_table_def = "'$msgid', '$forum', '$lastrepid', '$lastreptime', '$number', '1', '$type'";

if(!mysql_query("REPLACE INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());

echo "<font class=\"subhead\">$txt_changessaved</font><p class=\"indent\"/> <a href=\"view.php?forum=$forum&msgid=$msgid\">$txt_viewtopic</a>";
}

else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_norightslock";
}
}

}
elseif($t == "lockforum") {
echo "<font class=\"header\"><a href=\"index.php\">$sitename</a> $split <a href=\"view.php?forum=$forum\">$forumname</a> $split $txt_lockforum</font><p class=\"indent\"/>";
if ($admincheck == 1) {

$forumdetails = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumdetails, 0, "forumname");
$forumdesc = mysql_result($forumdetails, 0, "forumdesc");
$restricted = mysql_result($forumdetails, 0, "restricted");
$fororder = mysql_result($forumdetails, 0, "fororder");
$cat = mysql_result($forumdetails, 0, "cat");

$forumname = str_replace("'", "\'", $forumname);
$forumdesc = str_replace("'", "\'", $forumdesc);

$forums_tablename = "${table_prefix}forums";
$forums_table_def = "'$forum', '$forumname', '$forumdesc', '$restricted', '1', '$fororder', '$cat'";

if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "$txt_forumlocked <a href=\"index.php\">$txt_cont</a>";

}

else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_adminlockforums";
}
}
elseif($t == "unlockforum") {
echo "<font class=\"header\"><a href=\"view.php?forum=$forum\">$forumname</a> $split $txt_unlockforum</font><p class=\"indent\">";
if ($admincheck == 1) {
$forumdetails = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumdetails, 0, "forumname");
$forumdesc = mysql_result($forumdetails, 0, "forumdesc");
$restricted = mysql_result($forumdetails, 0, "restricted");
$fororder = mysql_result($forumdetails, 0, "fororder");
$cat = mysql_result($forumdetails, 0, "cat");

$forumname = str_replace("'", "\'", $forumname);
$forumdesc = str_replace("'", "\'", $forumdesc);

$forums_tablename = "${table_prefix}forums";
$forums_table_def = "'$forum', '$forumname', '$forumdesc', '$restricted', '0', '$fororder', '$cat'";

if(!mysql_query("REPLACE INTO $forums_tablename VALUES($forums_table_def)")) die(sql_error());

echo "$txt_forumunlocked <a href=\"index.php\">$txt_cont</a>";
}
else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_adminunlockforums";
}
}

elseif ($t == "unlock") {
echo "<font class=\"header\"><a href=\"index.php\">$sitename</a> $split <a href=\"view.php?forum=$forum\">$forumname</a> $split $txt_unlocktopic</font><p class=\"indent\"/>";

$posttolock = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid' AND forum='$forum'");
if(mysql_num_rows($posttolock) != 1) {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_postnotexist";
}
else {

if(strstr($theaccess, " $forum,")) {
$modallowed = 1;
}

if ($modallowed == 1 || $admincheck == 1) {
$lastrepid = mysql_result($posttolock, 0, "lastrepid");
$lastreptime = mysql_result($posttolock, 0, "lastreptime");
$number = mysql_result($posttolock, 0, "number");
$type = mysql_result($posttolock, 0, "type");

$threads_tablename = "${table_prefix}threads";
$threads_table_def = "'$msgid', '$forum', '$lastrepid', '$lastreptime', '$number', '0', '$type'";

if(!mysql_query("REPLACE INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());

echo "<font class=\"subhead\">$txt_changessaved</font><p class=\"indent\"/>$topicunlocked <a href=\"view.php?forum=$forum&msgid=$msgid\">$txt_viewtopic</a>.";
}

else {
echo "<font class=\"subhead\">$txt_error</font><p class=\"indent\"/>$txt_norightsunlock";
}
}
}
}
else {
echo "<font class=\"header\">$txt_error</font><p class=\"indent\"/>$txt_norightsaccess";
}
include "footer.php";
?>