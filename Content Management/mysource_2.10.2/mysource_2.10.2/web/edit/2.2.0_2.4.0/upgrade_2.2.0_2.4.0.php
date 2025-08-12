<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.2.0_2.4.0/Attic/upgrade_2.2.0_2.4.0.php,v $
## $Revision: 1.1.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/13 23:34:52 $
#######################################################################
# Initialise
require_once("../../init.php");
#---------------------------------------------------------------------#

include_once ("./upgrade_functions.php");

 ####################################################################
# tell anyone who isn't root to politely go away
$UPGRADE_FROM = '2.2.0';
$UPGRADE_TO   = '2.4.0';


if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be logged in.");
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade to $UPGRADE_TO", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
}

?>
Upgrading MySource <?=$UPGRADE_FROM?> to <?=$UPGRADE_TO?>...<br><br>
<?
$num_upgrade_scripts = 2; 
if (file_exists("$SYSTEM_ROOT/xtras/page/templates/bmail/bmail.inc")) $num_upgrade_scripts++;

if (!$_GET['system_backed_up']) {
?>
<div align="center">
<form action="<?= $_SERVER['PHP_SELF']; ?>" method="GET">
<b>Are you sure that you have backed up your system? You only need to make copies of the <b>data</b> directory and dump you MySource database(s).</b><p>
<input type="hidden" name="time" value="<?=microtime()?>">
<input type="submit" name="system_backed_up" value="Yes">
<input type="button" value="No" onClick="javascript: alert('Well get on with it :)');">
</form>
</div>
<?
exit;
}

error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();
global $CACHE;

$CACHE->wipe();

 ########################################################################
# Set the system logging on, so logging doesn't stop after the upgrade
$SYSTEM_CONFIG->set_log_visitors(1);
$SYSTEM_CONFIG->conf_updated();


 #########################################################
# Web DB Changes
$sql = array();
$sql[] = "ALTER TABLE site_url ADD COLUMN protocol VARCHAR(10) NOT NULL";
$sql[] = "ALTER TABLE page ADD COLUMN `ssl` TINYINT NOT NULL";
$sql[] = "UPDATE site_url SET protocol='http'";
$sql[] = "ALTER TABLE file ADD COLUMN log_hits SET('Y','N') DEFAULT 'N' NOT NULL AFTER visible;";
$sql[] = "ALTER TABLE page CHANGE next_status_change next_action DATETIME NOT NULL";
$sql[] = "ALTER TABLE page_status RENAME page_action";
$sql[] = "ALTER TABLE page_action CHANGE status action_value VARCHAR(255) NOT NULL";
$sql[] = "ALTER TABLE page_action ADD COLUMN action VARCHAR(255) NOT NULL";
$sql[] = "ALTER TABLE page_action ADD INDEX (ACTION)";
$sql[] = "ALTER TABLE page_action DROP PRIMARY KEY";
$sql[] = "ALTER TABLE page_action ADD PRIMARY KEY (PAGEID,DATE,ACTION)";
$sql[] = "UPDATE page_action SET action='status' WHERE action_value IN ('L','R','P','U','D','A','E')";

$sql[] = "CREATE TABLE log_page_not_found (pageid MEDIUMINT UNSIGNED NOT NULL, sessionid CHAR(32) NOT NULL, hit_time DATETIME NOT NULL, userid MEDIUMINT UNSIGNED NOT NULL, referer TEXT NOT NULL, KEY (pageid), KEY (hit_time), KEY (sessionid), KEY (userid));";

$sql[] = "CREATE TABLE page_admin (userid MEDIUMINT UNSIGNED NOT NULL, pageid MEDIUMINT UNSIGNED NOT NULL, PRIMARY KEY (userid,pageid), KEY (pageid))";

$sql[] = "ALTER TABLE page ADD COLUMN imageid MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL;";
$sql[] = "ALTER TABLE site ADD COLUMN forbidden_pageid MEDIUMINT UNSIGNED NOT NULL;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD COLUMN selective_emails TEXT;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column receipt_email TINYINT DEFAULT 0;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column recipient_email_body TEXT;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column receipt_email_body TEXT;";
$sql[] = "CREATE TABLE xtra_page_template_forbidden (pageid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY, parameters LONGTEXT NOT NULL);";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN page_copy LONGTEXT;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN link_colour CHAR(6)  DEFAULT '';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN number_per_row SMALLINT UNSIGNED DEFAULT '1'";
#$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP textwrap;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP longname;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP showdesc;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP showthumb;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page DROP shortname;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column formelements_keyword TEXT;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column hide_results TINYINT DEFAULT 0;";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN number_per_page INT DEFAULT 0;";
$sql[] = "ALTER TABLE page_action DROP PRIMARY KEY;";
$sql[] = "ALTER TABLE page_action CHANGE userid userid MEDIUMINT(8) UNSIGNED NOT NULL;";
$sql[] = "ALTER TABLE page_action ADD KEY(pageid);";
$sql[] = "ALTER TABLE page_action ADD KEY(date);";
$sql[] = "ALTER TABLE page_action ADD KEY(userid);";
$sql[] = "ALTER TABLE page_admin ADD COLUMN parameters VARCHAR(255) DEFAULT '0';";
$sql[] = "ALTER TABLE site_admin ADD COLUMN parameters VARCHAR(255) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_site_map ADD COLUMN print_stalks tinyint(1) default '1';";
$sql[] = "ALTER TABLE xtra_page_template_site_map ADD COLUMN level_hierarchy tinyint(1) default '1';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_menu CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_tool CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_width  VARCHAR(5);";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_height VARCHAR(5);";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_resize CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_status CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_scroll CHAR(1) DEFAULT '0';";
$sql[] = "ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_location CHAR(1) DEFAULT '0';";


foreach($sql as $run) $webdb->select($run);

# User database
$sql = array();
$sql[] = "ALTER TABLE location ADD column name VARCHAR(255) DEFAULT '' NOT NULL;";
$sql[] = "ALTER TABLE user ADD COLUMN created_date date NOT NULL AFTER web_status;";

foreach($sql as $run) $usersdb->select($run);

$CACHE->wipe();

?>
<br>

<?
report_success($_SERVER['SCRIPT_FILENAME']);
?>
