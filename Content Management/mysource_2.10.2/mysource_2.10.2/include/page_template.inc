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
## File: include/page_template.inc
## Desc: Parent class for page template xtra classes
## $Source: /home/cvsroot/mysource/include/page_template.inc,v $
## $Revision: 2.52.2.3 $
## $Author: achadszinow $
## $Date: 2004/05/24 04:08:46 $
#######################################################################
#---------------------------------------------------------------------#

class Page_Template extends WebObject {

	# A strign representing the root db table name 
	var $table_name='';

	# A string representing the codename of this template - automatic
	var $codename='';

	# A string representing the name of this template - automatic
	var $name='';
	
	# The version number of this xtra instance
	var $version='';

	# The path to this Xtra's directory
	var $xtra_path='';

	# The pageid this object extends
	var $id = 0;

	# The data dir that this template can store its files in
	var $data_path='';

	var $_backend_setup = false;  # a boolean specifiying whether the backend has been setup or not

	var $temp=''; # Temporary runtiem data

	 ##############################
	# Constructor
	function Page_Template($pageid=0) {

		WebObject::WebObject();

		 #################################################
		# Load the user information if an id is specified
		if ($pageid) {
			return $this->load($pageid);
		}

	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element("codename",$result);
		array_remove_element("name",$result);
		array_remove_element("version",$result);
		array_remove_element("table_name",$result);
		array_remove_element("xtra_path",$result);
		array_remove_element("data_path",$result);
		array_remove_element("_backend_setup",$result);
		array_remove_element("temp",$result);
		return $result;
	}


