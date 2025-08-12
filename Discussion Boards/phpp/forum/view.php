<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
if($doneheader != "1") include "header.php";
include "timeconvert.php";

$access = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$thisforum = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");
$restricted = mysql_result($thisforum, 0, "restricted");
$rights = mysql_result($access, 0, "access");

$forumname = mysql_result($thisforum, 0, "forumname");
$pagetitle = "$sitename $split $forumname";
if(isset($msgid)) {
$records = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$msgid'");
$subj = mysql_result($records, 0, "subject");
$pagetitle .= " $split $subj";
}

if($doneheader != "1") include "template.php";

echo "<div class=\"central\">";

$forumlocked = mysql_result($thisforum, 0, "locked");

if(mysql_num_rows($thisforum) > 0) {

if(strstr($rights, " $forum,")) {
$allowed = 1;
}
else {
$allowed = 0;
}

if ($restricted == 0 || ($restricted == 1 && $allowed == 1 || $overalladmin == 1)) {

if(isset($msgid)) {

$checking = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$msgid' && forum='$forum'");
if (mysql_num_rows($checking) == 1) {

$notifchk = mysql_query("SELECT * FROM ${table_prefix}notif WHERE threadid='$msgid' && username='$logincookie[user]' && replies='1'");
if(mysql_num_rows($notifchk) > 0) {
$notif_table_def = "NULL, '$logincookie[user]', '$msgid', '0'";
if(!mysql_query("REPLACE INTO ${table_prefix}notif VALUES($notif_table_def)")) die(sql_error());
}

if (mysql_result($checking, 0, "locked") == 1) {
$topiclocked = 1;
}
if (mysql_result($checking, 0, "locked") == 0) {
$topiclocked = 0;
}

include "msg.php";
$user = mysql_result($records, 0, "userfrom");

echo "<div class=\"header\"><a href=\"view.php?forum=$forum\">$forumname</a> $split $subj</div><p/>";

if(!isset($page)) {
$page = 1;
}

$lastpost = $postsperpage * $page;
$firstpost = $lastpost - $postsperpage + 1;

$pagelinks = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$msgid'");
$totposts = mysql_num_rows($pagelinks);
$totpages = ($totposts + 1)/$postsperpage;
$totpages = ceil($totpages);

$links = "<div class=\"linksbar\"><table width=\"100%\"><tr><td>";

if (isset($logincookie[user]) || $guestpost == "1") {
if ($forumlocked != "1") {
if(file_exists("gfx/templates/$template/new.gif")) $links .= "<a href=\"addmessage.php?forum=$forum\"><img src=\"gfx/templates/$template/new.gif\" alt=\"$txt_add\"/></a>";
else $links .= "<a href=\"addmessage.php?forum=$forum\"><img src=\"gfx/templates/$template/new.jpg\" alt=\"$txt_add\"/></a>";

if ($topiclocked == 1) {
if(file_exists("gfx/templates/$template/locked.gif")) $links .= " <img src=\"gfx/templates/$template/locked.gif\" alt=\"$txt_topiclocked\"/></a>";
else $links .= " <img src=\"gfx/templates/$template/locked.jpg\" alt=\"$txt_topiclocked\"/></a>";
}
elseif ($topiclocked == 0) {
if(file_exists("gfx/templates/$template/reply.gif")) $links .= " <a href=\"addmessage.php?msgid=$msgid&amp;a=r\"><img src=\"gfx/templates/$template/reply.gif\" alt=\"$txt_reply\"/></a>";
else $links .= " <a href=\"addmessage.php?msgid=$msgid&amp;a=r\"><img src=\"gfx/templates/$template/reply.jpg\" alt=\"$txt_reply\"/></a>";
}
}
else {
if(file_exists("gfx/templates/$template/locked.gif")) $links .= " <img src=\"gfx/templates/$template/locked.gif\" alt=\"$txt_forumlocked\"/></a>";
else $links .= " <img src=\"gfx/templates/$template/locked.jpg\" alt=\"$txt_forumlocked\"/></a>";
}
}
else {
$links .= "&nbsp;";
}

$theaccess = mysql_query("SELECT * FROM userrights WHERE userid='$logincookie[user]'");
$theaccess = mysql_result($theaccess, 0, "mod");

if(strstr($theaccess, " $forum,")) {
$mod = 1;
}
else {
$mod = 0;
}

if ($mod == 1 || $admincheck == 1) {
if ($topiclocked == 1) {
if(file_exists("gfx/templates/$template/unlock.gif")) $links .= " <a href=\"mod.php?t=unlock&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/unlock.gif\" alt=\"$txt_unlocktopic\"/></a>";
else $links .= " <a href=\"mod.php?t=unlock&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/unlock.jpg\" alt=\"$txt_unlocktopic\"/></a>";
}
elseif ($topiclocked == 0) {
if(file_exists("gfx/templates/$template/lock.gif")) $links .= " <a href=\"mod.php?t=lock&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/lock.gif\" alt=\"$txt_locktopic\"/></a>";
else $links .= " <a href=\"mod.php?t=lock&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/lock.jpg\" alt=\"$txt_locktopic\"/></a>";
}
if(file_exists("gfx/templates/$template/move.gif")) $links .= " <a href=\"mod.php?t=move&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/move.gif\" alt=\"$txt_movetopic\"/></a>";
else $links .= " <a href=\"mod.php?t=move&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/move.jpg\" alt=\"$txt_movetopic\"/></a>";
}
if ($admincheck == 1) {
if(file_exists("gfx/templates/$template/delete.gif")) $links .= " <a href=\"mod.php?t=delete&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/delete.gif\" alt=\"$txt_deltopic\"/></a>";
else $links .= " <a href=\"mod.php?t=delete&amp;msgid=$msgid&amp;forum=$forum\"><img src=\"gfx/templates/$template/delete.jpg\" alt=\"$txt_deltopic\"/></a>";
}

$links .= "</td><td align=\"right\" class=\"pages\">";

if ($totpages > 1) {

$h = $page - 1;
$j = $page + 1;

if($page > 1) {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$h\">&lt;&lt; $txt_prev</a> ";
}

$links .= "Page ";

if ($totpages < 6) {
for ($i = 1; $i <= $totpages; $i++) {
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$i\">$i</a> ";
}
}
}
else {
for ($i = 1; $i <= 3; $i++) {
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$i\">$i</a> ";
}
}

if ($page < 3 && $totpages > 6) {
$links .= "... ";
}

if ($page > 2 && $page < ($totpages - 1)) {

$low = $page - 1;
$hi = $page + 1;

if ($low > 4) {
$links .= "... ";
}

if ($low > 3) {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$low\">$low</a> ";
}
if ($page != 3 && $page != ($totpages - 2)) {
$links .= "<font class=\"emph\">$txt_page</font> ";
}
if ($hi < $totpages - 2) {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$hi\">$hi</a> ";
}
if ($hi <= $totpages - 3) {
$links .= "... ";
}
}
$dots = 0;
for ($i = $totpages - 2; $i <= $totpages; $i++) {
if ($dots != 1 && $page >= 9) {
$links .= "... ";
$dots = 1;
}
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$i\">$i</a> ";
}
}
}
}

if($j <= $totpages && $totpages != 1) {
$links .= "<a href=\"view.php?msgid=$msgid&amp;forum=$forum&amp;page=$j\">$txt_next &gt;&gt;</a>";
}

$links .= "</td></tr></table></div>";

echo "$links<div class=\"central\">

<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">";

if ($page == 1) {
$profile = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$user'");
$posts = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$user'");
$userpostcount = mysql_num_rows($posts);
$registerdate = mysql_result($profile, 0, "registerdate");
$usermsn = mysql_result($profile, 0, "usermsn");
$useraol = mysql_result($profile, 0, "useraol");
$usericq = mysql_result($profile, 0, "usericq");
$useryahoo = mysql_result($profile, 0, "useryahoo");
$dispemail = mysql_result($profile, 0, "dispemail");
$useremail = mysql_result($profile, 0, "useremail");
$userhomepage = mysql_result($profile, 0, "userhomepage");
$usersig = mysql_result($profile, 0, "usersig");
$userbanned = 0;
$userbanned = mysql_result($profile, 0, "userbanned");
if ($userhomepage != "" && !strstr($userhomepage, "http://")) {
$userhomepage = "http://".$userhomepage;
}

echo "<tr>
<td rowspan=\"3\" valign=\"top\" class=\"box\" width=\"15%\"><a name=\"$msgid\"></a>
<font class=\"useremph\">$user</font><br/>";
if ($userbanned == 1) {
echo "$txt_banned<br/>";
}
if($registerdate != "" && $registerdate != "0000-00-00") {
echo "$txt_totposts: $userpostcount<br/>";

echo "$txt_registertm:<br/>";
converttime($registerdate, 1);
if(file_exists("gfx/avatars/$user.gif") && $avatars == "1") echo "<br/><img src=\"gfx/avatars/$user.gif\" class=\"avatar\" alt=\"$txt_userposted\"/>";
}
else {
echo "($txt_guestuc)";
}
echo "<br/>
</td>
<td align=\"right\" class=\"boxhdrt\">";

if($registerdate != "" && $registerdate != "0000-00-00") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/profile.gif\" alt=\"$txt_profile\"/></a> &nbsp;";
}

if ($userhomepage != "") {
echo "<a href=\"$userhomepage\" target=_blank><img src=\"gfx/icons/home.gif\" alt=\"$txt_homepage: $userhomepage\"/></a> &nbsp;";
}
if ($dispemail == 1) {
echo "<a href=\"mailto:$useremail\"><img src=\"gfx/icons/mail.gif\" alt=\"$txt_email: $useremail\"/></a> &nbsp;";
}

if ($useraol != "") {
echo "<a href=\"aim:goim?screenname=$useraol&amp;message=Hello...+Are+you+there?\"><img src=\"gfx/icons/aol.gif\" alt=\"$txt_aol: $useraol\"/></a> &nbsp;";
}
if ($usericq != "") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/icq.gif\" alt=\"$txt_icq: $usericq\"/></a> &nbsp;";
}
if ($usermsn != "") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/msn.gif\" alt=\"$txt_msn: $usermsn\"/></a> &nbsp;";
}
if ($useryahoo != "") {
echo "<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=$useryahoo&amp;.src=pg\"><img src=\"gfx/icons/yahoo.gif\" alt=\"$txt_yahoo: $useryahoo\"/></a>";
}

