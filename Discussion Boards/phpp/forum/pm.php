<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "protection.php";
include "header.php";
$script = "textbox";
if($s == "i" && isset($msgid)) {
$records = mysql_query("SELECT * FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgnumber='$msgid'");
$msgsubj = mysql_result($records, 0, "subject");
$pagetitle = "$sitename $split $txt_inbox $split $msgsubj";
}
elseif($s == "o") $pagetitle = "$sitename $split $txt_pmsend";
else $pagetitle = "$sitename $split $txt_inbox";
include "template.php";
include "timeconvert.php";

if ($s == "i") {

if($a == "d") {

if(!mysql_query("DELETE FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgnumber='$msgid'")) die(sql_error());

echo "<font class=\"header\">$txt_inbox</font><p class=\"indent\"/>$txt_pmdel <a href=\"pm.php?s=i\">$txt_inbox</a><p/>";
}

else {

if(isset($msgid)) {

$private_tablename = "${table_prefix}private";

$msgfrom = mysql_result($records, 0, "userfrom");
$msgbody = mysql_result($records, 0, "message");
$msgread = mysql_result($records, 0, "msgread");
$pmsgtime = mysql_result($records, 0, "posttime");
$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgnumber='$msgid'"); 
$msgtime = mysql_result($result, 0, 0);

$msgsubj2 = str_replace("\"", "\\\"", $msgsubj);
$msgbody2 = str_replace("\"", "\\\"", $msgbody);

if ($msgread != 1) {
$private_table_def = "'$msgid', '$msgfrom', '$logincookie[user]', \"$msgsubj\", \"$msgbody\", '1', '$pmsgtime'";
if(!mysql_query("REPLACE INTO $private_tablename VALUES($private_table_def)")) die(sql_error());
}

$msgtime = date($timeform, $msgtime + (3600 * $zone));

echo "<font class=\"header\"><a href=\"pm.php?s=i\">$txt_inbox</a> $split $msgsubj</font><p/>

<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">
<font class=\"subhead\">$txt_fromuc <a href=\"profile.php?user=$msgfrom\">$msgfrom</a> $txt_at $msgtime</font><p/>";
include "msg.php";
replacestuff($msgbody);

echo "</div></div></div><p/>
<div class=\"linksbar\"><a href=\"pm.php?s=o&msgid=$msgid&a=r\">";
if(file_exists("gfx/templates/$template/reply.gif")) echo "<img src=\"gfx/templates/$template/reply.gif\" alt=\"$txt_reply\"/>";
else echo "<img src=\"gfx/templates/$template/reply.jpg\" alt=\"$txt_reply\"/>";
echo "</a> <a href=\"pm.php?s=i&msgid=$msgid&a=d\">";
if(file_exists("gfx/templates/$template/delete.gif")) echo "<img src=\"gfx/templates/$template/delete.gif\" alt=\"$txt_del\"/>";
else echo "<img src=\"gfx/templates/$template/delete.jpg\" alt=\"$txt_del\"/>";
echo "</a></div>";
echo "<p/>";

}

else {

echo "<font class=\"header\">$txt_inbox</font><p/><div class=\"linksbar\">";

if(file_exists("gfx/templates/$template/send.gif")) echo "<a href=\"pm.php?s=o\"><img src=\"gfx/templates/$template/send.gif\" alt=\"$txt_pmsend\"/></a>";
else echo "<a href=\"pm.php?s=o\"><img src=\"gfx/templates/$template/send.jpg\" alt=\"$txt_pmsend\"/></a>";
echo "</div><p/>";

$records = mysql_query("SELECT * FROM ${table_prefix}private WHERE userto='$logincookie[user]' ORDER BY posttime DESC");

if (mysql_num_rows($records) == 0) {
echo "<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\"><p/>$txt_pmnone<p/></div></div></div>";
}
else {

echo "<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">
<tr><td class=\"boxhdrt\" width=\"2%\">&nbsp;</td><td class=\"boxhd\" width=\"50%\">$txt_subject</td><td class=\"boxhd\" width=\"20%\">$txt_fromuc</td><td class=\"boxhdrt\" width=\"28%\">$txt_time</td></tr>";


for ($i = 0; $i < mysql_num_rows($records); $i++) {

$result = mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}private WHERE userto='$logincookie[user]' ORDER BY posttime DESC"); 
$msgtime = mysql_result($result, $i, 0);
$msgfrom = mysql_result($records, $i, "userfrom");
$msgtime = date($timeform, $msgtime + (3600 * $zone));
$msgid = mysql_result($records, $i, "msgnumber");
echo "<tr><td class=\"boxrt\"><a href=\"pm.php?s=i&msgid=$msgid&a=d\"><img src=\"gfx/templates/$template/icons/nonew.gif\" alt=\"$txt_del\"/></a></td><td class=\"box\"><a href=\"pm.php?s=i&amp;msgid=$msgid\">", mysql_result($records, $i, "subject"), "</a></td><td class=\"box\"><a href=\"profile.php?user=$msgfrom\">$msgfrom</a></td><td class=\"boxrt\">$msgtime</td></tr>";
}
echo "</table></div>";
}
echo "<p/><div class=\"linksbar\">";
if(file_exists("gfx/templates/$template/send.gif")) echo "<a href=\"pm.php?s=o\"><img src=\"gfx/templates/$template/send.gif\" alt=\"$txt_pmsend\"/></a>";
else echo "<a href=\"pm.php?s=o\"><img src=\"gfx/templates/$template/send.jpg\" alt=\"$txt_pmsend\"/></a>";
echo "</div><p/>";
}
}
}
elseif($s == "o") {
if(isset($submit) && !empty($subject) && !empty($message) && !empty($userto)) {

$name = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$userto'");

$userset = mysql_num_rows($name);

if ($userset == 1) {

$private_tablename = "${table_prefix}private";
$subject = str_replace("\"", "&quot;", $subject);
$message = str_replace("\"", "&quot;", $message);
$private_table_def = "NULL, '$logincookie[user]', '$userto', '$subject', '$message', 0, NULL";

if(!mysql_query("INSERT INTO $private_tablename VALUES($private_table_def)")) die(sql_error());

$pmnotifyq = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$userto'");

if(mysql_result($pmnotifyq, 0, "pmnotify") == 1) {
$pmfrom = $logincookie[user];
$pmsubj = $subject;

$emailaddress = mysql_result($pmnotifyq, 0, "useremail");
$realname = mysql_result($pmnotifyq, 0, "username");
include "mails/pmnotify.txt";
if(!mail($emailaddress, $mailsubject, $mailmessage, "From: $siteemail")) echo "$txt_errnotif<br/>"; 

}

echo "<font class=\"header\">$txt_pmsend</font><p/>$txt_pmsent <a href=\"pm.php?s=i\">$txt_inbox</a><p/>";
}

else {
echo "<font class=\"header\">$txt_pmsend</font><p/><b>$txt_error</b><br/>$username <i>$userto</i> $txt_pmnoexist <a href=\"pm.php?s=i\">$txt_inbox</a>";
}

}

else {

if ($a == "r") {
$records = mysql_query("SELECT * FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgnumber='$msgid'");

$replyuser = mysql_result($records, 0, "userfrom");
$replysubj = mysql_result($records, 0, "subject");
$replymsg = mysql_result($records, 0, "message");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgnumber='$msgid'"); 
$replytime = mysql_result ($result, 0, 0);

}

if(isset($userto)) $replyuser = $userto;
echo "<font class=\"header\">$txt_pmsend</font><p/>

<div class=\"boxes\"><div class=\"boxbot\"><form action=\"pm.php?s=o\" method=\"post\" name=\"msgform\">
<table>
<tr><td>$txt_to:</td><td><input type=\"text\" name=\"userto\" value=\"$replyuser\"/></td>
<td rowspan=\"5\">";
include "shortcode.php";
echo "</td></tr>
<tr><td>$txt_subject:</td><td><input type=\"text\" name=\"subject\"";

if ($a == "r") {
if(strtoupper(substr($replysubj, 0, 4)) == "RE: ") echo " value=\"$replysubj\"";
else echo " value=\"Re: $replysubj\"";
}
else echo " value=\"$subject\"";
echo "/></td></tr>
<tr><td valign=\"top\">$txt_message:</td><td><textarea name=\"message\" cols=\"60\" rows=\"15\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\" ondblclick=\"storeCaret(this);\">";

if ($a == "r") {
echo "\n\n________________
$txt_quoting $replyuser:\n$replymsg";
}
else echo $message;

echo "</textarea></td></tr>
<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"$txt_pmsend\"/><input type=\"hidden\" name=\"submit\" value=\"yes\"/>
</td></tr></table></form></div></div>";
}
}

include "footer.php";
?>