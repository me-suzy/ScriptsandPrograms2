<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/contrib/fix_page_data_dirs.php,v $
## $Revision: 1.2 $
## $Author: csmith $
## $Date: 2003/11/18 04:05:16 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

 ####################################################################
# tell anyone who isn't a superuser to begone
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Fix Data Dirs", "You must be logged in.");
} else if (!user_root()) {
	$SESSION->login_screen("Fix Data Dirs", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
}

# OK, we can't have any max execution time
if (get_cfg_var("safe_mode") && get_cfg_var("max_execution_time") > 0) {
?> 
	<b>We need to have no time limit on this script because it can potentially take a very long time, depending on the number of pages you have</b><br>
	Either take off safe_mode in your php.ini or set the max_execution_time to zero
<?
	exit;
}#end if

if (!get_cfg_var("safe_mode")) {
	# remove time limit
	set_time_limit(0);
}

?>
Fix Data Dirs ...<br><br>
<?

if (!$system_backed_up) {
?>
<div align="center">
<form onSubmit="javascript: return confirm('Here we go ... Good luck.');">
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

 ###########################################################################
# OK Firstly add the site_design dir to all page data dirs
$sql = "SELECT pageid FROM page";
$pages = $webdb->single_column($sql);
foreach($pages as $pageid) {
	$page = &$web->get_page($pageid);
	if(!$page->id) continue;
	# just to fix up old systems that where using the $page->effective_public()
	$right_dir = get_data_path($page->effective_unrestricted(), "page/".$page->id);
	$wrong_dir = get_data_path(!$page->effective_unrestricted(), "page/".$page->id);
	if (is_dir($right_dir) && is_dir($wrong_dir)) {
		echo("delete_directory($wrong_dir);<br>");
		delete_directory($wrong_dir);
		clearstatcache();
	}
	restrict_data_path($page->effective_unrestricted(), "page/".$page->id);
	$web->forget_page($pageid);
}#end foreach

clear_mysource_cache();

?>
<br>
<a href="./">...upgrade complete.</a>