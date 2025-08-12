<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();
$USER = unp_getUser(0);
unp_getSettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
require('news.inc.php');
$n = new News;
$n->smiliesallowance = $smiliesallowance;
$n->unpallowance = $unpallowance;
$n->htmlallowance = $htmlallowance;

$n->unp_getStyle();
// +------------------------------------------------------------------+
// | Check Authorization                                              |
// +------------------------------------------------------------------+
if ($commentsallowance != '1')
{
	unp_msgBox($gp_invalidrequest);
	exit;
}
if ($USER['groupid'] != 0)
{
	$isloggedin = 1;
}
else
{
	$isloggedin = 0;
}
// +------------------------------------------------------------------+
// | Process Submission                                               |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submitcomment']))
	{
		$newsid = addslashes($_POST['newsid']);
		$name = addslashes(trim($_POST['name']));
		$password = addslashes(trim($_POST['password']));
		$email = addslashes(trim($_POST['email']));
		$date = time();
		$title = addslashes(trim($_POST['title']));
		$comments = addslashes($_POST['comments']);
		$ipaddress = addslashes($_POST['ipaddress']);
		$proxy = addslashes($_POST['proxy']);
		if (!preg_match('/^[\d]+$/', $newsid))
		{
			unp_msgBox($gp_invalidrequest);
			exit;
		}
		$checknews = $DB->query("SELECT * FROM `unp_news` WHERE newsid='$newsid'");
		if (!$DB->is_single_row($checknews))
		{
			unp_msgBox($gp_invalidrequest);
			exit;
		}
		if (!unp_isvalidemail($email))
		{
			unp_msgBox($gp_invalidemail);
			exit;
		}
		// Check Username v Password
		$checkuser = $DB->query("SELECT * FROM `unp_user` WHERE username='$name'");
		if ($DB->is_single_row($checkuser))
		{
			$checkuser2 = $DB->fetch_array($checkuser);
			if (!isset($password) || (md5($password) != $checkuser2['password']))
			{
				unp_msgBox('You are trying to post under another user\'s name. If you want to use this name you will have to enter the correct password.');
				exit;
			}
		}
		if (!unp_isempty($name) && !unp_isempty($email) && !unp_isempty($title) && !unp_isempty($comments))
		{
			$get_comment_count = $DB->query("SELECT `comments` FROM `unp_news` WHERE newsid='$newsid'");
			$c_count = $DB->fetch_array($get_comment_count);
			$inc_c_count = $c_count['comments'] + 1;
			$add_comment = $DB->query("INSERT INTO `unp_comments` (`newsid`,`name`,`email`,`date`,`title`,`comments`,`ipaddress`,`proxy`) VALUES ('$newsid','$name','$email','$date','$title','$comments','$ipaddress','$proxy')");
			$update_comments_count = $DB->query("UPDATE `unp_news` SET `comments`='$inc_c_count' WHERE newsid='$newsid'");
			if ($add_comment && $update_comments_count)
			{
				eval('$comments_redirect_posted = "'.unp_printTemplate('comments_redirect_posted').'";');
				unp_echoTemplate($comments_redirect_posted);
				unp_autoBuildCache();
			}
		}
		else
		{
			unp_msgBox($gp_allfields);
			exit;
		}
	}	
}

