<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

/*
	+-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	| Upgrade Script
	| Version - 1.1.0 to 1.1.1
	| Instructions: Upload to Utopia News Pro directory and
	|               run. Immediately remove file from server
	|               after use.
	+-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

*/

/* General Config */
$version_current = '1.1.0'; $version_current_pdless = 110;
$version_next = '1.1.1'; $version_next_pdless = 111;

$this_upgrade = 'upgrade2.php';
$next_upgrade = 'upgrade3.php';
/* General Config */

require('functions.inc.php');

// +------------------------------------------------------------------+
// | Step Setup                                                       |
// +------------------------------------------------------------------+
if (isset($_POST['step1'])) {       // <--Create New Tables
	$step = 1;
}
elseif (isset($_POST['step2']))
{ // <--Update FAQs
	$step = 2;
}
elseif (isset($_POST['step3']))
{ // <--Settings Update
	$step = 3;
}
elseif (isset($_POST['step4']))
{ // <--Table Alteration
	$step = 4;
}
elseif (isset($_POST['step5']))
{ // <--Complete
	$step = 5;
}
else
{                            // <--Introduction
	$step = 0;
}

// +------------------------------------------------------------------+
// | Global HTML                                                      |
// +------------------------------------------------------------------+
$p_header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" href="style.css" media="all" />
	<title>Utopia News Pro '.$version_current.' to '.$version_next.' Upgrade</title>
<meta name="generator" content="Utopia News Pro - http://www.utopiasoftware.net/" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>

<center>
<div align="left" class="hbox">
<div class="lbox"><img src="images/unp_logo.jpg" alt="Utopia News Pro" border="0" /></div>
</div></center>
<br />';

$p_openbox = '<center>
<div align="left" class="box">
<font class="normalfont">
<strong>Utopia News Pro Installer - Step '.$step.' of 5</strong><br /><br />';

$p_closebox = '</font></div></center>';

$p_footer = '
<br />
<center>
<div align="center" class="fbox">
<span class="smallfont">
Utopia News Pro Upgrade<br />
Copyright &copy;2003 UtopiaSoft, UtopiaSoftware.net
</span>
</div></center>
</body>
</html>';

