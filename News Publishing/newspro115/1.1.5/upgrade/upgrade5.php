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
	| Version - 1.1.3 to 1.1.4
	| Instructions: Upload to Utopia News Pro directory and
	|               run. Immediately remove file from server
	|               after use.
	+-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

*/

/* General Config */
$version_current = '1.1.3'; $version_current_pdless = 113;
$version_next = '1.1.4'; $version_next_pdless = 114;

$this_upgrade = 'upgrade5.php';
$next_upgrade = 'upgrade6.php';
/* General Config */

require('functions.inc.php');

// +------------------------------------------------------------------+
// | Step Setup                                                       |
// +------------------------------------------------------------------+
if (isset($_POST['step1']))     // <--Create New Tables
{
	$step = '1';
}
elseif (isset($_POST['step2'])) // <--Update FAQs
{
	$step = '2';
}
elseif (isset($_POST['step3'])) // <--Settings Update
{
	$step = '3';
}
elseif (isset($_POST['step4'])) // <--Table Alteration
{
	$step = '4';
}
elseif (isset($_POST['step5'])) // <--Updating Templates
{
	$step = '5';
}
elseif (isset($_POST['step6'])) // <--Complete
{
	$step = '6';
}
elseif (isset($_GET['action'])) // <--Miscellaneous Actions
{
	$action = $_GET['action'];
	$step = 'X';
}
else							// <--Introduction
{
	$step = '0';
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
<strong>Utopia News Pro Installer - Step '.$step.' of 6</strong><br /><br />';

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
if ($step == '0')
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
if ($step == '1')
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade is now adding new tables that appear in '.$version_next.'<br /><br />';
	echo 'There are no new tables in this version.';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step2" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}

// +------------------------------------------------------------------+
// | Step 2 - Update FAQs                                             |
// +------------------------------------------------------------------+
if ($step == '2')
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'The upgrade will now update the internal FAQ.<br /><br />';
	echo 'There are no new FAQs in this new version.';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step3" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}

// +------------------------------------------------------------------+
// | Step 3 - Settings Update                                         |
// +------------------------------------------------------------------+
if ($step == '3')
{
	echo ($p_header);
	echo ($p_openbox);
	$URI = explode('upgrade', $_SERVER['REQUEST_URI']);
	$currentdir = $URI[0];
	echo 'Upgrade will now add any settings introduced by features that appear in '.$version_next.'<br /><br />';
	echo 'This version does not introdude any new settings.';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step4" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}

// +------------------------------------------------------------------+
// | Step 4 - Table Alteration                                        |
// +------------------------------------------------------------------+
if ($step == '4')
{
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade is now altering any tables that changed between '.$version_current.' and '.$version_next.'<br /><br />';
	echo 'There are no table alterations in this version.';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step5" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}

// +------------------------------------------------------------------+
// | Step 5 - Updating Templates                                      |
// +------------------------------------------------------------------+
if ($step == '5')
{
	// No need to delete old templates
	$insertNewTemplate = $DB->query("INSERT INTO `unp_template` (`setid`, `templatename`, `template`) VALUES (3, 'news_header', '<!-- news_header -->')");
	echo ($p_header);
	echo ($p_openbox);
	echo 'Upgrade will now update old '.$version_current.' templates.<br /><br />';
	echo 'All templates updated!';
	echo '<form action="'.$this_upgrade.'" method="post"><input type="submit" name="step6" value="Continue -&gt;" /></form>';
	echo ($p_closebox);
	echo ($p_footer);
}

// +------------------------------------------------------------------+
// | Step 6 - Complete                                                |
// +------------------------------------------------------------------+
if ($step == '6')
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

?>