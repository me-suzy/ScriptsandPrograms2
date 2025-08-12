<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
unp_getSettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
// +------------------------------------------------------------------+
// | Initialize News Class                                            |
// +------------------------------------------------------------------+
require('news.inc.php');
$n = new News;
$n->smiliesallowance = $smiliesallowance;
$n->unpallowance = $unpallowance;
$n->htmlallowance = $htmlallowance;
$n->action = $action;

$n->unp_getStyle();
/* #################### AVATARS #################### */
// Make educated guesses as to where we are...
if (isset($unpdir) && !preg_match('/\/[a-zA-Z0-9]\//', $unpdir))
{
	$unpdir = './'.$unpdir.'/';
}
if (!preg_match('/\.\/[a-zA-Z0-9]\//', $unpdir))
{
	$unpdir = '.'.$unpdir;
}
$INUNPDIR = false;
if (!file_exists($unpdir.'news.php'))
{
	$INUNPDIR = true;
}
/* #################### AVATARS #################### */
// +------------------------------------------------------------------+
// | Process News - Standard                                          |
// +------------------------------------------------------------------+
if ($action == '')
{
	define('ISPRINTABLEPAGE', false);
	$templatesused = 'news_newsbit,news_newsbit_commentslink,news_avatarbit,news_header,news_footer';
	unp_cacheTemplates($templatesused);
	eval('$news_header = "'.unp_printTemplate('news_header').'";');
	unp_echoTemplate($news_header);
	$getnews = $DB->query("SELECT * FROM `unp_news` ORDER BY `date` DESC LIMIT $newslimit");
	while ($news = $DB->fetch_array($getnews))
	{
		$newsid = $news['newsid'];
		$subject = $news['subject'];
		$newstext = $news['news'];
		$poster = $news['poster'];
		$posterid = $news['posterid'];
		$date = $news['date'];
		$postdate = unp_date($dateformat, $date);
		$posttime = unp_date($timeformat, $date);
		$avatar = unp_checkAvatar($posterid);
		if (!$avatar)
		{
			$useravatar = '';
		}
		else
		{
			eval('$useravatar = "'.unp_printTemplate('news_avatarbit').'";');
		}
		if ($commentsallowance == '1')
		{
			$comments = $news['comments'];
			eval('$commentsinfo = "'.unp_printTemplate('news_newsbit_commentslink').'";');
		}
		else
		{
			$commentsinfo = '&nbsp;';
		}
		$comments = $news['comments'];
		$newstext = $n->unp_doNewsFormat($newstext);
		$subject = $n->unp_doSubjectFormat($subject);
		// NewsBit
		eval('$news_newsbit = "'.unp_printTemplate('news_newsbit').'";');
		unp_echoTemplate($news_newsbit);
		// NewsBit
		echo "\n\n";
	}
	unset($news);
	eval('$news_footer = "'.unp_printTemplate('news_footer').'";');
	unp_echoTemplate($news_footer);
}

// +------------------------------------------------------------------+
// | Process News - Printable                                         |
// +------------------------------------------------------------------+
if ($action == 'printable')
{
	define('ISPRINTABLEPAGE', true);
	isset($_GET['showall']) ? $showall = true : $showall = false;
	isset($_GET['newsid']) ? $getnewsid = $_GET['newsid'] : $getnewsid = false;
	if (($getnewsid != false) && (!preg_match('/^[\d]+$/', $getnewsid)))
	{
		unp_msgBox('You have entered an invalid news ID.');
		exit;
	}
	$templatesused = 'printable_header,printable_footer,printable_newsbit';
	unp_cacheTemplates($templatesused);
	if (!$showall)
	{
		eval('$showall_link = "'.unp_printTemplate('printable_showall_link').'";');
	}
	else
	{
		$showall_link = '';
	}
	eval('$printable_header = "'.unp_printTemplate('printable_header').'";');
	unp_echoTemplate($printable_header);
	echo "\n";
	if (!$showall && $getnewsid)
	{
		$getnews = $DB->query("SELECT * FROM `unp_news` WHERE `newsid`='$getnewsid' ORDER BY `date` DESC LIMIT 1");
	}
	elseif (!$showall && !$getnewsid)
	{
		$getnews = $DB->query("SELECT * FROM `unp_news` ORDER BY `date` DESC LIMIT $newslimit");
	}
	else
	{
		$getnews = $DB->query("SELECT * FROM `unp_news` ORDER BY `date` DESC");
	}
	while ($news = $DB->fetch_array($getnews))
	{
		$newsid = $news['newsid'];
		$subject = $news['subject'];
		$newstext = $news['news'];
		$poster = $news['poster'];
		$date = $news['date'];
		$postdate = unp_date($dateformat, $date);
		$posttime = unp_date($timeformat, $date);
		$newstext = $n->unp_doNewsFormat($newstext);
		$subject = $n->unp_doSubjectFormat($subject);
		/* NewsBit */
		eval('$printable_newsbit = "'.unp_printTemplate('printable_newsbit').'";');
		unp_echoTemplate($printable_newsbit);
		/* NewsBit */
	}
	unset($news);
	eval('$printable_footer = "'.unp_printTemplate('printable_footer').'";');
	unp_echoTemplate($printable_footer);
}

// +------------------------------------------------------------------+
// | Email Link                                                       |
// +------------------------------------------------------------------+
if ($action == 'mail')
{
	isset($_GET['uname']) ? $uname = $_GET['uname'] : $uname = '';
	$uname = addslashes($uname);
	$getUser = $DB->query("SELECT `email` FROM `unp_user` WHERE username='$uname'");
	$getUserinfo = $DB->fetch_array($getUser);
	$email = $getUserinfo['email'];
	@header("Location: mailto:$email");
}
?>