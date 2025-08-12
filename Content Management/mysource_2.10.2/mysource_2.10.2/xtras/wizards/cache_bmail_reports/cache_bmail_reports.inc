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
## $Source: /home/cvsroot/xtras/wizards/cache_bmail_reports/cache_bmail_reports.inc,v $
## $Revision: 1.6 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Bmail Links Wizard
* A wizard to produce list of all links clicked through
* by readers of a b-Mail
*
* @access public
* @package Wizards
*/
class Cache_Bmail_Reports extends Wizard {

	var $compatible_with = array('users_extension_bmail_bulkmail');
	var $current_wizard = '';
	var $parameters = array();


	 ##############################
	# Constructor
	function Cache_Bmail_Reports (&$asset) {	
		Wizard::Wizard($asset);
	}


	function get_num_steps() {
		$session = &get_mysource_session();
		if (!$session->has_var('wizards_available')) {
			global $XTRAS_PATH;
			$wizards_available = array();
			$code = 'users_extension_bmail_bulkmail';
			foreach ($this->_types->register as $wizard_type) {
				if ($wizard_type->codename == get_class($this)) continue;
				# get the wizard - include the file and create the object
				include_once($XTRAS_PATH.'/wizards/'.$wizard_type->codename.'/'.$wizard_type->codename.'.inc');
				$wizard = new $wizard_type->codename($code);
				
				# check that this wizard is compatible with us
				# and that is has a generate_summary function
				if ($wizard->is_compatible($code)) {
					if (method_exists($wizard,'generate_summary')) $wizards_available[] = $wizard_type->codename;
				}
			}
			$session->set_var('wizards_available', $wizards_available);
		} else {
			$wizards_available = $session->get_var('wizards_available');
		}
		return count($wizards_available) + 1;
	}


	function process_special_action($action) {
		$backend = &$this->get_backend();
		$session = &get_mysource_session();
		if (!$session->has_var('wizards_available')) return;

		$active_step = $_REQUEST['active_step'];
		if ($active_step <= 0) return;
		$wizards_available = $session->get_var('wizards_available');
		$wizard_type = $wizards_available[($active_step -1)];
		if (!$wizard_type) return;

		global $XTRAS_PATH;
		include_once($XTRAS_PATH.'/wizards/'.$wizard_type.'/'.$wizard_type.'.inc');
		$caller = $this->caller;
		$wizard = new $wizard_type($caller);
		
		# return_href is a url that the popup should redirect us to when its finsihed
		$return_href = $this->get_backend_href().'&active_step='.($active_step+1);
		if (method_exists($wizard,'generate_summary')) {
			$this->current_wizard = $wizard->_types->register[get_class($wizard)];
			$wizard->generate_summary($return_href);
		}
	}


	function get_backend_href() {
		$bmail_system = $this->caller->get_bmail_system();
		$bulkmail = $bmail_system->get_bulkmail($this->caller->id);
		return $bulkmail->get_backend_href().'&wizard_type=cache_bmail_reports';
	}


	function additional_screen_content(&$backend, $active_step) {
		if (trim($this->current_wizard->name) == '') return;
		$backend->open_section('Caching report...');
		$backend->open_field('Name','one_liner');
		echo $this->current_wizard->name;
		echo ' (version '.$this->current_wizard->version.')';
		$backend->open_field('Description','one_liner');
		echo $this->current_wizard->description;
	}
	
	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		
		# delete the live READ stats
		$users = &get_users_system();
		$db = &$users->get_db();
		$db->update('DELETE FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid="'.$this->caller->id.'"');
	
		?>
		<table border="0" bgcolor="#F0F0F0">
			<tr>
				<td>b-Mail report caching is complete. The statisitcs for this b-Mail have been deleted to save space in the database, but all reports are still able to be viewed.</td>
			</tr>
		</table>
		<?
	}

}