if (isset($logincookie[user]) || $guestpost == "1") {
echo " &nbsp;<a href=\"addmessage.php?msgid=$msgid&amp;a=r&amp;q=$msgid\"><img src=\"gfx/icons/quote.gif\" alt=\"$txt_quote\"/></a>";
}
if (isset($logincookie[user]) && $registerdate != "" && $registerdate != "0000-00-00") {
echo " &nbsp;<a href=\"pm.php?s=o&amp;replyuser=$user\"><img src=\"gfx/icons/pm.gif\" alt=\"$txt_pmsend\"/></a>";
}
echo "</td></tr>
<tr>
<td valign=\"top\" class=\"boxrt\" width=\"85%\">";

$msg = mysql_result($records, 0, "message");
replacestuff($msg);

if (isset($usersig) && $usersig != "") {
echo "<br/>________________<br/>";
$sig = 1;
replacestuff($usersig, $sig, $logincookie[user]);
$sig = 0;
}

$result=mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$msgid'"); 
$timetosort = mysql_result($result, 0, 0);
$timetosort = date($timeform, $timetosort + (3600 * $zone));

echo "<p/></td></tr><tr><td class=\"boxrt\" align=\"right\">$subj $split <font class=\"emph\">$txt_sposted $timetosort</font>";

if ($mod == 1 || $admincheck == 1) {
echo "&nbsp; <a href=\"mod.php?t=ip&amp;a=ip&amp;msgid=$msgid\"><img src=\"gfx/icons/ip.gif\" alt=\"$txt_iplookup\"/></a>";
}

