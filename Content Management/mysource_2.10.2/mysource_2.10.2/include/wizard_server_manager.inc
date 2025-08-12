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
## File: include/wizard_server_manager.inc
## Desc: manage squiz server jobs
## $Source: /home/cvsroot/mysource/include/wizard_server_manager.inc,v $
## $Revision: 2.1 $
## $Author: bvial $
## $Date: 2004/02/25 23:01:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
#---------------------------------------------------------------------#


/**
* 
* Manages access to the squiz server to perform more complex mysource tasks
*
*/
class Wizard_Server_Manager extends SystemObject {

	/*
	* id so that we can cache this object
	* @var string
	*/
	var $id = 'Wizard_Server_Manager';
	
	/**
	* temporary values that we do not want cached
	* @var array
	*/
	var $temp = Array();

	/**
	* array of jobids indexed by their status
	* @var array
	*/
	var $jobs = Array();

	/*
	* array of possible statii the job can have
	* @var array
	*/
	var $statii = Array('N'=>'New','R'=>'Running','A'=>'Aborted','F'=>'Finished');

	/**
	* Constructor
	*
	* @return object Wizard_Server_Manager
	* @access public
	*/
	function Wizard_Server_Manager () {
		SystemObject::SystemObject();
		$this->load();
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
	* Get the wizard object
	*
	* @return object Wizard
	* @access public
	*/
	function &get_wizard() {
		if(!isset($this->temp['wizard'])) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/wizard.inc");
			$this->temp['wizard'] = new Wizard();
		}
		return $this->temp['wizard'];
	}

	/**
	* Load this object from cache
	*
	* @returns boolean
	* @access public
	*/
	function load() {

		# Load from cache
		if($this->load_from_cache()) return true;

		# Load from database
		$db = &$this->get_db();
		$this->jobs = $db->associative_column("SELECT status,jobid FROM wizard_server_job");

		$this->save_to_cache();
		
		return true;
	}

	/**
	* Returns the web database object
	*
	* @return object Db
	* @access public
	*/
	function &get_db() {
		$web = &get_web_system();
		$db = &$web->get_db();
		return $db;
	}

	/**
	* Add a job to the system
	*
	* @param object $caller the class which called us
	*
	* @return bool
	* @access public
	*/
	function add_job(&$caller) {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/wizard_server_job.inc");

		# We need a caller class
		if(!$caller) return false;

		$session = &get_mysource_session();
		$job = new Wizard_Server_Job();
		if($jobid = $job->create(get_class($caller),$caller->parameters,get_class($caller->caller),$caller->caller->id,$session->user->id)) {
			$this->clear_cache();
			$this->load();
			return $jobid;
		}
		
		return false;
	}

	/**
	* Remove a job from the queue
	*
	* @param integer $jobid the jobid to remove
	*
	* @return array
	* @acces public
	*/
	function remove_job($jobid) {

		# We need a jobid
		if(!$jobid) return;
	
		$job = &$this->get_job($jobid);
		if($job->delete()) {
			$this->clear_cache();
			$this->load();
			return array(MYSOURCE_ERROR_CODE_NONE,"Server Job (ID: $jobid) deleted.");
		}
		
		return array(MYSOURCE_ERROR_CODE_ERROR,"Unable to delete Server Job (ID: $jobid).");
	}

	/**
	* Run a job
	*
	* @param integer $jobid the jobid to run
	*
	* @return bool
	* @access public
	*/
	function run_job($jobid) {

		# We need a jobid
		if(!$jobid) return false;
		
		$job = &$this->get_job($jobid);

		if($job->id) {
			return $job->run();
		}

		return false;
	}	

