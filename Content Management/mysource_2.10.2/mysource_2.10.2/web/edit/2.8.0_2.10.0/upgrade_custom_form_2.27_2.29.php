<?
include_once('./upgrade_functions.php');
require_once('../../init.php');
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc");
include_once("$INCLUDE_PATH/webobject.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");

/**********************************************
This upgrade script can be run to upgrade any custom form version in the specifed range 2.27-2.29 to version 2.29

UPGRADE BODYCOPIES
Custom Form version -- 2.28 send to friend email bodycopy added
Custom Form version-- 2.29 email bodycopies where added

Custom Form Version 2.27
cvs up -r 2.38 Xtra.info
cvs up -r 2.71 form.inc
cvs up -r 2.21 form.pset
**********************************************/

$web_system = &get_web_system();
$db = &$web_system->get_db();

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade Custom Form", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade Custom Form", "You must be <b>root</b> to upgrade the system.",$SESSION->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/page/templates/form/form.inc")) {
	report_ignore($_SERVER['SCRIPT_FILENAME']);
	exit(); 
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

$pages = $db->single_column('SELECT pageid FROM page WHERE template=\'form\'');

if (count($pages) == 0) {
	echo 'There are no Custom Form Pages to upgrade';
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

	# old text email copies need to be upgraded to bodycopies
	if (substr(stripslashes($param['copy']['recipient_email_body']),0,14) != 'O:8:"bodycopy"') {
		$email_txt = rawhtmlreplace($param['copy']['recipient_email_body']);
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_txt);
		$param['copy']['recipient_email_body'] = serialize($rawhtmlcopy);
	}
	if (substr(stripslashes($param['copy']['receipt_email_body']),0,14) != 'O:8:"bodycopy"') {
		$email_txt = rawhtmlreplace($param['copy']['receipt_email_body']);
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_txt);
		$param['copy']['receipt_email_body'] = serialize($rawhtmlcopy);
	}
	if (substr(stripslashes($param['copy']['send_to_friends_body']),0,14) != 'O:8:"bodycopy"') {
		$email_txt = rawhtmlreplace($param['copy']['send_to_friends_body']);
		$rawhtmlcopy->tables[0]->rows[0]->cells[0]->type->set_html($email_txt);
		$param['copy']['send_to_friends_body'] = serialize($rawhtmlcopy);
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

?>
