<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "settings.php";
if($guestpost != "1" || isset($logincookie[user])) {
include "protection.php";
}
include "header.php";
$script = "textbox";

if(!isset($messageid)) $messageid = $msgid;

if(!isset($forum)) {
$theforum = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$messageid'");
$forum = mysql_result($theforum, 0, "forum");
}
if($forum == "") {
$thethread = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$thread = mysql_result($thethread, 0, "reply");
$theforum = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$thread'");
$forum = mysql_result($theforum, 0, "forum");
}
$forumname = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$forumname = mysql_result($forumname, 0, "forumname");
$thisforumname = $forumname;

$pagetitle = "$sitename $split $forumname $split ";

$topicname = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$topicname = mysql_result($topicname, 0, "subject");

if(isset($msgid)) {
$pagetitle .= "$topicname $split ";
if($a == "r") $pagetitle .= "$txt_reply";
if($a == "e") $pagetitle .= "$txt_editmsg";
}
else {
$pagetitle .= "$txt_add";
}


include "template.php";


if(!isset($loginname)) {
$loginname = $logincookie[user];
}

$checkit1 = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$messageid'");
$isitlocked = mysql_result($checkit1, 0, "locked");


$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$rights = mysql_result($access, 0, "access");

$thisforum = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$restricted = mysql_result($thisforum, 0, "restricted");

