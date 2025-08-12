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
## $Source: /home/cvsroot/mysource/include/wizard.inc,v $
## $Revision: 2.15.2.2 $
## $Author: achadszinow $
## $Date: 2004/05/21 07:02:15 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
include_once("$INCLUDE_PATH/parameter_set.inc");
#---------------------------------------------------------------------#


/**
* The base class for creating wizards
* The base class for creating wizards to guide users
* through the more complex actions in MySource
*
* @access  public
* @package Wizards
*/
class Wizard extends SystemObject {

	/**
	* The type of asset that calls us (page, site etc)
	* @var string
	*/
	var $asset_type = '';

	/**
	* A reference to the asset that called us
	* @var object
	*/
	var $caller;
	
	/**
	* An array to hold parameters for the pset
	* @var array
	* @see &get_pset()
	*/
	var $parameters = array();
	
	/**
	* A reference to the wizard backend object
	* @var object Wizard_Backend
	* @see &get_backend();
	*/
	var $backend;
	

	/**
	* The names of the standard wizard buttons
	* @var array
	*/
	var $button_names = array(	'back'   => '« Back',
								'next'   => 'Next »', 
								'start'  => 'Start Over', 
								'finish' => 'Finish',
								'cancel' => 'Cancel',
								'done'   => 'Done'
								);

	/**
	* Array of buttons that we should use - NOT DONE YET
	* @var array
	*/
	var $buttons = array('back','next','start','finish','cancel','done');
	

	/**
	* An array to hold additional buttons to show on the various steps of the wizard
	* @var array
	*/
	var $special_buttons = array();


	/**
	* An XtrasRegistry of all the wizards that are available to us
	* @var object XtrasRegistry
	*/
	var $_types = array();


	/**
	* An array of asset types that this wizard is compatible with.
	* eg. array('site','page') - the wizard can be run on a site or page
	* @var array
	*/
	var $compatible_with = array();


	/**
	* This will restrict who can see a particular wizard (for example superusers only).
	* @var array
	*/
	var $restricted_to = array();


	/**
	* Constructor
	*
	* @param   object &$asset A reference to the asset that called us
	* @returns object Wizard
	* @access  public
	*/
	function Wizard (&$asset) {
		global $XTRAS_PATH;
		SystemObject::SystemObject();
		$this->asset_type = get_class($asset);
		$this->caller = $asset;
		$this->_types = new XtrasRegistry($XTRAS_PATH.'/wizards/', 'Wizard');
	}


	/**
	* Prints a list of all the wizards that can be run by this asset type
	*
	* @returns void
	* @access  public
	*/
	function print_backend() {
		# Check if we are printing a wizard or if we need to select the type of wizard
		global $XTRAS_PATH;
		if ($wizard_type = $this->type_set()) {
			$wizard_type = $this->_types->register[$wizard_type];
			include_once($XTRAS_PATH.'/wizards/'.$wizard_type->codename.'/'.$wizard_type->codename.'.inc');
			$wizard = new $wizard_type->codename($this->caller);
			$wizard->print_wizard();
			return;
		}

		$web_system = &$this->get_web_system();	# get the web system
		$backend = &$web_system->get_backend();	# to get the mysource backend
		$wizards_available = array();			# (not the wizard backend)

		# loop through the wizards and add them to a category
		# the wizards are grouped by the category found in the xtra.info file
		if (!empty($this->_types->register)) {
			foreach ($this->_types->register as $wizard_type) {
				if (!$wizard_type->codename) continue; # not sure how a blank one got here, but let's skip it!
				# get the wizard - include the file and create the object
				include_once($XTRAS_PATH.'/wizards/'.$wizard_type->codename.'/'.$wizard_type->codename.'.inc');
				$wizard = new $wizard_type->codename($this->caller);

				# check that this wizard is compatible with the asset that called us, and we have enough access to use it.
				if ($wizard->is_compatible($this->asset_type) && $wizard->has_access()) {
					# if the category is not set, add this wizard to the miscelaneous category
					if (!isset($wizard_type->extra_values['category'])) $wizard_type->extra_values['category'] = 'Miscellaneous';
					if (isset($wizard_type->extra_values['config_check'])) {
						$config = &get_system_config();
						list($var,$name) = explode("=", $wizard_type->extra_values['config_check']);
						if ($var && $name) {
							$var = trim($var); $name = trim($name);
							if ($config->$var == $name) {
								$wizards_available[$wizard_type->extra_values['category']][] = $wizard_type;
							}
						}
					} else {
						$wizards_available[$wizard_type->extra_values['category']][] = $wizard_type;
					}
				}
			}
		}

		if (!empty($wizards_available)) {
			foreach ($wizards_available as $category => $wizards) {
				$backend->open_section(ucfirst($category)); # print category name
				foreach ($wizards as $wizard_type) {
					$backend->open_field($wizard_type->name, 'one_liner','','',180); # print wizard name
					$wizard = new $wizard_type->codename($this->caller);
					$description = $wizard->get_description($wizard_type->description);
					# print a link to open the wizard
					?>
					<a href="#" onclick="window.open('<?=$this->get_wizard_link($wizard_type->codename)?>&wizard_start=1','<?=$this->get_code($wizard_type->codename)?>','toolbar=no,status=yes,width=700,height=350,titlebar=false,scrollbars=yes,resizable=yes');"><?=$description?></a><br>
					<?
					$backend->close_field();
				}
				$backend->close_section();
			}
		} else {
			# we got nothin'
			$backend->open_section('There are no wizards available.');
			$backend->close_section();
		}

		# Print the squiz server job backend
		$backend->open_section('Squiz Server Jobs Summary');

		$wizard_server = &$this->get_wizard_server_manager();

		$wizard_server->print_backend($backend);

		$backend->close_section();

	}



