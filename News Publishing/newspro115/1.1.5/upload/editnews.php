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
$USER = unp_getUser();
unp_getsettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';

// +------------------------------------------------------------------+
// | Process News Edit Query                                          |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submit']))
	{
		$newsid = $_POST['newsid'];
		if (!preg_match('/^[\d]+$/', $newsid))
		{
			unp_msgBox('You have entered an invalid news ID.');
			exit;
		}
		$checkNews = $DB->query("SELECT * FROM `unp_news` WHERE newsid='$newsid'");
		if (!($DB->is_single_row($checkNews)))
		{
			unp_msgBox($gp_invalidrequest);
			exit;
		}
		$checkNewsarray = $DB->fetch_array($checkNews);
		if (($checkNewsarray['poster'] != $USER['username']) && $USER['groupid'] == 3)
		{
			unp_msgBox('You do not have permission to edit this news post.');
			exit;
		}
		if (isset($_POST['deletenews']))
		{
			unp_deleteNews($newsid);
			unp_autoBuildCache();
			unp_redirect('editnews.php?action=edit','Your news was successfully deleted!<br />You will now be taken back to the news editing page.');
		}
		else
		{
			// make shorter vars
			$subject = $_POST['subject'];
			$news = $_POST['news'];
			// fix up text for database injection
			$subject = addslashes(trim($subject));
			$news = addslashes(trim($news));
			if (unp_isempty($subject) || unp_isempty($news))
			{
				unp_msgBox($gp_allfields);
				exit;
			}
			$DB->query("UPDATE `unp_news` SET subject='$subject',news='$news' WHERE newsid=$newsid");
			unp_autoBuildCache();
			unp_redirect('editnews.php?action=edit','Your news was successfully edited!<br />You will now be taken back to the news editing page.');
		}
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Process Edit News Page                                           |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	if ($USER['groupid'] == 1 || $USER['groupid'] == 2)
	{
		$getnews = $DB->query("SELECT * FROM `unp_news` ORDER BY `newsid` DESC");
	}
	else
	{
		$getnews = $DB->query("SELECT * FROM `unp_news` WHERE poster='".$USER['username']."' ORDER BY `newsid` DESC");
	}
	$getnewsnumber = $DB->num_rows($getnews);
	include('header.php');
	unp_openbox();
	echo '<strong>Edit News</strong>&nbsp;';
	unp_faqLink(3);
	echo '<br />';
// +------------------------------------------------------------------+
// | Posting Allowances                                               |
// +------------------------------------------------------------------+
if (($getnewsnumber) > 0)
{
		echo '
		<table align="right" border="0" cellpadding="-1" cellspacing="0"><tr><td>
		<div align="left" class="pbox">
		<strong>Posting Allowances</strong><br />';
		// HTML allowed?
		echo '<strong>HTML Code:</strong> ';
		if ($htmlallowance == 1)
		{
			echo 'On<br />';
		}
		else
		{
			echo 'Off<br />';
		}
		// UNP Code allowed?
		echo '<strong>UNP Code:</strong> ';
		if ($unpallowance == 1)
		{
			echo 'On [<a href="faq.php?action=question&amp;question=10" target="_blank">?</a>]<br />';
		}
		else
		{
			echo 'Off<br />';
		}
		// Smilies allowed?
		echo '<strong>Smilies:</strong> ';
		if ($smiliesallowance == 1)
		{
			echo 'On [<a href="faq.php?action=question&amp;question=21" target="_blank">?</a>]<br />';
		}
		else
		{
			echo 'Off<br />';
		}
		echo '</div></td></tr></table>';
}
// +------------------------------------------------------------------+
// | Edit News                                                        |
// +------------------------------------------------------------------+
	if (($getnewsnumber) > 0)
	{
		while ($newsarray = $DB->fetch_array($getnews))
		{
			// make shorter vars
			$newsid = $newsarray['newsid'];
			$subject = $newsarray['subject'];
			$date = $newsarray['date'];
			$news = $newsarray['news'];
			// fix up text for display
			$subject = stripslashes($subject);
			$news = stripslashes($news);
			$postdate = unp_date($dateformat, $date);
			$posttime = unp_date($timeformat, $date);
			$poster = $newsarray['poster'];
			$comments_num = $newsarray['comments'];
			if ($commentsallowance == '1')
			{
				$comments = $news['comments'];
				$comments_links = '<a onClick=\'open("comments.php?action=list&amp;newsid='.$newsid.'","View","width=550, height=550, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no")\' href="javascript:void(0)">[View Comments]</a>&nbsp;<a onClick=\'open("comments.php?action=deleteall&amp;newsid='.$newsid.'","Remove","width=550, height=550, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no")\' href="javascript:void(0)">[Remove All Comments]</a>';
			}
			if ($smiliesallowance == '1')
			{
				echo '
				<script type="text/javascript">
				function dosmilie'.$newsid.'(smiliecode) {
					// insert smilie
					document.unpform'.$newsid.'.news.value += smiliecode+" ";
					document.unpform'.$newsid.'.news.focus();
				}
				</script>';
			}
			echo '
			<form action="editnews.php" name="unpform'.$newsid.'" method="post">
			<input type="hidden" name="newsid" value="'.$newsid.'" />
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%"><strong>Poster:</strong></td>
					<td width="85%"><span class="normalfont">'.$poster.'</span></td>
				</tr>
				<tr>
					<td width="15%"><strong>Post Date:</strong></td>
					<td width="15%"><span class="normalfont">'.$postdate.' at '.$posttime.'</span></td>
				</tr>';
			if ($commentsallowance == '1')
			{
				echo '
				<tr>
					<td width="15%"><strong>Comments:</strong></td>
					<td width="15%"><span class="normalfont">'.$comments_num.' comments - '.$comments_links.'</span></td>
				</tr>';
			}
			echo '
				<tr>
					<td width="15%"><strong>Subject:</strong></td>
					<td width="15%"><input type="text" name="subject" maxlength="100" size="35" tabindex="'.$newsid.'1" value="'.htmlspecialchars($subject).'" /></td>
				</tr>
				<tr valign="top">
				<td width="15%"><strong>News:</strong><br /><span class="smallfont">Delete News: <input type="checkbox" name="deletenews" value="1" /></span>';
				unp_smilieBox($newsid);
				echo '
				</td>
				<td width="85%"><textarea name="news" cols="65" rows="20" tabindex="'.$newsid.'2">'.htmlspecialchars($news).'</textarea></td>
				</tr>
			</table>
			<center><input type="submit" name="submit" value="Submit Changes" />&nbsp;<input type="reset" value="Reset" /></center>
			</form><hr /><br />
			';
		}
		unset($newsarray); // drop this large variable, it's unnecessary now
	}
	else
	{
		echo 'There is no news available to edit.';
	}
	unp_closebox();
	include('footer.php');
}
?>