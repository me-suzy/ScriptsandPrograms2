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
## File: include/wizard_server_job.inc
## Desc: manage squiz server jobs
## $Source: /home/cvsroot/mysource/include/wizard_server_job.inc,v $
## $Revision: 2.1 $
## $Author: bvial $
## $Date: 2004/02/25 23:01:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
#---------------------------------------------------------------------#


/**
* 
* A wizard job to be run on the squiz server
*
*/
class Wizard_Server_Job extends SystemObject {

	/**
	* id to identify this job
	* @var integer
	*/
	var $id = 0;

	/**
	* the type of job
	* @var string
	*/
	var $job_type = '';

	/**
	* parameters of the running job
	* @var array
	*/
	var $parameters = Array();

	/**
	* type of object which called the wizard
	* @var string
	*/
	var $caller_type = '';

	/**
	* id of object which called the wizard
	* @var integer
	*/
	var $callerid = 0;

	/**
	* the owner of the job
	* @var integer
	*/
	var $userid = 0;

	/*
	* information about the job
	* @var string
	*/
	var $message = '';

	/**
	* time this job was last updated
	* @var string
	*/
	var $last_updated = '';

	/**
	* status of the job, N => new, R => running, F => finished, A => aborted
	* @var array
	*/
	var $status = 'N';

	/**
	* md5 hash of the taskid running on the server
	* @var array
	*/
	var $taskid = 0;
	
	/**
	* temporary values that we do not want cached
	* @var array
	*/
	var $temp = Array();

	/**
	* Constructor
	*
	* @return object wizard_server_job
	* @access public
	*/
	function Wizard_Server_Job($jobid=0) {
		SystemObject::SystemObject();
		if($jobid) {
			$this->load($jobid);
		}
	}

	/**
	* Member properties we do not want serialized for cacheing
	*
	* @return array
	* @access public
	*/
	function __sleep() {
		$result = SystemObject::__sleep();
		array_remove_element('temp',$result);
		return $result;
	}

	/**
	* Load the object from cache or database into this object
	*
	* @param integer $jobid
	*
	* @return boolean
	* @access public
	*/
	function load($jobid=0) {
		
		# We need a jobid
		if(!$jobid) return false;

		# Load from cache
		if($this->load_from_cache()) return $this->id;

		# Load from database
		$db = &$this->get_db();

		list($this->id,$this->job_type,$tmp,$this->caller_type,$this->callerid,$this->userid,$this->last_updated,$this->status,$this->message,$this->taskid) = $db->single_row("SELECT jobid,job_type,parameters,caller_type,callerid,userid,last_updated,status,message,taskid FROM wizard_server_job WHERE jobid='".$jobid."'");
		$this->parameters = unserialize(stripslashes($tmp));

		if(!$this->id) return false;

		$this->save_to_cache($this->id);

		return $this->id;
	}

	/**
	* Returns the web database object
	*
	* @return object Db
	* @access  public
	*/
	function &get_db() {
		$web = &get_web_system();
		$db = &$web->get_db();
		return $db;
	}

