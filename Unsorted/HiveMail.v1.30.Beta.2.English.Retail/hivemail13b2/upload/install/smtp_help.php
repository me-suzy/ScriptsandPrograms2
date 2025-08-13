<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: smtp_help.php,v $ - $Revision: 1.10 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
define('ALLOW_INSTALL_FOLDER', true);
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
require_once('../includes/functions.php');
require_once('../includes/functions_admin.php');
require_once('../includes/functions_template.php');
require_once('../includes/functions_smtp.php');
require_once('../includes/init.php');
cp_header(' &raquo; Configuration Assistant', false, false);

// ############################################################################
// Header
starttable('<table border="0" cellpadding="0" cellspacing="0" style="border-width: 0px;" align="center"><tr class="thead" style="background: none;"><td><img src="../misc/cp_logo.gif" valign="middle" /></td><td style="padding-left: 12px;"><span style="font-size: 20px;">HiveMail&trade; Configuration</span><br /><!--CyKuH [WTN]-->Nullified by WTN Team `2004</td></tr></table>', '100%');
echo "	<tr class=\"".getclass()."\" height=\"175\" valign=\"top\">\n";
echo "		<td style=\"padding: 10px;\">";

// ############################################################################
// Intro
if (empty($address)) {
	echo '<p>Welcome to the HiveMail&trade; confuration assistant!<br />
	This script will attempt to \'guess\' the correct settings for the SMTP server HiveMail&trade; will use.</p>';

	startform(basename($_SERVER['PHP_SELF']));
	echo '<p>In order to test the settings, the script will need to send you a test email message.<br />Please enter your email address below:<br />
	<input type="text" class="bginput" name="address" value="" size="35" />';

	echo "</td>\n";
	echo "	</tr>\n";
	endform('Begin Test');
	endtable();
}

// ############################################################################
// Do it
else {
	// Possible server ports
	$ports = array(25, 465, 24, 209);
	$smtp_config['host'] = ini_get('SMTP');
	$smtp_config['helo'] = exec('hostname');
	$smtp_config['auth'] = false;
	$smtp_config['user'] = '';
	$smtp_config['pass'] = '';

	echo "<p>Trying to send message using the server: $smtp_config[host]
	<ul>";
	foreach ($ports as $port) {
		echo "<li>Port $port: ";
		$smtp_config['port'] = $port;
		$ok = smtp_mail($address, 'Test message from HiveMail', "The test was successful!\n\nThese are the values you should use for the SMTP configuration section in the admin options:\nServer name: $smtp_config[host]\nServer port: $smtp_config[port]\nHELO greeting: $smtp_config[helo]\nUse authentication: No\nUsername: (leave empty)\nPassword: (leave empty)", 'From: YourMail Configuration Assistant', false, $smtp_config);
		if ($ok) {
			echo '<span class="cp_temp_orig"><b>SUCCESS!</b></span></li>';
			break;
		} else {
			echo '<span class="cp_temp_cust"><b>Could not send message</b></span></li>';
		}
	}
	echo "</ul></p>\n";

	if (is_object($_smtp_connection)) {
		$_smtp_connection->quit();
	}

	if ($ok) {
		echo '<p>The test is now complete. According to the server responses, the message was successfully sent. However, in order to make sure it actually worked, please check your email account and see if the message was really sent. The correct values that can be used <a href="../admin/option.php">here</a> are included in the message.</p>';
	} else {
		echo '<p>We\'re sorry, the script was not able to send the email message successfully. Please contact your host and request for the exact values you can use.</p>';
	}

	echo "</td>\n";
	echo "	</tr>\n";
	tablehead(array('&nbsp;'));
	endtable();
}

cp_footer(false);
?>