if (($logincookie[user] == $user || $mod == 1 || $admincheck == 1) && $forumlocked != "1" and $topiclocked != "1") {
echo "&nbsp; <a href=\"addmessage.php?msgid=$msgid&amp;a=e\"><img src=\"gfx/icons/edit.gif\" alt=\"$txt_editmsg\"/></a>";
}

if(isset($logincookie[user])) {
echo "&nbsp; <a href=\"report.php?msgid=$msgid\"><img src=\"gfx/icons/report.gif\" alt=\"$txt_reportpost\"/></a>";
}
echo "</td></tr></table></div>";
}

$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$msgid' ORDER BY posttime ASC");


if ($page != 1) {
$firstpost = $firstpost - 1;
}

for ($i = $firstpost - 1; $i < mysql_num_rows($replies); $i++) {

$lastrepid = mysql_result($replies, (mysql_num_rows($replies) - 1), "msgnumber");

if ($firstpost < $lastpost) {
$firstpost++;
$subj = mysql_result($replies, $i, "subject");
$user = mysql_result($replies, $i, "userfrom");

$msgno = mysql_result($replies, $i, "msgnumber");

$profile = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$user'");
$posts = mysql_query("SELECT * FROM ${table_prefix}public WHERE userfrom='$user'");
$userpostcount = mysql_num_rows($posts);
$registerdate = mysql_result($profile, 0, "registerdate");
$usermsn = mysql_result($profile, 0, "usermsn");
$useraol = mysql_result($profile, 0, "useraol");
$usericq = mysql_result($profile, 0, "usericq");
$useryahoo = mysql_result($profile, 0, "useryahoo");
$dispemail = mysql_result($profile, 0, "dispemail");
$useremail = mysql_result($profile, 0, "useremail");
$userhomepage = mysql_result($profile, 0, "userhomepage");
$usersig = mysql_result($profile, 0, "usersig");
$userbanned = 0;
$userbanned = mysql_result($profile, 0, "userbanned");
if ($userhomepage != "" && !strstr($userhomepage, "http://")) {
$userhomepage = "http://".$userhomepage;
}

echo "<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\"><tr>
<td rowspan=\"3\" valign=\"top\" class=\"box\" width=\"15%\"><a name=\"$msgno\"></a>
<font class=\"useremph\">$user</font><br/>";
if ($userbanned == 1) {
echo "$txt_banned<br/>";
}
if($registerdate != "" && $registerdate != "0000-00-00") {
echo "$txt_totposts: $userpostcount<br/>";

echo "$txt_registertm:<br/>";
converttime($registerdate, 1);
if(file_exists("gfx/avatars/$user.gif") && $avatars == "1") echo "<br/><img src=\"gfx/avatars/$user.gif\" class=\"avatar\" alt=\"$txt_userposted\"/>";
}
else {
echo "($txt_guestuc)";
}
echo "<br/>
</td>
<td align=\"right\" class=\"boxrt\">";

if($registerdate != "" && $registerdate != "0000-00-00") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/profile.gif\" alt=\"$txt_profile\"/></a> &nbsp;";
}

if ($userhomepage != "") {
echo "<a href=\"$userhomepage\" target=\"_blank\"><img src=\"gfx/icons/home.gif\" alt=\"$txt_homepage: $userhomepage\"/></a> &nbsp;";
}
if ($dispemail == 1) {
echo "<a href=\"mailto:$useremail\"><img src=\"gfx/icons/mail.gif\" alt=\"$txt_email: $useremail\"/></a> &nbsp;";
}

if ($useraol != "") {
echo "<a href=\"aim:goim?screenname=$useraol&amp;message=Hello...+Are+you+there?\"><img src=\"gfx/icons/aol.gif\" alt=\"$txt_aol: $useraol\"/></a> &nbsp;";
}
if ($usericq != "") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/icq.gif\" alt=\"$txt_icq: $usericq\"/></a> &nbsp;";
}
if ($usermsn != "") {
echo "<a href=\"profile.php?user=$user\"><img src=\"gfx/icons/msn.gif\" alt=\"$txt_msn: $usermsn\"/></a> &nbsp;";
}
if ($useryahoo != "") {
echo "<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=$useryahoo&amp;.src=pg\"><img src=\"gfx/icons/yahoo.gif\" alt=\"$txt_yahoo: $useryahoo\"/></a>";
}

