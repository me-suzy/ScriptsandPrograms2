<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/upgrade_2.0.2_2.2.0.php,v $
## $Revision: 2.5 $
## $Author: nduggal $
## $Date: 2004/03/16 03:49:45 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

 ####################################################################
# tell anyone who isn't root to politely go away
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade to 2.2.0", "You must be logged in.");
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade to 2.2.0", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
}

?>
Upgrading MySource 2.0.2 BETA to 2.2.0...<br><br>
<?

if (!$_GET['system_backed_up']) {
?>
<div align="center">
<form onSubmit="javascript: return confirm('Since this upgrade script is upgrading from a BETA release, we cannot guarantee it wil function perfectly. It has been fairly well tested, however.');">
<b>Are you sure that you have backed up your system ?</b><br>
<input type="hidden" name="time" value="<?=microtime()?>">
<input type="submit" name="system_backed_up" value="&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;">
<input type="button" value="&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;" onClick="javascript: alert('Well get on with it :)');">
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

 ##############################################################
# clear the cache
function clear_mysource_cache() {
	global $CACHE_PATH;
	$cache_ptr = @opendir($CACHE_PATH) or die("Unable to open cache dir - '$CACHE_PATH'");
	while (false !== ($cache_file = readdir($cache_ptr))) { 
		if ($cache_file == "." || $cache_file == "..") continue;
		# only delete files with an extension
		if (strstr($cache_file, ".")) { 
			unlink("$CACHE_PATH/$cache_file") or die("Unable to remove '$CACHE_PATH/$cache_file'");
		} #end if

	}#end while
	closedir($cache_ptr); 
}

clear_mysource_cache();


 #########################################################
# Web DB Changes
$sql = array();
$sql[] = "ALTER TABLE page ADD COLUMN designid MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL;";
$sql[] = "ALTER TABLE page CHANGE COLUMN short_name short_name VARCHAR(40) NOT NULL;";
$sql[] = "ALTER TABLE page_editor ADD COLUMN readonly TINYINT UNSIGNED DEFAULT 0 NOT NULL;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD column paginate TINYINT DEFAULT 0;";
$sql[] = "ALTER TABLE xtra_page_template_form ADD back_button_text VARCHAR(128);";
$sql[] = "ALTER TABLE xtra_page_template_pullcontent ADD COLUMN title VARCHAR(255);";
$sql[] = "ALTER TABLE xtra_page_template_pullcontent ADD COLUMN subpage_emulation CHAR(1) DEFAULT '0';";

foreach($sql as $run) $webdb->select($run);

# User database
$sql = array();
foreach($sql as $run) $usersdb->select($run);

clear_mysource_cache();

 ###########################################################################
# OK Firstly add the site_design dir to all page data dirs
$sql = "SELECT pageid FROM page";
$pages = $webdb->single_column($sql);
foreach($pages as $pageid) {
	$page = &$web->get_page($pageid);
	if(!$page->id) continue;
	$new_dir = $page->data_path.'/site_design';
	# just to fix up old systems that where using the $page->effective_public()
	restrict_data_path($page->effective_unrestricted(), "page/".$page->id);
	if (!file_exists($new_dir)) {
		create_directory($new_dir);
	}#end if
	$web->forget_page($pageid);
}#end foreach

clear_mysource_cache();

 #################################################################################
# Now we need to create all the generated directories for all the site designs
$sql = "SELECT siteid FROM site";
$sites = $webdb->single_column($sql);
foreach($sites as $siteid) {
	$site = &$web->get_site($siteid);
	if(!$site->id) continue;
	$new_dir = $site->data_path.'/site_design/generated';
	if (!file_exists($new_dir)) {
		create_directory($new_dir);
	}#end if
	$web->forget_site($siteid);
}#end foreach

clear_mysource_cache();

?>
<br>
<a href="upgrade_2.2.0_2.4.0.php">Click here to upgrade to 2.4.0</a>
