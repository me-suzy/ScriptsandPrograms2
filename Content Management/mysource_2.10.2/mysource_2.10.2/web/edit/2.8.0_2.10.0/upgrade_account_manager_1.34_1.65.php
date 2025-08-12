<?
include_once('./upgrade_functions.php');
require_once('../../init.php');
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc");
include_once("$INCLUDE_PATH/webobject.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");

/**********************************************
This upgrade script can be run to upgrade any account manager version in the specifed range 1.34-1.62 to version 1.62

UPGRADE BODYCOPIES
Account Manager version -- 1.34 or lower does not have email bodycopies
Account Manager version-- 1.35 or higher has email bodycopies

UPGRADE KEYWORD
version version = 1.62
$backend_url/user.php?userid=%userid% ==> %backend_manager_user_url%

Account Manager Version 1.34
cvs up -r 2.35 Xtra.info
cvs up -r 2.59 account_manager.inc
cvs up -r 2.13 account_manager.pset
**********************************************/

$web_system = &get_web_system();
$db = &$web_system->get_db();

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

$pages = $db->single_column('SELECT pageid FROM page WHERE template=\'account_manager\'');

if (count($pages) == 0) {
	echo 'There are no Account Manager Pages to upgrade';
}

# email bodycopy - setup email bodycopies
$rawhtmlcopy = new BodyCopy();
$rawhtmlcopy->insert_table(1,1,1,'bodycopy_table_cell_type_rawhtml',false,array('width'=>'100%'));

echo '<table border="1"><tr><td width="100">Page Name</td><td width="50">Page ID</td><td>&nbsp;</td></tr>';

foreach ($pages as $pageid) {
	$page = &$web_system->get_page($pageid);
	$page_template = &$page->get_template();
	echo "<tr><td>$page->name</td><td>$page->id</td><td><b>Upgraded</b></td></tr>";

	$param = &$page_template->parameters;

	# old text email copies need to be upgraded
	if (substr(stripslashes($param['copy']['join_email']),0,14) != 'O:8:"bodycopy"' && substr(stripslashes($param['copy']['signup_email']),0,14) != 'O:8:"bodycopy"' && substr(stripslashes($param['copy']['password_email']),0,14) != 'O:8:"bodycopy"') {
		$join_email_txt = rawhtmlreplace($param['copy']['join_email']);
		$signup_email_txt = rawhtmlreplace($param['copy']['signup_email']);
		$password_email_txt = rawhtmlreplace($param['copy']['password_email']);
		# upgrade text params to bodycopies
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($join_email_txt);

		$param['copy']['join_email'] = serialize($rawhtmlcopy);
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($signup_email_txt);
		$param['copy']['signup_email'] = serialize($rawhtmlcopy);
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($password_email_txt);
		$param['copy']['password_email'] = serialize($rawhtmlcopy);
	}

	# set email subects
	if (!isset($param['join_subject'])) {
		$param['join_subject'] = 'Account Manager Join';
	}
	if (!isset($param['join_manager_email_subject'])) {
		$param['join_manager_email_subject'] = 'Account Manager Join';
	}
	if (!isset($param['signup_email_subject'])) {
		$param['signup_email_subject'] = 'Account Manager SignUp';
	}
	if (!isset($param['signup_manager_email_subject'])) {
		$param['signup_manager_email_subject'] = 'Account Manager SignUp';
	}
	if (!isset($param['password_email_subject'])) {
		$param['password_email_subject'] = 'New Password';
	}
	if (!isset($param['managers_edit_email_subject'])) {
		$param['managers_edit_email_subject'] = 'Account Manager Edit';
	}
	if (!isset($param['users_edit_email_subject'])) {
		$param['users_edit_email_subject'] = 'Account Manager Edit';
	}

	# set up the rest of the emails
	if (substr(stripslashes($param['copy']['join_manager_email']),0,14) != 'O:8:"bodycopy"') {
		$email_text = "A existing user - %name% - has asked to join %organisation% on the form at:\n\r<br />\n\r<br /> Their request has been accepted and they are now affiliated with the %organisation% organisation.\n\r<br />\n\r<br /> You can view and edit the account details at:\n\r<br />\n\r<br />%backend_manager_user_url%\n\r<br />\n\r<br />";
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_text);
		$param['copy']['join_manager_email'] = serialize($rawhtmlcopy);
	}
	if (substr(stripslashes($param['copy']['signup_manager_email']),0,14) != 'O:8:"bodycopy"') {
		$email_text = "A user has signed up on the form at:\n\r<br />\n\r<br /> %url% \n\r<br />\n\r<br /> An account has been created for them and they have been affiliated\n\r<br /> with the %organisation% organisation.\n\r<br />\n\r<br /> %Approval Status% \n\r<br />\n\r<br /> You can view and edit the account details at:\n\r<br />\n\r<br />%backend_manager_user_url%\n\r<br />\n\r<br />";
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_text);
		$param['copy']['signup_manager_email'] = serialize($rawhtmlcopy);
	}
	if (substr(stripslashes($param['copy']['users_edit_email']),0,14) != 'O:8:"bodycopy"') {
		$email_text = "You have edited your details on the form at: %url%\n\r<br />\n\r<br />";
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_text);
		$param['copy']['users_edit_email'] = serialize($rawhtmlcopy);
	}
	if (substr(stripslashes($param['copy']['edit_managers_email']),0,14) != 'O:8:"bodycopy"') {
		$email_text = "A user has edited their details on the form at: %url%\n\r<br />\n\r<br /> You can view and edit the account details at:\n\r<br />\n\r<br />%backend_manager_user_url%\n\r<br />\n\r<br />";
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_text);
		$param['copy']['edit_managers_email'] = serialize($rawhtmlcopy);
	}

	# replace "%backend_url%/user.php?userid=%userid%" on managers emails
	upgrade_email_keywords($param['copy']['edit_managers_email']);
	upgrade_email_keywords($param['copy']['join_manager_email']);
	upgrade_email_keywords($param['copy']['signup_manager_email']);

	# set all emails to be sent as text
	if (!isset($param['join_html_email'])) {
		$param['join_html_email'] = 0;
	}
	if (!isset($param['join_manager_html_email'])) {
		$param['join_manager_html_email'] = 0;
	}
	if (!isset($param['signup_html_email'])) {
		$param['signup_html_email'] = 0;
	}
	if (!isset($param['signup_manager_html_email'])) {
		$param['signup_manager_html_email'] = 0;
	}
	if (!isset($param['password_html_email'])) {
		$param['password_html_email'] = 0;
	}
	if (!isset($param['users_edit_html_email'])) {
		$param['users_edit_html_email'] = 0;
	}
	if (!isset($param['edit_managers_html_email'])) {
		$param['edit_managers_html_email'] = 0;
	}

	#save and clear cache
	$page_template->save_parameters();
	$page->clear_cache();
}

echo '</table>';
report_success($_SERVER['SCRIPT_FILENAME']);

function rawhtmlreplace($string) {
	return str_replace(array("\n\r","\n"), array("</br />\n\r", "</br />\n\r"), $string);
}

function upgrade_email_keywords(&$param) {
	$bodycopy = new BodyCopy($param);
	$html = $bodycopy->tables[0]->rows[0]->cells[0]->type->html;
	$html = str_replace('%backend_url%/user.php?userid=%userid%','%backend_manager_user_url%', $html);
	$param = serialize($bodycopy);
	return ;
}

//report_failure($_SERVER['SCRIPT_FILENAME']);
?>
