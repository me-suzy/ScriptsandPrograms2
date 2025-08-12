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
$poster = $USER['username'];

// +------------------------------------------------------------------+
// | Process News Submission                                          |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submitnews']))
	{
		$subject = addslashes(trim($_POST['subject']));
		$news = addslashes(trim($_POST['news']));
		$posttime = time();
		$newsposter = $USER['username'];
		$newsposterid = $USER['userid'];
		// Verification
		if (unp_isEmpty($subject) || unp_isEmpty($news))
		{
			unp_msgBox($gp_allfields);
			exit;
		}
		if (!preg_match('/^[\d]+$/', $newsposterid))
		{
			// Make sure it's really a number
			unp_msgBox($gp_invalidrequest);
			exit;
		}
		$submitnews = $DB->query("INSERT INTO `unp_news` (`date`, `subject`, `news`, `posterid`,`poster`) VALUES ('$posttime','$subject','$news','$newsposterid','$newsposter')");
		unp_autoBuildCache();
		unp_redirect('index.php','Your news was successfully entered into the database!<br />You will now be taken to the main administration page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
	}
}

// +------------------------------------------------------------------+
// | Process Post News Page Content                                   |
// +------------------------------------------------------------------+
if ($action == 'post')
{
		include('header.php');
		if ($smiliesallowance == '1')
		{
			echo '
			<script type="text/javascript">
			function dosmilie(smiliecode) {
				// insert smilie
				document.unpform.news.value += smiliecode+" ";
				document.unpform.news.focus();
			}
			</script>
			';
		}
		unp_openbox();
		echo '
		<form action="postnews.php" name="unpform" method="post">
		<strong>Post News</strong>&nbsp;';
		unp_faqLink(1);
		echo '
		<br />
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="15%"><strong>Poster:</strong></td>
				<td width="85%">'.$poster.'&nbsp;<span class="smallfont"><a href="login.php?action=logout">[logout]</a></span></td>
			</tr>
			<tr>
				<td width="15%"><strong>Date:</strong></td>
				<td width="85%">'.unp_date($dateformat).' at '.unp_date($timeformat).'</td>
			</tr>
			<tr>
				<td width="15%"><strong>Subject:</strong></td>
				<td width="85%"><input type="text" size="35" tabindex="1" maxlength="100" name="subject" /></td>
			</tr>
			<tr valign="top">
				<td width="15%"><strong>News:</strong>';
				unp_smilieBox();
		echo '
				</td>
				<td width="85%"><textarea tabindex="2" name="news" cols="65" rows="20"></textarea></td>
			</tr>
		</table>
		<center><input type="submit" name="submitnews" value="Post News" accesskey="s" /> <input type="reset" value="Reset" /></center>
		</form>';
		// +------------------------------------------------------------------+
		// | Posting Allowances                                               |
		// +------------------------------------------------------------------+
		echo '
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
		echo '</div>';
		unp_closebox();
		include('footer.php');
}
?>