	/**
	* Returns a unique id string for this wizard
	*
	* @param   string $wizard_type The type of this wizard
	* @returns string
	* @access  public
	*/
	function get_code($wizard_type='') {
		if (!$wizard_type) $wizard_type = $this->type_set();
		return 'mysource_wizard_'.$this->_types->register[$wizard_type]->codename."_{$this->asset_type}_{$this->caller->id}";
	}


	/**
	* Returns a custom description for this wizard.
	*
	* If this fucnction returns an empty string, the description
	* from the Xtra.info file will be used instead. Override this
	* function for a custom description.
	*
	* @param   default string The default description from the Xtra.info file
	* @returns string
	* @access  public
	*/
	function get_description($default='') {
		return $default;
	}


	/**
	* Returns the web database object
	*
	* @returns object Db
	* @access  public
	*/
	function &get_db() {
		$web = &get_web_system();
		$db = &$web->get_db();
		return $db;
	}


	/**
	* Is this wizard compatible with the asset that called us?
	*
	* This function will check the compatible_with array by default
	* so for basic wizards, this function does not have to be overridden
	* but for more complex wizards, other checks can be made
	*
	* @param   string $asset_type The type of asset we are checking the compatability of (eg. site, page etc)
	* @returns boolean
	* @access  public
	*/
	function is_compatible($asset_type='') {
		return in_array($asset_type,$this->compatible_with);
	}


	/**
	* Do we have enough access to get to this wizard?
	*
	* This function will check the restricted_to array by default
	* so for basic wizards, this function does not have to be overridden
	* but for more complex wizards, other checks can be made.
	* Values can be:
	*	superuser_web, superuser_user
	*	siteadmin, siteeditor
	*	pageadmin, pageeditor
	*
	* @param   string $user_level The level of access we are checking.
	* @returns boolean
	* @access  public
	*/
	function has_access() {
		if (empty($this->restricted_to)) return true;
		foreach($this->restricted_to as $level) {
			$level = strtolower($level);
			switch($level) {
				case 'superuser_web':
					return superuser('web');
				break;
				case 'superuser_user':
					return superuser('user');
				break;
				case 'siteadmin':
				case 'siteeditor':
					if ($this->asset_type != 'site' && $this->asset_type != 'page') return false;
					$web = &get_web_system();
					$site = &$web->get_site();
					return ($level == 'siteeditor') ? $site->admin_access() : $site->editor_access();
				break;
				case 'pageadmin':
				case 'pageeditor':
					if ($this->asset_type != 'page') return false;
					$web = &get_web_system();
					$page = &$web->get_page();
					return ($level == 'pageadmin') ? $page->admin_access() : $page->editor_access();
				break;
				default:
					return false;
			}
		}
		return false;
	}


