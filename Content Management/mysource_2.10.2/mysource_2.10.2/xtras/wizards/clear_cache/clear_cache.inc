<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/report.inc
## Desc: Parent class for system reporting
## $Source: /home/cvsroot/xtras/wizards/clear_cache/clear_cache.inc,v $
## $Revision: 1.3 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Clear Cache Wizard
* A wizard to clear the mysource cache from the web.
*
* @access public
* @package Wizards
*/
class Clear_Cache extends Wizard {

	var $compatible_with = array('web');
	var $parameters = array(
								'clear_sessions' => 0
							);

	/*
		What level of access is this restricted to?
	*/
	var $restricted_to = array('superuser_web');

	 ##############################
	# Constructor
	function Clear_Cache (&$asset) {
		Wizard::Wizard($asset);
		ini_set('track_errors', true);
		$this->add_button('Clear Cache','Clear_Cache',100,4);
	}

	function process_clear_cache_options() {
		if (!$_POST['clear_sessions']) return;
		global $CACHE_PATH, $SYSTEM_ROOT;

		$my_session_id = 'sess_' . session_id();

		$session_file_list = list_files($CACHE_PATH, 'sess_*');
		foreach($session_file_list as $dir => $list) {
			# we don't really care about subdirectories at this point, so skip them. All session files are in the main CACHE directory.
			if (is_array($list)) continue;

			# make sure it hasn't been cleared by garbage collection already.
			if (!is_file($session_file_list[$dir])) continue;

			$file = str_replace($CACHE_PATH.'/', '', $session_file_list[$dir]);
			if ($file == $my_session_id) continue; # we'll clear ours last.
			if (!unlink($session_file_list[$dir])) {
				?>Unable to remove file <?=$file?>: <?=$php_errormsg?><br /><?
			}
		}

		# finally, clear our own session file.
		# If this works, we can't do anything, we'll be logged out anyway. Only need to see if it failed.
		$session_file = $CACHE_PATH.'/'.$my_session_id;
		if (!unlink($session_file)) {
			?>Unable to remove file <?=$file?>: <?=$php_errormsg?><br /><?
		}
	}

	/**
	* process_wizard
	* This displays the results of the wizard.
	* @param	object &$backend The backend the wizard created previously.
	* @returns	void
	* @access	private
	*/

	function process_wizard_web(&$backend) {

		$web_system = &$this->get_web_system();
		$backend->enable_tooltips();
		$session = &get_mysource_session();

		$backend->open_section('Summary');

		$backend->open_section('Report');
		$backend->open_field('');

		global $CACHE;
		$CACHE->wipe();
		?>Cache cleared.<?
	}

	function print_clear_cache_options() {
		global $CACHE_PATH;
		$session_file_list = list_files($CACHE_PATH, 'sess_*');
		$session_count = 0;
		# Since the cache directory is divided up, we skip the "array" elements - which are the sub directories.
		# The session files are all stored in the base cache/ directory.
		foreach($session_file_list as $dir => $list) {
			if (is_array($list)) continue;
			$session_count++;
		}

		if ($session_count > 0) {
			?>
				Include clearing session files? <input type="checkbox" name="clear_sessions" value="1"><br />
				<b>This will clear <?=$session_count?> session(s) - which will log users out of their accounts - including you.<br />Use under extreme caution.</b>
			<?
		}
	}
}
?>