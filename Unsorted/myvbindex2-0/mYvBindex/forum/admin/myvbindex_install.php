<?php
error_reporting(7);

require ("./global.php");
cpheader();
$version = "2.0";

?>
<html><head>
<link rel="stylesheet" href="../cp.css">
<title>mYvBindex v<? echo $version; ?> Install script</title>
</HEAD>
<body>
<table width="100%" bgcolor="#3F3849" cellpadding="2" cellspacing="0" border="0"><tr><td>
<table width="100%" bgcolor="#524A5A" cellpadding="3" cellspacing="0" border="0"><tr>
<td><a href="http://vbulletin.com/forum" target="_blank"><img src="cp_logo.gif" width="160" height="49" border="0" alt="Click here to visit the vBulletin support forums"></a></td>
<td width="100%" align="center">
<p><font size="2" color="#F7DE00"><b>mYvBindex Install Script <? echo $version; ?></b></font></p>
<p><font size="1" color="#F7DE00"><b>(Note: Please be patient as some parts of this may take some time.)</b></font></p>
</td></tr></table></td></tr></table>
<br>
<?php

if ($step=="") {
  $step=1;
}

if ($step==1) {


echo "<p>Welcome to mYvBindex version $version. Running this script will do a clean install of mYvBindex onto your server.<p>Step 1: Alters database for news forum.<BR>Step 2: Creates new mYvBindex templates<BR>Step 3: Adds mYvBindex options to your User CP<BR>Step 4: Adds database fields for current weather. (optional)";
echo "<p><a href=\"myvbindex_install.php?step=".($step+1)."\"><b>Click here to begin the installation process --&gt;</b></a></p>\n";
}



if ($step >= 2) {
  include("./config.php");
}

if ($step == 2) {


echo "<p><b>Altering Database Tables to include News ID...</b><p>";

$DB_site->query("ALTER table post ADD isnews char(1) NOT NULL DEFAULT 'N';");

echo "Database Altered!";


echo "<p><a href=\"myvbindex_install.php?step=".($step+1)."\"><b>Click here to continue with the next step --&gt;</b></a></p>\n";
}  // end step 1

if ($step >= 3) {
  include("./config.php");
}