	/**
	* The function that actually prints the wizard
	*
	* This function handles moving between steps with the
	* back and forward buttons and also allows some interaction with
	* the wizard to process complex actions and print additional buttons
	*
	* @returns void
	* @access  public
	*/
	function print_wizard() {

		$wizard_start = $_REQUEST['wizard_start'];

		$session = &get_mysource_session();
		$system_config = &get_system_config();

		# the code for this wizards so we can find its params in the session
		$param_code = $this->get_code().'_params';

		# if we are starting the wizard (usually from the
		# Start Over button or from a new window opening),
		# clear the params in the session and set the defaults
		if ($wizard_start) {
			$session->unset_var($param_code);
			
			# set dynamic defaults such as dates
			# other defaults can be hard coded into the params array
			$this->set_defaults();
		}

		# get params from session if they are there
		if ($session->has_var($param_code)) {
			$this->parameters = $session->get_var($param_code);
		}

		$backend = &$this->get_backend(); # the wizard backend

		$backend->set_hidden_field('wizard_start','');
		$backend->set_hidden_field('active_step','');
		$backend->set_hidden_field('action','');

		# process any special actions for this wizard
		# the wizard needs to handle the processing and can then
		# change the global var $active_step at this point to trick
		# the wizard into displaying another screen
		$action = $_REQUEST['action'];
		$this->process_special_action($action);

		# working out the heading for this wizard
		# involves checking the caller object to see
		# what information we have access to (eg. an
		# ID, a name, a lineage) and then creating the
		# headng using a combination of these elements
		$heading = 'MySource '.$this->_types->register[$this->type_set()]->name.' Wizard';
		$heading .= '<br><span style="font-size:12px">';
		$heading .= ucfirst(get_class($this->caller));
		if (isset($this->caller->id)) {
			$heading .= ' '.$this->caller->id;
		} else {
			$heading .= ' System';
		}
		if (isset($this->caller->name)) $heading .= ' ('.$this->caller->name.')';
		if (method_exists($this->caller,'get_lineage')) {
			$lineage = $this->caller->get_lineage();
			if (!empty($lineage)) {
				$heading .= '</span><br><span style="font-size:10px">';
				# a special case for pages so we can put the
				# site name at the front of the lineage
				if (get_class($this->caller) == 'page') {
					$site = $this->caller->get_site();
					$heading .= strtoupper($site->name);
				}
				foreach($lineage as $pageid => $name) $heading .=  " &gt; $name";
			}
		}
		$heading .= '</span>';

		$backend->set_heading($heading);
		$pset = &$this->get_pset();

		$num_steps = $this->get_num_steps();
		
		# what screen are we on - or at least think we are on?
		$active_step = (int)$_REQUEST['active_step'];

		# print a basic navigation to show the user where they are in the step-by-step process
		if ($active_step < $num_steps) $backend->set_subheading('Step '.($active_step+1)." of $num_steps");

		if ($active_step && $action == 'Commit') {
			# process the last screen
			$pset->process_screen($backend,$active_step);
		}

		# print the next screen or print the result of this wizard
		if ($active_step > $num_steps) $active_step = $num_steps;
		$active_step++;
		if ($active_step > 1) {
			$backend->add_button($this->button_names['back'],'document.edit.action.value=\'\';document.edit.active_step.value='.($active_step-2).';document.edit.submit()');
		}

		# add special buttons for this screen
		if (!empty($this->special_buttons[$active_step])) {
			foreach ($this->special_buttons[$active_step] as $button => $data) {
				$backend->add_button($button,"document.edit.action.value='".$data['action']."';document.edit.active_step.value=".$data['skip_to'].";document.edit.submit()");
			}
		}

		$backend->set_hidden_field('active_step',$active_step);

		 # we are printing the result of this wizard
		if ($active_step > $num_steps) {
			$wizard_type = $this->type_set();
			$wizard_type = $this->_types->register[$wizard_type];
			$backend->add_button($this->button_names['start'],'document.edit.action.value=\'\';document.edit.wizard_start.value=1;document.edit.active_step.value=\'\';document.edit.submit()');
			$backend->add_button($this->button_names['done'],'if (confirm(\'Are you sure you want to close this wizard?\')) { window.close(); }');
			$backend->print_header();
			
			# run this job through the squiz server?
			if($wizard_type->extra_values['server'] && $system_config->squiz_server_details['path']) {
				$wizard_server = &$this->get_wizard_server_manager();

				# add this job to the server
				if(!$jobid = $wizard_server->add_job($this)) {
					$backend->add_message(MYSOURCE_ERROR_CODE_ERROR,'Unable to add job to wizard server. Attempting to run the job through the browser.');
					$this->process_wizard_web($backend);
				} else {
					# run this job
					if(!$wizard_server->run_job($jobid)) {
						$backend->add_message(MYSOURCE_ERROR_CODE_ERROR,'Unable to run job through the wizard server. Attempting to run the job through the browser.');
						$wizard_server->remove_job($jobid);
						$this->process_wizard_web($backend);
					}
				}
			} else {
				# just run the job through the browser
				$this->process_wizard_web($backend);
			}

			$backend->print_commit_button();
			$backend->print_footer();
		} else {

			$backend->add_button($this->button_names['cancel'],'if (confirm(\'Are you sure you want to cancel?\')) { window.close(); }');
			if ($active_step == $num_steps) {
				$backend->add_button($this->button_names['finish'],"document.edit.action.value='Commit';document.edit.active_step.value=$active_step;document.edit.submit()");
			} else {
				$backend->add_button($this->button_names['next'],"document.edit.action.value='Commit';document.edit.active_step.value=$active_step;document.edit.submit()");
			}
			$pset->print_screen($backend,$active_step);
			$this->additional_screen_content($backend, $active_step);
		}
		
		# save params to the session
		$this->parameters['active_step'] = $active_step;
		$session->set_var($param_code, $this->parameters);

	}



