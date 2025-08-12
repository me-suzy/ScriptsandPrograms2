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
// | Process Submission                                               |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submit']))
	{
		$tempid = $_POST['tempid'];
		$template = mysql_escape_string($_POST['template']);
		if (unp_isEmpty($template))
		{
			unp_msgBox($gp_allfields);
			exit;
		}
		if (!preg_match('/^[\d]+$/', $tempid))
		{
			unp_msgBox('You have entered an invalid template ID.');
			exit;
		}
		$updateTemplate = $DB->query("UPDATE `unp_template` SET template='$template' WHERE id='$tempid'");
		unp_autoBuildCache();
		unp_redirect('templates.php?action=list','Your template was successfully updated!<br />You will now be taken back to the templates list.');
	}
	elseif (isset($_POST['submitnew']))
	{
		if (DEV_BUILD == true)
		{
			$templatename = $_POST['templatename'];
			$template = mysql_escape_string($_POST['template']);
			if (unp_isEmpty($templatename) || unp_isEmpty($template))
			{
				unp_msgBox($gp_allfields);
				exit;
			}
			if (!eregi('^[a-zA-Z]{1}[a-zA-Z0-9_]+$', $templatename))
			{
				unp_msgBox('You have entered an invalid template name. Template names must begin with a letter and may only contain letters, numbers, and underscores (_).');
				exit;
			}
			// One last test.. see if a template with this name already exists
			$checkTempname = $DB->query("SELECT * FROM `unp_template` WHERE templatename='$templatename'");
			if ($DB->num_rows($checkTempname) != 0)
			{
				unp_msgBox('A template with this name already exists!');
				exit;
			}
			$addTemplate = $DB->query("INSERT INTO `unp_template` (`setid`,`templatename`,`template`) VALUES ('0','$templatename','$template')");
			unp_autoBuildCache();
			unp_redirect('templates.php?action=list','Your template was successfully added!<br />You will now be taken back to the templates list.');
		}
		else
		{
			unp_msgBox($gp_invalidrequest);
			exit;
		}
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Templates List                                                   |
// +------------------------------------------------------------------+
if ($action == 'list')
{
	include('header.php');
	unp_openBox();
	echo '<strong>Template Manager</strong>&nbsp;';
	unp_faqLink(9);
	echo '<br /><br />'."\n";
	if (DEV_BUILD == true)
	{
		echo '<center><a href="templates.php?action=add">Add Custom Template</a></center>';
	}
	echo '&nbsp;<img src="images/folder.gif" alt="Folder" />&nbsp;'."\n\n";
	echo '<strong>Utopia News Pro Templates</strong>';
	echo '<ul class="templatesgroup">';
	/* Comments Templates */
	echo '<li><strong>&nbsp;Comments Templates</strong></li>';
	echo '<ul class="templates">';
	$getTemps = $DB->query("SELECT * FROM `unp_template` WHERE setid='1' ORDER BY templatename ASC");
	while ($templates = $DB->fetch_array($getTemps))
	{
		echo '<li>&nbsp;'."\n";
		echo $templates['templatename'].'&nbsp;'."\n";
		echo '<span class="smallfont">[<a href="templates.php?action=edit&amp;tempid='.$templates['id'].'">edit</a>]</span>'."\n";
		echo '</li>'."\n";
	}
	echo '</ul>'."\n";

	/* Headlines Templates */
	echo '<li><strong>&nbsp;Headlines Templates</strong></li>';
	echo '<ul class="templates">';
	$getTemps = $DB->query("SELECT * FROM `unp_template` WHERE setid='2' ORDER BY templatename ASC");
	while ($templates = $DB->fetch_array($getTemps))
	{
		echo '<li>&nbsp;'."\n";
		echo $templates['templatename'].'&nbsp;'."\n";
		echo '<span class="smallfont">[<a href="templates.php?action=edit&amp;tempid='.$templates['id'].'">edit</a>]</span>'."\n";
		echo '</li>'."\n";
	}
	echo '</ul>'."\n";

	/* News Display Templates */
	echo '<li><strong>&nbsp;News Display Templates</strong></li>';
	echo '<ul class="templates">';
	$getTemps = $DB->query("SELECT * FROM `unp_template` WHERE setid='3' ORDER BY templatename ASC");
	while ($templates = $DB->fetch_array($getTemps))
	{
		echo '<li>&nbsp;'."\n";
		echo $templates['templatename'].'&nbsp;'."\n";
		echo '<span class="smallfont">[<a href="templates.php?action=edit&amp;tempid='.$templates['id'].'">edit</a>]</span>'."\n";
		echo '</li>'."\n";
	}
	echo '</ul>'."\n";

	/* Printable News Display Templates */
	echo '<li><strong>&nbsp;Printable News Display Templates</strong></li>';
	echo '<ul class="templates">';
	$getTemps = $DB->query("SELECT * FROM `unp_template` WHERE setid='4' ORDER BY templatename ASC");
	while ($templates = $DB->fetch_array($getTemps))
	{
		echo '<li>&nbsp;'."\n";
		echo $templates['templatename'].'&nbsp;'."\n";
		echo '<span class="smallfont">[<a href="templates.php?action=edit&amp;tempid='.$templates['id'].'">edit</a>]</span>'."\n";
		echo '</li>'."\n";
	}
	echo '</ul>'."\n";

	/* Other Templates */
	echo '<li><strong>&nbsp;Other Templates</strong></li>';
	echo '<ul class="templates">';
	$getTemps = $DB->query("SELECT * FROM `unp_template` WHERE setid='0' ORDER BY templatename ASC");
	if ($DB->num_rows($getTemps) < 1)
	{
		echo '<li>&nbsp;None</li>';
	}
	else
	{
		while ($templates = $DB->fetch_array($getTemps))
		{
			echo '<li>'."\n";
			echo $templates['templatename'].'&nbsp;'."\n";
			echo '<span class="smallfont">[<a href="templates.php?action=edit&amp;tempid='.$templates['id'].'">edit</a>]';
			echo '&nbsp;[<a href="templates.php?action=delcustom&amp;tempid='.$templates['id'].'">remove</a>]</span>'."\n";
			echo '</li>'."\n";
		}
	}
	echo '</ul>'."\n";

	echo '</ul>'."\n";
	unp_closeBox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Edit Templates                                                   |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	isset($_GET['tempid']) ? $tempid = $_GET['tempid'] : $tempid = '';
	if (!ereg('^[0-9]+$',$tempid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$getTemplate = $DB->query("SELECT * FROM `unp_template` WHERE id='$tempid'");
	if (!($DB->is_single_row($getTemplate)))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$template = $DB->fetch_array($getTemplate);
	include('header.php');
	unp_openBox();
	echo '<strong>Edit Template</strong>&nbsp;';
	unp_faqLink(9);
	echo '<br />';
	echo '
	<br />
	<form action="templates.php" method="post">
	<input type="hidden" name="tempid" value="'.$tempid.'" />
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%"><strong>Template Name:</strong></td>
			<td width="80%"><input type="text" disabled size="35" value="'.$template['templatename'].'" /></td>
		</tr>
		<tr>
			<td width="20%" valign="top"><strong><span class="normalfont">Template:</span></strong></td>
			<td width="80%"><textarea name="template" class="tmpleditor" cols="70" rows="20">'.htmlspecialchars($template['template']).'</textarea></td>
		</tr>
	</table>
	<center><input type="submit" name="submit" value="Submit Changes" />&nbsp;<input type="reset" value="Reset" /></center>
	</form>';
	unp_closeBox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Add Templates (Developer Build Only)                             |
// +------------------------------------------------------------------+
if ($action == 'add')
{
	if (DEV_BUILD != true)
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	include('header.php');
	unp_openBox();
	echo '<strong>Add Custom Templates</strong>&nbsp;';
	unp_faqLink(9);
	echo '<br />';
	echo '
	<br />
	<form action="templates.php" method="post">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%"><strong>Template Name:</strong></td>
			<td width="80%"><input type="text" maxlength="100" name="templatename" size="35" /></td>
		</tr>
		<tr>
			<td width="20%" valign="top"><strong>Template:</strong></td>
			<td width="80%"><textarea name="template" class="tmpleditor" cols="70" rows="20"></textarea></td>
		</tr>
	</table>
	<center><input type="submit" name="submitnew" value="Submit Changes" />&nbsp;<input type="reset" value="Reset" /></center>
	</form>';
	unp_closeBox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Delete Custom Templates                                          |
// +------------------------------------------------------------------+
if ($action == 'delcustom')
{
	isset($_GET['tempid']) ? $tempid = $_GET['tempid'] : $tempid = '';
	isset($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';
	if (!ereg('^[0-9]+$',$tempid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$getTemplate = $DB->query("SELECT * FROM `unp_template` WHERE id='$tempid'");
	if (!($DB->is_single_row($getTemplate)))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	$template = $DB->fetch_array($getTemplate);
	if ($template['setid'] == '0')
	{
		if ($verify != '1')
		{
			include('header.php');
			unp_openbox();
			echo 'Are you sure you want to remove template <span>'.$template['templatename'].'</span>?<br />
			<a href="templates.php?action=delcustom&tempid='.$tempid.'&verify=1">Yes</a><br />
			<a href="templates.php?action=list">No</a>';
			unp_closebox();
			include('footer.php');
		}
		else
		{
			// looks like a custom template - delete it!
			$delTemp = $DB->query("DELETE FROM `unp_template` WHERE id='$tempid'");
			unp_autoBuildCache();
			unp_redirect('templates.php?action=list','Your template was successfully deleted!<br />You will now be taken back to the templates list.');
		}
	}
	else
	{
		unp_msgBox('This is not a custom template. You cannot remove non-custom templates.');
		exit;
	}
}
?>