// +------------------------------------------------------------------+
// | Submit Comments Page                                             |
// +------------------------------------------------------------------+
if ($action == 'post')
{
	isset($_GET['newsid']) ? $newsid = $_GET['newsid'] : $newsid = '';
	if (!ereg('^[0-9]+$', $newsid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$checknews = $DB->query("SELECT * FROM `unp_news` WHERE newsid='$newsid'");
	if (!$DB->is_single_row($checknews))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$news = $DB->fetch_array($checknews);
	extract($news);
	/*
	 * Start Get IP Address
	 */
	$ipaddress = '';
	$proxy = '0';
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$proxy = $_SERVER['REMOTE_ADDR'];
	}
	elseif (isset($_SERVER['HTTP_CLIENT_IP']))
	{
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	else
	{
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	if ($proxy == '0')
	{
		$proxy = 'None';
	}
	/*
	 * End Get IP Address
	 */
	$subject = $n->unp_doSubjectFormat($subject);
	$date = unp_date($dateformat);
	$time = unp_date($timeformat);
	if ($isloggedin == 1)
	{
		$username = $USER['username'];
	}
	else
	{
		$username = '';
	}
	eval('$comments_submit = "'.unp_printTemplate('comments_submit').'";');
	unp_echoTemplate($comments_submit);
}

// +------------------------------------------------------------------+
// | Show News Comments                                               |
// +------------------------------------------------------------------+
if ($action == 'list')
{
	define('ISPRINTABLEPAGE', false);
	isset($_GET['newsid']) ? $newsid = $_GET['newsid'] : $newsid = '';
	if (!ereg('^[0-9]+$', $newsid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$checknews = $DB->query("SELECT * FROM `unp_news` WHERE newsid='$newsid'");
	if (!$DB->is_single_row($checknews))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	while ($c_news = $DB->fetch_array($checknews))
	{
		extract($c_news);
		$subject = $n->unp_doSubjectFormat($subject);
		$news = $n->unp_doNewsFormat($news);
	}
	$getcomments = $DB->query("SELECT * FROM `unp_comments` WHERE newsid='$newsid'");
	eval('$comments_list_header = "'.unp_printTemplate('comments_list_header').'";');
	unp_echoTemplate($comments_list_header);
		if ($DB->num_rows($getcomments) > 0)
		{
			while ($comments = $DB->fetch_array($getcomments))
			{
				// grab and fix up comments
				$c_id = $comments['id'];
				$c_title = htmlspecialchars(stripslashes($comments['title']));
				$c_name = htmlspecialchars(stripslashes($comments['name']));
				$c_email = htmlspecialchars(stripslashes($comments['email']));
				$c_date = unp_date($dateformat, $comments['date']);
				$c_time = unp_date($timeformat, $comments['date']);
				$c_text = nl2br(htmlspecialchars(stripslashes($comments['comments'])));
				$c_ipaddress = $comments['ipaddress'];
				$c_proxy = $comments['proxy'];
				$c_text = $n->unp_doSmilies($c_text);
				if ($isloggedin == 1)
				{
					eval('$removecommentlink = "'.unp_printTemplate('comments_list_commentbit_removecomment').'";');
				}
				else
				{
					$removecommentlink = '';
				}
				
				if ($isloggedin == 1)
				{
					eval('$ipaddressinfo = "'.unp_printTemplate('comments_list_commentbit_ipaddress').'";');
				}
				else
				{
					$ipaddressinfo = '';
				}
		
				eval('$comments_list_commentbit = "'.unp_printTemplate('comments_list_commentbit').'";');
				unp_echoTemplate($comments_list_commentbit);
			}
		}
		else
		{
			echo 'None';
		}
	eval('$comments_list_footer = "'.unp_printTemplate('comments_list_footer').'";');
	unp_echoTemplate($comments_list_footer);
}

// +------------------------------------------------------------------+
// | Remove Comment                                                   |
// +------------------------------------------------------------------+
if ($action == 'delete')
{
	$USER = unp_getUser();
	isset($_GET['cid']) ? $cid = $_GET['cid'] : $cid = '';
	if (!eregi('^[0-9]+$', $cid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$check_c = $DB->query("SELECT * FROM `unp_comments` WHERE id='$cid'");
	if (!$DB->is_single_row($check_c))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	while ($comments = $DB->fetch_array($check_c))
	{
		$newsid = $comments['newsid'];
	}
	$get_comment_count = $DB->query("SELECT `comments` FROM `unp_news` WHERE newsid='$newsid'");
	$c_count = $DB->fetch_array($get_comment_count);
	$dec_c_count = $c_count['comments'] - 1;
	$remove_c = $DB->query("DELETE FROM `unp_comments` WHERE id='$cid'");
	$lower_c_count = $DB->query("UPDATE `unp_news` SET `comments`='$dec_c_count'  WHERE newsid='$newsid'");
	if ($remove_c && $lower_c_count)
	{
		eval('$comments_redirect_deleted = "'.unp_printTemplate('comments_redirect_deleted').'";');
		unp_echoTemplate($comments_redirect_deleted);
		unp_autoBuildCache();
	}
}

// +------------------------------------------------------------------+
// | Remove All Comments For News                                     |
// +------------------------------------------------------------------+
if ($action == 'deleteall')
{
	$USER = unp_getUser();
	isset($_GET['newsid']) ? $newsid = $_GET['newsid'] : $newsid = '';
	if (!eregi('^[0-9]+$', $newsid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$check_news = $DB->query("SELECT * FROM `unp_news` WHERE newsid='$newsid'");
	if (!$DB->is_single_row($check_news))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$thisnews = $DB->fetch_array($check_news);
	$c_num = $thisnews['comments'];
	if ($c_num == 0)
	{
		unp_msgBox('There are no comments to delete.');
		exit;
	}
	$remove_c = $DB->query("DELETE FROM `unp_comments` WHERE newsid='$newsid'");
	$lower_c_count = $DB->query("UPDATE `unp_news` SET comments='0' WHERE newsid='$newsid'");
	if ($remove_c && $lower_c_count)
	{
		eval('$comments_redirect_deleted = "'.unp_printTemplate('comments_redirect_deleted').'";');
		unp_echoTemplate($comments_redirect_deleted);
		unp_autoBuildCache();
	}
}
?>