	 ##############################################
	# Wake up with this handy information VERY day !
	function __wakeup() {
		WebObject::__wakeup();
		$this->set_generated_information();
	}


	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid=0) {
		$db = &$this->get_db();
		if($db->single_element("SELECT pageid FROM $this->table_name WHERE pageid='$pageid'")) {
			$this->_set_error("This page is already using this page template.",__FILE__,__LINE__);
			return 0;
		} elseif(!$db->single_element("SELECT pageid FROM page WHERE pageid='$pageid'")) {
			$this->_set_error("There is no page record for this pageid: $pageid",__FILE__,__LINE__);
			return 0;
		}
		if($db->insert("INSERT INTO $this->table_name (pageid) VALUES ('$pageid')")) {
			$this->id = $pageid;
		}
		return "$this->name page template created.";
	}


	 ################################
	# Handy little wrappers
	function &get_site($siteid=0) {
		if(!$siteid) {
			$page = &$this->get_page();
			$siteid = $page->siteid;
		}
		return $this->web_system->get_site($siteid);
	}
	function &get_page($pageid=0) {
		if(!$pageid) $pageid = $this->id;
		return $this->web_system->get_page($pageid);
	}
	function &get_current_page() {
		return $this->web_system->get_page($this->web_system->current_pageid);
	}
	function &get_file($fileid=0) {
		return $this->web_system->get_file($fileid);
	}
	function &get_template_description() {
		global $XTRAS;
		return $XTRAS->description("page/templates",$this->codename);
	}
	function &get_url_leftovers() {
		$page = &$this->get_page();
		return $page->url_leftovers;
	}

	 ################################################
	# Loads the temaplte details, from cache or database
	function load($pageid=0) {
		if ($pageid <= 0 && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name page template without a valid pageid.",__FILE__,__LINE__);
		}


		 ###############################################
		# Check the cache - but remember where mum is !
		
		if ($this->load_from_cache($pageid)) {
			return $pageid;
		}

		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();
		list($this->id) = $db->single_row("SELECT pageid FROM $this->table_name WHERE pageid='$pageid'");


		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		$this->set_generated_information();

		 ####################################################################
		# Save to the cache - probably not wise to do this here, but
		# children should do it when they're ready in their load() overloads
		#$this->save_to_cache($pageid);
		return $pageid;
	}

	/**
	* Duplicates this entire page template to a new pageid
	*
	* this function checks whether any of the attributes are bodycopies. If so, 
	* it asks the bodycopies to use the $DUPE_MAP to change the old fileids to the 
	* new fileids that are attached to the new page. Handles bodycopies in parameter sets 
	* within the first level (i.e. if it is in the $parameters array) or if it is in the 
	* $parameters['copy'] array. Otherwise, you're on your own. 
	*
	* @param int	 $new_pageid New Pageid that we're copying the data to
	* @param boolean $remap		 If we should remap all the link ids or not
	* @returns string
	* @access public
	*/
	function dupe($new_pageid=0, $remap=true) {
		$session = &get_mysource_session();
		$dupe_map = $session->get_var('dupe_map');

		if ($new_pageid > 0) {
			$db = &$this->get_db();
			$page_array = $db->associative_row("SELECT * FROM $this->table_name WHERE pageid='$this->id'");

			$queries = array();
			if (empty($page_array)) return false;

			foreach($page_array as $key => $value) {
				if ($key == 'pageid') continue;
				if ($remap) {
					$settings = unserialize($value);
					if ($settings) {
						$settings = $this->recursive_remapper($settings, $dupe_map);
						$value = serialize($settings);
					}
				}
				$q = "UPDATE $this->table_name SET $key='".addslashes($value)."' WHERE pageid='$new_pageid'";
				$queries[] = $q;
			}
			$msgs = array();
			foreach($queries as $query) {
				$msgs[] = $db->update($query);
			}

			if ($remap) {
				$this->template_defined_remapper($dupe_map);
			}

			$this->clear_cache($new_pageid);
			if (empty($msgs)) {
				return false;
			} else {
				return implode('<br />', $msgs);
			}
		} else {
			$this->_set_error("Attempt to duplicate a page without specifying new pageid: $new_pageid",__FILE__,__LINE__);
			return false;
		}
	}

	/**
	* Applies the dupe map to everything possible in this templates table no matter where it is
	*
	* @param array $dupe_map	The dupe map array
	* @returns string
	* @access public
	*/
	function remap_link_ids($dupe_map) {
		$db = &$this->get_db();
		$this->template_defined_remapper($dupe_map);
		$page_array = $db->associative_row("SELECT * FROM $this->table_name WHERE pageid='$this->id'");
		$queries = array();
		if (empty($page_array)) continue;
		foreach($page_array as $key => $value) {
			$value = gpc_stripslashes($value);
			$settings = unserialize($value);
			# if it wasn't possible to unserialise
			if (!$settings) continue;
			$settings = $this->recursive_remapper($settings, $dupe_map);
			$queries[] = "UPDATE $this->table_name SET $key='".addslashes(serialize($settings))."' WHERE pageid='$this->id'";
		}
		$msgs = array();
		foreach($queries as $query) {
			$msgs[] = $db->update($query);
		}
		$this->clear_cache($this->id);
		return $db->update($query);
	}

	/**
	* Goes through anything you give it checking for bodycopies in any form. If there is an array it calls itself to look through those too
	*
	* @param mixed $data		Can be a whole parameters array or something smaller like a string value or a 							bodycopy or anything really. It should be safe in all cases.
	* @param array $dupe_map	The dupe map array
	* @returns mixed
	* @access public
	*/
	function recursive_remapper($data, $dupe_map) {
		if (!isset($data)) return $data;
		if (get_class($data) == 'bodycopy') {
			# non serialised bodycopy so fix her up and put her back the way we found her
			$bodycopy = $data;
			$bodycopy->remap_link_ids($dupe_map);
			$data = $bodycopy;
		} elseif (substr($data,0,14) == 'O:8:"bodycopy"') {
			# serialised bodycopy so fix her up and put her back the way we found her
			$bodycopy = new BodyCopy($data, 'bodycopy');
			$bodycopy->remap_link_ids($dupe_map);
			$data = $bodycopy->pack();
		} elseif (is_array($data)) {
			$data_keys = array_keys($data);
			foreach($data_keys as $data_key) {
				$data[$data_key] = $this->recursive_remapper($data[$data_key], $dupe_map);
			}
		}
		return $data;
	}

	/**
	* Can get over written by templates if the have things that need to be re mapped other than bodycopies
	*
	* @param array $dupe_map	The dupe map array
	* @returns void
	* @access public
	*/
	function template_defined_remapper($dupe_map) {
	}

	 #######################################################
	# Changes the id for this particular template
	function newid($new_pageid=0) {
		$db = &$this->get_db();
		$qry = "UPDATE $this->table_name SET pageid='$new_pageid' WHERE pageid='$this->id'";
		$db->update($qry);
		$this->clear_cache($this->id);
		$this->id = $new_pageid;
		$this->save_to_cache($this->id);
	}


	function delete() {
		$db = &$this->get_db();
		$this->clear_cache($this->id);
		$db->delete("DELETE FROM $this->table_name WHERE pageid='$this->id'");
		$message = "(PageID: $this->id) $this->name page template deleted.";
		unset($this);
		return $message;
	}


	 ####################################################
	# Changes the type of the template to a new template
	function change($new_template_code='') {
		if(strtolower($new_template_code) == strtolower($this->codename)) return "Template unchanged.";
		$data_to_pass = $this->export_data();
		$class_name = "page_template_$new_template_code";
		global $XTRAS_PATH;
		include_once("$XTRAS_PATH/page/templates/$new_template_code/$new_template_code.inc");
		$new_template = new $class_name(0,$this->web_system);
		$new_template->create($this->id);
		$new_template->import_data($data_to_pass);
		$oldname = $this->name;
		$newname = $new_template->name;
		$this->delete();
		$this = $new_template;
		return "Page template changed from '$oldname' to '$newname'";
	}


	 #################################################################################
	# Provides an associative array of data that other templates might like to import
	function export_data() {
		return array();
	}

	 
	 ###########################################
	# Imports data exported from another tempate
	function import_data(&$data) {
		if (!empty($data)) {
			foreach($data as $code => $datum) {
				continue;
			}
		}
	}

	/*
		Returns an array of designs this template conflicts with.
		So the design areas can check to see if a template conflicts with a design.
		eg. sub_page design area conflicts with sub_page page template.
		pagefile_list design area conflicts with file_list page template.
	*/
	function conflicts_with_designs() {
		return array();
	}

	 #####################################################
	# Each template can go through its own data and return
	# A list of relevant keywords.
	function extract_keywords() {
		return "";
	}

	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		$page = &$this->get_page();
		return $page->get_backend_href()."&template_edit=1";
	}

	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_url() {
		$page = &$this->get_page();
		return $page->get_backend_url()."&template_edit=1";
	}

	 ########################################################
	# Gets a references to the parameter set (if there is one)
	function &get_pset() {
		if(get_class($this->temp['pset']) !== "parameter_set") {
			$this->temp['pset'] = new Parameter_Set(get_class($this),"$this->xtra_path/$this->codename.pset",$this->parameters, $this);
		}
		return $this->temp['pset'];
	}

	 ###############################################################
	# Prints an interface for loading parameters from builtin files
	function backend_print_parameter_summary() {
		$pset = &$this->get_pset();
		$pset->print_summary("page.php?pageid=$this->id&template_edit=1");
	}

	 ##############################################################
	# Returns an array of built-in parameters for the user to load
	function &get_pset_supplied_params() {
		$options = array();

		if (!is_dir("$this->xtra_path/sample_param")) return $options;

		$d = opendir("$this->xtra_path/sample_param");
		while($d && $file = readdir($d)) {
			if(ereg("^.+\.param$",$file)) {
				$name = str_replace("_"," ",ereg_replace("\.param$","",$file));
				$options[$file] = $name;
			}
		}
		ksort($options);
		$page = &$this->get_page();
		$site = &$this->get_site();
		$list = &$this->web_system->get_template_pages($this->codename,$page->siteid);

		if (!empty($list)) {
			foreach($list as $pageid => $name) {
				if($pageid == $this->id) continue;
				if($site->page_editor_access($pageid)) {
					$options["__page:$pageid"] = "PAGE: $name";
				}
			}
		}
		return $options;
	}

	 #############################
	# Returns a builtin parameter
	function get_pset_supplied_param($filename='') {
		if(substr($filename,0,7) == '__page:') {
			$other_page = &$this->web_system->get_page(substr($filename,7));
			if($other_page->id && $other_page->write_access()) {
				$template = $other_page->get_template();
				$new_p = $template->parameters;
				$new_p['__id__']      = get_class($this);
				$new_p['__version__'] = $template->version;
				return $new_p;
			}
		}
		return unserialize(trim(file_to_string("$this->xtra_path/sample_param/$filename"),"\0x00..\0x1F \t\n\r"));
	}


	 #####################################################
	# Saves the current parameter array into the database
	function save_parameters() {
		$this->parameters['__id__']    = $this->codename;
		$this->parameters['__version__'] = $this->version;
		$db = &$this->get_db();
		if ($ret_val = $db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE pageid='$this->id'")) {
			$this->updated();
		}
		return $ret_val;
	}


	 #################################
	# Load the parameters from a file
	function load_parameters_from_file($file='') {
		$p = unserialize(trim(file_to_string($file),"\0x00..\0x1F \t\n\r"));
		if(is_array($p)) {
			if($p["__id__"] == get_class($this)) {
				if(version_no_compare($p["__version__"],$this->version) > 0) {
					$message = "WARNING: The imported parameters are from a more recent version of '$this->name'. Some of your settings may be corrupt.";
				} elseif(version_no_compare($p["__version__"],$this->version) < 0) {
					$message = "WARNING: The imported parameters are from an older version of '$this->name'. Some of your settings may be corrupt.";
				} else {
					$message = "Parameters imported successfully.";
				}
				$this->parameters = &$p;
				$this->save_parameters();
				$this->updated();
				return $message;
			}
			return "Unable to import parameters. The file is of the wrong type.";
		}
		return "Unable to import parameters. The file is corrupt.";
	}

	 #################################
	# Save the parameters to a file
	function save_parameters_to_file($file='') {
		return string_to_file(serialize($this->parameters),$file);
	}


	 ############################
	# Sets up the backend for use
	function &setup_backend() {

		$page = &$this->get_page();
		$site = &$this->get_site();
		$backend = &$page->setup_backend(); # Backend refrence

		 ###################################
		# Setup the backend a little
		if (!$this->_backend_setup) {
			$backend->set_title($page->name." - $this->name Template");
			$backend->set_active_tab('page_template');

			$backend->set_heading("$this->name v$this->version","xtra");
			$backend->set_subheading($page->name);
			$backend->set_id_string($this->id);

			if(!empty($site->urls)) {
				$backend->set_toolbar_button("preview", $page->get_href(), "Preview this page on the frontend.", "preview", "_blank");
			}

			$backend->set_hidden_field("siteid",$page->siteid);
			$backend->set_hidden_field("pageid",$this->id);
			$backend->set_hidden_field("template_edit",$this->codename);
			$backend->set_hidden_field("action");
			
			 #############################
			# Get the character set right
			$backend->set_charset($page->get_effective_default_charset());

			$this->_backend_setup = 1;
		}#end if

		return $backend;
	}

	 ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$backend = &$this->setup_backend();
		$backend->print_header();		
		$backend->open_section('This page template has no options.');		
		$backend->print_footer();
	}


	  ######################################################
	 # Update this templates page then reload this template
	function updated() {
		$page = &$this->get_page();
		$page->updated();
		# now reload this object, just in case
		$this->clear_cache();
		$this->load();
	}


	 #########################################################
	# Allow MySource to attempt to moderate the http caching
	# If returns true , use PHP defaults
	# If returns false, "Don't Cache Me" headers set, 
	# Dynamic content templates should probably return false.
	function moderate_caching() {
		return false;
	}


	 ################################################
	# Prints or does various things on the front-end
	function print_frontend() {

		 ###########################################
		# Let the site design take care of the rest
		$page   = &$this->get_page();
		$design = &$page->get_design();
		$design->paint($this);
	}

	 ######################################################
	# Returns an associaive array of universal replacements
	# These can be used to replace special keywords in copy
	function &get_universal_replacements($descs=false) {
		$g = $this->table_name."_universal_replacements";
		$g2 = $this->table_name."_universal_replacements_desc";

		$session = &get_mysource_session();

		$k = $_REQUEST[$g];
		$d = $_REQUEST[$g2];

		if(!is_array($k)) {
			$d = array();
			$k = array();

			$user = &$session->user;
			if($user->id) {
				$d['name']      = "Current user name";
				$k['name']      = $user->name();

				$d['firstname'] = "Current user firstname";
				$k['firstname'] = &$user->firstname;

				$d['surname']   = "Current user surname";
				$k['surname']   = &$user->surname;

				$d['login']     = "Current user login name";
				$k['login']     = &$user->login;

				$d['email']     = "Current user email address";
				$k['email']     = &$user->email;

				$d['expiry']    = "Expiry date of the account";
				$date = &$user->expiry_date;				
				if ($date == '0000-00-00') {
					$k['expiry']    = 'Account does not expire';
				} else {
					$k['expiry']    = &$user->expiry_date;
				}

				$d['expiry_formatted']    = "Expiry date of the account formatted nicely";
				$date = &$user->expiry_date;
				if ($date == '0000-00-00') {
					$k['expiry_formatted']    = 'Account does not expire';
				} else {
					$values = split ('-', $date);
					$time_stamp = mktime ('00', '00', '00', $values[1], $values[2], $values[0]);
					$k['expiry_formatted']    = date("l d, M, Y",$time_stamp);
				}
				
				$d['expiry_time'] = "Days until the account expires";
				$date = &$user->expiry_date;
				if ($date == '0000-00-00') {
					$k['expiry_time']    = 'Account does not expire';
				} else {
					$values = split ('-', $date);
					$time_stamp = mktime ('00', '00', '00', $values[1], $values[2], $values[0]);
					$now = mktime('00', '00', '00', date('m'), date('d'), date('Y'));
					$seconds = $time_stamp - $now;
					$days = intval($seconds / 86400);
					//$days = strtotime (
					$k['expiry_time']    = $days;
				}

			}

			$site = &$this->get_site();
			if($site->id > 0) {
				$d['site_name'] = "Current site name";
				$k['site_name'] = &$site->name;

				$d['site_url']  = "Current site URL";
				$k['site_url']  = $site->get_url();

				$d['site_href']  = "Current site HREF";
				$k['site_href']  = $site->get_href();
			}

			$page = &$this->get_page();
			if($page->id > 0) {
				$d['page_id']        = "Current page ID";
				$k['page_id']        = $page->id;

				$d['page_name']        = "Current page name";
				$k['page_name']        = $page->name;

				$d['page_short_name']  = "Current page short name";
				$k['page_short_name']  = $page->short_name;

				$d['page_description'] = "Current page description";
				$k['page_description'] = $page->description;
				

				$d['page_url']  = "Current page URL";
				$k['page_url']  = $page->get_url();

				$d['page_href']  = "Current page HREF";
				$k['page_href']  = $page->get_href();

				$d['page_create_date']  = "Date Page was created";
				$k['page_create_date']  = $page->create_date;

				$d['page_update_date']  = "Date Page was last updated";
				$k['page_update_date']  = $page->last_update;
			}

		}
		if($descs) return $d;
		return $k;
	}

	 ####################################################
	# Returns an array of keywords and their descriptions
	function &get_universal_replacements_descriptions() {
		return $this->get_universal_replacements(1);
	}

	 ############################################
	# Prints a summary of the available keywords
	function print_universal_replacements_descriptions() {
		echo("<table cellpadding=2 cellspacing=0 border=0><tr><td><b>Keyword</b></td><td><b>Replaced With</b></td></tr>");
		$desc_list = $this->get_universal_replacements_descriptions();
		if (!empty($desc_list)) {
			foreach($desc_list as $k => $d) {
				echo("<tr><td>%$k%</td><td>$d</td></tr>");
			}
		} else {
			echo ('<tr><td colspan="2">None</td></tr>');
		}
		echo('</table>');
	}

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		$page = &$this->get_page();
		return $page->name;
	}

	 
	 ################################################
	# Prints the "body" of a page.. the main section
	function print_body() {
		echo "This page template is under construction.";
	}

	 ###############################################################
	# returns the HTML that would have been printed by print_body()
	function get_html() {

		# start output buffering
		ob_start();
		# now call paint as usual
		$this->print_body();
		# now get the painted HTML
		$html = ob_get_contents();
		# turn off the output buffering without printing anything
		ob_end_clean();

		return $html;

	}#end get_html()

	 ################################################
	# Prints backend for a bodycopy object
	function print_bodycopy_backend(&$bodycopy, $bodycopy_name='',$commit='Commit') {
		$page           = &$this->get_page();
		$site_design    = &$page->get_design();
		$special_styles = $site_design->get_var('bodycopy_styles');
		$special_styles = $special_styles['options'];
		# Empty (and not universal) because some page templates would like to overwrite these but the way they do it is get the html first to find out what %keywords% exists HOWEVER get_html replaces any keywords set in the object already so we don't get to find out a true picture of %keywords%. Best to keep this blank and have all templates put in the global replacements themselves as it currently is.
		$keyword_replacements = array();
		$bodycopy->print_backend($bodycopy_name,
								 'action', 
								 $commit, 
								 'edit', 
								 $site_design->stylesheet('src'), 
								 $site_design->stylesheet('rel'), 
								 $special_styles,
								 $page->siteid,
								 $page->id,
								 squizlib_href('bodycopy'),
								 $keyword_replacements
			);
	}

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS, $XTRAS_PATH;
		$this->codename   = $XTRAS->codename("page/templates",ereg_replace("^page_template_","",get_class($this)));
		$this->name       = $XTRAS->name("page/templates",$this->codename);
		$this->version    = $XTRAS->version("page/templates",$this->codename);
		$this->table_name = "xtra_page_template_$this->codename";
		$this->xtra_path  = "$XTRAS_PATH/page/templates/$this->codename";
		
		$page = &$this->get_page($this->id);
		$this->data_path = $page->data_path."/template";

	}# set_generated_information()


	 #########################################################################
	# Returns a href to a file associated with this template
	function get_file_href($filename='') {
		$page = &$this->get_page($this->id);
		return $page->get_file_href("template/$filename");
	}

	function image_tag($file='',$alt='',$width=0,$height=0,$extra='') {
		$page = &$this->get_page();
		return $page->image_tag("template/$file",$alt,$width,$height,$extra);
	}


}

?>