if (isset($logincookie[user]) || $guestpost == 1) {
echo " &nbsp;<a href=\"addmessage.php?msgid=$msgid&amp;a=r&amp;q=$msgno\"><img src=\"gfx/icons/quote.gif\" alt=\"txt_quote\"/></a>";
}
if (isset($logincookie[user]) && $registerdate != "" && $registerdate != "0000-00-00") {
echo " &nbsp;<a href=\"pm.php?s=o&amp;replyuser=$user\"><img src=\"gfx/icons/pm.gif\" alt=\"$txt_pmsend\"/></a>";
}
echo "</td></tr>
<tr>
<td valign=\"top\" class=\"boxrt\" width=\"85%\">";

$msg = mysql_result($replies, $i, "message");

replacestuff($msg);

if (isset($usersig) && $usersig != "") {
echo "<br/>________________<br/>";
$sig = 1;
replacestuff($usersig, $sig, $logincookie[user]);
$sig = 0;
}

$result=mysql_query ("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE reply='$msgid' ORDER BY posttime ASC"); 
$timetosort = mysql_result($result, $i, 0);
$timetosort = date($timeform, $timetosort + (3600 * $zone));

echo "<p/></td></tr><tr><td class=\"boxrt\" align=\"right\">$subj $split <font class=\"emph\">$txt_sposted $timetosort</font>";