if ($step == 3) {
echo "<p><b>Adding Templates...</b><p>";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index', '{htmldoctype}\r\n<html>\r\n<head>\r\n<!-- no cache headers -->\r\n<meta http-equiv=\"Pragma\" content=\"no-cache\">\r\n<meta http-equiv=\"no-cache\">\r\n<meta http-equiv=\"Expires\" content=\"-1\">\r\n<meta http-equiv=\"Cache-Control\" content=\"no-cache\">\r\n<!-- end no cache headers -->\r\n<META NAME=\"title\" CONTENT=\"\$bbtitle\">\r\n<META NAME=\"keywords\" CONTENT=\"\">\r\n<META NAME=\"description\" CONTENT=\"\">\r\n\r\n<TITLE>\$bbtitle</TITLE>\r\n\$headinclude\r\n</head>\r\n<body>\r\n\$index_header\r\n\r\n<table width=\"100%\" cellspacing=\"10\"><tr align=\"left\" valign=\"top\"><td width=\"165\">\r\n\r\n\r\n\$welcometext\r\n\r\n\$buddylist\r\n\r\n\$loggedinusers\r\n\r\n\$search\r\n\r\n\$custom1\r\n\r\n\$currentpoll\r\n\r\n\$currentweather\r\n\r\n\r\n</td><td valign=\"top\">\$newsbits</td>\r\n\r\n<td valign=\"top\" width=170 >\r\n\r\n\r\n\$custom2\r\n \r\n\$calendar\r\n\r\n\r\n<table cellpadding=\"3\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">\r\n<tr><td valign=\"top\" bgcolor={tableheadbgcolor}><smallfont color=\"{categoryfontcolor}\"><b>&raquo;Latest Forum Topics</b></normalfont>\r\n<table width=\"100%\">\$threadbits</table></td></tr></table><p>\r\n\r\n\r\n</td></tr></table>\r\n\$index_footer\r\n</body></html>')");
echo "Template <i>index</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_buddy', '<tr><td width=\"100%\" nowrap><smallfont><a href=\"\$bburl/member.php?s=\$session[sessionhash]&action=getinfo&userid=\$buddy[userid]\">\$buddy[username]</a></smallfont></td>\r\n<td nowrap><smallfont><a href=\"\$bburl/private.php?s=\$session[sessionhash]&action=newmessage&userid=\$buddy[userid]\">PM</a>\r\n<a href=\"\$bburl/member2.php?s=\$session[sessionhash]&action=removelist&userlist=buddy&userid=\$buddy[userid]\">X</a></smallfont></td></tr>')");
echo "Template <i>index_buddy</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_buddylist', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">\r\n<tr><td valign=\"top\" bgcolor=\"{tableheadbgcolor}\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;<a href=\"\$bburl/member2.php?s=\$session[sessionhash]&action=viewlist&userlist=buddy\" style=\"text-decoration: none\"><smallfont color=\"{categoryfontcolor}\">Online Buddies</smallfont></a></b></normalfont><table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=\"\$getbgrow\">\$onlineusers</tr></td><tr><td height=\"2\" colspan=\"2\"> </td</tr> \$buddypmlink</table></td></tr></table><p>')");
echo "Template <i>index_buddylist</i> Created!<p>\n";
$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_buddypmlink', '<tr><td nowrap colspan=\"2\" align=\"center\" valign=\"top\"><a href=\"\$bburl/private2.php?s=\$session[sessionhash]&action=choosebuddies\"><smallfont>Send PM to Buddies</smallfont></a></td></tr>')");
echo "Template <i>index_buddypmlink</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_calendar', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n<tr><td valign=\"top\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;<a href=\"\$bburl/calendar.php?s=\$session[sessionhash]\" style=\"text-decoration: none\"><smallfont color=\"{categoryfontcolor}\">\$cal_date</a></b></smallfont><table cellpadding=4 width=100% cellspacing=0 bgcolor=\"\$getbgrow\"><tr>\r\n\$calendar_daynames</tr>\r\n<tr bgcolor=\"{calbgcolor}\">\r\n\$calendarbits</tr></table>\r\n</td></tr></table><p>')");
echo "Template <i>index_calendar</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_custom1', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\"><tr><td valign=\"top\">\r\n\r\n<smallfont color=\"{categoryfontcolor}\"><b>&raquo;Custom Template 1</b></smallfont><table cellpadding=4 width=100% cellspacing=0 bgcolor=\"\$getbgrow\"><tr><td><smallfont>\r\n\r\n\r\nCustom Template 1 Content\r\n\r\n\r\n</smallfont></td></tr></form></table></td></tr></table><p>')");
echo "Template <i>index_custom1</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_custom2', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\"><tr><td valign=\"top\">\r\n\r\n<smallfont color=\"{categoryfontcolor}\"><b>&raquo;Custom Template 2</b></smallfont><table cellpadding=4 width=100% cellspacing=0 bgcolor=\"\$getbgrow\"><tr><td><smallfont>\r\n\r\n\r\nCustom Template 2 Content\r\n\r\n\r\n</smallfont></td></tr></form></table></td></tr></table><p>')");
echo "Template <i>index_custom2</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_footer', '</td>\r\n</tr>\r\n</table>\r\n<!-- /content area table -->\r\n</center>\r\n\r\n<p align=\"center\"><smallfont><!-- Do not remove this copyright notice --><A HREF=\"http://vbulletin.com\" target=\"new\" style=\"text-decoration: none\">vBulletin</a>, Copyright ©2000 - 2002, Jelsoft Enterprises Limited<!-- Do not remove this copyright notice --></smallfont>')");
echo "Template <i>index_footer</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_header', '<!-- logo and buttons -->\r\n<center>\r\n<table border=\"0\" width=\"{tablewidth}\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n  <td valign=\"top\" align=\"left\" background=\"{imagesfolder}/menu_background.gif\"><a href=\"\$bburl/index.php?s=\$session[sessionhash]\"><img src=\"{titleimage}\" border=\"0\" alt=\"\$bbtitle\"></a></td>\r\n  <td valign=\"bottom\" align=\"right\" nowrap background=\"{imagesfolder}/menu_background.gif\">\r\n   <!-- toplinks -->\r\n   <a href=\"\$bburl/usercp.php?s=\$session[sessionhash]\"><img src=\"{imagesfolder}/top_profile.gif\" alt=\"Here you can view your subscribed threads, work with private messages and edit your profile and preferences\" border=\"0\"></a>\r\n   <a href=\"\$bburl/register.php?s=\$session[sessionhash]&action=signup\"><img src=\"{imagesfolder}/top_register.gif\" alt=\"Registration is free!\" border=\"0\"></a>\r\n   <a href=\"\$bburl/calendar.php?s=\$session[sessionhash]\"><img src=\"{imagesfolder}/top_calendar.gif\" alt=\"Calendar\" border=\"0\"></a>\r\n   <a href=\"\$bburl/memberlist.php?s=\$session[sessionhash]\"><img src=\"{imagesfolder}/top_members.gif\" alt=\"Find other members\" border=\"0\"></a>\r\n   <a href=\"\$bburl/misc.php?s=\$session[sessionhash]&action=faq\"><img src=\"{imagesfolder}/top_faq.gif\" alt=\"Frequently Asked Questions\" border=\"0\"></a>\r\n   <a href=\"\$bburl/search.php?s=\$session[sessionhash]\"><img src=\"{imagesfolder}/top_search.gif\" alt=\"Search\" border=\"0\"></a>\r\n   <!-- /toplinks -->\r\n  </td>\r\n</tr>\r\n</table>\r\n<!-- /logo and buttons -->\r\n\r\n<!-- content table -->\r\n<table bgcolor=\"{pagebgcolor}\" width=\"{tablewidth}\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\r\n<tr>\r\n  <td>')");
echo "Template <i>index_header</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_logincode', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n<tr>\r\n<td valign=\"top\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;Login</b></smallfont><table cellpadding=4 width=100% cellspacing=0 bgcolor=\"\$getbgrow\"><tr><td><smallfont><form action=\"\$bburl/member.php\" method=\"post\"><b>Username:</b></smallfont><BR><input type=\"hidden\" name=\"s\" value=\"\$session[sessionhash]\">\r\n	<input type=\"hidden\" name=\"action\" value=\"login\">\r\n	<input type=\"text\" class=\"bginput\" name=\"username\" size=\"12\"></td></tr><tr><td><smallfont><b>Password:</b></smallfont><BR>\r\n	<input type=\"password\" name=\"password\" size=\"12\" class=\"bginput\"></td></tr><tr><td>\r\n	<input type=\"submit\" class=\"bginput\" value=\"Login!\">\r\n</td></tr><tr><td>\r\n<smallfont>Not a member yet?<BR><A HREF=\"\$bburl/register.php\">Register Now!</a></td></tr></form></table></td></tr></table><p>')");
echo "Template <i>index_logincode</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_newsbits', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n<tr>\r\n<td valign=\"top\">\$newsicon <normalfont color=\"{categoryfontcolor}\"><b>\$news[title]</b></normalfont><table cellpadding=\"4\" width=\"100%\" border=\"0\" cellspacing=\"0\" bgcolor=\"{firstaltcolor}\">\r\n<tr><td colspan=\"2\" bgcolor=\"{secondaltcolor}\"><smallfont>- by <a href=\"\$bburl/member.php?s=\$session[sessionhash]&action=getinfo&userid=\$news[postuserid]\">\$news[postusername]</a> on \$dateposted </smallfont></td>\r\n</tr>\r\n<tr><td colspan=\"2\"><normalfont>\$news[message]<br></normalfont></td>\r\n</tr>\r\n<tr bgcolor=\"{secondaltcolor}\">\r\n<td align=\"left\"><smallfont>\$adminopts &nbsp;</smallfont></td><td align=\"right\"><smallfont>&nbsp; \$newscomments</smallfont></td></tr></table></td></tr></table><p>')");
echo "Template <i>index_newsbits</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_news_adminopts', '[<a href=\"\$bburl/editpost.php?s=\$session[sessionhash]&action=editpost&postid=\$news[postid]\"><smallfont color=\"{calpubliccolor}\">Edit</smallfont></a>] [<a href=\"\$bburl/newthread.php?s=\$session[sessionhash]&action=newthread&forumid=\$newsforum\"\"><smallfont color=\"{calpubliccolor}\">New News Post</smallfont></a>]')");
echo "Template <i>index_news_adminopts</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_news_commentnull', '\$news[replycount] comments [<a href=\"\$bburl/newreply.php?s=&action=newreply&threadid=\$news[threadid]\">make a comment</a>]')");
echo "Template <i>index_news_commentnull</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_news_comments', '<a href=\"\$bburl/showthread.php?s=\$session[sessionhash]&threadid=\$news[threadid]\">\$news[replycount] comment\$pluralize</a> [<a href=\"\$bburl/newreply.php?s=&action=newreply&threadid=\$news[threadid]\">make a comment</a>]')");
echo "Template <i>index_news_comments</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_news_readmore', '... <nobr>[<a href=\"\$bburl/showthread.php?s=\$session[sessionhash]&threadid=\$news[threadid]\">read more</a>]</nobr></smallfont>')");
echo "Template <i>index_news_readmore</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_online', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n<tr>\r\n<td valign=\"top\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;<a href=\"\$bburl/online.php?s=\$session[sessionhash]\" style=\"text-decoration: none\"><smallfont color=\"{categoryfontcolor}\">Active Users</a>: \$totalonline</b></smallfont><table cellpadding=4 width=100% cellspacing=0 bgcolor=\"\$getbgrow\"><tr><td><smallfont>	\$numberregistered members | \$numberguest guests <BR>\$activeusers<p>Most users ever online was \$recordusers on \$recorddate</smallfont></td></tr></table></td></tr></table><p>')");
echo "Template <i>index_online</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_polldiscuss', '&raquo;<a href=\"\$bburl/showthread.php?s=\$session[sessionhash]&threadid=\$pollinfo[threadid]\">Discuss This Poll</a>')");
echo "Template <i>index_polldiscuss</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_polledit', '<br>&raquo;<a href=\"\$bburl/poll.php?s=\$session[sessionhash]&action=polledit&pollid=\$pollinfo[pollid]\"><smallfont color=\"{calpubliccolor}\">Edit This Poll</smallfont></a>')");
echo "Template <i>index_polledit</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_polloption', '<tr bgcolor=\"{secondaltcolor}\"><td width=\"10%\"><input type=\"radio\" name=\"optionnumber\" value=\"\$option[number]\"></td><td align=\"left\"><smallfont>\$option[question]</smallfont></td></tr>')");
echo "Template <i>index_polloption</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_polloptions', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">\r\n<tr>\r\n<td valign=\"top\" bgcolor=\"{tableheadbgcolor}\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;Poll</b></smallfont><br>\r\n<table cellpadding=4 width=100% cellspacing=\"0\" bgcolor=\"\$getbgrow\"><tr align=\"left\">\r\n<td bgcolor=\"{firstaltcolor}\"><smallfont>\r\n\$pollinfo[question]\r\n<table>\r\n<form action=\"\$bburl/poll.php\" method=\"post\">\r\n<input type=\"hidden\" name=\"s\" value=\"\$session[dbsessionhash]\">\r\n<input type=\"hidden\" name=\"action\" value=\"pollvote\">\r\n<input type=\"hidden\" name=\"pollid\" value=\"\$pollinfo[pollid]\">\r\n\$pollbits</table>\r\n\r\n<input type=\"submit\" value=\"Vote\" name=\"button\" class=\"bginput\"></td></tr>\r\n\r\n<tr><td bgcolor=\"{secondaltcolor}\"><smallfont>&raquo;<a href=\"\$bburl/poll.php?s=\$session[sessionhash]&action=showresults&pollid=\$pollinfo[pollid]\">View Results</a><br>\r\n\$discusspoll\r\n\$editpoll</smallfont></td>\r\n</tr>\r\n</form>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n<p>')");
echo "Template <i>index_polloptions</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_polloption_multiple', '<input type=\"checkbox\" name=\"optionnumber[\$option[number]]\" value=\"yes\">\$option[question]<br>
')");
echo "Template <i>index_polloption_multiple</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_pollresult', '<tr><td align=\"left\" bgcolor=\"{secondaltcolor}\"><smallfont>\$option[question] - \$option[percent]%</smallfont><br><img src=\"{imagesfolder}/polls/bar\$option[graphicnumber]-l.gif\" width=\"3\" height=\"10\" alt=\"\$option[votes] vote\$pluralize\"><img src=\"{imagesfolder}/polls/bar\$option[graphicnumber].gif\" width=\"\$option[barnumber]\" height=\"10\" alt=\"\$option[votes] vote\$pluralize\"><img src=\"{imagesfolder}/polls/bar\$option[graphicnumber]-r.gif\" width=\"3\" height=\"10\" alt=\"\$option[votes] vote\$pluralize\"></td></tr>')");
echo "Template <i>index_pollresult</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_pollresults', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">\r\n<tr>\r\n<td valign=\"top\" bgcolor=\"{tableheadbgcolor}\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;Poll Results</b></smallfont><br>\r\n<table cellpadding=4 width=100% cellspacing=\"0\" bgcolor=\"\$getbgrow\"><tr align=\"left\">\r\n<td bgcolor=\"{firstaltcolor}\"><smallfont>\r\n\$pollinfo[question]</smallfont></td></tr>\r\n\$pollbits\r\n<tr><td align=\"left\" bgcolor=\"{firstaltcolor}\">\r\n<smallfont><b>Total Votes: \$pollinfo[numbervotes]</b><br>\r\n<font color=\"{timecolor}\">\$pollstatus</font></smallfont></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor=\"{secondaltcolor}\"><smallfont>\$discusspoll \$editpoll</smallfont></td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table><p>')");
echo "Template <i>index_pollresults</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_pollresults_closed', 'This poll is closed.')");
echo "Template <i>index_pollresults_closed</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_pollresults_voted', 'You have already voted on this poll.')");
echo "Template <i>index_pollresults_voted</i> Created!<p>\n";


