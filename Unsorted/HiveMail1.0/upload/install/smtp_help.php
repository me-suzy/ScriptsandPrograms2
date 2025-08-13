<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('ALLOW_LOGGED_OUT', true);
define('ALLOW_INSTALL_FOLDER', true);
require_once('../includes/config.php');
require_once('../includes/db_mysql.php');
require_once('../includes/functions.php');
require_once('../includes/admin_functions.php');
require_once('../includes/template_functions.php');
require_once('../includes/smtp_functions.php');
require_once('../includes/init.php');
cp_header(' &raquo; Configuration Assistant', false, false);

// ############################################################################
// Header
starttable('<table border="0" cellpadding="0" cellspacing="0" style="border-width: 0px;" align="center"><tr class="thead" style="background: none;"><td><img src="../misc/cp_logo.gif" valign="middle" /></td><td style="padding-left: 12px;"><span style="font-size: 20px;">HiveMail Configuration</span><br /><span style="font-size: 15px;" class="theadlink">Nullified by CyKuH from WTN Team</span></td></tr></table>', '100%');
echo "	<tr class=\"".getclass()."\" height=\"175\" valign=\"top\">\n";
echo "		<td style=\"padding: 10px;\">";

// ############################################################################
// Intro
if (empty($address)) {
	echo '<p>Welcome to HiveMail confuration assistant!<br />
	This script will attempt to \'guess\' the correct settings for the SMTP server HiveMail will use.</p>';

	startform(basename($_SERVER['PHP_SELF']));
	echo '<p>In order to test the settings, the script will need to send you a test email message.<br />Please enter your email address below:<br />
	<input type="test" class="bginput" name="address" value="" size="35" />';

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
		$ok = smtp_mail($address, 'Test message from HiveMail', "The test was successful!\n\nThese are the values you should put in the config.php file:
// +-------------------------------------------------------------+
// | SMTP Configuration
// +-------------------------------------------------------------+
\$smtp_config = array(
	'host' => '$smtp_config[host]',
	'port' => $smtp_config[port],
	'helo' => '$smtp_config[helo]',
	'auth' => false,
	'user' => '',
	'pass' => '',
);", 'From: HiveMail Configuration Assistant');
		if ($ok) {
// CyKuH [WTN]
			echo '<span class="cp_temp_orig"><b>SUCCESS!</b></span></li>';
			break;
		} else {
			echo '<span class="cp_temp_cust"><b>Could not send message</b></span></li>';
		}
	}
	echo "</ul></p>\n";

	if ($ok) {
		echo '<p>The test is now complete. According to the server responses, the message was successfully sent. However, in order to make sure it actually worked, please check your email account and see if the message was really sent. In that email are included the values you can use for the <tt>config.php</tt> file.</p>';
	} else {
		echo '<p>We\'re sorry, the script was not able to send the email message successfully. Please contact your host and request for the exact values you can use.</p>';
	}

	echo "</td>\n";
	echo "	</tr>\n";
	tablehead(array(iif(defined('GOTO_NEXT') and GOTO_NEXT == true, '<a href="index.php?step='.($step + 1).'" class="navlink"><span class="theadlink">&raquo; Continue to next step: '.$steps[($step + 1)].' &raquo;</span></a>', '&nbsp;')));
	endtable();
}

cp_footer(false);
?>