<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "protection.php";
include "header.php";
if(isset($msgid)) $pagetitle = "$sitename $split $txt_reportpost";
else $pagetitle = "$sitename $split $txt_reportedposts";
include "template.php";
include "timeconvert.php";
include "msg.php";

if(isset($msgid)) {
echo "<font class=\"header\">$txt_reportpost</font><p class=\"indent\"/>";

if(isset($submit)) {

$report_tablename = "${table_prefix}reported";
$report_table_def = "NULL, '$msgid', '$logincookie[user]', '$repmsg', now()";
if(!mysql_query("INSERT INTO $report_tablename VALUES($report_table_def)")) die(sql_error());

echo "$txt_repsubmitted<p class=\"indent\"/>$txt_cont";

}
else {
echo "<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">
$txt_repdesc<p/>

<form method=\"post\" action=\"report.php?msgid=$msgid\">
<textarea name=\"repmsg\" cols=\"60\" rows=\"15\"></textarea><br/>
<input type=\"hidden\" name=\"submit\" value=\"yes\"/>

<input type=\"submit\" value=\"$txt_reportpost\"/></form><p/>

</div></div></div>";
}

}

else {
echo "<font class=\"header\">$txt_reportedposts</font><p class=\"indent\"/>";

$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$theaccess = mysql_result($access, 0, "mod");

if(strstr($theaccess, ",")) {
$mod = 1;
}
else {
$mod = 0;
}

if($mod == 1 || $admincheck ==1) {

if($a == "d") {

if(!mysql_query("DELETE FROM ${table_prefix}reported WHERE autonumber='$repid'")) die(sql_error());

echo "$txt_repremoved <a href=\"report.php\">$txt_reportedview</a><p/>";

}

else {
$repsquery = mysql_query("SELECT * FROM ${table_prefix}reported ORDER BY time DESC");

if(mysql_num_rows($repsquery) > 0) {

for ($i = 0; $i < mysql_num_rows($repsquery); $i++) {

$repid = mysql_result($repsquery, $i, "autonumber");
$repby = mysql_result($repsquery, $i, "repby");
$repmsg = mysql_result($repsquery, $i, "repmsg");
$repmsgid = mysql_result($repsquery, $i, "threadid");

$origmsgquery = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$repmsgid'");

$origsubject = mysql_result($origmsgquery, 0, "subject");
$origposter = mysql_result($origmsgquery, 0, "userfrom");
$origmsg = mysql_result($origmsgquery, 0, "message");
$origreply = mysql_result($origmsgquery, 0, "reply");

if ($origreply != "0") $origthread = $origreply;
else $origthread = $repmsgid;

$origforumq = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$origthread'");
$origforum = mysql_result($origforumq, 0, "forum");

$origforumnameq = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$origforum'");
$origforumname = mysql_result($origforumnameq, 0, "forumname");

$origtimeq = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$repmsgid'");
$origtime = mysql_result($origtimeq, 0, 0);

$userquery = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$origposter'");
$usersig = mysql_result($userquery, 0, "usersig");
$userbanned = mysql_result($userquery, 0, "userbanned");
$userguestchk = mysql_result($userquery, 0, "registerdate");
if ($userguestchk == "0000-00-00") {
$userguest = 1;
}
if(mysql_num_rows($userquery) == 0 || $userguest == 1) {
$guest = 1;
}

if(strstr($theaccess, " $origforum,") || $admincheck == 1) {

$viewed = 1;

if($origmsg != "") {

echo "<div id=\"central\"><div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\"><tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\">
$origsubject $split $txt_reportby <a href=\"pm.php?s=o&amp;replyuser=$repby\">$repby</a> $txt_at ";

$reptimeq = mysql_query("SELECT UNIX_TIMESTAMP(time) as epoch_time FROM ${table_prefix}reported WHERE autonumber='$repid'");
$reptime = mysql_result($reptimeq, 0, 0);

converttime($reptime, 0, $zone);

echo "</td></tr>

<tr><td valign=\"top\" class=\"box\" width=\"20%\">$txt_origmsg<br/>
$txt_from <a href=\"profile.php?user=$origposter\">$origposter</a>";

if ($userbanned == 1) echo "<br/>$txt_banned";
if ($guest == 1) echo "<br/><font class=\"emph\">$txt_guestuc</font>";

echo "<br/>$txt_at ";

converttime($origtime, 0, $zone);

echo "<br/><a href=\"view.php?forum=$origforum&amp;msgid=$origthread\">$txt_viewtopic</a>";

echo "</td><td valign=\"top\" class=\"boxrt\" width=\"80%\">";

replacestuff($origmsg);

if (isset($usersig) && $usersig != "") {
echo "<br/>________________<br/>";
$sig = 1;
replacestuff($usersig, $sig, $logincookie[user]);
$sig = 0;
}

echo "</td></tr>";

if($repmsg != "") {
echo "<tr><td class=\"box\" valign=\"top\"><font class=\"subhead\">$txt_repmsgsent</font></td><td class=\"boxrt\" valign=\"top\">";
replacestuff($repmsg);
echo "</td></tr>";
}


echo "<tr><td class=\"boxrt\">&nbsp;</td><td class=\"boxrt\"><a href=\"report.php?a=d&amp;repid=$repid\">$txt_delreport</a></td></tr></table></div></div>
<p/>";
}
else {
echo "$txt_alreadygone <a href=\"report.php?a=d&repid=$repid\">$txt_delreport</a><br/>";
}
}
}
if ($viewed != 1) {
echo "$txt_repnorights<br/>";
}

}
else {
echo "$txt_norepposts<br/>";
}
}

}

else {
echo "$txt_mustbemod<br/>";
}
}
include "footer.php";
?>