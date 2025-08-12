<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/upgrade_bmail_1.5.13_2.0.1.php,v $
## $Revision: 1.4.2.1 $
## $Author: brobertson $
## $Date: 2004/05/11 11:29:27 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade B-Mail", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade B-Mail", "You must be <b>root</b> to upgrade the system.",$session->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/users/extensions/bmail/bmail.inc")) {
	report_ignore($_SERVER['SCRIPT_FILENAME']);
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.failure')) {
	unlink($_SERVER['SCRIPT_FILENAME'] . '.failure');
}

?>
Upgrading MySource Bmail.<br><br>
<?
error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();
global $CACHE;

if(!$_REQUEST['step']) {
	$queries[] = "CREATE TABLE xtra_users_extension_bmail_backup (
		  userid          MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
		  default_subject VARCHAR(255) NOT NULL, 
		  default_from    VARCHAR(255) NOT NULL, 
		  default_org     VARCHAR(255) NOT NULL, 
		  default_bounce  VARCHAR(255) NOT NULL, 
		  default_days_open INT UNSIGNED NOT NULL,
		  signature       TEXT         NOT NULL,
		  bounce_emailaccount_username TEXT,
		  bounce_emailaccount_password TEXT,
		  bounce_emailaccount_server   TEXT,
		  bounce_emailaccount_port     TEXT,
		  PRIMARY KEY(userid)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_backup SELECT * FROM xtra_users_extension_bmail";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_list_backup (
		listid       MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
		userid       MEDIUMINT    UNSIGNED NOT NULL,
		name         VARCHAR(255) NOT NULL,
		description  VARCHAR(255) NOT NULL,
		PRIMARY KEY(listid),
		KEY(userid),
		KEY(name)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_list_backup SELECT * FROM xtra_users_extension_bmail_list";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_subscription_org_backup (
		  listid          MEDIUMINT    UNSIGNED NOT NULL,
		  organisationid  MEDIUMINT    UNSIGNED NOT NULL,
		  include_subs    TINYINT      UNSIGNED NOT NULL DEFAULT '0',
		  managers        TINYINT      UNSIGNED NOT NULL DEFAULT '3',
		  titles          VARCHAR(255) NOT NULL DEFAULT '',
		  subscribe_date  DATETIME NOT NULL,
		  PRIMARY KEY(listid,organisationid),
		  KEY(organisationid),
		  KEY(subscribe_date)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_subscription_org_backup SELECT * FROM xtra_users_extension_bmail_subscription_org";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_unsubscription_user_backup (
		  listid          MEDIUMINT UNSIGNED NOT NULL,
		  userid          MEDIUMINT UNSIGNED NOT NULL,
		  reason          VARCHAR(255) NOT NULL, # In case you want to remember why
		  PRIMARY KEY(listid,userid),
		  KEY(userid)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_unsubscription_user_backup SELECT * FROM xtra_users_extension_bmail_unsubscription_user";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_subscription_user_backup (
		  listid          MEDIUMINT UNSIGNED NOT NULL,
		  userid          MEDIUMINT UNSIGNED NOT NULL,
		  subscribe_date  DATETIME NOT NULL,
		  PRIMARY KEY(listid,userid),
		  KEY(userid),
		  KEY(subscribe_date)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_subscription_user_backup SELECT * FROM xtra_users_extension_bmail_subscription_user";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_subscription_external_user_backup (
		  listid          MEDIUMINT UNSIGNED NOT NULL,
		  external_userid    MEDIUMINT UNSIGNED NOT NULL,
		  subscribe_date  DATETIME NOT NULL,
		  PRIMARY KEY(listid,external_userid),
		  KEY(external_userid),
		  KEY(subscribe_date)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_subscription_external_user_backup SELECT * FROM xtra_users_extension_bmail_subscription_external_user";

	$queries[] = "CREATE TABLE xtra_users_extension_bmail_external_user_backup (
		  external_userid MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
		  firstname    VARCHAR(128) NOT NULL,
		  surname      VARCHAR(128) NOT NULL,
		  email        VARCHAR(255) NOT NULL,
		  other_data   TEXT         NOT NULL,
		  signup_date  DATETIME      NOT NULL,
		  userid       MEDIUMINT UNSIGNED NOT NULL,
		  PRIMARY KEY(external_userid),
		  KEY(firstname),
		  KEY(surname),
		  KEY(email)
		);";
	$insert_queries[] = "INSERT INTO xtra_users_extension_bmail_external_user_backup SELECT * FROM xtra_users_extension_bmail_external_user";

	foreach($queries as $query) {
		if(!$usersdb->select($query)) {
			echo "Error - There was a problem creating a backup bmail table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}
	
	foreach($insert_queries as $query) {
		$usersdb->insert($query);
	}

	$sql[] = "DROP TABLE xtra_users_extension_bmail;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_list;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_org;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_unsubscription_user;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_user;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_external_user;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_external_user;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_folder;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_bmail;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_bmail_list;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_bounce_errorlist;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_bounce_rulelist;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_reads;";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_links;";

	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list (
		  listid           MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  creatorid        MEDIUMINT UNSIGNED NOT NULL,
		  parameters       LONGTEXT NOT NULL
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_user_grant (
		  listid           MEDIUMINT UNSIGNED NOT NULL,
		  userid           MEDIUMINT UNSIGNED NOT NULL,
		  access_type      ENUM('R','W') NOT NULL,
		  PRIMARY KEY(listid,userid,access_type)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail_user_grant (
		  bulkmailid       MEDIUMINT UNSIGNED NOT NULL,
		  userid           MEDIUMINT UNSIGNED NOT NULL,
		  PRIMARY KEY(bulkmailid,userid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail_group_grant (
		  bulkmailid       MEDIUMINT UNSIGNED NOT NULL,
		  groupid          MEDIUMINT UNSIGNED NOT NULL,
		  PRIMARY KEY(bulkmailid,groupid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_org (
		  listid           MEDIUMINT UNSIGNED NOT NULL,
		  organisationid   MEDIUMINT UNSIGNED NOT NULL,
		  subscribe_date   DATETIME NOT NULL,
		  PRIMARY KEY(listid,organisationid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_group_grant (
		  listid           MEDIUMINT UNSIGNED NOT NULL,
		  groupid          MEDIUMINT UNSIGNED NOT NULL,
		  access_type      ENUM('R','W') NOT NULL,
		  PRIMARY KEY(listid,groupid,access_type)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_unsubscribed_user (
		  listid           MEDIUMINT UNSIGNED NOT NULL,
		  userid           MEDIUMINT UNSIGNED NOT NULL,
		  unsubscribe_date DATETIME NOT NULL,
		  PRIMARY KEY(listid,userid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_user (
		  listid           MEDIUMINT UNSIGNED NOT NULL,
		  userid           MEDIUMINT UNSIGNED NOT NULL,
		  subscribe_date   DATETIME NOT NULL,
		  PRIMARY KEY(listid,userid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_list_external_user (
		  listid          MEDIUMINT UNSIGNED NOT NULL,
		  external_userid MEDIUMINT UNSIGNED NOT NULL,
		  subscribe_date  DATETIME NOT NULL,
		  PRIMARY KEY(listid,external_userid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_external_user (
		  external_userid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  email           VARCHAR(255) NOT NULL,
		  firstname	  VARCHAR(255) NOT NULL,
		  surname         VARCHAR(255) NOT NULL,
		  parameters      LONGTEXT
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail (
		  bulkmailid      MEDIUMINT    UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  parameters      LONGTEXT NOT NULL,
		  folder          ENUM('D','E','S','T') NOT NULL,
		  create_date     DATETIME NOT NULL,
		  sent_date       DATETIME NOT NULL,
		  creatorid       MEDIUMINT UNSIGNED NOT NULL,
		  pointer         MEDIUMINT UNSIGNED NOT NULL,
		  status          ENUM('open','closed') NOT NULL,
		  num_sent        MEDIUMINT UNSIGNED NOT NULL,
		  closed_date     DATETIME NOT NULL
		 );";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail_list (
		  bulkmailid      MEDIUMINT    UNSIGNED NOT NULL,
		  listid          MEDIUMINT    UNSIGNED NOT NULL,
		  PRIMARY KEY(bulkmailid,listid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bounce_errorlist (
			errorid MEDIUMINT    UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			errormessage VARCHAR(255)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail_reads (
		  bulkmailid    MEDIUMINT    UNSIGNED NOT NULL,
		  mailid        MEDIUMINT    UNSIGNED NOT NULL,
		  userid        MEDIUMINT    UNSIGNED NOT NULL,
		  email_address VARCHAR(255) NOT NULL,
		  reads         MEDIUMINT    UNSIGNED NOT NULL,
		  forwards      MEDIUMINT    UNSIGNED NOT NULL,
		  first_read    DATETIME NOT NULL,
		  last_read     DATETIME NOT NULL,
		  mail_client   TEXT NOT NULL,
		  ip            VARCHAR(20) NOT NULL,
		  PRIMARY KEY  (bulkmailid, mailid)
		);";
	$sql[] = "CREATE TABLE xtra_users_extension_bmail_bulkmail_links (
		  bulkmailid  MEDIUMINT    UNSIGNED NOT NULL,
		  link        VARCHAR(255) NOT NULL,
		  hits        MEDIUMINT    UNSIGNED NOT NULL,
		  hit_times   LONGTEXT NOT NULL,
		  PRIMARY KEY (bulkmailid, link)
		);";

	$sql[] = "ALTER TABLE xtra_wizard_bmail_links DROP PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_links CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_links_summary DROP PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_links_summary CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_summary DROP PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_summary CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_user_summary DROP PRIMARY KEY;";
	$sql[] = "ALTER TABLE xtra_wizard_bmail_user_summary CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;";

	foreach($sql as $run) $usersdb->select($run);

		?> 
	<p>The database changes for the bmail upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="<?= $_SERVER[PHP_SELF]; ?>" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the bmail table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?	

} elseif ($_REQUEST['step'] == 2) {
	$lists = $usersdb->associative_array('SELECT listid,name,userid FROM xtra_users_extension_bmail_list_backup');

	foreach($lists as $listid => $data) {
		$params['name'] = $data[name];
		$usersdb->insert("INSERT INTO xtra_users_extension_bmail_list (listid,creatorid,parameters) VALUES ($listid,'$data[userid]','".addslashes(serialize($params))."')");
	}

	$external_users = $usersdb->associative_array('SELECT external_userid,firstname,surname,email,other_data,signup_date FROM xtra_users_extension_bmail_external_user_backup');

	foreach($external_users as $external_userid => $data) {
		$params['other_info'] = $data['other_data'];
		$usersdb->insert("INSERT INTO xtra_users_extension_bmail_external_user (external_userid,email,firstname,surname,parameters) VALUES ($external_userid,'".addslashes($data[email])."','".addslashes($data[firstname])."','".addslashes($data[surname])."','".addslashes(serialize($params))."')");
	}

	$subscribed_orgs = $usersdb->associative_array2('SELECT listid,organisationid,subscribe_date FROM xtra_users_extension_bmail_subscription_org_backup');

	foreach($subscribed_orgs as $listid => $data) {
		foreach($data as $orgid => $subscribe_date) {
			$usersdb->insert("INSERT INTO xtra_users_extension_bmail_list_org (listid,organisationid,subscribe_date) VALUES ($listid,$orgid,'$subscribe_date')");
		}
	}

	$subscribed_users = $usersdb->associative_array2('SELECT listid,userid,subscribe_date FROM xtra_users_extension_bmail_subscription_user_backup');

	foreach($subscribed_users as $listid => $data) {
		foreach($data as $userid => $subscribe_date) {
			$usersdb->insert("INSERT INTO xtra_users_extension_bmail_list_user (listid,userid,subscribe_date) VALUES ($listid,$userid,'$subscribe_date')");
		}
	}

	$unsubscribed_users = $usersdb->associative_column('SELECT listid,userid FROM xtra_users_extension_bmail_unsubscription_user_backup');

	foreach($unsubscribed_users as $listid => $userids) {
		foreach($userids as $userid) {
			$usersdb->insert("INSERT INTO xtra_users_extension_bmail_list_unsubscribed_user (listid,userid) VALUES ($listid,$userid)");
		}
	}

	$subscribed_external_users = $usersdb->associative_array2('SELECT listid,external_userid,subscribe_date FROM xtra_users_extension_bmail_subscription_external_user_backup');

	foreach($subscribed_external_users as $listid => $data) {
		foreach($data as $external_userid => $subscribe_date) {
			$usersdb->insert("INSERT INTO xtra_users_extension_bmail_list_external_user (listid,external_userid,subscribe_date ) VALUES ($listid,$external_userid,'$subscribe_date')");
		}
	}

	echo "B-Mail system has been upgraded. Dropping backup table...<p>";
	
	$sql[] = "DROP TABLE xtra_users_extension_bmail_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_list_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_org_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_user_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_subscription_external_user_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_external_user_backup";
	$sql[] = "DROP TABLE xtra_users_extension_bmail_unsubscription_user_backup";

	foreach($sql as $run) {
		$usersdb->select($run);
	}

	report_success($_SERVER['SCRIPT_FILENAME']);

	echo "All bmail pages have been converted. Dropping backup tables...<p>";
	
	report_success($_SERVER['SCRIPT_FILENAME']);
}
?>