$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_search', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n<tr>\r\n<td valign=\"top\"><smallfont color=\"{categoryfontcolor}\"><b>&raquo;Search Site</b></smallfont><table cellpadding=\"4\" width=\"100%\" cellspacing=\"0\" bgcolor=\"\$getbgrow\"><form action=\"\$bburl/search.php\" method=\"post\" name=\"search\">\r\n<input type=\"hidden\" name=\"s\" value=\"\$session[sessionhash]\">\r\n<input type=\"hidden\" name=\"forumchoice\" value=\"-1\">\r\n<input type=\"hidden\" name=\"searchin\" value=\"subject\">\r\n<input type=\"hidden\" name=\"searchdate\" value=\"-1\">\r\n<tr><td bgcolor=\"{firstaltcolor}\"><input type=\"text\" name=\"query\" size=\"16\" class=\"bginput\"> \$gobutton<br>\r\n<smallfont>&raquo;<a href=\"\$bburl/search.php?s=\$session[sessionhash]\">Advanced Search</a></smallfont></td></tr></form></table></td></tr></table><p>')");
echo "Template <i>index_search</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_threadbit', '<tr>\r\n<td bgcolor=\"\$getbgrow\">\$thread[icon]<normalfont><a href=\"\$bburl/showthread.php?s=\$session[sessionhash]&threadid=\$thread[threadid]\"><b>\$title</b></a></normalfont><br>\r\n<smallfont>\$thread[date] <font color=\"{timecolor}\">\$thread[time]</font><BR> by <a href=\"\$bburl/member.php?s=\$session[sessionhash]&action=getinfo&userid=\$thread[postuserid]\">\$thread[postusername]</a><br>\r\n        Replies: \$thread[replycount] | Views: \$thread[views]</smallfont></td>\r\n        </tr>')");
echo "Template <i>index_threadbit</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_welcometext', '<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\" bgcolor=\"{tableheadbgcolor}\">\r\n        <tr>\r\n        <td valign=\"top\"><smallfont color=\"{categoryfontcolor}\"><b>\r\n        \r\n        \r\n        &raquo;<a href=\"\$bburl/usercp.php?s=\$session[sessionhash]\" style=\"text-decoration: none\"><smallfont color=\"{categoryfontcolor}\">User CP</smallfont></a></b></smallfont></font><table cellpadding=2 width=100% cellspacing=2 bgcolor=\"\$getbgrow\"><tr><td><smallfont>Welcome back, <b>\$username</b><BR><table cellpadding=3><tr><td>\$avatarimage</td></tr></table>\r\n        You last visited:<BR>\$bbuserinfo[lastvisitdate].<BR>\r\n<a href=\"\$bburl/private.php?s=\$session[sessionhash]\">New PM\'s</a>:  \$unreadpm[messages]<BR>\r\n        <a href=\"\$bburl/member.php?s=\$session[sessionhash]&action=logout\">Log Out</a></td></tr></table>\r\n        \r\n        \r\n        \r\n        </td></tr></table><p>')");
echo "Template <i>index_welcometext</i> Created!<p>\n";