if ($mod == 1 || $admincheck == 1) {
echo "&nbsp; <a href=\"mod.php?t=ip&amp;a=ip&amp;msgid=$msgno\"><img src=\"gfx/icons/ip.gif\" alt=\"$txt_iplookup\"/></a>";
}

if (($logincookie[user] == $user || $mod == 1 || $admincheck == 1) && $forumlocked != "1" and $topiclocked != "1") {
echo "&nbsp; <a href=\"addmessage.php?msgid=$msgno&amp;a=e\"><img src=\"gfx/icons/edit.gif\" alt=\"$txt_editmsg\"/></a>";
}

if(isset($logincookie[user])) {
echo "&nbsp; <a href=\"report.php?msgid=$msgno\"><img src=\"gfx/icons/report.gif\" alt=\"$txt_reportpost\"/></a>";
}
echo "</td></tr></table></div>";
}
}

echo "</div>$links";

}
else {
echo "<font class=\"header\"><a href=view.php?forum=$forum>$forumname</a> $split $txt_error</font><p/>

$txt_cantdisplay";
}

}
else {

if(!isset($page)) {
$page = 1;
}

$forumdetails = mysql_query("SELECT * FROM ${table_prefix}forums WHERE forumno='$forum'");

$forumname = mysql_result($forumdetails, 0, "forumname");

echo "<font class=\"header\">$forumname</font><p/>";

$links = "<div class=\"linksbar\"><table width=\"100%\"><tr><td>";

if ($forumlocked == 0) {
if(file_exists("gfx/templates/$template/new.gif")) $links .= "<a href=\"addmessage.php?forum=$forum\"><img src=\"gfx/templates/$template/new.gif\" alt=\"$txt_add\"/></a>";
else $links .= "<a href=\"addmessage.php?forum=$forum\"><img src=\"gfx/templates/$template/new.jpg\" alt=\"$txt_add\"/></a>";
if ($admincheck == 1) {
if(file_exists("gfx/templates/$template/lockf.gif")) $links .= " <a href=\"mod.php?t=lockforum&amp;forum=$forum\"><img src=\"gfx/templates/$template/lockf.gif\" alt=\"$txt_lockforum\"/></a>";
else $links .= " <a href=\"mod.php?t=lockforum&amp;forum=$forum\"><img src=\"gfx/templates/$template/lockf.jpg\" alt=\"$txt_lockforum\"/></a>";
}
}
elseif ($forumlocked == 1) {
if(file_exists("gfx/templates/$template/locked.gif")) $links .= "<img src=\"gfx/templates/$template/locked.gif\" alt=\"$txt_forumlocked\"/></a>";
else $links .= "<img src=\"gfx/templates/$template/locked.jpg\" alt=\"$txt_forumlocked\"/></a>";
if ($admincheck == 1) {
if(file_exists("gfx/templates/$template/unlockf.gif")) $links .= " <a href=\"mod.php?t=unlockforum&amp;forum=$forum\"><img src=\"gfx/templates/$template/unlockf.gif\" alt=\"$txt_unlockforum\"/></a>";
$links .= " <a href=\"mod.php?t=unlockforum&amp;forum=$forum\"><img src=\"gfx/templates/$template/unlockf.jpg\" alt=\"$txt_unlockforum\"/></a>";
}
}

$links .= "</td><td align=\"right\" class=\"pages\">";

$thetotal = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forum' AND type='n'");
$totthreads = mysql_num_rows($thetotal);

$totpages = $totthreads/$threadsperpage;
$totpages = ceil($totpages);

if ($totpages > 1) {

$h = $page - 1;
$j = $page + 1;

if($page > 1) {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$h\">&lt;&lt; $txt_prev</a> ";
}

if ($totpages < 6) {
for ($i = 1; $i <= $totpages; $i++) {
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$i\">$i</a> ";
}
}
}
else {
for ($i = 1; $i <= 3; $i++) {
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?&amp;forum=$forum&amp;page=$i\">$i</a> ";
}
}

if ($page < 3 && $totpages > 6) {
$links .= "... ";
}

if ($page > 2 && $page < ($totpages - 1)) {

$low = $page - 1;
$hi = $page + 1;

if ($low > 4) {
$links .= "... ";
}

if ($low > 3) {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$low\">$low</a> ";
}
if ($page != 3 && $page != ($totpages - 2)) {
$links .= "<font class=\"emph\">$page</font> ";
}
if ($hi < $totpages - 2) {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$hi\">$hi</a> ";
}
if ($hi < $totpages - 3) {
$links .= "... ";
}
}
$dots = 0;
for ($i = $totpages - 2; $i <= $totpages; $i++) {
if ($dots != 1 && $page >= 9) {
$links .= "... ";
$dots = 1;
}
if ($page == $i) {
$links .= "<font class=\"emph\">$i</font> ";
}
else {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$i\">$i</a> ";
}
}
}
}

