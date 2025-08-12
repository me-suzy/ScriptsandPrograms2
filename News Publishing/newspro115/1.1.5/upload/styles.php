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
// | Check Logged In User Has Permission To Access This               |
// +------------------------------------------------------------------+
if ($USER['groupid'] != 1)
{
	// permission denied
	unp_msgBox($gp_permserror);
	exit;
}
// +------------------------------------------------------------------+
// | Process Style Submission                                         |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submitstyle']))
	{
		$stylevar1 = $_POST['1'];
		$stylevar2 = $_POST['2'];
		$stylevar3 = $_POST['3'];
		$stylevar4 = $_POST['4'];
		$stylevar5 = $_POST['5'];
		$stylevar6 = $_POST['6'];
		$stylevar7 = $_POST['7'];
		$stylevar8 = $_POST['8'];
		$stylevar9 = $_POST['9'];
		$stylevar10 = $_POST['10'];
		// BEGIN VALIDATION
		unp_isValidStyle($stylevar1);
		unp_isValidStyle($stylevar2);
		unp_isValidStyle($stylevar3);
		unp_isValidStyle($stylevar4);
		unp_isValidStyle($stylevar5);
		unp_isValidStyle($stylevar6);
		unp_isValidStyle($stylevar7);
		unp_isValidStyle($stylevar8);
		unp_isValidStyle($stylevar9);
		unp_isValidStyle($stylevar10);
		// END VALIDATION
		$DB->query("UPDATE `unp_style` SET value='$stylevar1' WHERE id='1'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar2' WHERE id='2'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar3' WHERE id='3'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar4' WHERE id='4'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar5' WHERE id='5'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar6' WHERE id='6'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar7' WHERE id='7'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar8' WHERE id='8'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar9' WHERE id='9'");
		$DB->query("UPDATE `unp_style` SET value='$stylevar10' WHERE id='10'");
		unp_autoBuildCache();
		unp_redirect('styles.php?action=edit','Styles successfully updated!<br />You will now be taken back to the style page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Process Style Edit Page                                          |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	include('header.php');
	unp_openbox();
	echo '
	<form action="styles.php" method="post">
	<strong>Edit Styles</strong>&nbsp;';
	unp_faqLink(5);
	echo '<br />
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td width="50%"><em>Style Variable</em></td>
		<td width="30%"><em>Value</em></td>
		<td width="20%"><em>Example</em></td>
		</tr>';
	$getstyle = $DB->query("SELECT * FROM `unp_style`");
	while ($style = $DB->fetch_array($getstyle))
	{
		$id = $style['id'];
		$title = $style['title'];
		$varname = $style['varname'];
		$value = $style['value'];
		echo '
		<tr>
			<td>'.$title.'</td>
			<td><input type="text" value="'.htmlspecialchars($value).'" size="20" name="'.$id.'" /></td>
			<td><input type="button" value="              " disabled="disabled" style="background-color: '.htmlspecialchars($value).'" /></td>
		</tr>';
	}
	echo '
	</table>
	<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
	<td width="100%" align="center"><input type="submit" value="Submit Style" name="submitstyle" accesskey="s" />&nbsp;<input type="reset" value="Reset" /></td>
	</tr>
	</table>
	</form>';
	unp_closebox();
	include('footer.php');
}
?>