	/**
	* Returns the Wizard Server Manager object
	*
	* @return object Wizard_Server_Manager
	* @access public
	*/
	function &get_wizard_server_manager() {
		if(empty($this->temp['wizard_server_manager'])) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/wizard_server_manager.inc");
			$this->temp['wizard_server_manager'] = new Wizard_Server_Manager();
		}
		return $this->temp['wizard_server_manager'];
	}

	/**
	* Clear this job's cache
	*
	* @access public
	*/
	function updated() {
		$db = &$this->get_db();
		$db->update("UPDATE wizard_server_job SET last_updated=now() WHERE jobid='$this->id'");
		$this->last_updated = date("Y-m-d h:i:s");
		$this->clear_cache();
		$wizard_server = &$this->get_wizard_server_manager();
		$wizard_server->clear_cache();
		unset($this->temp['wizard_server_manager']);
	}

	/**
	* Create a new job in the database
	*
	* @param string $job_type the class name of the wizard to be run
	* @param array $parameters the parameters of the calling class
	* @param string $caller_type the class name of the object which called the wizard we will run (eg. web,site,page)
	* @param integer callerid the id of the object which called the wizard we will run
	* @param integer $userid the person running the job
	*
	* @returns boolean
	* @access public
	*/
	function create($job_type='', $parameters=array(), $caller_type='', $callerid=0, $userid=0) {
		
		if(!$job_type || !$caller_type || !$userid) {
			$this->_set_error('Unable to create wizard server job. Missing parameter.',__FILE__,__LINE__);
			return false;
		}
		
		# Create an entry for this job in the database
		$db = &$this->get_db();
		$jobid = $db->insert("INSERT INTO wizard_server_job (job_type,parameters,caller_type,callerid,userid,last_updated) VALUES ('".$job_type."','".addslashes(serialize($parameters))."','".$caller_type."','".$callerid."','".$userid."',now())");

		return $jobid;
	}

	/**
	* Delete this job from database
	*
	* @return boolean
	* @access public
	*/
	function delete() {
		$db = &$this->get_db();
		if($db->delete("DELETE FROM wizard_server_job WHERE jobid='".$this->id."'")) {
			$this->clear_cache();
			return true;
		}
		return false;
	}

	/**
	* Set the status of this job
	*
	* @param integer $status the status to set
	*
	* @access public
	*/
	function set_status($status) {
		$wizard_server = &$this->get_wizard_server_manager();
		if(!in_array($status,array_keys($wizard_server->statii))) return;
		$this->status = $status;
		$db = &$this->get_db();
		$db->update('UPDATE wizard_server_job SET status=\''.$status.'\',last_updated=now() WHERE jobid='.$this->id);
		$this->updated();
	}

	/**
	* Update the message on the server
	*
	* @param string $msg the message to set
	* 
	* @access public
	*/
	function set_message($msg) {
		$this->message = $msg;
		$db = &$this->get_db();
		$db->update('UPDATE wizard_server_job SET message=\''.addslashes($msg).'\',last_updated=now() WHERE jobid='.$this->id);
		$this->updated();
	}

	/**
	* Set the server taskid
	*
	* @param integer $taskid
	*
	* @access public
	*/
	function set_taskid($taskid) {
		if(!$taskid) return;
		$this->taskid = $taskid;
		$db = &$this->get_db();
		$db->update('UPDATE wizard_server_job SET taskid=\''.$taskid.'\' WHERE jobid='.$this->id);
		$this->updated();
	}

	/**
	* Connect to the squiz server and tell it to start running this job
	*
	* @return boolean
	* @access public
	*/
	function run() {

		$session = &get_mysource_session();

		$data = Array(
					'command'	=> 'start',
					'userid'	=> $session->user->id,
					'jobid'		=> $this->id
				);

		$ret_val = false;
		
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/squiz_server_client_mysource2.inc");

		# Get a copy of the squiz server client and connect
		$client = new Squiz_Server_Client_MySource2();
		if($client->connect()) {
			if($client->write('wizard',$data)) {
				$ack = $client->read();
				if(!is_null($ack)) {
					switch($ack['result']) {
						case 'ack' :
							$ret_val = true;
							echo 'This job (ID: '.$this->id.') has been sent to the Squiz Server to run.';
							echo js("window.opener.location = window.opener.location.protocol + '//' + window.opener.location.host + window.opener.location.pathname + window.opener.location.search;");
							break;

						case 'error' :
							$this->_set_error('Squiz Server Error: '.$ack['msg']);
							break;

						default :
							$this->_set_error('Unexpected return from the Squiz Server: '.print_r($ack,true));
					}
				}
			}
			$client->disconnect();
		}

		return $ret_val;
	}

	/**
	* Abort the running job
	*
	* @return boolean
	* @access public
	*/
	function abort() {
		$session = &get_mysource_session();

		$data = Array(
					'command'	=> 'abort',
					'userid'	=> $session->user->id,
					'jobid'		=> $this->id,
					'taskid'	=> $this->taskid
				);

		$ret_val = false;
		
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/squiz_server_client_mysource2.inc");

		# Get a copy of the squiz server client and connect
		$client = new Squiz_Server_Client_MySource2();
		if($client->connect()) {
			if($client->write('wizard',$data)) {
				$ack = $client->read();
				if(!is_null($ack)) {
					switch($ack['result']) {
						case 'ack' :
							$ret_val = true;
							$this->set_status('A');
							break;

						case 'error' :
							$this->_set_error('Squiz Server Error: '.$ack['msg']);
							break;

						default :
							$this->_set_error('Unexpected return from the Squiz Server: '.print_r($ack,true));
					}
				}
			}
			$client->disconnect();
		}

		return $ret_val;
	}

	/**
	* Process this job. This is called by the server, hence no backend object available
	*
	* @access public
	*/
	function process() {
		if(!$this->job_type) return false;

		# Load the object which orginally called our wizard
		$caller = new $this->caller_type($this->callerid);

		global $XTRAS_PATH;
		include_once("$XTRAS_PATH/wizards/$this->job_type/$this->job_type.inc");

		$wizard = new $this->job_type($caller);
		$wizard->parameters = $this->parameters;

		# Set the status of this job to running
		$this->set_status('R');

		$msg = $wizard->process_wizard_server($this->id);

		# Update the message
		$this->set_message($msg);

		# Set the status to finished
		$this->set_status('F');
	}

}