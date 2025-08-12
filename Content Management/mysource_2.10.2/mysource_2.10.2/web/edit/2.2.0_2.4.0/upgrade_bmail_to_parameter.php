<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.2.0_2.4.0/Attic/upgrade_bmail_to_parameter.php,v $
## $Revision: 1.1.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/13 23:34:31 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade B-Mail", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade B-Mail", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/bmail/bmail.inc")) {
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

$CACHE->wipe();

if(!$_REQUEST['step']) {
	# First step!
	 #########################################################
	# Web DB Changes
	$sql = array();
	$query = "CREATE TABLE xtra_page_template_bmail_backup (
		pageid mediumint(9) unsigned NOT NULL default '0',
		title varchar(128) default NULL,
		functions varchar(255) default '',
		copy text,
		allow_users tinyint(3) unsigned NOT NULL default '1',
		allow_external_users tinyint(3) unsigned NOT NULL default '1',
		allow_unsubscribe tinyint(3) unsigned NOT NULL default '1',
		bmail_form text,
		primary_listid mediumint(9) unsigned default NULL,
		recipient_emails text,
		PRIMARY KEY  (pageid)
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup bmail table. The rest of the script can't run until this problem is fixed.<p>";
		report_failure($_SERVER['SCRIPT_FILENAME']);
		exit;
	}

	# check to see whether the bmail table is already compatible with parameters - if so, don't perform this upgrade step
	$parameter_column = $webdb->single_element("SELECT parameters from xtra_page_template_bmail limit 1");
	
	# upgrade unnecessary
	if (isset($parameter_column)) {
		report_ignore($_SERVER['SCRIPT_FILENAME']);
		exit();
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_bmail");
	
	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_bmail_backup (pageid, title, functions, copy, allow_users, allow_external_users,allow_unsubscribe, bmail_form, primary_listid, recipient_emails) SELECT f.pageid, f.title, f.functions, f.copy, f.allow_users, f.allow_external_users, f.allow_unsubscribe, f.bmail_form, f.primary_listid, f.recipient_emails FROM xtra_page_template_bmail f";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the bmail table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to bmail table.";

	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN title";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN functions";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN copy";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN allow_users";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN allow_external_users";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN allow_unsubscribe";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN bmail_form";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN primary_listid";
	$sql[] = "ALTER TABLE xtra_page_template_bmail DROP COLUMN recipient_emails";
	$sql[] = "ALTER TABLE xtra_page_template_bmail ADD  COLUMN parameters LONGTEXT";
	foreach($sql as $run) $webdb->select($run);

	?> 
	<p>The database changes for the bmail upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_bmail_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the bmail table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$forms = $webdb->associative_array("SELECT * from xtra_page_template_bmail_backup");
	$clean_convert = true;
	include_once("$SQUIZLIB_PATH/form/form.inc");
	include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
	foreach($forms as $pageid => $data) {
		$notify = array();
		$functionality = array();
		$emails = split("[ \n\r\t\,\;]+", $data['recipient_emails']);
		if (is_array($emails) && count($emails) > 1) {
			foreach ($emails as $email) {
				$usr = $users->find_user($email);
				if($usr['userid']) {
					$notify[] = $usr['userid'];
				} elseif ($email != '') {
					?>
					<div style="background-color: orange">
					On page <?= $pageid; ?>, the email address <?= htmlspecialchars($email); ?> <b>was</b> on the email notification list, however they no longer are since I could not find a user in your MySource system who has that email. <br />
					</div>
					<?
				} 
			}
		} else {
			?>
			<div style="background-color: yellow">
			On page <?= $pageid; ?>, there were no users in the email notification list, you might want to think about adding some. <br />
			</div>
			<?
		}
		if ($data['allow_users'])
			$functionality[] = 'users_subscribe';
		if ($data['allow_external_users'])
			$functionality[] = 'external_subscribe';
		if ($data['allow_unsubscribe'])
			$functionality[] = 'unsubscribe';
		
		foreach ($data['allowed_listids'] as $listid) {
			$parameters['list_groups'][0][] = $listid;
		}

		$frm = new Form($data['subscription_form']);
		$qids = $frm->get_requested_information_allocations('listids');
		foreach($qids as $qid) {
			$form->information_request_assignments['list_groups_1'] = $qid;
		}

		$parameters = array();
		$parameters['title']							= $data['title'];
		$parameters['email_notification_recipients']	= $notify;
		$parameters['functionality']					= $functionality;
		$parameters['primary_listid']					= $data['primary_listid'];
		$parameters['subscription_form']				= $data['bmail_form'];
		$copies = array (
			'welcome'				=> 'Welcome',
			'thankyou'				=> 'Thankyou',
			'sign_up_success'		=> 'SignUpSuccess',
			'sign_up_failure'		=> 'SignUpFailure',
			'unsubscribe_success'	=> 'UnsubscribeSuccess',
			'unsubscribe_failure'	=> 'UnsubscribeFailure'
		);
		$copy = unserialize($data['copy']);
		foreach ($copies as $new_copy => $old_copy) {
			if ($data['copy'][$copyname] != '') {
				$parameters['copy'][$new_copy] = $copy[$old_copy];
			}
		}

		if(!$webdb->update("update xtra_page_template_bmail set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
			report_failure($_SERVER['SCRIPT_FILENAME']);
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All bmail pages have been converted. Dropping backup table...<p>";
		$webdb->select("DROP TABLE xtra_page_template_bmail_backup");
		report_success($_SERVER['SCRIPT_FILENAME']);
	}
?>
<br>
...upgrade complete.
<?
}

$CACHE->wipe();

?>