$DB_site->query("INSERT INTO template (templatesetid,title,template) VALUES ('-1', 'index_welcometext_avatar', '<a href=\"\$bburl/member.php?s=\$session[sessionhash]&action=editavatar\"><img src=\"\$bburl/\$avatarurl\" border=\"0\"></a>')");
echo "Template <i>index_welcometext_avatar</i> Created!<p>\n";



echo "Templates Created!</p>";
echo "<p><a href=\"myvbindex_install.php?step=".($step+1)."\"><b>Click here to continue with the next step --&gt;</b></a></p>\n";
}
if ($step >= 4) {
  include("./config.php");
}

if ($step == 4) {


$DB_site->query("INSERT INTO settinggroup VALUES (50,'mYvBindex','50');");
echo "Added mYvBindex group to the Admin CP.<p>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'News Forum ID','newsforum','','The ID number of your news forum.','','1');");
echo "Added option to set your news forum ID.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'News Posts Maximum','newslimit','10','The maximum number of news posts to display on your homepage. Set to 0 to disable it.','','2');");
echo "Added option to set the maximum number of news posts displayed.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'News Maximum Characters','maxnewschars','5000','The maximum number of characters to display per news post before it is replaced by the \"read more\" link. Set this to 0 to disable it.','','3');");
echo "Added option to set the maximum number of characters per news post.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Allow News Comments?','showcomments','1','Allow users to comment on news posts? Note: Your news forum may not have permissions set to make it a private forum if you wish to allow comments.','yesno','4');");
echo "Added option to show online users box.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show News Smilies?','shownewssmilies','0','Show smilies in your news posts?','yesno','5');");
echo "Added option to show smilies in your news posts.<br>";    

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show News Icon?','shownewsicon','1','Show your news posts icon?','yesno','6');");
echo "Added option to show news icon.<br>";  

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Latest Threads Maximum','maxlatethreads','10','The maximum number of latest threads to display on your homepage. Set this to 0 to disable it.','','7');");
echo "Added option to set the maximum number of latest threads displayed.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Latest Threads Maximum Characters','maxthreadchars','20','The maximum number of characters of your latest threads title to display before it is replaced by \"...\". Set this to 0 to disable it.','','8');");
echo "Added option to set the maximum number of characters per latest thread.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Latest Threads Date & Time?','showthreaddate','1','Show the time and date the thread was created.','yesno','9');");
echo "Added option to show threads date.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Latest Threads Icon?','showthreadicon','1','Show threads icon in latest threads section.','yesno','10');");
echo "Added option to show threads icons.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Polls Forum ID','pollsforum','','The ID number of your polls forum.','','11');");
echo "Added option to set your polls forum ID.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Poll?','showpoll','1','Show a poll your homepage.','yesno','12');");
echo "Added option to show a poll on your homepage.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Allow Poll Discussion?','showpolldiscuss','1','Show the \"Discuss this poll\" link on your poll and allow users to discuss the poll.','yesno','13');");
echo "Added option to show a link to discuss current poll.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Poll Smilies?','showpollsmilies','0','Show smilies in your poll?','yesno','14');");
echo "Added option to show smilies in your poll.<br>";  

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Users Avatar?','showavatar','1','Show users avatar on your homepage.','yesno','15');");
echo "Added option to show users their avatar.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Private Messages?','showpm','1','Show users new private messages and pop up message if user has a new pm.','yesno','16');");
echo "Added option to show private messages.<br>";
  
