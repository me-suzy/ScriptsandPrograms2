<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/main_page_2.155_2.156.php,v $
## $Revision: 1.2 $
## $Author: brobertson $
## $Date: 2004/03/16 10:43:24 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade Page for Sub Page Auto Order", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade Page for Sub Page Auto Order", "You must be <b>root</b> to upgrade the system.",$session->user->login);
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
Upgrading Page for Sub Page Auto Order.<br><br>
<?
error_reporting(5);
global $CACHE;

$web = &get_web_system();
$webdb = &$web->get_db();

$sql = "ALTER TABLE page ADD COLUMN subpage_auto_order VARCHAR(2) NULL";
$webdb->select($sql);

# Upgrade page ordernos to work with new sitemap
echo '<br><b>Upgrading Pages Order Numbers to be in sequence</b><br>';
$sql = '';
$pageids = array();
$count=0;
$sql = 'SELECT pageid FROM page;';
$pageids = $webdb->single_column($sql);
$pageids[] = 0; # add parent pageid
foreach ($pageids as $pageid) {
	$count++;
	$sql = "SELECT pageid FROM page WHERE parentid=$pageid ORDER BY orderno";
	$childids = $webdb->single_column($sql);
	for ($i=1; $i<=count($childids); $i++) {
		$sql = "UPDATE page SET orderno=$i where pageid=".$childids[$i-1];
		$webdb->update($sql);
	}
}
echo "Upgraded $count pages<br><br>";

report_success($_SERVER['SCRIPT_FILENAME']);

$CACHE->wipe();

echo "Done<p>";

?>