	/**
	* Use this function to add a sepcial button to the wizard
	*
	* Pass in the button name (text on the button), the action to perform
	* (you will need to process this in your process_special_action function),
	* the step to skip to when this action is performed, and an array of
	* step numbers to show this button on. The step numbers can be an array
	* or a single integer value.
	*
	* @param   string $name		The text on the button
	* @param   string $action	The action to perform when the button is pressed
	* @param   string $skip_to	The step to skip to when the action is performed
	* @param   array  $screens	An array of step numbers to show this button on.
	*							This can be an array of a single value
	* @returns void
	* @access  public
	*/
	function add_button($name='',$action='',$skip_to='',$screens='') {
		if (is_array($screens)) {
			foreach ($screens as $screen) {
				$this->special_buttons[$screen][$name]['action'] = $action;
				$this->special_buttons[$screen][$name]['skip_to'] = $skip_to;
			}
		} else {
			$this->special_buttons[$screens][$name]['action'] = $action;
			$this->special_buttons[$screens][$name]['skip_to'] = $skip_to;
		}
	}



	/**
	* The function that does all the processing
	*
	* Wizards need to overwrite this function or they will end up doing nothing
	*
	* @param   object Wizard_Backend &$backend A reference to the wizard backend object
	* @returns void
	* @access  public
	*/
	function process_wizard(&$backend) {
		return;
	}


	/**
	* Set default dynamic values for this wizard
	*
	* Only need to override this function if you can't set the
	* defaults in the $parameters array
	*
	* @returns void
	* @access  public
	*/
	function set_defaults() {
		return;
	}


	/**
	* Use this function to process custom actions that were assigned to special buttons
	*
	* @param   string $action The action to perform when this button is pressed
	* @returns void
	* @access  public
	*/
	function process_special_action($action='') {
		return;
	}


	/**
	* Use this function to print content on a screen before the pset prints its content
	*
	* @param   object  Wizard_Backend &$backend    A reference to the wizard backend object
	* @param   integer active_step    $active_step The number of the screen we are printing
	* @returns void
	* @access  public
	*/
	function additional_screen_content(&$backend, $active_step=1) {
		return;
	}


	function get_num_steps() {
		$pset = &$this->get_pset();

		# work out the number of steps we have by looking at the screen names in the pset
		# all screens for steps must be named with the step number
		# eg. ^1^ - the screen name for step 1
		#	  ^2^ - the screen name for step 2
		# it will ignore screens with non numeric names
		$screens = $pset->get_screen_names();
		$num_steps = 0;
		if (!empty($screens)) {
			foreach($screens as $screen_name) {
				$screen_name = (int)$screen_name;
				if ($screen_name > 0 && $screen_name > $num_steps) $num_steps = $screen_name;
			}
		}
		return $num_steps;
	}


	/**
	* Tells you if this wizard is of a particular type or if we still need to select the type
	*
	* @returns boolean
	* @access  public
	*/
	function type_set() {
		$wizard_type = trim($_GET['wizard_type']);
		if (isset($this->_types->register[$wizard_type])) {
			return $wizard_type;
		}
		return false;
	}


	/**
	* Get the parameter set for this wizard
	*
	* All wizards MUST have a parameter set
	*
	* @returns object Parameter_Set
	* @access  public
	*/
	function &get_pset() {
		global $XTRAS_PATH;
		$class = get_class($this);
		$this->temp['pset'] = new Parameter_Set($class,"$XTRAS_PATH/wizards/$class/$class.pset", $this->parameters, $this);
		return $this->temp['pset'];
	}



	/**
	* Returns a relative url to the wizard
	*
	* @param   string $wizard_type The code of the wizard we are running
	* @returns string
	* @access  public
	*/
	function get_wizard_link($wizard_type='') {
		return $_SERVER['PHP_SELF'].'?wizard_type='.$wizard_type.'&'.$_SERVER['QUERY_STRING'];
	}



	/**
	* Returns a reference to the Wizard backend object
	*
	* @returns object Wizard_Backend
	* @access  public
	*/
	function &get_backend() {
		if(get_class($this->backend) != 'wizard_backend') {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/wizard_backend.inc");
			global $EDIT_DIR, $EDIT_PATH;
			$this->backend = new Wizard_Backend($EDIT_DIR,$EDIT_PATH);

			# carry over the hidden fields so we submit to the right spot
			$web_system = &$this->get_web_system();
			$web_backend = &$web_system->get_backend();
			$this->backend->hidden_fields = $web_backend->hidden_fields;
		}
		return $this->backend;
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

}

?>