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
// | Process Main FAQ Page                                            |
// +------------------------------------------------------------------+
if ($action == '')
{
	include('header.php');
	unp_openbox();
	$faqcat = $DB->query("SELECT * FROM `unp_faq_categories` ORDER BY display ASC");
	while ($faqcats = $DB->fetch_array($faqcat))
	{
		$catid = $faqcats['id'];
		$catname = $faqcats['catname'];
		echo '
			<table width="90%" border="0" align="center"><tr><td>
			<table border="0" width="100%" style="
			border-left: #000000 1px solid;
			border-right: #000000 1px solid;
			border-top: #000000 1px solid"
			cellpadding="5" cellspacing="0">
			<tr>
			<td bgcolor="#6384B0" style="border-bottom: #000000 1px solid" colspan="2">
			<span class="tblheadtxt"><strong><a href="faq.php?action=category&amp;catid='.$catid.'"><span class="tblheadtxt" style="text-decoration: none">'.$catname.'</span></a></strong></span></td></tr>
			<tr><td bgcolor="#FFFFFF" colspan="2" style="border-bottom : #000000 1px solid">';
			$getques = $DB->query("SELECT * FROM `unp_faq_questions` WHERE groupid='$catid'");
			while ($questions = $DB->fetch_array($getques))
			{
				$questionid = $questions['id'];
				$question = $questions['question'];
				echo '<a href="faq.php?action=question&amp;question='.$questionid.'">'.$question.'</a><br />';
			}
			unset($getques);
			unset($questions);
		echo '</td></tr></table><br /></td></tr></table>';
	}
	unset($faqcats);
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Category Page                                            |
// +------------------------------------------------------------------+
if ($action == 'category')
{
	isset($_GET['catid']) ? $catid = $_GET['catid'] : $catid = '';
	if (!preg_match('/^[\d]+$/', $catid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$getcat = $DB->query("SELECT * FROM `unp_faq_categories` WHERE id='$catid'");
	if (!$DB->is_single_row($getcat))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	include('header.php');
	unp_openbox();
	while($category = $DB->fetch_array($getcat))
	{
		$catname = $category['catname'];
		$getquestions = $DB->query("SELECT * FROM `unp_faq_questions` WHERE groupid='$catid'");
		echo '
			<table width="90%" border="0" align="center"><tr><td>
			<table border="0" width="100%" style="
			border-left: #000000 1px solid;
			border-right: #000000 1px solid;
			border-top: #000000 1px solid"
			cellpadding="5" cellspacing="0">
			<tr>
			<td bgcolor="#6384B0" style="border-bottom : #000000 1px solid" colspan="2">
			<span class="tblheadtxt"><strong>'.$catname.'</strong></span></td></tr>
			<tr><td bgcolor="#FFFFFF" colspan="2" style="border-bottom : #000000 1px solid">';
		while ($questions = $DB->fetch_array($getquestions))
		{
			$questionid = $questions['id'];
			$question = $questions['question'];
			echo '<a href="faq.php?action=question&amp;question='.$questionid.'">'.$question.'</a><br />';
		}			
		echo '</td></tr></table><br /></td></tr></table>';
	}
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Question Page                                            |
// +------------------------------------------------------------------+
if ($action == 'question')
{
	isset($_GET['question']) ? $questionid = $_GET['question'] : $questionid = '';
	if (!preg_match('/^[\d]+$/', $questionid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$getquestion = $DB->query("SELECT * FROM `unp_faq_questions` WHERE id='$questionid'");
	if (!($DB->is_single_row($getquestion)))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	include('header.php');
	unp_openbox();
	while($question = $DB->fetch_array($getquestion))
	{
		$questiontitle = $question['question'];
		$answer = $question['answer'];
		echo '
			<table width="90%" border="0" align="center"><tr><td>
			<table border="0" width="100%" style="
			border-left: #000000 1px solid;
			border-right: #000000 1px solid;
			border-top: #000000 1px solid"
			cellpadding="5" cellspacing="0">
			<tr>
			<td bgcolor="#6384B0" style="border-bottom : #000000 1px solid" colspan="2">
			<span class="tblheadtxt"><strong>'.$questiontitle.'</strong></span></td></tr>
			<tr><td bgcolor="#FFFFFF" colspan="2" style="border-bottom : #000000 1px solid">
			'.$answer.'</td></tr></table><br /></td></tr></table>';
	}
	unp_closebox();
	include('footer.php');
}
?>