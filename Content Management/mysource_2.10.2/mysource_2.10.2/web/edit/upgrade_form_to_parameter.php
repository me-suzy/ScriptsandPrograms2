<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/upgrade_form_to_parameter.php,v $
## $Revision: 2.3.4.1 $
## $Author: brobertson $
## $Date: 2004/05/11 11:29:27 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Form", "You must be logged in.");
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Form", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
}

?>
Upgrading MySource Form.<br><br>
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
	$query = "CREATE TABLE xtra_page_template_form_backup (
	  pageid                MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
	  title                 VARCHAR(128),
	  bodycopy              LONGTEXT,
	  thankyou              LONGTEXT,
	  form                  LONGTEXT,
	  recipient_emails      TEXT,
	  selective_emails      TEXT,
	  receipt_email         TINYINT DEFAULT 0,
	  hide_results          TINYINT DEFAULT 0,
	  paginate              TINYINT(3) DEFAULT 0,
	  back_button_text      VARCHAR(128),
	  log_form_submission   TINYINT DEFAULT 0,
	  recipient_email_body  TEXT,
	  receipt_email_body    TEXT,
	  formelements_keyword  TEXT
	)";

	if(!$webdb->select($query)) {
		# d'oh! can't go on, so die.
		echo "Error - There was a problem creating a backup table for your form results. The rest of the script can't run until this problem is fixed.<p>";
		exit;
	}

	$number = $webdb->single_element("SELECT count(*) from xtra_page_template_form");

	if($number) { # yes, we have some data we have to back up.
		$query = "INSERT INTO xtra_page_template_form_backup (pageid, title, bodycopy, thankyou, form, recipient_emails,selective_emails, receipt_email, hide_results, paginate, back_button_text, log_form_submission, recipient_email_body, receipt_email_body, formelements_keyword) SELECT f.pageid, f.title, f.bodycopy, f.thankyou, f.form, f.recipient_emails, f.selective_emails, f.receipt_email, f.hide_results, f.paginate, f.back_button_text, f.log_form_submission, f.recipient_email_body, f.receipt_email_body, f.formelements_keyword from xtra_page_template_form f";
		if(!$webdb->insert($query)) {
			#d'oh! no point if we can't copy what's in there, huh?
			echo "Error - There was a problem copying the data from the form table to the backup table. The rest of the script can't run until this problem is fixed.<p>";
			exit;
		}
	}

	echo 	"<p>Backup tables created and data copied OK. Starting database changes to form table.";

	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN title";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN bodycopy";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN thankyou";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN form";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN recipient_emails";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN selective_emails";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN receipt_email";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN hide_results";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN paginate";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN back_button_text";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN log_form_submission";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN recipient_email_body";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN receipt_email_body";
	$sql[] = "ALTER TABLE xtra_page_template_form DROP COLUMN formelements_keyword";
	$sql[] = "ALTER TABLE xtra_page_template_form ADD  COLUMN parameters LONGTEXT";
	foreach($sql as $run) $webdb->select($run);

	?> 
	<p>The database changes for the form upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="upgrade_form_to_parameter.php" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the form table have been changed (there should be only two - pageid and parameters.)'); return false;">
	</form>
	<?		

} elseif ($_REQUEST['step'] == 2) {
	# clear the past sql statements. 
	$sql = array();

	# now get all the rows in there and convert them to parameter set.
	# THIS MIGHT NOT WORK if the first column in your db is not pageid. But we'll get to that....
	$forms = $webdb->associative_array("SELECT * from xtra_page_template_form_backup");
	$clean_convert = true;

	foreach($forms as $pageid => $data) {
		$parameters = array();
		$parameters['copy']['title'] = $data['title'];
		$parameters['copy']['bodycopy'] = $data['bodycopy'];
		$parameters['copy']['thankyou_bodycopy'] = $data['thankyou'];
		$parameters['form'] = $data['form'];
		$parameters['recipient_emails'] = $data['recipient_emails'];
		$parameters['selective_emails'] = unserialize($data['selective_emails']);
		$parameters['receipt_email'] = $data['receipt_email'];
		$parameters['hide_results'] = $data['hide_results'];
		$parameters['paginate'] = $data['paginate'];
		$parameters['copy']['back_button_text'] = $data['back_button_text'];
		$parameters['log_form_submission'] = $data['log_form_submission'];
		$parameters['copy']['recipient_email_body'] = $data['recipient_email_body'];
		$parameters['copy']['receipt_email_body'] = $data['receipt_email_body'];
		$parameters['formelements_keyword'] = unserialize($data['formelements_keyword']);
		if(!$webdb->update("update xtra_page_template_form set parameters='".addslashes(serialize($parameters))."' where pageid='$pageid'")) {
			echo "<b>WARNING: saving page $pageid to parameter set failed. </b><br>";
			$clean_convert = false;
		} else {
			echo "Converted page $pageid successfully.<br>";
		}
	}

	if($clean_convert) {
		echo "All form pages have been converted. Dropping backup table...<p>";
		$webdb->select("DROP TABLE xtra_page_template_form_backup");
	}
if (file_exists("$SYSTEM_ROOT/xtras/page/templates/bmail/bmail.inc")) {
	?>
	<br>
	<a href="./upgrade_bmail_to_parameter.php">Click here to upgrade the bmail page templates.</a>
	<?
} else {
	?>
	<br>
	<a href="./">...upgrade complete.</a>
	<?
}
}

$CACHE->wipe();

?>