if(mysql_num_rows($thisforum) > 0) {

if(strstr($rights, " $forum,")) {
$allowed = 1;
}
else {
$allowed = 0;
}
if ($admincheck == 1) {
$allowed = 1;
}

if ($isitlocked == 1) {
$topiclocked = 1;
}
else {
$topiclocked = 0;
}

$isforumlocked = mysql_result($thisforum, 0, "locked");
if ($isforumlocked == 1) {
$forumlocked = 1;
}
else {
$forumlocked = 0;
}


if (($topiclocked == 1 || $forumlocked == 1) && allowed != 1) {

echo "<font class=\"header\">";
if ($topicname != "" && isset($topicname)) {
echo "<a href=\"view.php?forum=$forum&msgid=$messageid\">$topicname</a> $split ";
}
echo "$txt_error</font><p class=\"indent\"/>

$txt_sorry ";

if ($forumlocked == 1) {
echo "$txt_theforum ";
}
elseif ($topiclocked == 1) {
echo "$txt_it ";
}

echo "$txt_islocked. <a href=\"view.php?forum=$forum\">$txt_viewindex</a>";
}

else {

if ($restricted == 0 || ($restricted == 1 && $allowed == 1)) {

$pagetitle = "<font class=\"header\"><a href=\"view.php?forum=$forum\">$thisforumname</a> $split ";

if ($a == "r" || $ac == "r") {
$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$msgid'");
$pagetitle .= "$txt_reply";
}
elseif ($a == "e" || $ac == "e") {
$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$pagetitle .= "$txt_editmsg";
}
else {
$pagetitle .= "$txt_add";
}

if(isset($submit)) {
if($subject == "" || $message == "") {
echo "$txt_error</font><p class=\"indent\"/>$txt_subjmsg $txt_goback<p/>";

}
else {

$subject = str_replace ('"', '&quot;', $subject);

$public_tablename = "${table_prefix}public";
$threads_tablename = "${table_prefix}threads";
$ip = $SERVER[REMOTE_ADDR];

if ($ac == "e") {
$totedits++;
$edittime = time();
$theip = mysql_result($records, 0, "ip");

$message = $message."\n\n\[EDITCODE $logincookie[user] $edittime $totedits\]";
$public_table_def = "'$msgnumber', '$postuser', '$subject', '$message', '$reply', '$posttime', '$theip'";
}
elseif ($ac == "r") {
$public_table_def = "NULL, '$loginname', '$subject', '$message', '$messageid', NULL, '$theip'";
}
else {
$public_table_def = "NULL, '$loginname', '$subject', '$message', '0', NULL, '$theip'";
}

if ($ac == "e") {

$forumcheck = mysql_query("SELECT * FROM public WHERE msgnumber='$msgnumber'");
$forumno = mysql_result($forumcheck, 0, "reply");
if($forumno == "") {
$forumno = mysql_result($forumcheck, 0, "msgnumber");
}
$findforum = mysql_query("SELECT * FROM threads WHERE threadid='$msgnumber'");
$theforum = mysql_result($findforum, 0, "forum");

$theaccess = mysql_query("SELECT * FROM userrights WHERE userid='$logincookie[user]'");
$theaccess = mysql_result($theaccess, 0, "mod");

if(strstr($theaccess, " $forum,")) {
$mod = 1;
}

if ($logincookie[user] == $postuser || $mod == 1 || $admincheck == 1) {
if(!mysql_query("REPLACE INTO $public_tablename VALUES($public_table_def)")) die(sql_error());
$disallowed = 0;
}
else {
$disallowed = 1;
}
}
else {

if(!isset($logincookie[user])) {
$blocked = 0;
$regusersquery = mysql_query("SELECT * FROM ${table_prefix}users");
for ($i = 0; $i < mysql_num_rows($regusersquery); $i++) {
$thisuser = mysql_result($regusersquery, $i, "userid");
if ($thisuser == $loginname) {
$blocked = 1;
}
}
}
if($blocked != 1) {
if(!mysql_query("INSERT INTO $public_tablename VALUES($public_table_def)")) die(sql_error());
}
}

$postedq = mysql_query("SELECT posttime FROM ${table_prefix}public WHERE userfrom='$loginname' AND subject='$subject' AND message='$message'");
$posted = mysql_result($postedq, 0, 0);

if($ac == "r") {
$adding = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$messageid' AND userfrom='$loginname' AND posttime='$posted'");

$prevthreadq = mysql_query("SELECT number FROM ${table_prefix}threads WHERE threadid='$messageid'");
$prevthread = mysql_result($prevthreadq, 0, 0);

$lastrepid = mysql_result($adding, 0, msgnumber);
$threads_table_def = "'$messageid', '$forum', '$lastrepid', '$posted', '$prevthread', '0', '$type'";
$thisthread = $messageid;
if($blocked != 1) {
if(!mysql_query("REPLACE INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());
}
}
elseif ($ac == "e") {
$adding = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$isitareply = mysql_result($adding, 0, "reply");
if ($isitareply == "0") {
$threadid = $messageid;
$lastmsgid = $messageid;
$thisthread = $threadid;
}
else {
$threadid = $isitareply;
$thisthread = $threadid;
$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$threadid' ORDER BY posttime DESC");
$lastmsgid = mysql_result($replies, 0, "msgnumber");
}

$postedq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$threadid'");
$posted = mysql_result($postedq, 0, "lastreptime");
$number = mysql_result($postedq, 0, "number");

$threads_table_def = "'$threadid', '$forum', '$lastmsgid', '$posted', '$number', '0', '$type'";
if($blocked != 1) {
if(!mysql_query("REPLACE INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());
}

}
else {
$adding = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='0' AND userfrom='$loginname' AND posttime='$posted'");
$lastmsgid = mysql_result($adding, 0, "msgnumber");
$thisthread = $lastmsgid;
$threads_table_def = "'$lastmsgid', '$forum', '$lastmsgid', '$posted', NULL, '0', '$type'";
if($blocked != 1) {
if(!mysql_query("INSERT INTO $threads_tablename VALUES($threads_table_def)")) die(sql_error());
}
}

if($notify == "1") {
$notif_table_def = "NULL, '$logincookie[user]', '$thisthread', '0'";
if(!mysql_query("REPLACE INTO ${table_prefix}notif VALUES($notif_table_def)")) die(sql_error());
}
else {
if(!mysql_query("DELETE FROM ${table_prefix}notif WHERE username='$logincookie[user]' AND threadid='$thisthread'")) die(sql_error());
}

if($a != "e") {
$notifs = mysql_query("SELECT * FROM ${table_prefix}notif WHERE threadid='$thisthread' AND username NOT LIKE '$logincookie[user]' AND replies='0'");
if(mysql_num_rows($notifs) > 0) {
$threadq = mysql_query("SELECT subject FROM ${table_prefix}public WHERE msgnumber='$thisthread'");
$threadname = mysql_result($threadq, 0, 0);
$forumq = mysql_query("SELECT forum FROM ${table_prefix}threads WHERE threadid='$thisthread'");
$forumno = mysql_result($forumq, 0, 0);
}
for($i = 0; $i < mysql_num_rows($notifs); $i++) {
$thisuser[$i] = mysql_result($notifs, $i, "username");
$mailnotifyq = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$thisuser[$i]'");

$emailaddress[$i] = mysql_result($mailnotifyq, $i, "useremail");
$realname[$i] = mysql_result($mailnotifyq, $i, "username");
include "mails/repnotify.txt";
if(!mail($emailaddress[$i], $mailsubject, $mailmessage, "From: $siteemail")) echo "$txt_errnotif"; 
$notif_table_def = "NULL, '$thisuser[$i]', '$thisthread', '1'";
if(!mysql_query("REPLACE INTO ${table_prefix}notif VALUES($notif_table_def)")) die(sql_error());
}
}

echo "$pagetitle</font><p class=\"indent\"/>";

if ($disallowed == 0 && $blocked != 1) {

echo "$txt_posthasbeen ";

if ($ac == "e") {
echo "$txt_postedit.";
}
else {
echo "$txt_postadd.";
}

$justdone = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$loginname' AND posttime='$posted'");
$newpost = mysql_result($justdone, 0, "msgnumber");

$workingpages = $messageid;

$workitout = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$workingpages'");
$workitout = mysql_num_rows($workitout);
$workitout++;
$workitout = $workitout/$postsperpage;
$page = ceil($workitout);

if ($ac == "r") {
echo " <a href=\"view.php?msgid=$messageid&forum=$forum&page=$page#$lastrepid\">$txt_viewtopic</a>";
}
elseif ($ac == "e") {

if ($reply != "0") {
echo " <a href=\"view.php?msgid=$reply&forum=$forum&page=$page#$lastrepid\">$txt_viewtopic</a>";
}
else {
echo " <a href=\"view.php?msgid=$messageid&forum=$forum&page=$page#$lastrepid\">$txt_viewtopic</a>";
}
}
else {
echo " <a href=\"view.php?msgid=$newpost&forum=$forum\">$txt_viewtopic</a>";
}
}
else {
if ($blocked == 1) {

$userbannedquery = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$loginname'");
$userbanned = mysql_result($userbannedquery, 0, "userbanned");

if ($userbanned == 1) {
echo "<font class=\"emph\">$txt_error</font><p/>$txt_banneduser";
}
else {
echo "<font class=\"emph\">$txt_error</font><p/>$txt_userregd";
}
}
else {
echo "<font class=\"emph\">$txt_error</font><p/>$txt_norights";
}
}
}
}

else {

echo "$pagetitle</font>";

if(isset($preview)) {
include "msg.php";
$subject = stripslashes($subject);
$message = stripslashes($message);
echo "<p/>
<div class=\"infobox\">
<font class=\"subhead\">$txt_preview $split $subject</font><p/>";
replacestuff($message);
echo "</div>";
}


echo "<div class=\"boxes\"><div class=\"boxbot\">
<form action=\"addmessage.php\" name=\"msgform\" method=\"post\">
<table cellpadding=\"0\">";

if($guestpost == 1) {

echo "<tr><td>$txt_username:</td><td><input type=\"text\" name=\"loginname\"";

if(isset($logincookie[user])) {
echo " value=\"$logincookie[user]\" disabled=\"disabled\"";
}
echo "/></td>";

}
else echo "<tr><td colspan=\"2\">&nbsp;</td>";

echo "<td rowspan=\"5\">";

include "shortcode.php";

echo "</td></tr>";

echo "<tr><td>$txt_subject:</td><td><input type=\"text\" name=\"subject\"";

if(isset($preview)) {
echo " value=\"$subject\"";
}
else {
if ($a == "r" || $a == "e") {
$replysubj = mysql_result($records, 0, "subject");
if ($a == "r") {
echo " value=\"Re: $replysubj\"";
}
else {
echo " value=\"$replysubj\"";
}
}
}

if (isset($q)) {
$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$q'");
$quotemsg = mysql_result($replies, 0, "message");
$quotefrom = mysql_result($replies, 0, "userfrom");

}
elseif (isset($a)) {
$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$msgid'");

}

echo "/></td>

</tr>
<tr><td valign=\"top\">$txt_message:</td><td><textarea name=\"message\" cols=\"60\" rows=\"15\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\" ondblclick=\"storeCaret(this);\">";

if(isset($preview)) {
echo $message;
}
else {
if (isset($q)) {
$findstring = "/\n\n\[EDITCODE ([^\s]+)\s([^\s]+)\s([^\]]+)\]/";
$quotemsg = preg_replace($findstring, "", $quotemsg);

echo "[quote=\"$quotefrom\"]\n$quotemsg\n[/quote]";
}

if ($a == "e" && !isset($preview)) {
$msg = mysql_result($records, 0, "message");
$findstring = "/\n\n\[EDITCODE ([^\s]+)\s([^\s]+)\s([^\]]+)\]/";
function functest($matches) {
global $totedits;
$totedits = $matches[3];
}
$edits = preg_replace_callback($findstring, functest, $msg);
$msg = preg_replace($findstring, "", $msg);
echo $msg;
}
}

echo "</textarea></td></tr>";

if(isset($logincookie[user])) {
$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$thisforum = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$restricted = mysql_result($thisforum, 0, "restricted");
$rights = mysql_result($access, 0, "access");

if(mysql_num_rows($thisforum) > 0) {

if(strstr($rights, " $forum,")) {
$allowed = 1;
}
else {
$allowed = 0;
}
if ($admincheck == 1) {
$allowed = 1;
}
}

$firstmsg = 1;

if($a == "r") {
$firstmsg = 0;
$isitthefirst = $msgid;
}

if($a == "e") {
$isitthefirst = mysql_result($replies, 0, "reply");
if($isitthefirst != "0") $firstmsg = 0;
}

if($allowed == 1 && $firstmsg == 1) {
if ($a == "e") {
$currenttype = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid'");
$currenttype = mysql_result($currenttype, 0, "type");

echo "<tr><td>$txt_posttype:</td><td><input type=\"radio\" class=\"noborder\" name=\"type\" value=\"n\"";

if ($currenttype == "n") echo " checked=\"checked\"";

echo "/>$txt_normal <input type=\"radio\" class=\"noborder\" name=\"type\" value=\"s\"";

if ($currenttype == "s") echo " checked=\"checked\"";

echo "/>$txt_sticky <input type=\"radio\" class=\"noborder\" name=\"type\" value=\"a\"";

if ($currenttype == "a") echo " checked=\"checked\"";

echo "/> $txt_announcement</td></tr>";
}
else {
echo "<tr><td>$txt_posttype:</td><td><input type=\"radio\" class=\"noborder\" name=\"type\" value=\"n\" checked=\"checked\"/>$txt_normal <input type=\"radio\" class=\"noborder\" name=\"type\" value=\"s\"/>$txt_sticky <input type=\"radio\" class=\"noborder\" name=\"type\" value=\"a\" checked=\"checked\"/> $txt_announcement</td></tr><tr><td colspan=\"2\">";
}
}
else {
$currenttype = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$isitthefirst'");
$currenttype = mysql_result($currenttype, 0, "type");
if($currenttype == "") {
$currenttype = "n";
}
echo "<tr><td>&nbsp;</td><td><input type=\"hidden\" name=\"type\" value=\"$currenttype\"/>";
}
}
else {

if ($a == "e" || $a == "r") {
$currenttype = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$isitthefirst'");
$currenttype = mysql_result($currenttype, 0, "type");
echo "<tr><td>&nbsp;</td><td><input type=\"hidden\" name=\"type\" value=\"$currenttype\"/>";
}
else {
echo "<tr><td>&nbsp;</td><td><input type=\"hidden\" name=\"type\" value=\"n\"/>";
}
}

if($a == "e" || $a == "r") {
if($a == "e") $reply = mysql_result($records, 0, "reply");
if(!empty($reply)) $thisthread = $reply;
else $thisthread = $msgid;
$notifq = mysql_query("SELECT * FROM ${table_prefix}notif WHERE username='$logincookie[user]' AND threadid='$thisthread'");
if(mysql_num_rows($notifq) > 0) $notif = 1;
}

echo "<tr><td>&nbsp;</td><td>$txt_notifyreps: <input type=\"checkbox\" class=\"noborder\" name=\"notify\" value=\"1\"";
if($notif == "1") echo " CHECKED";
echo "/></td></tr>";
if($a == "e") echo "<input type=\"hidden\" name=\"totedits\" value=\"$totedits\"/>";
echo "</td></tr><tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" ";

$firstmsg = 1;

if($a == "r") {
$firstmsg = 0;
$isitthefirst = $msgid;
}

if($a == "e") {
$isitthefirst = mysql_result($replies, 0, "reply");
if($isitthefirst != "0") $firstmsg = 0;
} 

if ($a == "r") {
echo "value=\"$txt_reply\"";
}
elseif ($a == "e") {
echo "value=\"$txt_editmsg\"";
}
else {
echo "value=\"$txt_add\"";
}

echo "/>
<input type=\"submit\" name=\"preview\" value=\"$txt_preview\"/>";
if ($a == "r") {
echo "<input type=\"hidden\" name=\"msgid\" value=\"$msgid\"/>";
echo "<input type=\"hidden\" name=\"messageid\" value=\"$msgid\"/>";
echo "<input type=\"hidden\" name=\"a\" value=\"r\"/>";
echo "<input type=\"hidden\" name=\"ac\" value=\"r\"/>";
}
elseif ($a == "e") {
$postuser = mysql_result($records, 0, "userfrom");
echo "
<input type=\"hidden\" name=\"postuser\" value=\"$postuser\"/>
<input type=\"hidden\" name=\"a\" value=\"e\"/>
<input type=\"hidden\" name=\"ac\" value=\"e\"/>
<input type=\"hidden\" name=\"msgid\" value=\"$msgid\"/>
<input type=\"hidden\" name=\"messageid\" value=\"$msgid\"/>
<input type=\"hidden\" name=\"reply\" value=\"",mysql_result($records, 0, "reply"),"\"/>
<input type=\"hidden\" name=\"posttime\" value=\"",mysql_result($records, 0, "posttime"),"\"/>
<input type=\"hidden\" name=\"msgnumber\" value=\"",mysql_result($records, 0, "msgnumber"),"\"/>";
}
echo "<input type=\"hidden\" name=\"forum\" value=\"$forum\"/>

</td></tr></table>
</form>
</div></div>";

}

}

else {
echo "<font class=\"heading\"><a href=\"index.php\">$sitename</a> $split $txt_noaccess</font><p/>

$txt_norightsforum";
}
}
}
else {
echo "<font class=\"header\">$txt_forumnotexist</font><p/>

$txt_forumnosorry";
}

include "footer.php";
?>