// +------------------------------------------------------------------+
// | Step 0 - Introduction                                            |
// +------------------------------------------------------------------+
if ($step == 0)
{
	if (str_strip('.', $version) <= $version_current_pdless)
	{
		unp_msgbox('You are attempting to upgrade from version '.$version_current.' to '.$version_next.', but you are running version '.$version.'.');
		exit;
	}
	echo ($p_header);
	echo ($p_openbox);
	echo 'Welcome to the Utopia News Pro upgrade! The upgrade will first add any new tables that were not present in version '.$version_current.'<br /><br />';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step1" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Step 1 - Add New Tables                                          |
// +------------------------------------------------------------------+
elseif ($step == 1)
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade is now adding new tables that appear in '.$version_next.'<br /><br />';
	$DB->query("CREATE TABLE `unp_template` (
	`id` smallint(4) unsigned NOT NULL auto_increment,
	`setid` smallint(3) unsigned NOT NULL default '0',
	`templatename` varchar(50) NOT NULL default '',
	`template` mediumtext NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `templatename` (`templatename`)
	) TYPE=MyISAM
	");

	$DB->query("INSERT INTO `unp_template` (`id`, `setid`, `templatename`, `template`) VALUES
	(1, 3, 'news_newsbit', '<!-- News.Bit - NewsID {\$newsid} -->\r\n<a name=\"unpnews{\$newsid}\"></a>\r\n<table width=\"90%\" align=\"center\">\r\n	<tr><td>\r\n		<table border=\"0\" width=\"100%\" valign=\"top\" style=\"border: {\$bordercolor} 1px solid\" cellpadding=\"5\" cellspacing=\"0\">\r\n			<tr>\r\n				<td bgcolor=\"{\$subjectbg}\" style=\"border-bottom : {\$bordercolor} 1px solid\" colspan=\"2\">\r\n				<font face=\"verdana,arial,helvetica\" color=\"{\$subjecttext}\" size=\"2\"><strong>{\$subject}</strong></font></td>\r\n			</tr>\r\n			<tr>\r\n				<td bgcolor=\"{\$newsbg}\" colspan=\"2\" style=\"border-bottom : {\$bordercolor} 1px solid\">\r\n				<font face=\"verdana,arial,helvetica\" color=\"{\$newstextcolor}\" size=\"2\">{\$newstext}</font></td>\r\n			</tr>\r\n			<tr>\r\n				<td bgcolor=\"{\$postdatebg}\" align=\"right\" width=\"100%\">\r\n				<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tr>\r\n						<td align=\"left\">{\$commentsinfo}</td>\r\n						<td align=\"right\"><font face=\"verdana,arial,helvetica\" color=\"{\$postdatetext}\" size=\"1\"><strong>Posted by <a href=\"{\$unpurl}news.php?action=mail&uname={\$poster}\"><font color=\"{\$tableheadlink}\" size=\"1\">{\$poster}</font></a> on {\$postdate} at {\$posttime}</strong></font></td>\r\n					</tr>\r\n				</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td></tr>\r\n</table>\r\n<br />\r\n<!-- News.Bit - NewsID {\$newsid} -->'),
	(2, 3, 'news_newsbit_commentslink', '<a onClick=\'open(\"{\$unpurl}/comments.php?action=list&newsid={\$newsid}\",\"View\",\"width=550, height=550, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no\")\' href=\"javascript:void(0)\"><font face=\"verdana, arial, helvetica\" color=\"{\$postdatetext}\" size=\"1\"><strong>Comments:</strong> {\$comments}</font></a>&nbsp;<a onClick=\'open(\"{\$unpurl}/comments.php?action=post&newsid={\$newsid}\",\"Post\",\"width=550, height=550, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no\")\' href=\"javascript:void(0)\"><font face=\"verdana, arial, helvetica\" color=\"{\$postdatetext}\" size=\"1\">(Add Comment)</font></a>'),
	(3, 2, 'headlines_displaybit', '<a href=\"{\$siteurl}#unpnews{\$newsid}\"><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">{\$subject}</a> - {\$date}</font><br />'),
	(4, 4, 'printable_header', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\r\n	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>{\$sitetitle} - Powered by Utopia News Pro</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: #ffffff;\r\n	color: #000000;\r\n	margin: 10px;\r\n	font-family: verdana,arial,helvetica,sans-serif;\r\n	font-size: 12px;\r\n}\r\na:link, a:active, a:visited {\r\n	color: #000000;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<strong><font size=\"4\" face=\"verdana,arial, helvetica\" color=\"#000000\" style=\"font-variant: small-caps\">{\$sitetitle} News</font></strong><br />\r\n<font size=\"2\" face=\"verdana,arial, helvetica\" color=\"#000000\">Powered by Utopia News Pro</font><br />\r\n<font size=\"2\" face=\"verdana,arial, helvetica\" color=\"#000000\">{\$siteurl}</font>\r\n{\$showall_link}\r\n<hr />\r\n<font size=\"2\" face=\"verdana,arial, helvetica\" color=\"#000000\"><strong>News:</strong></font><br />'),
	(5, 4, 'printable_footer', '<center><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">News generated by </font><a href=\"http://www.utopiasoftware.net\"><font face=\"verdana,arial,helvetica\" size=\"1\" color=\"{\$linkcolor}\">Utopia News Pro</font></a></center>\r\n</body>\r\n</html>'),
	(6, 3, 'news_footer', '<center><a href=\"{\$unpurl}news.php?action=printable\"><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">Show Printable Version</font></a></center><br />\r\n\r\n<center><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">News generated by </font><a href=\"http://www.utopiasoftware.net\"><font face=\"verdana,arial,helvetica\" size=\"1\" color=\"{\$linkcolor}\">Utopia News Pro</font></a></center>'),
	(7, 4, 'printable_newsbit', '<!-- PrintNews.Bit -->\r\n<font size=\"2\" face=\"verdana,arial,helvetica\" color=\"#000000\">\r\n<strong>{\$subject}</strong><br />\r\n<em>Posted by {\$poster} on {\$postdate} at {\$posttime}</em></font>\r\n<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td><font size=\"2\" face=\"verdana,arial,helvetica\" color=\"#000000\">{\$newstext}</font></td>\r\n	</tr>\r\n</table>\r\n<hr />\r\n<!-- PrintNews.Bit -->\r\n\r\n'),
	(8, 1, 'comments_submit', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>Post Comments - {\$subject}</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n}\r\nform {\r\n	display: inline;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<table width=\"90%\" align=\"center\">\r\n	<tr><td>\r\n	<table border=\"0\" width=\"100%\" valign=\"top\" style=\"border: {\$bordercolor} 1px solid\" cellpadding=\"5\" cellspacing=\"0\">\r\n	<tr><td bgcolor=\"{\$subjectbg}\" style=\"border-bottom : {\$bordercolor} 1px solid\" colspan=\"2\"><font face=\"verdana,arial,helvetica\" color=\"{\$subjecttext}\" size=\"2\"><strong>Submit Comments</strong> - {\$subject}</font></td></tr>\r\n	<tr><td bgcolor=\"{\$newsbg}\" colspan=\"2\" style=\"border-bottom : {\$bordercolor} 1px solid\">\r\n<!-- Submission Form -->\r\n<form action=\"comments.php\" method=\"post\">\r\n<font face=\"verdana,arial,helvetica\" color=\"{\$newstextcolor}\" size=\"2\">\r\n<strong>Name:</strong><br />\r\n<input type=\"text\" name=\"name\" value=\"{\$username}\" size=\"30\" /><br />\r\n<strong>Email Address:</strong><br />\r\n<input type=\"text\" name=\"email\" value=\"\" size=\"30\" /><br />\r\n<strong>Subject:</strong><br />\r\n<input type=\"text\" name=\"title\" value=\"Re: {\$subject}\" size=\"30\" /><br />\r\n<strong>Comments:</strong><br />\r\n<textarea rows=\"15\" cols=\"55\" name=\"comments\"></textarea><br />\r\n<input type=\"hidden\" value=\"{\$newsid}\" name=\"newsid\" /><input type=\"hidden\" value=\"{\$ipaddress}\" name=\"ipaddress\" /><input type=\"hidden\" value=\"{\$proxy}\" name=\"proxy\" />\r\n<input type=\"submit\" name=\"submitcomment\" value=\"Post Comment\" />\r\n</font>\r\n</form>\r\n<!-- / Submission Form -->\r\n</td></tr>\r\n	<tr><td bgcolor=\"{\$postdatebg}\" align=\"right\" width=\"100%\">\r\n	<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr><td align=\"left\"><font face=\"verdana,arial, helvetica\" color=\"{\$postdatetext}\" size=\"1\"><strong>Date:</strong> {\$date} at {\$time}</font></td>\r\n	<td align=\"right\"><font face=\"verdana,arial,helvetica\" color=\"{\$postdatetext}\" size=\"1\"><strong>IP Address:</strong> {\$ipaddress} <strong>Proxy:</strong> {\$proxy}</font></td>\r\n	</tr>\r\n	</table>\r\n	</td>\r\n	</tr>\r\n</table>\r\n<br />\r\n</td></tr></table>\r\n</body>\r\n</html>'),
	(9, 1, 'comments_list_header', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>Post Comments - {\$subject}</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<table width=\"90%\" align=\"center\">\r\n	<tr><td>\r\n	<table border=\"0\" width=\"100%\" valign=\"top\" style=\"border: {\$bordercolor} 1px solid\" cellpadding=\"5\" cellspacing=\"0\">\r\n	<tr><td bgcolor=\"{\$subjectbg}\" style=\"border-bottom : {\$bordercolor} 1px solid\" colspan=\"2\"><font face=\"verdana,arial,helvetica\" color=\"{\$subjecttext}\" size=\"2\"><strong>Comments</strong> - {\$subject}</font></td></tr>\r\n	<tr><td bgcolor=\"{\$newsbg}\" colspan=\"2\" style=\"border-bottom : {\$bordercolor} 1px solid\"><font face=\"verdana,arial,helvetica\" color=\"{\$newstextcolor}\" size=\"2\"><strong>News:</strong><br />\r\n<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>\r\n<font face=\"verdana,arial,helvetica\" color=\"{\$newstextcolor}\" size=\"2\">\r\n{\$news}\r\n</font></td></tr></table>\r\n<hr />\r\n<!-- Comments -->\r\n<strong>Comments:</strong><br /><br />'),
	(10, 1, 'comments_list_commentbit', '<!-- comment.bit -->\r\n<strong><font size=\"3\">{\$c_title}</font></strong>\r\n\$removecommentlink\r\n<br />\r\n<strong>Poster:</strong> <a href=\"mailto:{\$c_email}\"><font face=\"verdana,arial,helvetica\" size=\"2\" color=\"{\$linkcolor}\">{\$c_name}</font></a><br />\r\n<strong>Date:</strong> {\$c_date} at {\$c_time}<br />\r\n\$ipaddressinfo\r\n<strong>Comments:</strong><br />\r\n{\$c_text}\r\n<hr />\r\n<!-- comment.bit -->'),
	(13, 1, 'comments_redirect_posted', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<link rel=\"stylesheet\" href=\"style.css\" />\r\n<title>{\$sitetitle} - Powered by Utopia News Pro</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<meta http-equiv=\"refresh\" content=\"1; url=comments.php?action=list&newsid={\$newsid}\" />\r\n<style type=\"text/css\">\r\nbody {\r\n   background-color: {\$bgcolor};\r\n   color: {\$newstextcolor};\r\n   margin: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<center>\r\n<div align=\"center\" style=\"color: black; background-color: #FFFFFF; border: 1px solid black; padding:5px; width: 750px\">\r\n<span class=\"smallfont\"><strong>Comments Added - Taking You Back To Comments</strong><br />\r\n<a href=\"comments.php?action=list&newsid={\$newsid}\">Click here if you do not wish to wait<br />(Or if your browser doesn\'t forward you)</a></span>\r\n</div></center>\r\n</body>\r\n</html>'),
	(11, 1, 'comments_list_commentbit_removecomment', '<br /><a href=\"comments.php?action=delete&cid={\$c_id}\"><font face=\"verdana,arial,helvetica\" size=\"2\" color=\"{\$linkcolor}\">[Remove Comment]</font></a>'),
	(12, 1, 'comments_list_commentbit_ipaddress', '<strong>IP Address:</strong> {\$c_ipaddress}<br /><strong>Proxy:</strong> {\$c_proxy}<br />'),
	(14, 1, 'comments_redirect_deleted', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<link rel=\"stylesheet\" href=\"style.css\" />\r\n<title>Comment(s) Removed</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<meta http-equiv=\"refresh\" content=\"1; url=comments.php?action=list&newsid={\$newsid}\" />\r\n<style type=\"text/css\">\r\nbody {\r\n  background-color: {\$bgcolor};\r\n  color: {\$newstextcolor};\r\n  margin: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<center>\r\n<div align=\"center\" style=\"color: black; background-color: #FFFFFF; border: 1px solid black; padding:5px; width: 750px\">\r\n<span class=\"smallfont\"><strong>Comment(s) Removed - Taking You Back To Comments</strong><br />\r\n		<a href=\"comments.php?action=list&newsid={\$newsid}\">Click here if you do not wish to wait<br />(Or if your browser doesn\'t forward you)</a></span>\r\n</div></center>\r\n</body>\r\n</html>'),
	(15, 1, 'comments_list_footer', '						<!-- / Comments -->\r\n						</font></td>\r\n					</tr>\r\n					<tr>\r\n						<td bgcolor=\"{\$postdatebg}\" align=\"right\" width=\"100%\">&nbsp;</td>\r\n					</tr>\r\n				</table>\r\n			<br />\r\n		</td></tr></table>\r\n</body>\r\n</html>'),
	(16, 4, 'printable_showall_link', '<br /><a href=\"{\$unpurl}news.php?action=printable&amp;showall\">Show All News</a>')
	");

	echo 'Successfully created new tables!';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step2" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Step 2 - Update FAQs                                             |
// +------------------------------------------------------------------+
elseif ($step == 2)
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'The upgrade will now update the internal FAQ.<br /><br />';
	// EMPTY TABLES
	echo 'Emptying table <strong>faq_categories</strong>...<br /><br />';
	$DB->query("DELETE FROM `faq_categories`");
	
	echo 'Emptying table <strong>faq_questions</strong>...<br /><br />';
	$DB->query("DELETE FROM `faq_questions`");
	// REPOPULATE NEW TABLES
	echo 'Repopulating table <strong>faq_categories</strong>...<br /><br />';
	$DB->query("INSERT INTO `faq_categories` (`id`, `display`, `catname`) VALUES
	(1, 1, 'Posting News'),
	(2, 3, 'News Cache'),
	(3, 2, 'Editing News'),
	(4, 4, 'Settings'),
	(5, 5, 'Styles'),
	(6, 6, 'User Management'),
	(7, 9, 'Miscellaneous'),
	(8, 7, 'Comments'),
	(9, 8, 'Templates Editor')
	");
	
	echo 'Repopulating table <strong>faq_questions</strong>...<br /><br />';
	$DB->query("INSERT INTO `faq_questions` (`id`, `groupid`, `question`, `answer`) VALUES
	(1, 1, 'How do I post news?', 'To post news to the database, click either on the \"Post News\" link in the header or on the main News Pro page. Once on the news posting page, fill in both the Subject and News fields with the news subject and content respectively. When done, press Post News, and your news will be submitted. (NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(2, 1, 'Why do I receive errors when trying to submit news?', 'You are most likely not filling in both the subject and news fields. Both fields are required to submit news.'),
	(3, 1, 'Why do the dates on the posting page and the actual post date differ?', 'The date shown on the news posting page is the time that the page was loaded. In most cases, it will take some time to enter the news, and the date and time upon which the news is actually submitted usually differs from the time the page was loaded. The time of submission is the time recorded.'),
	(4, 2, 'What is the news cache?', 'The news cache saves the display of the dynamic news page into a single text file. This greatly increases the speed of displaying news (especially on high-traffic sites) as it does not have to be extracted from the database and dynamically altered; it can merely be pulled out of a static text file very quickly.'),
	(5, 2, 'How do I use the news cache?', 'To use the news cache, first submit news as you normally would. Then, go to the news cache management page and click either \"Update News Cache\" or \"Create News Cache\". After submitting new news or after editing news, you must click \"Update News Cache\" again unless Auto Cache Update is turned ON in the settings.'),
	(6, 2, 'When would I use the news cache?', 'You may use the cache for many reasons.<blockquote>1. You have a high-traffic site in which you want to take the extra load posed by dynamic news off the server.<br />2. You wish to speed up your news display by converting it into static files.<br />3. You wish to ensure that your news page does not display un-professional looking errors in times when MySQL might be down.</blockquote>'),
	(7, 3, 'How do I edit my news?', 'To edit existing news, click on the \"Edit News\" link in the header or on the main News Pro page. Navigate to the news post which you want to edit and make necessary changes. Then, press Submit to commit the changes to the database.\r\n(NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(8, 3, 'How do I delete news?', 'To delete existing news, click on the \"Edit News\" link in the header or on the main News Pro page. Navigate to the news post which you want to delete and click the checkbox next to Delete News. Then, press Submit to delete the news from the database.\r\n(NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(9, 4, 'Why should I turn HTML off?', 'You should turn HTML off because, with it on, it could allow users to entirely alter the news display page in a very bad way. Instead, if you wish to be able to do many HTML formatting techniques, use UNP code.'),
	(10, 4, 'What are UNP codes?', 'UNP codes are replacements for certain basic HTML codes. They are as follows:<br />\r\n<table border=\"1\" align=\"left\" cellpadding=\"1\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[b]This text is bold.[/b]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><strong>This text is bold.</strong></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[i]This text is italicized.[/i]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><em>This text is italicized.</em></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[u]This text is underlined.[/u]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><u>This text is underlined.</u></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[url]http://www.utopiasoftware.net[/url]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><a href=\"http://www.utopiasoftware.net/\" target=\"_blank\"><font class=\"normalfont\">http://www.utopiasoftware.net</font></a></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[url=http://www.utopiasoftware.net]UtopiaSoftware[/url]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"http://www.utopiasoftware.net/\" target=\"_blank\"><font class=\"normalfont\">UtopiaSoftware</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[email]support@utopiasoftware.net[/email]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"mailto:support@utopiasoftware.net\" target=\"_blank\"><font class=\"normalfont\">support@utopiasoftware.net</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[email=support@utopiasoftware.net]Email Us[/url]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"mailto:support@utopiasoftware.net\" target=\"_blank\"><font class=\"normalfont\">Email Us</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[color=red]This text is red.[/color]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><font color=\"#FF0000\">This text is red.</font></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[size=3]This text is size 3.[/size]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><font size=\"3\">This text is size 3.</font></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[blockquote]This text is blockquoted.[/blockquote]</font></center></td>\r\n		<td width=\"50%\"><font class=\"normalfont\"><blockquote>This text is blockquoted.</blockquote></font></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[img]http://www.utopiasoftware.net/images/icon_ok.gif[/img]</font></center></td>\r\n		<td width=\"50%\"><center><img src=\"images/icon_ok.gif\" alt=\"Image\"></center></td>\r\n	</tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[hr] - Horizontal Ruler:</font></center></td>\r\n		<td width=\"50%\"><center><hr /></center></td>\r\n	</tr>\r\n</table>'),
	(11, 4, 'What are the date/time formats for?', 'This is the format of how you want the date and time to be displayed throughout Utopia News Pro as well as on the news display page. If you are unsure of what to fill in, either leave them at the defaults or check the resources listed for more information.'),
	(12, 5, 'What are the styles colors used for?', 'The styles colors are used on the news display page (news.php and news.txt). Colors entered here are automatically sent to replace the default colors on the news display page.'),
	(13, 5, 'What kind of values am I supposed to enter for a style color?', 'You can enter either hexadecimal values or color names. For example, if you want red, you can enter either \"red\" or \"#FF0000\" (including the #). Either way, the color will be red. Generally it is best to use hexadecimal values when possible.'),
	(14, 6, 'How do I add another user?', 'To add another user to the database, click either on the \"Manage Users\" link in the header or on the main News Pro page. Then, click on the \"Add User\" link. Once on the new user page, fill in all fields and select a user level. When done, press Add User.'),
	(15, 6, 'How do I edit a previously created user?', 'To edit an existing user, click on the \"Manage users\" link in the header or on the main News Pro page. Find the user you want to edit on the main users list, and click on the [Edit] link next to their name. Again, fill in all fields, and press Submit Changes, and the changes will be committed to the database.'),
	(16, 6, 'How do I remove a user?', 'To remove an existing user, click on the \"Manage users\" link in the header or on the main News Pro page. Find the user you want to edit on the main users list, and click on the [Remove] link next to their name. On the next page, you will be prompted with a final chance to cancel the action. Confirm that you want to remove the user by pressing Yes, and the user will be removed. Additionally, you can navigate to the delete users prompt by going to edit a user\'s profile first, and then clicking Delete This User.'),
	(17, 6, 'What is the purpose of the user levels?', 'The user levels allow you to create special users who have more or less abilities than other users. This ensures that you don\'t trust too many people with too many abilities.'),
	(18, 6, 'What are the user levels?', 'The user levels are as follows:<blockquote>\r\n<b>Administrator</b> - Has access to all settings and styles, can post news, can edit <i>all</i> news<br /><b>Enhanced Level</b> - Can not edit settings or styles, can post news, can edit <i>all</i> news<br /><b>Standard Level</b> - Can not edit settings or styles, can post news, can edit only own news</blockquote>'),
	(19, 5, 'Why don\'t the example color boxes update?', 'The example color boxes update upon submission of changes and the reloading of the page, <i>not</i> upon entering a color value.'),
	(20, 5, 'Why are my colors showing up incorrectly?', 'Colors will only show up incorrectly if you have not entered a correct/valid value. Ensure that you have entered the correct hexadecimal value (including the #) or have spelled the color correctly.'),
	(21, 4, 'What are the smilies?', 'The smilies are small emoticons that can be used in news posts. They are as follows:<br />\r\n:) - <img src=\"images/smilies/happy.gif\" /><br />\r\n:( - <img src=\"images/smilies/sad.gif\" /><br />\r\n;) - <img src=\"images/smilies/wink.gif\" /><br />\r\n:D - <img src=\"images/smilies/biggrin.gif\" /><br />\r\n:P - <img src=\"images/smilies/tongue.gif\" /><br />\r\n:angry: - <img src=\"images/smilies/angry.gif\" /><br />\r\n:cool: - <img src=\"images/smilies/cool.gif\" /><br />\r\n:confused: - <img src=\"images/smilies/confused.gif\" /><br />'),
	(22, 4, 'What are headlines?', 'Headlines is a file that generates the latest <i>x</i> news posts\' subjects and dates which can then be included into a page to link to the news posts. For example, check out your <a href=\"headlines.php\" target=\"_blank\">headlines</a> page (only if headlines are enabled).'),
	(23, 7, 'How do I include my news?', 'There are two methods of including news: SSI and PHP includes. If the page in which you want to include your news is a standard HTML page, you will use SSI. If the page is PHP, you can use the more efficient PHP includes. If your page is HTML, rename the page with a *.shtml extension (unless your host allows SSI on *.html or *.htm pages). To include the news, place the following code wherever you want in the page:<br /><br /><tt>&lt;!--#include virtual=\"unp/news.php\"--&gt;</tt> - If you are <b>NOT</b> using the news cache<br /><br />OR<br /><br /><tt>&lt;!--#include virtual=\"unp/news.txt\"--&gt;</tt> - If you <b>ARE</b> using the news cache.<br /><br />If your page is PHP, there is no need to rename any files. Just place the following code wherever you want the news in the page:<br /><br /><tt>include(\'unp/news.php\');</tt> - If you are <b>NOT</b> using the news cache<br /><br />OR<br /><br /><tt>include(\'unp/news.txt\');</tt> - If you <b>ARE</b> using the news cache.<br /><br />You may need to surround the PHP statement with <tt>&lt;?php</tt> and <tt>?&gt;</tt> if PHP is not being parsed in the region you want the news inclusion. Please note that all 4 examples assume that the page the news is to be included in is one directory higher than UNP in directory structure. (ie. news page is <tt>/</tt>, unp is <tt>/unp</tt>). Remember to replace <tt>unp/</tt> with UNP\'s actual directory.'),
	(24, 7, 'How do I include my headlines?', 'There are two methods of including headlines: SSI and PHP includes. If the page in which you want to include your headlines in is a standard HTML page, you will use SSI. If the page is PHP, you can use the more efficient PHP includes. If your page is HTML, rename the page with a *.shtml extension (unless your host allows SSI on *.html or *.htm pages). To include the headlines, place the following code wherever you want in the page:<br /><br /><tt>&lt;!--#include virtual=\"unp/headlines.php\"--&gt;</tt><br /><br />If your page is PHP, there is no need to rename any files. Just place the following code wherever you want the news in the page:<br /><br /><tt>include(\'unp/headlines.php\');</tt><br /><br />You may need to surround the PHP statement with <tt>&lt;?php</tt> and <tt>?&gt;</tt> if PHP is not being parsed in the region you want the headlines inclusion. Please note that all 4 examples assume that the page the headlines are to be included in is one directory higher than UNP in directory structure. (ie. headlines page is <tt>/</tt>, unp is <tt>/unp</tt>). Remember to replace <tt>unp/</tt> with UNP\'s actual directory.'),
	(25, 7, 'How do I change the way the news looks?', 'The newsbit, as well as all other templates, can be altered in the template editor. The template for the way news looks is called <tt>news_newsbit</tt>.'),
	(26, 8, 'What are comments?', 'Comments are anonymously posted statements regarding a piece of news. Site visitors can post comments that other visitors can read.'),
	(27, 8, 'How do I disable comments?', 'If you do not want to use comments, you can disable them in the settings. Disabling comments does <i>not</i> remove them from the database. You can still enable them again and retain the original comments.'),
	(28, 8, 'Can I remove comments en masse?', 'You can remove all of the comments to a single news post on the Edit News page. Click on [Remove All Comments], and it will immediately remove all comments; there is no confirmation.'),
	(29, 8, 'What is allowed in comments?', 'In comments, only smilies are enabled (if smilies are turned on in the settings). UNPCode and HTML cannot and should not be enabled in comments for security reasons.'),
	(30, 8, 'Why can\'t I post comments under my UNP username?', 'You cannot post comments under any registered UNP username <i>unless</i> you are logged in at the time. Login before trying to post under a registered username.'),
	(31, 8, 'What can I do if someone is spamming the comments?', 'If someone is spamming your comments, log in to UNP first, and then view the comments made by this person. The IP address (and proxy, if applicable) of the poster is always logged. With the IP, you can find out the Internet Service Provider (ISP) of the poster and report them for defacing/vandalizing parts of your site.'),
	(32, 9, 'What are templates?', 'Templates are the editable chunks of HTML that are output on certain public pages of UNP such as comments pages and the news output. The template editor allows you to edit these and tailor them to your needs.'),
	(33, 9, 'What are \"*bits\"?', '*Bit templates are templates that are used repeatedly on a specific page. (ie. the newsbit is the template used repeatedly for each instance of a news post on the news display page, the headlinesbit template is the template used repeatedly for each instance of a headline.)'),
	(34, 9, 'How do I use variables in templates?', 'When using a variable in a template (ie. \$var), enclose the variable within curly braces (ie. {\$var}).'),
	(35, 9, 'How do I add my own templates?', 'Converting your UNP installation to a developer build will enable you to add your own custom templates. To convert, locate your global.inc.php file and change<br /><br />define(\"DEV_BUILD\", false);<br /><br />to<br /><br />define(\"DEV_BUILD\", true);'),
	(36, 9, 'How do I use custom templates?', 'Custom templates can be used and accessed from within the code itself. If you are modifying UNP functionality or adding functionality, then you can create custom templates for anything that you\'ll need. To use them, you must first activate the developer features.'),
	(37, 9, 'How do I know what each template is used for?', 'Templates are named and grouped by the page that they appear in. They have names that should fairly accurately describe the purpose of the template. If you cannot figure out what the template is used for just by looking at it, it would be wisest to not alter the template.')
	");

	echo 'Successfully updated FAQ!';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step3" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Step 3 - Settings Update                                         |
// +------------------------------------------------------------------+
elseif ($step == 3)
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade will now add any settings introduced by features that appear in '.$version_next.'<br /><br />';
	echo 'This version does not introdude any new settings.';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step4" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Step 4 - Table Alteration                                        |
// +------------------------------------------------------------------+
elseif ($step == 4)
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade is now altering any tables that changed between '.$version_current.' and '.$version_next.'<br /><br />';
	$DB->query("ALTER TABLE `comments` RENAME `unp_comments`");
	$DB->query("ALTER TABLE `faq_categories` RENAME `unp_faq_categories`");
	$DB->query("ALTER TABLE `faq_questions` RENAME `unp_faq_questions`");
	$DB->query("ALTER TABLE `news` RENAME `unp_news`");
	$DB->query("ALTER TABLE `setting` RENAME `unp_setting`");
	$DB->query("ALTER TABLE `style` RENAME `unp_style`");
	$DB->query("ALTER TABLE `user` RENAME `unp_user`");
	echo 'Successfully altered tables!';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step5" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Step 5 - Complete                                                |
// +------------------------------------------------------------------+
elseif ($step == 5)
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Congratulations! You have successfully upgraded Utopia News Pro from '.$version_current.' to '.$version_next.'!<br /><br />';
	echo 'Be sure to configure the new settings that were added in this version.</font><br /><br />';
	echo '<font class="highlight">NOTICE:</font> <font class="normalfont">Please <strong>delete</strong> this file once you have finished reading. Leaving this file on the server can pose a <strong>serious</strong> security threat!<br /><br />
	You may now access ';
	if (file_exists($next_upgrade))
	{
		echo 'the next upgrade script <a href="'.$next_upgrade.'">here</a>.';
	}
	else
	{
		echo 'the admin control panel <a href="index.php">here</a>.';
	}
	echo ($p_closebox);
	echo ($p_footer);
}
// +------------------------------------------------------------------+
// | Invalid Request                                                  |
// +------------------------------------------------------------------+
else
{
	require('functions.inc.php');
	unp_msgbox($gp_invalidrequest);
}
?>