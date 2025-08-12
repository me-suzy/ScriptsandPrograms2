<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" href="style.css" media="all" />
	<title>Utopia News Pro 1.1.2 Beta to 1.1.2 Final Upgrade</title>
<meta name="generator" content="Utopia News Pro - http://www.utopiasoftware.net/" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>

<center>
<div align="left" class="hbox">
<div class="lbox"><img src="images/unp_logo.jpg" alt="Utopia News Pro" border="0" /></div>
</div></center>
<br />

<center>
<div align="left" class="box">
<font class="normalfont">
<strong>Utopia News Pro Upgrade</strong><br /><br />';

$URI = explode('112Beta', $_SERVER['REQUEST_URI']);
$currentdir = $URI[0];
echo 'We will now introduce the new settings that appear in 1.1.2 Final that were not in 1.1.2 Beta<br /><br />';
unp_getSettings();
$DB->query("DELETE FROM `unp_setting`");
$DB->query("INSERT INTO `unp_setting` (`id`, `display`, `title`, `varname`, `value`, `description`, `optioncode`) VALUES
(1, 1, 'Site Title', 'sitetitle', '".$sitetitle."', 'Title of the main site where the news is to be posted.', 'text'),
(2, 2, 'Site URL', 'siteurl', '".$siteurl."', 'The URL to the main site where the news will be displayed. (Requires preceding http://)', 'text'),
(4, 6, 'UNP Code Allowances', 'unpallowance', '".$unpallowance."', 'Do you wish for UNP code to be enabled in news posts? If you set this to yes, then special UNP codes can be used in news posts to achieve certain HTML affects.', 'yesno'),
(5, 8, 'News Limit', 'newslimit', '".$newslimit."', 'The maximum number of news items you wish to display on the news page. Do not set this too high for performance reasons.', 'text'),
(6, 10, 'Automatically Rebuild Cache', 'autocache', '".$autocache."', 'Setting this feature to yes automatically rebuilds the news cache after submitting news, editing news, editing styles, and editing settings. Leaving this feature set to no will force you to manually rebuild the cache.', 'yesno'),
(7, 16, 'Date Format', 'dateformat', '".$dateformat."', 'This is the format that will be used to display dates throughout Utopia News Pro.<br /><br />Reference: <a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_blank\">http://www.php.net/manual/en/function.date.php</a><br /><br />Example:<br />US Format: (eg. July 3, 1988) - F j, Y<br />European Format: (eg. 3 July 1988) - j F Y', 'text'),
(8, 17, 'Time Format', 'timeformat', '".$timeformat."', 'This is the format that will be used to display times throughout Utopia News Pro.<br /><br />Reference: <a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_blank\">http://www.php.net/manual/en/function.date.php</a><br /><br />Example:<br />AM/PM Format: (eg. 10:31 PM) - h:i A<br />AM/PM Format With Seconds: (eg. 10:31:22) - h:i:s A<br />24-Hour Format: (eg. 22:31) - H:i<br />24-Hour Format With Seconds: (eg. 22:31:22) - H:i:s', 'text'),
(9, 4, 'URL to Utopia News Pro Install', 'unpurl', '".$unpurl."', 'The URL to the location where Utopia News Pro is installed. <b>With</b> final slash (/). (Requires preceding http://)', 'text'),
(10, 5, 'HTML Allowances', 'htmlallowance', '".$htmlallowance."', 'Do you wish for HTML to be enabled in news posts? We <b>strongly</b> advise against the use of HTML. Instead, to achive many common HTML affects, you can use UNP Code tags.', 'yesno'),
(11, 7, 'Smilies Allowance', 'smiliesallowance', '".$smiliesallowance."', 'Do you wish for smilies to be enabled in news posts? If you set this to yes, smilie codes will automatically be converted into smilies.', 'yesno'),
(12, 12, 'Comments', 'commentsallowance', '".$commentsallowance."', 'Do you wish for comments to be enabled? Enabling this will allow visitors to your site submit publicly viewable comments to news posts.', 'yesno'),
(13, 9, 'Headlines Limit', 'headlineslimit', '".$headlineslimit."', 'If you have headlines turned on, this is the maximum number of headlines to display.', 'text'),
(14, 11, 'Enable Headlines', 'headlinesallowance', '".$headlinesallowance."', 'Do you want to enable headlines? Setting this to No disables the view of headlines.', 'yesno'),
(15, 15, 'Time Zone', 'timeoffset', '0', 'What is the timezone in which you want news posts to be dated?', 'timezone'),
(16, 13, 'Avatar Allowances', 'avatarallowance', '1', 'Do you wish for users to be allowed to have avatars (small images displayed on every post a user makes)?', 'yesno'),
(17, 14, 'Avatar Dimensions', 'avatardimensions', '75', 'If you have avatars turned on, this is the maximum height and width, in pixels, of a user\'s avatar.', 'text'),
(18, 3, 'UNP Directory', 'unpdir', '$currentdir', 'The directory in which UNP is installed. <b>Format:</b> /directoryname/', 'text')
");

echo 'Success! Please delete this file from the web server.';

echo '</font></div></center>

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

?>