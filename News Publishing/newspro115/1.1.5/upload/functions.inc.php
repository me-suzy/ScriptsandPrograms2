<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('global.inc.php');

/***************************************************************
   Start Error Handling Function
***************************************************************/
function unp_msgBox($message)
{
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<link rel="stylesheet" href="style.css" media="all" />
	<title>Utopia News Pro</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	</head>
	<body>
	<table width="100%" height="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr align="center" valign="middle">
		<td>
			<table class="rbox">
				<tr valign="middle">
					<td><span class="smallfont"><strong>'.$message.'</strong></span></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
	</body>
	</html>';
}


/***************************************************************
   Start Redirect
***************************************************************/
function unp_redirect($url,$message='', $seconds=2)
{
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<link rel="stylesheet" href="style.css" media="all" />
	<title>Redirecting...</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="refresh" content="'.$seconds.'; url='.$url.'" />
	</head>
	<body>
	<table width="100%" height="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr align="center" valign="middle">
		<td>
			<table class="rbox">
				<tr valign="middle">
					<td><strong>'.$message.'</strong></td>
				</tr>
				<tr>
					<td><span class="smallfont"><a href="'.$url.'">Click here if you do not wish to wait<br />(Or if your browser doesn\'t forward you)</a></span></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
	</body>
	</html>';
}

/***************************************************************
   Start Click Smilies
***************************************************************/
function unp_smilieBox($newsid='')
{
	global $smiliesallowance;
	if ($smiliesallowance == '1')
	{
		echo '
		<div style="margin: 15px;text-align: left; width:70px;">
		<span class="smallfont"><strong>Smilies:</strong></span>
		<table width="100%" align="center" style="border: 1px solid black;">
		<tr>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':)\'); return false;"><img src="images/smilies/happy.gif" alt="Happy" title="Happy" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':(\'); return false;"><img src="images/smilies/sad.gif" alt="Sad" title="Sad" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\';)\'); return false;"><img src="images/smilies/wink.gif" alt="Wink" title="Wink" border="0" /></a></td>
		</tr>
	
		<tr>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':D\'); return false;"><img src="images/smilies/biggrin.gif" alt="Big Grin" title="Big Grin" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':P\'); return false;"><img src="images/smilies/tongue.gif" alt="Tongue" title="Tongue" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\'^_^\'); return false;"><img src="images/smilies/keke.gif" alt="Keke" title="Keke" border="0" /></a></td>
		</tr>
	
		<tr>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':angry:\'); return false;"><img src="images/smilies/angry.gif" alt="Angry" title="Angry" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':rolleyes:\'); return false;"><img src="images/smilies/rolleyes.gif" alt="Roll Eyes" title="Roll Eyes" border="0" /></a></td>
		<td align="center"><a href="#" onClick="dosmilie'.$newsid.'(\':confused:\'); return false;"><img src="images/smilies/confused.gif" alt="Confused" title="Confused" border="0" /></a></td>
		</tr>
		</table>
		</div>
		';
	}
}

/***************************************************************
   Start FAQ Link
***************************************************************/
function unp_faqLink($id)
{
	echo '<a href="faq.php?action=category&amp;catid='.$id.'" target="_blank"><img src="images/help.gif" alt="Read FAQ" style="cursor: help; border: 0; width: 22px; height: 20px" /></a>';
}

/***************************************************************
   Start Content Opener
***************************************************************/
function unp_openBox()
{
	echo '
	<center>
	<div align="left" class="box">
	';
}

/***************************************************************
   Start Content Closer
***************************************************************/
function unp_closeBox()
{
	echo '</div></center>';
}

/***************************************************************
   Start Get User
***************************************************************/
function unp_getUser($loggedoutaction=1)
{
	/*
	 * Logged Out Action
	 * 1 - display error
	 * 0 - omit error page - return vars
	*/
	global $DB;
	$USER = array();
	if (!isset($_SESSION['unp_user']))
	{
		if ($loggedoutaction == 1)
		{
			unp_msgBox('Only logged in users are authorized to access this page.<br /><a href="index.php">Click here to return to the login page.</a>');
			exit;
		}
		else
		{
			// Empty array
			$USER['userid'] = 0;
			$USER['username'] = '';
			$USER['password'] = '';
			$USER['groupid'] = 0;
			$USER['email'] = '';
		}
	}
	if (!isset($_SESSION['unp_pass']))
	{
		if ($loggedoutaction == 1)
		{
			unp_msgBox('Only logged in users are authorized to access this page.<br /><a href="index.php">Click here to return to the login page.</a>');
			exit;
		}
		else
		{
			// Empty array
			$USER['userid'] = 0;
			$USER['username'] = '';
			$USER['password'] = '';
			$USER['groupid'] = 0;
			$USER['email'] = '';
		}
	}
	else
	{
		$loggedinuser = $_SESSION['unp_user'];
		$getuser = $DB->query("SELECT * FROM `unp_user` WHERE username='$loggedinuser'");
		$querynum = $DB->num_rows($getuser);
		if (!($DB->is_single_row($getuser)))
		{
			if ($loggedoutaction == 1)
			{
				unp_msgBox('Only logged in users are authorized to access this page.<br /><a href="index.php">Click here to return to the login page.</a>');
				exit;
			}
			else
			{
				// Empty array
				$USER['userid'] = 0;
				$USER['username'] = '';
				$USER['password'] = '';
				$USER['groupid'] = 0;
				$USER['email'] = '';
			}
		}
		$userInfo = $DB->fetch_array($getuser);
		// Start load array
		$USER['userid']   =  $userInfo['userid'];
		$USER['username'] =  $userInfo['username'];
		$USER['groupid']  =  $userInfo['groupid'];
		$USER['password'] =  $userInfo['password'];
		$USER['email']    =  $userInfo['email'];
		// End load array
		if ($_SESSION['unp_pass'] != $USER['password'])
		{
			if ($loggedoutaction == 1)
			{
				unp_msgBox('Only logged in users are authorized to access this page.<br /><a href="index.php">Click here to return to the login page.</a>');
				exit;
			}
			else
			{
				// Empty array
				$USER['userid'] = 0;
				$USER['username'] = '';
				$USER['password'] = '';
				$USER['groupid'] = 0;
				$USER['email'] = '';
			}
		}
	}
	return $USER;
}

/***************************************************************
   Start Custom Empty Check
***************************************************************/
function unp_isEmpty($field)
{
	if (!isset($field) || !strlen($field))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/***************************************************************
   Start Time
***************************************************************/
function unp_date($format, $timestamp=TIMENOW)
{
	global $timeoffset;
	$timediff = (date('Z', TIMENOW) / 3600 - $timeoffset) * 3600;
	$date = date($format, $timestamp - $timediff);
	return $date;
}

/***************************************************************
   Start Get Settings
***************************************************************/
function unp_getSettings()
{
	global $DB;
	$getsettings = $DB->query("SELECT `varname` , `value` FROM `unp_setting`");
	while ($settings = $DB->fetch_array($getsettings))
	{
		global ${$settings['varname']};
		${$settings['varname']} = stripslashes($settings['value']);
	}
}

/***************************************************************
   Start iif
***************************************************************/
function unp_iif($expression, $truereturn, $falsereturn)
{
	if ($expression == 0)
	{
		return $falsereturn;
	}
	else
	{
		return $truereturn;
	}

}

/***************************************************************
   Start Delete News
***************************************************************/
function unp_deleteNews($newsid)
{
	global $DB;
	$deleteNews = $DB->query("DELETE FROM `unp_news` WHERE newsid='$newsid'");
	$deleteNewsComments = $DB->query("DELETE FROM `unp_comments` WHERE newsid='$newsid'");
	if ($deleteNews && $deleteNewsComments)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/***************************************************************
   Start Validate Email
***************************************************************/
function unp_isValidEmail($email)
{
	$email = trim($email);
	if (preg_match('/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/i', $email) && (strlen($email) > 5))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/***************************************************************
   Start Validate Style Entry
***************************************************************/
function unp_isValidStyle($stylevar)
{
	if (!preg_match('/^[#0-9a-zA-Z:\/.=" ]+$/i', $stylevar))
	{
		unp_msgBox('You have entered an invalid color.');
		exit;
	}
}

/***************************************************************
   Start Avatar Check
***************************************************************/
function unp_checkAvatar($userid)
{
	global $avatarallowance, $unpurl, $unpdir, $INUNPDIR;
	if ($avatarallowance == '1')
	{
		if ($INUNPDIR)
		{
			$avpath = 'images/avatars/avatar-'.$userid;
		}
		else
		{
			if (!preg_match('/\/$/', $unpdir))
			{
				$unpdir = $unpdir.'/';
			}
			$avpath = $unpdir.'images/avatars/avatar-'.$userid;
		}
		$absavpath = 'images/avatars/avatar-'.$userid;
		if (file_exists($avpath.'.gif'))
		{
			$avatar = $unpurl.$absavpath.'.gif';
			return $avatar;
		}
		elseif (file_exists($avpath.'.jpg'))
		{
			$avatar = $unpurl.$absavpath.'.jpg';
			return $avatar;
		}
		elseif (file_exists($avpath.'.png'))
		{
			$avatar = $unpurl.$absavpath.'.png';
			return $avatar;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

/***************************************************************
   Start Template Cache
***************************************************************/
function unp_cacheTemplates($templatesused)
{
	global $templatecache, $DB;
	$templatesused = str_replace(',',"','", addslashes($templatesused));
	$alltemps = $DB->query("SELECT templatename,template FROM `unp_template` WHERE templatename IN ('$templatesused')");
	while ($temps = $DB->fetch_array($alltemps))
	{
		$templatecache["$temps[templatename]"] = $temps['template'];
	}
	unset($temps);
	unset($alltemps);
}

/***************************************************************
   Start Template Printer
***************************************************************/
function unp_printTemplate($template)
{
	global $templatecache, $DB;
	if (isset($templatecache["$template"]))
	{
		$returnTemplate = $templatecache[$template];
	}
	else
	{
		$getTemplate = $DB->query("SELECT templatename,template FROM `unp_template` WHERE templatename='$template' LIMIT 1");
		while ($temp = $DB->fetch_array($getTemplate))
		{
			$templatecache[$template] = $temp['template'];
		}
		$returnTemplate = $templatecache[$template];
	}
	$returnTemplate = addslashes($returnTemplate);
	return $returnTemplate;
}


/***************************************************************
   Start Echo Template
***************************************************************/
function unp_echoTemplate($template)
{
	$template = stripslashes($template);
	echo $template;
}

/***************************************************************
   Start Auto Cache Builder
***************************************************************/
function unp_autoBuildCache()
{
	global $autocache, $unpurl;
	$newsphploc = $unpurl.'/news.php';
	$tempfilename = 'tempnews.txt';
	$news_txt = 'news.txt';
	/* Headlines Cache
	$headlinesphploc = $unpurl.'/headlines.php';
	$tempfilename = 'tempheadlines.txt';
	$headlines_txt = 'headlines.txt';
	*/
	if ($autocache == 1)
	{
		@unlink($tempfilename); // kill this as it might still be lying around
		$dynnews = @fopen($newsphploc, 'r');
		$htmldata = '';
		while (!feof($dynnews))
		{
			$htmldata = $htmldata.fread($dynnews, 1024);
		}
		@fclose($dynnews);
		$tempfile = @fopen($tempfilename, 'w');
		@fwrite($tempfile, $htmldata);
		@fclose($tempfile);
		$ok = @copy($tempfilename, $news_txt);
		@unlink($tempfilename);
	}
}

/***************************************************************
   Start Index Installed Check
***************************************************************/
function unp_indexInstallCheck()
{
	global $DB;
	if (file_exists('install.php') && ($DB->checkdb() == false))
	{
		unp_redirect('install.php','You have not installed Utopia News Pro yet!<br />You will now be taken to the installer.');
		exit;
	}
}

/***************************************************************
   Start String Strip
***************************************************************/
function str_strip($strip, $str)
{
	$str = str_replace($strip, '', $str);
	return $str;
}
?>