if($j <= $totpages && $totpages != 1) {
$links .= "<a href=\"view.php?forum=$forum&amp;page=$j\">$txt_next &gt;&gt;</a>";
}

$links .= "</td></tr></table></div>";

echo $links;

echo "<div class=\"boxes\"><table width=\"100%\" cellspacing=\"0\">";

echo "<tr><td class=\"boxhdrt\" width=\"2%\">&nbsp;</td><td class=\"boxhd\" width=\"40%\">$txt_topicuc</td><td class=\"boxhd\" width=\"10%\">$txt_topicstarter</td><td class=\"boxhd\" width=\"10%\">$txt_replies</td><td class=\"boxhdrt\" width=\"40%\">$txt_lastpost</td></tr>";

$lastthread = $threadsperpage * $page;
$firstthread = $lastthread - $threadsperpage + 1;

$pagelinks = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$msgid'");
$totposts = mysql_num_rows($pagelinks);
$totpages = ($totposts + 1)/$postsperpage;
$totpages = ceil($totpages);

$records = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forum' AND type='n' ORDER BY lastreptime DESC");

if(mysql_num_rows($records) == 0) {
echo "<tr><td colspan=\"5\" class=\"boxrt\"><font class=\"emph\">$txt_notopics</font></td></tr>";
}

$announcements = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forum' AND type='a' ORDER BY lastreptime DESC");

