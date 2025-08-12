<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
$pagetitle = "$sitename";
include "template.php";

$fors = mysql_query("SELECT * FROM ${table_prefix}forums");
if(mysql_num_rows($fors) == 1) {
$forid = mysql_result($fors, 0, "forumno");
$forum = $forid;
include "view.php";
}
else {
include "timeconvert.php";

$cats = mysql_query("SELECT * FROM ${table_prefix}categories ORDER BY catorder ASC");

echo "<div id=\"central\">";

for($cat = 0; $cat < mysql_num_rows($cats); $cat++) {

$catname = mysql_result($cats, $cat, "catname");
$catno = mysql_result($cats, $cat, "catno");
$catorder = mysql_result($cats, $cat, "catorder");

$forumdetails = mysql_query("SELECT * FROM ${table_prefix}forums WHERE cat='$catorder' ORDER BY fororder ASC");

$catrightsq = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$catrights = mysql_result($catrightsq, 0, "access");
$anytodisp = 0;

for($count = 0; $count < mysql_num_rows($forumdetails); $count++) {
$forrest = mysql_result($forumdetails, $count, "restricted");
$j = mysql_result($forumdetails, $count, "forumno");
if(strstr($catrights, " $j,") || $forrest == 0) {
$anytodisp = 1;
}
}
if($admincheck == 1) {
$anytodisp = 1;
}

if(mysql_num_rows($forumdetails) > 0 && $anytodisp == 1) {

echo "<div class=\"header\">$catname</div>";

echo "<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">";

echo "<tr><td class=\"boxhdrt\" width=\"2%\">&nbsp;</td><td class=\"boxhd\" width=\"50%\">$txt_forumname</td><td class=\"boxhd\" width=\"40%\">$txt_lastpost</td><td class=\"boxhdrt\" width=\"8%\">$txt_topics</td></tr>";

for ($i = 0; $i < mysql_num_rows($forumdetails); $i++) {

$forumname = mysql_result($forumdetails, $i, "forumname");
$forumdesc = mysql_result($forumdetails, $i, "forumdesc");
$forumno = mysql_result($forumdetails, $i, "forumno");

$forumposts = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forumno' ORDER BY lastreptime DESC");
$forumlastid = mysql_result($forumposts, 0, "lastrepid");
$threadlastid = mysql_result($forumposts, 0, "threadid");
$postsinlastthread = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$threadlastid'");
$postsinlastthread = mysql_num_rows($postsinlastthread);
$postsinlastthread++;
$postsinlastthread = $postsinlastthread/$postsperpage;
$postsinlastthread = ceil($postsinlastthread);

$lastpost = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$forumlastid'");
$lastuser = mysql_result($lastpost, 0, "userfrom");
$result = mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$forumlastid'"); 
$lasttime = mysql_result ($result, 0, 0);
$origthread = mysql_result($lastpost, 0, "reply");
if ($origthread == "0") {
$lastsubj = mysql_result($lastpost, 0, "subject");
$lastid = mysql_result($lastpost, 0, "msgnumber");
}
else {
$origpost = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$origthread'");
$lastsubj = mysql_result($origpost, 0, "subject");
$lastid = mysql_result($origpost, 0, "msgnumber");
}
$totalthreads = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forumno'");
$tot = mysql_num_rows($totalthreads);


$restricted = mysql_result($forumdetails, $i, "restricted");
$rights = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$rights = mysql_result($rights, 0, "access");
$j = $forumno;
if(strstr($rights, " $j,")) {
$allowed = 1;
}
else {
$allowed = 0;
}
if($admincheck == 1) {
$allowed = 1;
}

$forumlocked = mysql_result($forumdetails, $i, "locked");
if ($forumlocked == 0) {

if(!isset($lastvisit)) {
$lastvisit = $logincookie[last];
}

$newmsgcheck = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$j' ORDER BY lastreptime DESC");
$newmsgtime = mysql_result($newmsgcheck, 0, "lastreptime");

if (($lastvisit < $newmsgtime) && isset($logincookie[user])) {
$forumlocked = "<img src=\"gfx/templates/$template/icons/new.gif\" alt=\"$txt_newposts\"/>";
}
else {
$forumlocked = "<img src=\"gfx/templates/$template/icons/nonew.gif\" alt=\"$txt_nonewposts\"/>";
}

}
elseif ($forumlocked == 1) {
$newmsgcheck = mysql_query("SELECT * FROM threads WHERE forum='$j' ORDER BY lastreptime DESC");
$newmsgtime = mysql_result($newmsgcheck, 0, "lastreptime");

if (($lastvisit < $newmsgtime) && isset($logincookie[user])) {
$forumlocked = "<img src=\"gfx/templates/$template/icons/newlocked.gif\" alt=\"$txt_newposts $txt_forumlocked\"/>";
}
else {
$forumlocked = "<img src=\"gfx/templates/$template/icons/nonewlocked.gif\" alt=\"$txt_nonewposts $txt_forumlocked\"/>";
}
}

if($restricted == 0 || ($restricted == 1 && $allowed == 1)) {

if ($tot == 0) {
echo "<tr><td class=\"box\">$forumlocked</td><td class=\"box\"><a href=\"view.php?forum=$forumno\">$forumname</a><br/>$forumdesc</td><td colspan=\"2\" class=\"boxrt\"><font class=\"emph\">$txt_notopics</font></td></tr>";
}
else {
$lasttime = date($timeform, $lasttime + (3600 * $zone));
echo "<tr><td class=\"box\">$forumlocked</td><td class=\"box\"><a href=\"view.php?forum=$forumno\">$forumname</a><br/>$forumdesc</td><td class=\"box\"><a href=\"view.php?msgid=$lastid&amp;forum=$forumno&amp;page=$postsinlastthread#$forumlastid\">$lastsubj</a> $txt_by <a href=\"profile.php?user=$lastuser\">$lastuser</a><br/>$txt_at $lasttime</td><td class=\"boxrt\">$tot</td></tr>";
}
}
}
echo "</table>";
}
}
echo "</div>
<div class=\"infobox\">";

$usersonlinecheck = mysql_query("SELECT * FROM ${table_prefix}access");
$usersonline = mysql_num_rows($usersonlinecheck);
$regonlinecheck = mysql_query("SELECT * FROM ${table_prefix}access WHERE userid != 'Guest' ORDER BY userid");
$regonline = mysql_num_rows($regonlinecheck);
$guestsonline = $usersonline - $regonline;

$totuserscheck = mysql_query("SELECT * FROM ${table_prefix}users WHERE registerdate != '0000-00-00' ORDER BY registerdate DESC");
$totusers = mysql_num_rows($totuserscheck);
$lastuser = mysql_result($totuserscheck, 0, "userid");

$topicscheck = mysql_query("SELECT * FROM ${table_prefix}threads");
$tottopics = mysql_num_rows($topicscheck);

$postscheck = mysql_query("SELECT * FROM ${table_prefix}public");
$totposts = mysql_num_rows($postscheck);

echo "<font class=\"subhead\">$txt_userson</font><br/>";

if ($usersonline != 1) {
echo "$txt_thereare $usersonline $txt_usersonline";
}
else {
echo "$txt_thereis 1 $txt_useronline";
}


echo " - $regonline $txt_registered $txt_and $guestsonline ";

if ($guestsonline != 1) {
echo "$txt_guests";
}
else {
echo "$txt_guest";
}

echo ".<br/>";

if ($regonline != 0) {
echo "$txt_regusers ";

for ($i = 0; $i < $regonline; $i++) {
$thisuser = mysql_result($regonlinecheck, $i, "userid");
echo "<a href=\"profile.php?user=$thisuser\">$thisuser</a>";
if ($i != $regonline - 1) {
echo ", ";
}
}


echo ".<br/>";
}
echo "$txt_wehave $totusers ";

if ($totusers != 1) {
echo "$txt_members";
}
else {
echo "$txt_member";
}

echo " $txt_with $totposts ";

if ($totposts != 1) {
echo "$txt_posts";
}
else {
echo "$txt_post";
}

echo " $txt_in $tottopics ";

if ($tottopics != 1) {
echo "$txt_topicslc";
}
else {
echo "$txt_topic";
}

echo ".<br/>
$txt_lastreg <a href=\"profile.php?user=$lastuser\">$lastuser</a>.";

$thetime = time() + (3600 * $zone);

$today = date("-m-d", $thetime);

$birthdays = mysql_query("SELECT * FROM ${table_prefix}users WHERE userdob LIKE '%$today'");

if(mysql_num_rows($birthdays) > 0) {
echo "<br/>$txt_birthday ";
}
for ($i = 0; $i < mysql_num_rows($birthdays); $i++) {
$userbd = mysql_result($birthdays, $i, "userid");
if ($i == (mysql_num_rows($birthdays) - 1) && $i != 0) {
echo "and $userbd.";
}
elseif ($i == (mysql_num_rows($birthdays) - 1)) {
echo "$userbd.";
}
else {
echo "$userbd, ";
}
}

echo "</div>";

if(isset($logincookie[user])) {
echo "<div class=\"infobox\"><font class=\"subhead\">$txt_welcomeback $sitename, <font class=\"emph\">$logincookie[user]</font>.";
if ($pmno > 0) {
echo " $txt_youhave $pmno ";
if ($pmno != 1) echo "$txt_newpms.";
else echo "$txt_newpm.";
echo " <a href=\"pm.php?s=i\">$txt_inbox</a>";
}
echo "</font><br/>$txt_lastvisit ";
if(!isset($lastvisitunix)) {
$lastvisitunix = $logincookie[lastv];
}

$lastvisitunix = date($timeform, $lastvisitunix + (3600 * $zone));

echo "$lastvisitunix.";

$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$theaccess = mysql_result($access, 0, "mod");

if(strstr($theaccess, ",")) {
$mod = 1;
}
else {
$mod = 0;
}

if ($admincheck == 1 || $mod == 1) {
$reported = mysql_query("SELECT * FROM ${table_prefix}reported");
if (mysql_num_rows($reported) > 0) {
echo "<br/>$txt_reporteddeal <a href=\"report.php\">$txt_reportedview</a>";
}
}

echo "<br/><a href=\"index.php?mark=read\">$txt_markallread</a></div>";

}
else $notlogged = 1;

echo "<div class=\"infobox\">";

if($notlogged == 1) echo "<table width=\"100%\"><tr><td width=\"60%\"><font class=\"subhead\">$txt_login $txt_or <a href=\"register.php\">$txt_registerlc</a></font><br/>
<form action=\"logged.php?action=login\" method=\"post\"><table cellspacing=\"10\"><tr><td>$txt_username:<br/><input type=\"text\" name=\"loginname\" size=\"20\"/></td><td>$txt_password:<br/><input type=\"password\" name=\"loginpass\" size=\"20\"/></td><td>$txt_rememberme: <input type=\"checkbox\" class=\"noborder\" name=\"remember\" value=\"1\"/></td><td><input type=\"submit\" value=\"$txt_login\"/></td></tr></table></form></td><td width=\"40%\">";

echo "<font class=\"subhead\">$txt_key</font><br/>
<table>
<tr><td><img src=\"gfx/templates/$template/icons/new.gif\" alt=\"$txt_newposts\"/> $txt_newposts</td><td><img src=\"gfx/templates/$template/icons/nonew.gif\" alt=\"$txt_nonewposts\"/> $txt_nonewposts</td></tr>
<tr><td><img src=\"gfx/templates/$template/icons/newlocked.gif\" alt=\"$txt_newposts $txt_lockedt\"/> $txt_newposts $txt_lockedt</td><td><img src=\"gfx/templates/$template/icons/nonewlocked.gif\" alt=\"$txt_nonewposts $txt_lockedt\"/> $txt_nonewposts $txt_lockedt</td></tr>
</table>";

if($notlogged == 1) echo "</td></tr></table>";

echo "</div>
</div>";

include "footer.php";
}
?>