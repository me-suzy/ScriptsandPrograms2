<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.4.0_2.8.0/upgrade_account_manager_to_parameter.php,v $
## $Revision: 1.2.2.1 $
## $Author: brobertson $
## $Date: 2004/05/11 11:29:27 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Account Manager", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Account Manager", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/account_manager/account_manager.inc")) {
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
Upgrading MySource Account Manager.<br><br>
<?

error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();
global $CACHE;

$CACHE->wipe();

if(!$_REQUEST['step']) {
	# First step!
	 #########################################################
	# Web DB Changes
	$sql = array();
	$query = "CREATE TABLE xtra_page_template_account_manager_backup (
		pageid         MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
		title          VARCHAR(128),
		primary_orgid  MEDIUMINT(9) UNSIGNED NOT NULL,
		primary_listid MEDIUMINT(9) UNSIGNED,
		functions      VARCHAR(255) DEFAULT '',
		auto_affiliate VARCHAR(255) DEFAULT '',
		manager_email  VARCHAR(255),
		signup_form    TEXT,
		join_form      TEXT,
		edit_form      TEXT,
		signup_selective_emails TEXT,
		edit_selective_emails   TEXT,
		join_selective_emails   TEXT,
		default_title  VARCHAR(255) DEFAULT 'Customer',
		copy           MEDIUMTEXT
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup account manager table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	$query = "CREATE TABLE xtra_page_template_account_manager_assignment_backup (
		pageid         MEDIUMINT(9) UNSIGNED NOT NULL,
		organisationid MEDIUMINT(9) UNSIGNED NOT NULL,
		INDEX(pageid),
		PRIMARY KEY(pageid,organisationid)
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup account manager assignment table. The rest of the script can't run until this problem is fixed.<p>";
		exit;
	}

	$query = "CREATE TABLE xtra_page_template_account_manager_allowed_list_backup (
        pageid         MEDIUMINT(9) UNSIGNED NOT NULL,
        listid         MEDIUMINT(9) UNSIGNED NOT NULL,
        INDEX(pageid),
        PRIMARY KEY(pageid,listid)
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup account manager allowed list table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_account_manager");

	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_account_manager_backup (pageid, title, primary_orgid, primary_listid, functions, auto_affiliate, manager_email, signup_form, join_form, edit_form, signup_selective_emails, edit_selective_emails, join_selective_emails, default_title, copy) SELECT f.pageid, f.title, f.primary_orgid, f.primary_listid, f.functions, f.auto_affiliate, f.manager_email, f.signup_form, f.join_form, f.edit_form, f.signup_selective_emails, f.edit_selective_emails, f.join_selective_emails, f.default_title, f.copy FROM xtra_page_template_account_manager f";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the account manager table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}

		$query = "INSERT INTO xtra_page_template_account_manager_assignment_backup (pageid, organisationid) SELECT f.pageid, f.organisationid FROM xtra_page_template_account_manager_assignment f";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Warning - There was a problem copying the data from the account manager assignment table to the backup table. There may be no entries in this table.<p>";
		}

		$query = "INSERT INTO xtra_page_template_account_manager_allowed_list_backup (pageid, listid) SELECT f.pageid, f.listid FROM xtra_page_template_account_manager_allowed_list f";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Warning - There was a problem copying the data from the account manager list table to the backup table. There may be no entries in this table.<p>";
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to bmail table.";

	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN title";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN primary_orgid";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN primary_listid";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN functions";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN auto_affiliate";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN manager_email";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN signup_form";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN join_form";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN edit_form";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN signup_selective_emails";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN edit_selective_emails";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN join_selective_emails";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN default_title";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager DROP COLUMN copy";
	$sql[] = "ALTER TABLE xtra_page_template_account_manager ADD  COLUMN parameters LONGTEXT";
	$sql[] = "DROP TABLE xtra_page_template_account_manager_assignment";
	$sql[] = "DROP TABLE xtra_page_template_account_manager_allowed_list";
	
	foreach($sql as $run) $webdb->select($run);

	?> 
	<p>The database changes for the account manager upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_account_manager_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the account manager table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$ams = $webdb->associative_array("SELECT * from xtra_page_template_account_manager_backup");
	$clean_convert = true;
	include_once("$SQUIZLIB_PATH/form/form.inc");
	include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
	
	foreach($ams as $pageid => $data) {
		
		$listids = $webdb->associative_array("SELECT * from xtra_page_template_account_manager_allowed_list_backup WHERE pageid='$pageid'");
		$orgs = $webdb->associative_array("SELECT * from xtra_page_template_account_manager_assignment_backup WHERE pageid='$pageid'");

		$parameters = array();
		$parameters['title'] = $data['title'];
		$parameters['primary_orgid'] = $data['primary_orgid'];
		$parameters['primary_listid'] = $data['primary_listid'];
		$parameters['manager_email'] = $data['manager_email'];
		$parameters['signup_form'] = $data['signup_form'];
		$parameters['join_form'] = $data['join_form'];
		$parameters['edit_form'] = $data['edit_form'];
		$parameters['default_title'] = $data['default_title'];
		
		$functions = explode(',',$data['functions']);
		$parameters['functions'] = $functions;
			
		$auto = explode(',',$data['auto_affiliate']);
		foreach ($auto as $name) {
			$parameters['auto_affiliate'][] = "auto_$name";
		}
		$parameters['auto_affiliate'] = $parameters['auto_affiliate'];

		$parameters['allowed_listids'] = array_values($listids);
		$parameters['assigned_orgids'] = array_values($orgs);

		$copy = array();
		$copy = unserialize($data['copy']);

		$parameters['copy']['login_text'] = $copy['Login Text'];
		$parameters['copy']['password_text'] = $copy['Password Text'];
		
		$parameters['copy']['welcome'] = new BodyCopy();
		$parameters['copy']['welcome']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['welcome']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Welcome']);
		$parameters['copy']['welcome'] = serialize($parameters['copy']['welcome']);

		$parameters['copy']['login_invite'] = new BodyCopy();
		$parameters['copy']['login_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['login_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Login Invite']);
		$parameters['copy']['login_invite'] = serialize($parameters['copy']['login_invite']);

		$parameters['copy']['send_password'] = new BodyCopy();
		$parameters['copy']['send_password']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['send_password']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Send Password']);
		$parameters['copy']['send_password'] = serialize($parameters['copy']['send_password']);

		$parameters['copy']['password_sent'] = new BodyCopy();
		$parameters['copy']['password_sent']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['password_sent']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Password Sent']);
		$parameters['copy']['password_sent'] = serialize($parameters['copy']['password_sent']);

		$parameters['copy']['login_not_found'] = new BodyCopy();
		$parameters['copy']['login_not_found']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['login_not_found']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Login Not Found']);
		$parameters['copy']['login_not_found'] = serialize($parameters['copy']['login_not_found']);

		$parameters['copy']['email_not_sent'] = new BodyCopy();
		$parameters['copy']['email_not_sent']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['email_not_sent']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Email Not Sent']);
		$parameters['copy']['email_not_sent'] = serialize($parameters['copy']['email_not_sent']);

		$parameters['copy']['password_email'] = $copy['Password Email'];

		$parameters['copy']['signup_invite'] = new BodyCopy();
		$parameters['copy']['signup_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['signup_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Signup Invite']);
		$parameters['copy']['signup_invite'] = serialize($parameters['copy']['signup_invite']);

		$parameters['copy']['signup_incomplete'] = new BodyCopy();
		$parameters['copy']['signup_incomplete']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['signup_incomplete']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Signup Incomplete']);
		$parameters['copy']['signup_incomplete'] = serialize($parameters['copy']['signup_incomplete']);

		$parameters['copy']['existing_login'] = new BodyCopy();
		$parameters['copy']['existing_login']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['existing_login']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Existing Login']);
		$parameters['copy']['existing_login'] = serialize($parameters['copy']['existing_login']);

		$parameters['copy']['signup_complete'] = new BodyCopy();
		$parameters['copy']['signup_complete']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['signup_complete']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Signup Complete']);
		$parameters['copy']['signup_complete'] = serialize($parameters['copy']['signup_complete']);

		$parameters['copy']['new_user_login_invite'] = new BodyCopy();
		$parameters['copy']['new_user_login_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['new_user_login_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['New User Login Invite']);
		$parameters['copy']['new_user_login_invite'] = serialize($parameters['copy']['new_user_login_invite']);

		$parameters['copy']['signup_email'] = $copy['Signup Email'];

		$parameters['copy']['logged_in_welcome'] = new BodyCopy();
		$parameters['copy']['logged_in_welcome']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['logged_in_welcome']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Logged In Welcome']);
		$parameters['copy']['logged_in_welcome'] = serialize($parameters['copy']['logged_in_welcome']);

		$parameters['copy']['relogin_invite'] = new BodyCopy();
		$parameters['copy']['relogin_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['relogin_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Relogin Invite']);
		$parameters['copy']['relogin_invite'] = serialize($parameters['copy']['relogin_invite']);
		
		$parameters['copy']['join_invite'] = new BodyCopy();
		$parameters['copy']['join_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['join_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Join Invite']);
		$parameters['copy']['join_invite'] = serialize($parameters['copy']['join_invite']);

		$parameters['copy']['join_incomplete'] = new BodyCopy();
		$parameters['copy']['join_incomplete']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['join_incomplete']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Join Incomplete']);
		$parameters['copy']['join_incomplete'] = serialize($parameters['copy']['join_incomplete']);

		$parameters['copy']['join_complete'] = new BodyCopy();
		$parameters['copy']['join_complete']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['join_complete']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Join Complete']);
		$parameters['copy']['join_complete'] = serialize($parameters['copy']['join_complete']);

		$parameters['copy']['join_email'] = $copy['Join Email'];

		$parameters['copy']['affiliate_welcome'] = new BodyCopy();
		$parameters['copy']['affiliate_welcome']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['affiliate_welcome']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Affiliate Welcome']);
		$parameters['copy']['affiliate_welcome'] = serialize($parameters['copy']['affiliate_welcome']);

		$parameters['copy']['edit_invite'] = new BodyCopy();
		$parameters['copy']['edit_invite']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['edit_invite']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Edit Invite']);
		$parameters['copy']['edit_invite'] = serialize($parameters['copy']['edit_invite']);

		$parameters['copy']['edit_failed'] = new BodyCopy();
		$parameters['copy']['edit_failed']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['edit_failed']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Edit Failed']);
		$parameters['copy']['edit_failed'] = serialize($parameters['copy']['edit_failed']);

		$parameters['copy']['edit_success'] = new BodyCopy();
		$parameters['copy']['edit_success']->insert_table(1,1,1,'bodycopy_table_cell_type_wysiwyg',false,array('width'=>'100%'));
		$parameters['copy']['edit_success']->tables[0]->rows[0]->cells[0]->type->set_html($copy['Edit Success']);
		$parameters['copy']['edit_success'] = serialize($parameters['copy']['edit_success']);

		if(!$webdb->update("update xtra_page_template_account_manager set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
			report_failure($_SERVER['SCRIPT_FILENAME']);
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All account manager pages have been converted. Dropping backup tables...<p>";
		$webdb->select("DROP TABLE xtra_page_template_account_manager_backup");
		$webdb->select("DROP TABLE xtra_page_template_account_manager_assignment_backup");
		$webdb->select("DROP TABLE xtra_page_template_account_manager_allowed_list_backup");
		report_success($_SERVER['SCRIPT_FILENAME']);
	}
?>
<br>
...upgrade complete.
<?
}

$CACHE->wipe();

?>