	/**
	* Get a reference to a job
	*
	* @param integer $jobid the jobid to get
	* 
	* @return object wizard_server_job
	* @access public
	*/
	function &get_job($jobid) {

		# We need a jobid
		if(!$jobid) return false;

		if(!$this->temp[$jobid]) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/wizard_server_job.inc");
			$this->temp[$jobid] = new Wizard_Server_Job($jobid);
		}
		return $this->temp[$jobid];
	}

	/**
	* Called by the daemon script to inform the squiz server manager that it is about to start processing
	* This is needed so that we can check the the input from the web script and
	* return an ACK or an error to it before we start processing
	*
	* @param array	$input_data		data sent from the web script
	* @param array 	$output_data	data to be sent back to the web script - Array ('result' => [ack|error], 'msg' => '');
	* @param string	$taskid			the id of the task on the server (used if we need to abort later on)
	*
	* @return boolean	indicating whether processing should start or not
	* @access public
	*/
	function prepare_process_server(&$input_data, &$output_data, $taskid) {

		$this->temp['squiz_server']['jobid'] = 0;
		$this->temp['squiz_server']['taskid'] = 0;

		if (empty($input_data['userid'])) {
			$output_data['result'] = 'error';
			$output_data['msg']    = 'Userid not supplied on Input Data';
			return false;
		}

		$session = &get_mysource_session();
		$session->set_current_user($input_data['userid']);
		if (!$session->user->id) {
			$output_data['result'] = 'error';
			$output_data['msg']    = 'Unable to find User (ID: '.$input_data['userid'].') to run as';
			return false;
		}

		$job = &$this->get_job($input_data['jobid']);
		if (!$job->id) {
			$output_data['result'] = 'error';
			$output_data['msg']    = 'Unable to find Job (ID: "'.$input_data['jobid'].')"';
			return false;
		}

		if($job->set_taskid($taskid)) {
			$output_data['result']	= 'error';
			$output_data['msg']		= 'Unable to set the taskid for the job';
			return false;
		}

		$this->temp['squiz_server']['jobid'] = $job->id;
		$this->temp['squiz_server']['taskid'] = $taskid;

		pcntl_signal(SIGTERM, Array(&$this, 'serverSigHandler'));
		pcntl_signal(SIGINT,  Array(&$this, 'serverSigHandler'));

		$output_data['result']= 'ack';
		return true;

	}

	/**
	* Run the jobid specified
	*
	* @return boolean
	* @access public
	*/
	function process_server() {
		if (!$this->temp['squiz_server']['jobid'] || !$this->temp['squiz_server']['taskid']) return false;

		$job = &$this->get_job($this->temp['squiz_server']['jobid']);

		if (is_null($job)) {
			trigger_error('Unable to find a job (ID: "'.$this->temp['squiz_server']['jobid'].'")', E_USER_WARNING);
			return false;
		}

		$this->temp['squiz_server']['processing'] = true;

		$ret_val = $job->process();

		unset($this->temp['squiz_server']);

		return $ret_val;
	}

	/**
	* The handler for any signals from the system
	*
	* @return int		$signo	the signal number
	* @access public
	*/
	function serverSigHandler($signo)
	{
		switch($signo) {
			case SIGINT:
			case SIGTERM:
				error_log('Aborting Job...');
				if (!empty($this->temp['squiz_server']['jobid'])) {
					$job = &$this->get_job($this->temp['squiz_server']['jobid']);
					if ($job->id) {
						error_log("ABORT JOB --->");
						$job->abort();
					}
				}
				exit();
				break;
			default:
				trigger_error('Signal '.$signo.' not handled', E_USER_NOTICE);
				break;
		}
	}

	/**
	* Print the backend of current server jobs
	*
	* @param object $backend
	* 
	* @access public
	*/
	function print_backend(&$backend) {

		if($_POST['action'] == 'commit') {
			if(!empty($_POST['delete_abort'])) {
				foreach($_POST['delete_abort'] as $jobid) {
					$job = &$this->get_job($jobid);
					if($job->status == 'R') {
						if(!$job->abort()) {
							$backend->add_message(MYSOURCE_ERROR_CODE_ERROR,'Can not connect to server to abort job');
							$backend->add_message($this->remove_job($job->id));
						}
					}
					else $backend->add_message($this->remove_job($job->id));
				}
			}
		}

		if(empty($this->jobs)) {
			$backend->open_field('','one_liner');
			echo 'The are no jobs available.';
			return;
		}

		$users_system = &get_users_system();
		$wizard = &$this->get_wizard();
		
		foreach($this->statii as $code => $label) {
		
			if(!empty($this->jobs[$code])) {
				$backend->open_field($label.' Jobs');
			
				echo '<table cellpadding=2 cellspacing=1 border=0 bgcolor=black width=80%>';
				echo '<tr bgcolor=#cccccc>';
				echo '<td class=backend_field align=center valign=bottom>ID</td>';
				echo '<td class=backend_field align=center valign=bottom>Job Type</td>';
				echo '<td class=backend_field align=center valign=bottom>Created By</td>';
				echo '<td class=backend_field align=center valign=bottom>Last Updated</td>';
				echo '<td class=backend_field align=center valign=bottom>Message</td>';
				echo '<td class=backend_field align=center valign=bottom>Delete/Abort?</td>';
				echo '</tr>';

				foreach($this->jobs[$code] as $jobid) {
					$job = &$this->get_job($jobid);
					$user = &$users_system->get_user($job->userid);
					echo '<tr bgcolor=#bbbbbb><td class=backend_data align=center>'.$job->id.'</td>';
					echo '<td class=backend_data align=center>'.$wizard->_types->register[$job->job_type]->name.'</td>';
					echo '<td class=backend_data align=center>'.$user->name().'</td>';
					echo '<td class=backend_data align=center>'.easy_datetime(mysql_to_timestamp($job->last_updated)).'</td>';
					echo '<td class=backend_data align=center>'.$job->message.'</td>';
					echo '<td class=backend_data align=center><input type="checkbox" name="delete_abort[]" value="'.$jobid.'"></td>';
					echo '</tr>';
				}
				echo '</table>';
			}
		}

		$backend->print_commit_button('Commit','document.edit.action.value="commit";document.edit.submit()');
	}

}

?>