$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Users Buddylist?','showbuddylist','1','Show users buddylist on your homepage.','yesno','17');");
echo "Added option to show users their buddylist.<br>";
  
$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Online Users?','showonline','1','Show the online users box.','yesno','18');");
echo "Added option to show online users box.<br>";
  
$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Search Box?','showsearch','1','Show a box to allow users to search your forums.','yesno','19');");
echo "Added option to show search box.<br>";
  
$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Mini Calendar?','showcalendar','1','Show a mini calendar with links to this months events on your homepage.','yesno','20');");
echo "Added option to show calendar.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Custom Template 1?','showcustom1','0','Show your first customized template.','yesno','22');");
echo "Added option to show first custom template.<br>";

$DB_site->query("INSERT INTO setting VALUES (NULL,50,'Show Custom Template 2?','showcustom2','0','Show your second customized template.','yesno','23');");
echo "Added option to show second custom template.<p>";    

  

  



  echo "<br>mYvBindex Installation Complete! Now you may install the Weather Box or log into your Admin CP to set you mYvBindex options. Also don't forget to delete this file from your admin folder. Not deleting this file is a security risk!<p>";
echo "<a href=\"weather_install.php\"><b>Run Weather Install Script --&gt;</b></a><p><a href=\"index.php\"><b>Skip Weather and Log into Admin CP --&gt;</b></a>\n";


}



echo "</"."body>";
echo "<"."!--";
cpfooter();
?>

-->
</html>