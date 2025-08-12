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
## File: xtras/wizards/bmail_send.inc
## $Source: /home/cvsroot/xtras/wizards/bmail_send/bmail_send.inc,v $
## $Revision: 1.1 $
## $Author: bvial $
## $Date: 2004/02/25 23:05:17 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Bmail Send Wizard
* A wizard to send a bulkmail through the server
*
* @access public
* @package Wizards
*/
class Bmail_Send extends Wizard {

	/**
	* compatiable with
	* @var array
	*/
	var $compatible_with = array('users');
	
	/**
	* parameter settings
	* @var array
	*/
	var $parameters = array();


	/**
	* Constructor
	*
	* @param object $caller the class which called us
	* @access public
	*/
	function Bmail_Send (&$asset) {	
		Wizard::Wizard($asset);
	}


	/**
	* Get the href for this wizard
	*
	* @return string
	* @access public
	*/
	function get_backend_href() {
		$bmail_system = $this->caller->get_bmail_system();
		$bulkmail = $bmail_system->get_bulkmail($this->caller->id);
		return $bulkmail->get_backend_href().'&wizard_type=bmail_links';
	}

	/**
	* Get a list of bulkmailids which we can send, the preview id must have been entered
	*
	* @return array
	* @access public
	*/
	function get_draft_bulkmailids() {
		$r = array();
		$session = &get_mysource_session();
		$bmail_system = &$this->caller->get_extension('bmail');
		$draft_bulkmailids = $bmail_system->get_bulkmailids($session->user->id,'D');
		foreach($draft_bulkmailids as $bulkmailid) {
			$bulkmail = &$bmail_system->get_bulkmail($bulkmailid);
			if($bulkmail->is_previewid_valid()) {
				$r[$bulkmailid] = $bulkmail->parameters['name'];
			}
		}
		return $r;
	}

	/**
	* Prints the interface - assumes a backend has already been setup and the header printed
	* 
	* @param object backend
	* @access public
	*/
	function process_wizard_web(&$backend) {
		echo 'Please send the bulkmail from the bulkmail interface.';
		return;
	}

	/**
	* Process the wizard on the server
	*
	* @param $jobid the id of the server job which called us
	* @return boolean
	* @access public
	*/
	function process_wizard_server($jobid) {
		
		if(!$this->parameters['send_bulkmailid']) {
			# No bulkmailid? Delete this job (should we call abort())
			$wizard_server = &$this->get_wizard_server_manager();
			$wizard_server->remove_job($jobid);
			return;
		}

		$bmail_sys = &$this->caller->get_extension('bmail');
		$bulkmail = &$bmail_sys->get_bulkmail($this->parameters['send_bulkmailid']);
		
		$pointer = 0;
		while($pointer = $bulkmail->send($pointer,(($pointer)?false:true),true));
		
		$bulkmail->set_folder('S');

		return 'Bulkmail '.$bulkmail->parameters['name'].' (ID: '.$bulkmail->id.') has been sent to '.$bulkmail->get_number_recipients().' recipients';
	}

	/**
	* Do we have any bulkmails we can send?
	*
	* @return boolean
	* @access public
	*/
	function is_draft_bulkmailids() {
		$r = array();
		$session = &get_mysource_session();
		$bmail_system = &$this->caller->get_extension('bmail');
		$draft_bulkmailids = $bmail_system->get_bulkmailids($session->user->id,'D');
		foreach($draft_bulkmailids as $bulkmailid) {
			$bulkmail = &$bmail_system->get_bulkmail($bulkmailid);
			if($bulkmail->is_previewid_valid()) {
				return true;
			}
		}
		return false;
	}

	/**
	* Inverse of above function
	*
	* @return boolean
	* @access public
	*/
	function is_not_draft_bulkmailids() {
		return !$this->is_draft_bulkmailids();
	}

}