if(mysql_num_rows($announcements) > 0) {

for ($i = 0; $i < mysql_num_rows($announcements); $i++) {

$messageid = mysql_result($announcements, $i, "threadid");

$origmsg = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");

$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$noreps = mysql_num_rows($replies);

$lastno = $noreps - 1;

$nopages = $noreps + 1;
$nopages = $nopages/$postsperpage;
$nopages = ceil($nopages);

$lastrepname = mysql_result($replies, $lastno, "userfrom");

$lastrepid = mysql_result($replies, $lastno, "msgnumber");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$lastreptime = mysql_result($result, $lastno, 0);

if ($lastrepname == "") {
$lastrepname = mysql_result($origmsg, 0, "userfrom");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$lastreptime = mysql_result($result, 0, 0);

$lastrepid = mysql_result($origmsg, 0, "msgnumber");

}
else {
}

if (!isset($fjds)) {

$userfrom = mysql_result($origmsg, 0, "userfrom");

$topiclocked = mysql_query("SELECT * FROM threads WHERE threadid='$messageid'");
$topiclocked = mysql_result($topiclocked, 0, "locked");

$filename = "ann";
$alttext = "$txt_announcement";

echo "<tr><td class=\"box\"><img src=\"gfx/templates/$template/icons/$filename.gif\" alt=\"$alttext\"/></td><td class=\"box\">$txt_announcement<br/><a href=\"view.php?msgid=$messageid&amp;forum=$forum\">", mysql_result($origmsg, 0, "subject"), "</a>";

if ($nopages > 1) {
echo "<br/><font class=\"small\">$txt_page: ";

if ($nopages <= 6) {
for ($countpages = 1; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}
else {
for ($countpages = 1; $countpages <= 3; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
echo "... ";
for ($countpages = $nopages-2; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}

echo "</font>";
}
else {
$countpages = 2;
}

echo "</td><td class=\"box\"><a href=\"profile.php?user=$userfrom\">$userfrom</a></td><td class=\"box\">$noreps</td><td class=\"boxrt\">";
if ($lastreptime == "") {
echo $lastrepname;
}
else {
echo "<a href=\"profile.php?user=$lastrepname\">$lastrepname</a> $txt_at ";
}
$lastpage = $countpages - 1;
$lastreptime = date($timeform, $lastreptime + (3600 * $zone));

echo "$lastreptime $split <a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$lastpage#$lastrepid\">$txt_go</a></td></tr>";
}
}

}

$stickies = mysql_query("SELECT * FROM ${table_prefix}threads WHERE forum='$forum' AND type='s' ORDER BY lastreptime DESC");

if(mysql_num_rows($stickies) > 0) {
for ($i = 0; $i < mysql_num_rows($stickies); $i++) {

$messageid = mysql_result($stickies, $i, "threadid");

$origmsg = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");

$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$noreps = mysql_num_rows($replies);

$lastno = $noreps - 1;
$nopages = $noreps + 1;
$nopages = $nopages/$postsperpage;
$nopages = ceil($nopages);

$lastrepname = mysql_result($replies, $lastno, "userfrom");

$lastrepid = mysql_result($replies, $lastno, "msgnumber");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$lastreptime = mysql_result($result, $lastno, 0);

if ($lastrepname == "") {
$lastrepname = mysql_result($origmsg, 0, "userfrom");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$lastreptime = mysql_result($result, 0, 0);

$lastrepid = mysql_result($origmsg, 0, "msgnumber");

}
else {
}

if (!isset($fjdsalfjkalsfjklas)) {

$userfrom = mysql_result($origmsg, 0, "userfrom");

$topiclocked = mysql_query("SELECT * FROM threads WHERE threadid='$messageid'");
$topiclocked = mysql_result($topiclocked, 0, "locked");

$filename = "sti";
$alttext = "$txt_sticky ";


echo "<tr><td class=\"box\"><img src=\"gfx/templates/$template/icons/$filename.gif\" alt=\"$alttext\"/></td><td class=\"box\"><a href=\"view.php?msgid=$messageid&amp;forum=$forum\">", mysql_result($origmsg, 0, "subject"), "</a>";

if ($nopages > 1) {
echo "<br/><font class=\"small\">$txt_page: ";

if ($nopages <= 6) {
for ($countpages = 1; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}
else {
for ($countpages = 1; $countpages <= 3; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
echo "... ";
for ($countpages = $nopages-2; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}

echo "</font>";
}
else {
$countpages = 2;
}

echo "</td><td class=\"box\"><a href=\"profile.php?user=$userfrom\">$userfrom</a></td><td class=\"box\">$noreps</td><td class=\"boxrt\">";
if ($lastreptime == "") {
echo $lastrepname;
}
else {
echo "<a href=\"profile.php?user=$lastrepname\">$lastrepname</a> $txt_at ";
}
$lastpage = $countpages - 1;
$lastreptime = date($timeform, $lastreptime + (3600 * $zone));

echo "$lastreptime $split <a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$lastpage#$lastrepid\">$txt_go</a></td></tr>";
}
}
}


for ($i = $firstthread - 1; $i < mysql_num_rows($records); $i++) {

if ($firstthread <= $lastthread) {
$firstthread++;

$messageid = mysql_result($records, $i, "threadid");

$origmsg = mysql_query("SELECT * FROM ${table_prefix}public WHERE msgnumber='$messageid'");

$replies = mysql_query("SELECT * FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$noreps = mysql_num_rows($replies);

$lastno = $noreps - 1;

$nopages = $noreps + 1;
$nopages = $nopages/$postsperpage;
$nopages = ceil($nopages);

$lastrepname = mysql_result($replies, $lastno, "userfrom");

$lastrepid = mysql_result($replies, $lastno, "msgnumber");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE reply='$messageid' ORDER BY posttime ASC");

$lastreptime = mysql_result($result, $lastno, 0);

if ($lastrepname == "") {
$lastrepname = mysql_result($origmsg, 0, "userfrom");

$result = mysql_query("SELECT UNIX_TIMESTAMP(posttime) as epoch_time FROM ${table_prefix}public WHERE msgnumber='$messageid'");
$lastreptime = mysql_result($result, 0, 0);

$lastrepid = mysql_result($origmsg, 0, "msgnumber");

}
else {
}

$userfrom = mysql_result($origmsg, 0, "userfrom");

$topiclocked = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$messageid'");
$topiclocked = mysql_result($topiclocked, 0, "locked");

$lastvisit = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$logincookie[user]'");
$lastvisit = mysql_result($lastvisit, 0, "lastaccesstime");

$newmsgcheck = mysql_query("SELECT * FROM ${table_prefix}threads WHERE threadid='$messageid'");
$newmsgtime = mysql_result($newmsgcheck, 0, "lastreptime");

if (($logincookie[last] < $newmsgtime) && isset($logincookie[user]) && (!isset($post[$messageid]) || $post[$messageid] != $lastrepid)) {
$filename = "new";
$alttext = "$txt_newposts";
}
else {
$filename = "nonew";
$alttext = "$txt_nonewposts";
}
$hotreps = $hottopic - 1;
if ($noreps > $hotreps) {
$filename .= "hot";
$alttext .= " $txt_hot";
}
if ($topiclocked == 1) {
$filename .= "locked";
$alttext .= " $txt_lockedt";
}

echo "<tr><td class=\"box\"><img src=\"gfx/templates/$template/icons/$filename.gif\" alt=\"$alttext\"/></td><td class=\"box\"><a href=\"view.php?msgid=$messageid&amp;forum=$forum\">", mysql_result($origmsg, 0, "subject"), "</a>";

if ($nopages > 1) {
echo "<br/><font class=\"small\">$txt_page: ";

if ($nopages <= 6) {
for ($countpages = 1; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}
else {
for ($countpages = 1; $countpages <= 3; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
echo "... ";
for ($countpages = $nopages-2; $countpages <= $nopages; $countpages++) {
echo "<a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$countpages\">$countpages</a> ";
}
}

echo "</font>";
}
else {
$countpages = 2;
}

echo "</td><td class=\"box\"><a href=\"profile.php?user=$userfrom\">$userfrom</a></td><td class=\"box\">$noreps</td><td class=\"boxrt\">";
if ($lastreptime == "") {
echo $lastrepname;
}
else {
echo "<a href=\"profile.php?user=$lastrepname\">$lastrepname</a> $txt_at ";
}
$lastpage = $countpages - 1;

$lastreptime = date($timeform, $lastreptime + (3600 * $zone));

echo "$lastreptime $split <a href=\"view.php?msgid=$messageid&amp;forum=$forum&amp;page=$lastpage#$lastrepid\">$txt_go</a></td></tr>";
}
}
echo "</table></div>";

echo $links;

echo "<div class=\"infobox\"><font class=\"subhead\">$txt_key</font><br/>
<table>
<tr><td><img src=\"gfx/templates/$template/icons/new.gif\" alt=\"$txt_newposts\"/> $txt_newposts</td><td><img src=\"gfx/templates/$template/icons/nonew.gif\" alt=\"$txt_nonewposts\"/> $txt_nonewposts</td></tr>
<tr><td><img src=\"gfx/templates/$template/icons/newlocked.gif\" alt=\"$txt_newposts $txt_lockedt\"/> $txt_newposts $txt_lockedt</td><td><img src=\"gfx/templates/$template/icons/nonewlocked.gif\" alt=\"$txt_nonewposts $txt_lockedt\"/> $txt_nonewposts $txt_lockedt</td></tr>
</table></div>
</div>";

}

}

else {
echo "<font class=\"header\">$txt_error</></font><p/>

$txt_forumnorights";
}
}
else {
echo "<font class=\"header\">$txt_error</></font><p/>

$txt_forumnoexist";
}

echo "</div>";
include "footer.php";
?>