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
## File: include/page.inc
## Desc: Functions for dealing with "page"s in the system
## $Source: /home/cvsroot/mysource/include/page.inc,v $
## $Revision: 2.163.2.3 $
## $Author: achadszinow $
## $Date: 2004/05/24 04:08:46 $
#######################################################################
#---------------------------------------------------------------------#


  #######################################
 # Global variables
#######################################################
global $PAGE_STATUSES, $PAGE_STATUS_COLOUR;
$PAGE_STATUSES = array (
	'L' => 'Live',
	'R' => 'Live, but being SafeEdited',
	'P' => 'Pending Approval',
	'U' => 'Under Construction',
	'D' => 'Disabled',
	'A' => 'Archived',
	'E' => 'Up for Review'
);

  ###############################
 # Colour Scheme for page statii
$PAGE_STATUS_COLOUR = array (
	'L' => '99ee99', # Live					- Green
	'R' => 'ee9999', # Live, To be Replaced	- Red
	'P' => 'bb77bb', # Pending Approval		- Purple
	'U' => '9999ee', # Under Construction	- bluey/green
	'D' => 'bbbb77', # Disabled				- yellow/brown
	'A' => 'bbbbbb', # Archived				- Grey
	'E' => 'a7e8ef', # Up for Review		- yellow
);

 ###################################################################
# This class represents a web page. A Page is one of the core
# units of the MySource Web system.
class Page extends WebObject {

	var $site;      # A reference to the site this page belongs to
	var $data_path=''; # Directory where this page keeps its files.

	# Administrative
	var $id=0;
	var $siteid=0;
	var $parentid=0;

	# Descriptive
	var $name='';
	var $short_name='';
	var $page_notes='';
	var $imageid=0;     # a file to be used as the image
	var $description='';
	var $keywords='';

	# Multilingual
	var $default_languages='';
	var $default_charset='';

	# Order amoung peers
	var $orderno=0;
	var $subpage_auto_order=0; # auto ordering of subpages

	# Dirs in URLs that will find this page
	var $dirs = array();

	# Template
	var $template='';      # The template code
	var $page_template; # Instantiated (when necessary)

	# Security - read
	var $public = 1;              # Can people who aren't logged in view this site?
	var $access_grants = array(); # A list of access groupids granted access to this page

	var $replaceid = 0;

	# Secuirty - write
	var $editorids = array(); # People authorized to edit this page (not including site amins/editors)
	var $readonly_editorids = array(); # People authorised to view the page when it isn't live but can't change anything

	var $adminids = array(); # People authorized to administer this page (not including site admins/editors)

	# The files in a nicely accessible array
	var $file_index=array();

	var $create_date='0000-00-00'; # Timestamp of the day this page was created
	var $last_update='0000-00-00'; # Timestamp of the last time anything was changed on the site.

	# Runtime
	var $url_leftovers=''; # Dangling directories on the end of the URL, might be useful for the page template

	var $next_action='0000-00-00 00:00:00'; # When we next have to run an action change

	var $visible=0; # Is this page visible in the menu
	var $usessl=0;	# Is this page to be SSL encrypted
	var $level=0;	# How many parents between the page and the site

	# Designid and potentially loaded unserialized design object
	var $designid = 0; # if no design inherits from parent
	var $site_design;  # Unserialized

	var $_page_backend;  # an object holding the current page_backend for this site

	 ##############################
	# Constructor
	function Page($pageid=0) {
		WebObject::WebObject();
		 #################################################
		# Load the information if an id is specified
		if ($pageid) {
			return $this->load($pageid);
		}
	}

	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element('_page_backend',$result);
		array_remove_element('site_design',$result);
		array_remove_element('data_path',$result);
		return $result;
	}#end __sleep()


	 ######################################################################
	# Create a new site record in the database and load it into this object
	function create($name='',$template='',$siteid=0,$parentid=0,$status='U',$save_page_index_cache=true, $ignore_page_dirs=false) {
		$db = &$this->get_db();
		if (trim($name) == '') {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create page: please specify a name.');
		}

		# Verify site's existance
		$site = &$this->get_site($siteid);
		if(!$site->id) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create page: site could not be found.');
		}

		# By ref because lower function will change the index
		$index = &$site->get_page_index();

		# Verify parent's existance
		if($parentid) {
			$parent_data = &$index[$parentid];
			$parent_obj = &$this->get_page($parentid);
			if(!$parent_data['pageid'] || !$parent_obj->id) {
				return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create page: specified parent could not be found in site.');
			}
			$level = $parent_data['level'];
			# Add one because it's a child of it's parent
			$level++;
			$public = $parent_data['public'];
			$visible = $parent_data['visible'];
			$usessl = $parent_data['usessl'];
		} else {
			$parentid = 0;
			$parent_data = &$index[$parentid];
			$public = $site->public;
			$visible = 1;
			$usessl = 0;
			$level = 0;
			$parent_obj = &$site;
		}

		$orderno = $site->prepare_page_auto_order_no($parent_obj->subpage_auto_order, $parentid, $name);

		# Let's do it
		if (!$pageid = $db->insert("INSERT INTO page (name, short_name, siteid, parentid, create_date, last_update, public, visible, usessl, template, level, orderno) VALUES ('".addslashes($name)."','".addslashes($name)."','$siteid','$parentid',now(),now(),'$public','$visible','$usessl','$template','$level', '$orderno')")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create page: database error.');
		}
		# Okay, we have a page record.. inform the web system
		# lets pack stuff into it.
		$this->load($pageid);
		$this->web_system->register_new_page($this->id,$this);

		# Access grants can only exist after the page is created
		if($parentid) {
			$message .= $this->update_access_grants($parent_data['access_grants'], $save_page_index_cache=true).'\n';
		} else {
			$message .= $this->update_access_grants($site->access_grants, $save_page_index_cache=true).'\n';
		}

		# Status can only be added after page is loaded
		list($tmp_message,$relocation) = $this->add_status($status, '', $save_page_index_cache);
		$message .= $tmp_message.'\n';

		# Can only add the page to the page index after access grants have been set.
		# Add the page to the index rather than destroying it and recreating it.
		$site->add_page_to_page_index($save_page_index_cache, $pageid, $siteid, $parentid, $name, $name, $template, $public, '', $visible, $usessl, '', 0, $orderno);

		if (!$ignore_page_dirs) {
			# Can only register dirs after page has loaded - and the page index knows about the page.
			$message .= $this->update_dirs(array(strtolower($name)), $save_page_index_cache).'\n';
		}

		# Set up the template
		$this->template = '';
		$message .= $this->set_template($template, false)."\n";

		# Make a directories.
		if (!create_directory($this->data_path)) {
			$this->delete();
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create page directory.');
		}

		# Create a directory for the thumbs & templates in the web path
		# - assume this'll work if we got this far
		create_directory($this->data_path.'/thumbs');
		create_directory($this->data_path.'/template');
		create_directory($this->data_path.'/site_design/generated');
		$message = "Page $name created.\n";

		if ($save_page_index_cache) {
			$site->write_page_index_to_cache();
		}
		return array(MYSOURCE_ERROR_CODE_NONE, ereg_replace("[\n]+","\n",$message));
	}

	 ################################################
	# Loads all the site information into the object
	function load($pageid=0) {
		$pageid = abs((int)$pageid);
		$session = &get_mysource_session();

		if (!$pageid && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error('Attempt to load page without a valid pageid.',__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($pageid)) {
			$this->data_path = get_data_path($this->effective_unrestricted(), 'page/'.$this->id);
			if($this->next_action <= date('Y-m-d H:i:s') && $this->next_action != '0000-00-00 00:00:00') {
				$this->clear_cache();
				$this->load($this->id);
			}
			# If this page status is safe edit, then we have to load page set to replace us as it may want to delete us.
			if($this->status($this->id) == 'R') {
				$db = &$this->get_db();
				if($pid = $db->single_element("SELECT pageid FROM page WHERE replaceid='$this->id' AND next_action < now() AND next_action != '0000-00-00 00:00:00'")) {
					$p = &$this->get_page($pid);
					$this->clear_cache();
					$this->load($this->id);
				}
			}
			return $pageid;
		}

		 #######################################
		# Okay, no cache load from the database
		$db = &$this->get_db();
		list(
			$this->id,
			$this->siteid,
			$this->parentid,
			$this->orderno,
			$this->name,
			$this->short_name,
			$this->description,
			$this->keywords,
			$this->template,
			$this->public,
			$this->replaceid,
			$this->default_languages,
			$this->default_charset,
			$this->create_date,
			$this->last_update,
			$this->next_action,
			$this->visible,
			$this->usessl,
			$this->designid,
			$this->imageid,
			$this->level,
			$this->page_notes,
			$this->subpage_auto_order
		) = $db->single_row("SELECT pageid, siteid, parentid, orderno, name, short_name, description, keywords, template, public, replaceid, default_languages, default_charset, create_date, last_update, next_action, visible, usessl, designid, imageid, level, page_notes, subpage_auto_order FROM page WHERE pageid='$pageid'");
		if($this->id != $pageid) {
			return;
		}
		# Lets set the data path before and after changing statuses
		$this->data_path = get_data_path($this->effective_unrestricted(), 'page/'.$this->id);

		 ##################
		# Load more stuff!
		$this->dirs  = $db->single_column("SELECT dir FROM page_dir WHERE pageid='$this->id'");

		$this->access_grants  = $db->single_column("SELECT groupid FROM page_access_grant WHERE pageid='$this->id'");
		$this->editorids      = $db->single_column("SELECT userid FROM page_editor WHERE pageid='$this->id' AND readonly=0");
		$this->readonly_editorids = $db->single_column("SELECT userid FROM page_editor WHERE pageid='$this->id' AND readonly > 0");
		$this->adminids = $db->single_column("SELECT userid FROM page_admin WHERE pageid='$this->id'");
		// parameters is serialized
		$this->adminids_parameters = $db->associative_array("SELECT userid, parameters
															 FROM page_admin
														     WHERE pageid='$this->id'");

		 ##################################################
		# Get information on the files linked to this page
		$this->file_index = $db->single_column("SELECT fileid FROM file WHERE pageid='$this->id' ORDER BY orderno");

		# We need to change either the page status or position
		if($this->next_action <= date('Y-m-d H:i:s') && $this->next_action != '0000-00-00 00:00:00') {

			# Get all the actions we have to run
			$actions = &$this->get_actions('',$this->next_action,date('Y-m-d H:i:s'));

			$lockid = ($this->replaceid > 0) ? $this->replaceid : $this->id;

			if ($this->aquire_lock($lockid)) {

				# Run all the actions
				foreach($actions as $date => $data) {

					$next_action = $this->get_next_action_date($date);
					
					if($data['action'] == 'status') {
						$last_status = $this->get_last_status($date);
						$this->process_status_change($data['action_value'],$last_status);
					} elseif($data['action'] == 'parentid') {
						$this->page_move($data['action_value']);
					}
					$this->set_next_action($next_action);
				}
				$site = &$this->get_site();
				$site->clear_page_index();
				$this->restrict_data_path();
				if (!$this->release_lock($lockid)) report_error(__FILE__,__LINE__,"Unable to remove lock for '$this->id'");
			}
		}

		# Remember where we put things
		$this->data_path = get_data_path($this->effective_unrestricted(), 'page/'.$this->id);

		 ###################
		# Save to the cache
		$this->save_to_cache($this->id);

		return $pageid;
	}


	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function effective_public() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		return $index[$this->id]['effective_public'];
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function parent_public() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		if($this->parentid) return $index[$this->parentid]['effective_public'];
		return $site->public;
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function effective_access_grants() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		return $index[$this->id]['effective_access_grants'];
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function parent_access_grants() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		if($this->parentid) return $index[$this->parentid]['effective_access_grants'];
		return $site->access_grants;
	}

	 ##################################################################################
	# Returns the pages current status, which is stored in the site index
	function status($pageid=0) {
		if(!$pageid) $pageid = $this->id;
		$site = &$this->get_site();
		$index = &$site->get_page_index();
		return $index[$pageid]['status'];
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function effective_status() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		return $index[$this->id]['effective_status'];
	}

	 ############################
	# Is the page live or not?
	# A change to the status's which are considered live here must also be made in include/site.inc 
	# In the move_page_in_page_index function
	function live() {
		return in_array($this->effective_status(),array('L','R','E'));
	}

	 #######################################################
	# Should the page have its access unrestricted or not?
	function effective_unrestricted() {
		return $this->live() && $this->effective_public();
	}


	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function parent_status() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		if($this->parentid) return $index[$this->parentid]['effective_status'];
		return 'L';
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function effective_visible() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		return $index[$this->id]['effective_visible'];
	}

	 ###################################################################################
	# Returns effective info - context sensitive to where the page is in the hierarchy
	function parent_visible() {
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		if($this->parentid) return $index[$this->parentid]['effective_visible'];
		return true;
	}

	 ################################################
	# Returns a pointer to this page's site
	function &get_site($siteid=0) {
		if(!$siteid) $siteid = $this->siteid;
		return $this->web_system->get_site($siteid);
	}


	 #############################
	# Returns a pointer to a page
	function &get_page($pageid=0) {
		if(!$pageid) return $this; # Idiot
		return $this->web_system->get_page($pageid);
	}


	 ##########################################
	# Returns a URL that'll point to this page
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function get_url($start_query_string=false,$force_secure=0) {

		$url = $this->web_system->get_page_url($this->siteid,$this->id,$force_secure);
		if ($start_query_string) {
			# if there is a question mark then append an '&' otherwise append a '?'
			$url .= (strchr($url, '?')) ? '&' : '?';
		}#end if
		return $url;
	}

	 ####################################################
	# Returns a relative HREF to this page on the front
	function get_href($start_query_string=false) {
		$href = $this->web_system->get_page_href($this->siteid,$this->id);
		if ($start_query_string) {
			# if there is a question mark then append an '&' otherwise append a '?'
			$href .= (strchr($href, '?')) ? '&' : '?';
		}#end if
		return $href;
	}

	 #########################################################################
	# Returns a href to a file associated with this site (e.g. a custom image)
	function get_file_href($filename='') {
		# if we are public then anyone can view our files so no piping
		if ($this->effective_unrestricted()) {
			return data_href("page/$this->id/$filename");
		} else {
			$site = &$this->get_site();
			return (($GLOBALS['ABSOLUTE'])?$site->get_url(true)."mysource_action=send_file&type=page&s=$this->siteid&p=$this->id&file=$filename": $site->get_href(true)."mysource_action=send_file&type=page&s=$this->siteid&p=$this->id&file=$filename");
		}
	}


	 #############################################
	# Returns a the href to this object's backend
	function get_backend_href() {
		global $EDIT_DIR;
		return "$EDIT_DIR/page.php?p=$this->id";
	}

	 #############################################
	# Returns a the url to this object's backend
	function get_backend_url() {
		$system_config = &get_system_config();
		$site = &$this->get_site($this->siteid);
		return rtrim($site->get_url(),'/')."/$system_config->backend_suffix/page.php?p=$this->id";
	}

	 ###################################################
	# Returns a reference the a page template object
	function &get_template() {
		$class_name = 'page_template_'.$this->template;
		if(get_class($this->page_template) != $class_name) {
			global $XTRAS_PATH;
			if (!is_file("$XTRAS_PATH/page/templates/$this->template/$this->template.inc")) return false;
			include_once("$XTRAS_PATH/page/templates/$this->template/$this->template.inc");
			$this->page_template = new $class_name($this->id);
		}
		return $this->page_template;
	}

	 ##########################################
	# Return a reference to a file object
	function &get_file($fileid=0) {
		if (!$fileid) return false;
		return $this->web_system->get_file($fileid);
	}

	/**
	* Updates a bunch of page settings in the db and updates the page index cache for this page
	*
	* @param array $settings The key is the field name and the data is the setting to set it to
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return string
	*/
	function update_page_settings($settings=array(), $save_page_index_cache=true) {
		$query = 'UPDATE page SET ';
		$first_set = true;
		foreach($settings as $column_name => $setting) {
			eval("\$this->$column_name = \$setting;");
			if (!$first_set) {
				# So any settings after have the comma in it
				$column_name = ', '.$column_name;
			} else {
				$first_set = false;
			}
			$query .= $column_name.'=\''.addslashes($setting).'\'';
		}
		$query .= ' WHERE pageid=\''.$this->id.'\'';
		$db = &$this->get_db();
		$db->update($query);
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, $settings, $save_page_index_cache);
		$this->clear_cache();
		return 'Page Settings updated';
	}

	 ##########################################
	# Changes the name of the page
	function set_name($name='',$short_name='') {
		if(!$short_name) $short_name = $name;
		if(!$name) return 'Your page must have a name.';
		if(!$short_name) return 'Your page must have a short name.';
		if($name == $this->name && $short_name == $this->short_name) return '';
		$db = &$this->get_db();
		$this->name = $name;
		$this->short_name = $short_name;
		$db->update("UPDATE page SET name='".addslashes($this->name)."', short_name='".addslashes($this->short_name)."' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('name' => $this->name, 'short_name' => $this->short_name), true);
		$this->clear_cache();
		return "Page renamed to '$this->name' (shortened to '$this->short_name').";
	}

	 ##########################################
	# Set Page Notes
	function set_page_notes($page_notes) {
		if(!$page_notes || $page_notes == $this->page_notes) return '';
		$db = &$this->get_db();
		$this->page_notes = $page_notes;
		$db->update("UPDATE page SET page_notes='".addslashes($this->page_notes)."' WHERE pageid='$this->id'");
		$this->clear_cache();
		return "Page notes updated";
	}

	 ##########################################
	# Changes the description of the page
	function set_description($description='') {
		if($description == $this->description) return '';
		$db = &$this->get_db();
		$this->description = $description;
		$db->update("UPDATE page SET description='".addslashes($this->description)."' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('description' => $this->description), true);
		$this->clear_cache();
		return "Description updated for page '$this->name'.";
	}


	 ####################################
	# Changes the keyword set for a page
	function set_keywords($keywords='', $process_duplicates=false) {
		if($keywords == $this->keywords && !$process_duplicates) return '';
		if($process_duplicates) {
			$keywords = retrieve_keywords($keywords,"$this->name $this->short_name $this->description");
			if($keywords == $this->keywords) return '';
		}
		$db = &$this->get_db();
		$this->keywords = $keywords;
		$db->update("UPDATE page SET keywords='".addslashes($this->keywords)."' WHERE pageid='$this->id'");
		$this->clear_cache();
		return "Keywords updated for page '$this->name'.";
	}


	 ##########################################
	# Changes the default languages of the site
	function set_default_languages($default_languages='') {
		if($default_languages == $this->default_languages) return '';
		$db = &$this->get_db();
		$this->default_languages = $default_languages;
		$db->update("UPDATE page SET default_languages='".addslashes($this->default_languages)."' WHERE pageid='$this->id'");
		$this->clear_cache();
		if($this->default_languages) {
			$languages_config = &get_config('languages');
			$desc = $languages_config->name_list($this->default_languages);
		} else {
			$desc = 'System Default';
		}
		return "Default languages for page '$this->name' set to: $desc.";
	}


	 ##########################################
	# Changes the default languages of the site
	function set_default_charset($default_charset='') {
		if($default_charset == $this->default_charset) return '';
		$db = &$this->get_db();
		$this->default_charset = $default_charset;
		$db->update("UPDATE page SET default_charset='".addslashes($this->default_charset)."' WHERE pageid='$this->id'");
		$this->clear_cache();
		if($this->default_charset) {
			$charsets_config = &get_config('charsets');
			return "Default character set for site '$this->name' set to {$charsets_config->charsets[$this->default_charset]}.";
		} else {
			return "Default character set for page '$this->name' set to the System Default.";
		}
	}


	 #########################################################
	# Returns the effective default character set of the site
	function &get_effective_default_charset() {
		if($this->default_charset) {
			return $this->default_charset;
		} else {
			$site = &$this->get_site();
			return $site->get_effective_default_charset();
		}
	}

	 #########################
	# Changes the page template
	function set_template($new_template='', $update_page_table=true) {
		if(!$new_template) return 'Unable to set new template: not specified';
		if($new_template == $this->template) return '';
		if($this->template) {
			$template = &$this->get_template();
			$message = $template->change($new_template);
		} else {
			global $XTRAS, $XTRAS_PATH;
			if (!is_file("$XTRAS_PATH/page/templates/$new_template/$new_template.inc")) return 'Template does not exist';

			if(!$XTRAS->name('page/templates',$new_template)) return "New template not found for page '$this->name'.";
			include_once("$XTRAS_PATH/page/templates/$new_template/$new_template.inc");
			$class_name = 'page_template_'.$new_template;
			$this->page_template = new $class_name(0);
			$message .= $this->page_template->create($this->id)."\n";
		}
		$this->template = $new_template;
		if ($update_page_table) {
			$db = &$this->get_db();
			$db->update("UPDATE page SET template='$this->template' WHERE pageid='$this->id'");
		}
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('template' => $this->template), true);
		$this->clear_cache();
		return $message;
	}

	 ########################################
	# Sets whether the page is hidden or not
	function set_visible($visible=false) {
		if($visible) $visible=1;
		else $visible = 0;
		if($visible == $this->visible) return '';
		$this->visible = $visible;
		$db = &$this->get_db();
		$db->update("UPDATE page SET visible='$visible' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('visible' => $this->visible), true);
		$this->clear_cache();
		return "Page '$this->name' is ".(($visible)?"now":"no longer")." visible in the menu.";
	}

	 ##############################################
	# Sets whether the page is SSl encrypted or not
	function set_usessl($usessl=false) {
		if($usessl) $usessl=1;
		else $usessl=0;
		if($usessl == $this->usessl) return '';
		$this->usessl = $usessl;
		$db = &$this->get_db();
		$db->update("UPDATE page SET usessl='$usessl' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('usessl' => $this->usessl), true);
		$this->clear_cache();
		return "Page '$this->name' is ".(($usessl)?"now":"no longer")." SSL encrypted.";
	}

	 ####################################################
	# Set the last status that this page has used
	function set_next_action($next_action='') {
		$db = &$this->get_db();
		$this->next_action = $next_action;
		$db->update("UPDATE page SET next_action='$next_action' WHERE pageid='$this->id'");
		$this->clear_cache();
		return "Next action change set to $next_action for page '$this->name'.";
	}


	 #######################################################
	# Get actions for this page
	# Can be restricted by type, and date range
	function &get_actions($action='',$startdate='',$enddate='') {
		$db = &$this->get_db();
		$sql = "SELECT date,action,action_value FROM page_action WHERE pageid='$this->id'".(($action)?" AND action='$action'":'').(($startdate)?" AND date >= '$startdate'":'').(($enddate)?" AND date <= '$enddate'":'').' ORDER BY date';
		return $db->associative_array($sql);
	}

	 #############################################################
	# Given a datetime.....return the next status
	function get_next_status($datetime='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$statii = &$this->get_actions('status');
		$dates = array_keys($statii);
		if(!is_array($dates)||empty($dates)) return '';
		foreach($dates as $date) {
			if($date > $datetime) {
				return $statii[$date]['action_value'];
			}
		}
		return '';
	}

	 ###################################################
	# Given a datetime..return the status before this
	function get_last_status($datetime='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$statii = &$this->get_actions('status');
		$dates = array_keys($statii);
		if(!is_array($dates)||empty($dates)) return '';
		rsort($dates);
		foreach($dates as $date) {
			if($datetime > $date) {
				return $statii[$date]['action_value'];
			}
		}
		return '';
	}

	 #############################################
	# Given a datetime...return the next parentid
	function get_next_parentid($datetime='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$parentids = &$this->get_actions('parentid');
		$dates = array_keys($parentids);
		if(!is_array($dates)||empty($dates)) return '';
		foreach($dates as $date) {
			if($date > $datetime) {
				return $parentids[$date]['action_value'];
			}
		}
		return '';
	}

	 ###################################################
	# Given a datetime...return the parentid before this
	function get_last_parentid($datetime='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$parentids = &$this->get_actions('parentid');
		$dates = array_keys(array_reverse($parentids));
		if(!is_array($dates)||empty($dates)) return '';
		rsort($dates);
		foreach($dates as $date) {
			if($datetime > $date) {
				return $parentids[$date]['action_value'];
			}
		}
		return '';
	}
	 #############################################################
	# Given a datetime.....return the date of the next status
	function get_next_action_date($datetime='',$action='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$actions = &$this->get_actions($action);
		$dates = array_keys($actions);
		if(!is_array($dates)||empty($dates)) return '';
		foreach($dates as $date) {
			if($date > $datetime) {
				return $date;
			}
		}
		return '';
	}

	 #############################################################
	# Given a datetime..return the date of the status before this
	function get_last_action_date($datetime='',$action='') {
		if(!$datetime) $datetime = date('Y-m-d H:i:s');
		$actions = &$this->get_actions($action);
		$dates = array_keys(array_reverse($actions));
		if(!is_array($dates)||empty($dates)) return '';
		foreach($dates as $date) {
			if($datetime > $date) {
				return $date;
			}
		}
		return '';
	}

	 #######################################
	# Add a status of the page
	# This can be in the future or right now
	function add_status($new_status='',$datetime='', $save_page_index_cache=true) {
		global $PAGE_STATUSES;
		$new_status = strtoupper($new_status);
		if(!$PAGE_STATUSES[$new_status]) return Array('Unrecognised status. Unable to change.', 0);
		$db = &$this->get_db();
		$session = &get_mysource_session();
		$next_status = $this->get_next_status($datetime);
		$last_status = $this->get_last_status($datetime);
		$reject_future = false;
		$reject_past   = false;
		switch($new_status) {
			case 'L':
				if(in_array($next_status,array('P'))) $reject_future = true;
				break;
			case 'U':
				if(in_array($last_status,array('R'))) $reject_past = true;
				elseif(in_array($next_status,array('E'))) $reject_past = true;
				break;
			case 'D':
				if(in_array($last_status,array('R'))) $reject_past = true;
				elseif(in_array($next_status,array('P','R','E'))) $reject_future = true;
				break;
			case 'A':
				if(in_array($last_status,array('R'))) $reject_past = true;
				elseif(in_array($next_status,array('P','R','E'))) $reject_future = true;
				break;
			case 'P':
				if($last_status == 'R' || $last_status == 'L' || $last_status == 'D' || $last_status == 'A' || $last_status == 'E') $reject_past = true;
				elseif(in_array($next_status,array('R','E'))) $reject_future = true;
				break;
			case 'R':
				if(in_array($last_status,array('P','U','D','A','E'))) $reject_past = true;
				elseif(in_array($next_status,array('P','U','D','A','E')))   $reject_future = true;
				break;
			case 'E':
				if(in_array($last_status,array('P','U','D','R','A'))) $reject_past = true;
				elseif(in_array($next_status,array('P','R'))) $reject_future = true;
				break;
		}
		# If an error message is generated, then lets return it
		if($reject_past)       return Array('Cannot set the status to '.$PAGE_STATUSES[$new_status].' as this page '.(($datetime)?'will be':'is').' '.$PAGE_STATUSES[$last_status], 0);
		elseif($reject_future) return Array('Cannot set the status to '.$PAGE_STATUSES[$new_status].' as this page is about to be '.$PAGE_STATUSES[$next_status], 0);
		$site = &$this->get_site();
		# If no datetime, then lets change the status now
		if(!$datetime) {
			list($result,$message,$redirect_pageid) = $this->process_status_change($new_status,$last_status);
			if($result) {
				$db->insert("INSERT INTO page_action (pageid,date,action,action_value,userid) VALUES ('$this->id',now(),'status','".$new_status." ','".$session->user->id."')");
				$message .= "Page '$this->name' status set to {$PAGE_STATUSES[$new_status]}.";
				# Alter the pages status in the page index. If we are creating a new page don't worry that this entry doesn't already exist. The create will take care of it in the end.
				$site->update_page_index_status($this->id, $new_status, $save_page_index_cache);
			}
		} else {
			if($datetime <= date('Y-m-d H:i:s')) return array('Unable to add past status.','');
			$statii = &$this->get_actions('status');
			if($statii[str_replace(':[0-9][0-9]$','',$datetime)]) return array('A future status is already set this for this time.');
			$db->insert("INSERT INTO page_action (pageid,date,action,action_value,userid,log) VALUES ('$this->id','$datetime','status','".$new_status."','".$session->user->id."','')");
			$next_action = $this->get_next_action_date(date('Y-m-d H:i:s'));
			$this->set_next_action($next_action);
			$message = "Future status {$PAGE_STATUSES[$new_status]} added to this page '$this->name'.";
		}
		$this->restrict_data_path();
		$this->clear_cache();
		return array($message,$redirect_pageid);
	}

	 ########################
	# Remove a future status
	function remove_status($datetime='') {
		global $PAGE_STATUSES;
		$db = &$this->get_db();
		if($datetime <= date('Y-m-d H:i:s')) 'Unable to delete past status.';
		# There are some rules which prevent you from changin statuses willy nilly....
		$next_status = $this->get_next_status($datetime);
		$last_status = $this->get_last_status($datetime);

		switch($last_status) {
			case 'L':
				if(in_array($next_status,array('P'))) $reject = true;
				break;
			case 'U':
				if(in_array($last_status,array('R','E'))) $reject = true;
				break;
			case 'D':
				if(in_array($next_status,array('P','R','E'))) $reject = true;
				break;
			case 'A':
				if(in_array($next_status,array('P','R','E'))) $reject = true;
				break;
			case 'P':
				if(in_array($next_status,array('R','E'))) $reject = true;
				break;
			case 'R':
				if(in_array($next_status,array('P','U','D','A','E')))   $reject = true;
				break;
		}

		# If an error message is generated, then lets return it
		if($reject) return 'Cannot remove this status as this page will then have a status of '.$PAGE_STATUSES[$last_status].' before it is set to go '.$PAGE_STATUSES[$next_status];

		if($db->delete("DELETE FROM page_action WHERE pageid='$this->id' AND date='$datetime' AND action='status'")) {
			$next_action = $this->get_next_action_date(date('Y-m-d H:i:s'));
			$this->set_next_action($next_action);
			$this->clear_cache();
			return 'Future page status deleted.';
		} else return 'Unable to delete future page status.';
	}

	 ######################################################
	# Changes the pages status - can get complicated
	# Return true if the status was changed, otherwise false
	function process_status_change($new_status='',$old_status='') {
		global $PAGE_STATUSES;
		# If no old status passed in then lets use the pages current status
		if(!$old_status) {
			$old_status = $this->status();
		}
		# If we are not changing status, then return, else process the status change and set the last used status
		if($old_status == $new_status) {
			return array(false,'Status already set to '.$PAGE_STATUSES[$new_status]);
		}
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		$session = &get_mysource_session();

		switch($new_status) {
			case 'U': # Under construction
				return array(true);
				break;
			case 'P': # Pending

				$t = $this->web_system->master_email;

				# Lets try and mail all the site administrators
				$users_system= &get_users_system();
				$admin_mail = "The page at:\n\n\t".$this->get_url()."\n\nhas had its status changed to '".$PAGE_STATUSES[$new_status]."'.\n\nCould you please go to the backend at:\n\n\t".$this->web_system->get_backend_url()."/".$this->get_backend_href()."\n\nand process the page accordingly.";
				//send email to site admins
				if (!empty($site->adminids_parameters)) {
					while(list($id, $unserialized)=each($site->adminids_parameters)) {
						$parameters = unserialize($unserialized);
						$user = $users_system->get_user($id);
						if (array_key_exists('email_adminids',$parameters)) $email_flag = $parameters['email_adminids'];
						else $email_flag=0;
						if ($email_flag) {
							if($user->email) {
								mail($user->email,'MySource Page Status Change',$admin_mail,'From: '.$session->user->email);
							}
						}
					}
				}
				// turning off the feature to email super admins indefinetely or at least until the parameter thing is sorted out
				/*else {
					mail($this->web_system->master_email,"MySource Page Status Change",$admin_mail,"from: ".$session->user->email);
					}*/
				//send email to page admins
				if (!empty($this->adminids_parameters)) {
					while(list($id, $unserialized)=each($this->adminids_parameters)) {
						$parameters = unserialize($unserialized);
						$user = $users_system->get_user($id);
						if (array_key_exists('email_adminids',$parameters)) $email_flag = $parameters['email_adminids'];
						else $email_flag=0;
						if ($email_flag) {
							if($user->email) {
								mail($user->email,'MySource Page Status Change',$admin_mail,'From: '.$session->user->email);
							}
						}
					}
				}
				return array(true);
				break;
			case 'L': # Live
				$db = &$this->get_db();
				if($old_status == 'R') { # cancelling a safe-edit page.
					if($replacementid = $db->single_element("SELECT pageid FROM page WHERE replaceid='$this->id'")) {
						# We may not get in here, that's okay .. see the elseif for a reason
						$replacement_page = &$this->get_page($replacementid);
						$message .= $replacement_page->delete()."\n";
						unset($replacement_page);
						$this->clear_cache();
						return array(false,trim(ereg_replace("[\r\n]+","\n",$message)),$this->id);
					}
					# Even if we can't really cancel the edit, we need to do this.
					# So we get all our tabs etc back, let's clear our cache.
					$this->clear_cache();

				} elseif($this->replaceid) { # Replacing a SafeEdit!

				/*
					We aren't going to do any OO stuff here, it's all by hand.
					Procedure is :
					- Set the old pageid to 0
					- Set the new page to the old pages' id.
					- Update site_page_index - stuff it, just clear it!
					- Copy directory over to the old one.. In case we uploaded any new files.
					- Fix up files so they keep their id's from before.
					- Remap links in this bodycopy so it points to the old files again.
					- Delete anything we stuffed.

					Since we're doing this from the under construction version of a safe-edit page ..
					oldpageid (the current live one) = this->replaceid
					newpageid (the u/c version of the page) = me = this->id
				*/

					# Where to redirect us after all this happens.
					$replaceid = $this->id;

					# So we can clear it's cache etc.
					$old_page = &$this->get_page($this->replaceid);

					$db = &$this->get_db();
					$qry = "UPDATE page SET pageid='0' WHERE pageid='$this->replaceid'";
					if ($db->update($qry)) {
						# Move the file references out of the way.
						$qry = "UPDATE file SET pageid='0' WHERE pageid='$this->replaceid'";
						$db->update($qry);

						# Move the template out of the way.
						$old_template = &$old_page->get_template();
						$old_template->newid('0');

						# Clear the old directory
						clear_directory($old_page->data_path);
						# Now remove it, so we can just plug the new one in instead - a simple rename of the directories will do.
						rmdir($old_page->data_path);

						# Now everything is out of the way, let's move in ourselves.
						# Firstly the page itself.
						$qry = "UPDATE page SET pageid='$this->replaceid', create_date='" . addslashes($old_page->create_date) . "', replaceid='0' WHERE pageid='$this->id'";
						$db->update($qry);

						# Next, the customisations.
						# Move the old one out.
						$qry = "UPDATE site_design_customisation SET customisationid='0.page' WHERE customisationid='" . $old_page->id . ".page'";
						$db->update($qry);

						# Move us back in.
						$qry = "UPDATE site_design_customisation SET customisationid='" . $old_page->id . ".page' WHERE customisationid='" . $this->id . ".page'";
						$db->update($qry);

						# Clear the customisation cache - all of it.
						global $CACHE;
						$CACHE->clear($this->id.'.page', 'custom_site_design');
						$CACHE->clear($old_page->id.'.page', 'custom_site_design');
 
						# Now the file references.
						# We only do part of this in the database, we need to call the file object
						# So it can clear it's cache for each of the files.
						$qry = "SELECT fileid, replaceid FROM file WHERE pageid='$this->id'";
						$file_list = $db->associative_array($qry);
						if (!empty($file_list)) {

							# Delete the old file references.
							$qry = "DELETE FROM file WHERE fileid IN (".implode(",", $file_list).")";
							$db->delete($qry);
							foreach($file_list as $fid => $replaceid) {
								# Let's move the file references.
								if ($replaceid) {
									$qry = "UPDATE file SET pageid='$this->replaceid', fileid='$replaceid' WHERE pageid='$this->id' AND fileid='$fid'";
									$clear_fileid = $replaceid;
								} else {
									$qry = "UPDATE file SET pageid='$this->replaceid' WHERE pageid='$this->id' AND fileid='$fid'";
									$clear_fileid = $fid;

									# This seems weird but for when we remap (just below) we need to keep the same file id. Otherwise the file seems to disappear!
									$file_list[$fid] = $fid;
								}
								$db->update($qry);

								# Now we've run the update, we need to clear it's cache.
								$file = & new File($clear_fileid);
								$file->clear_cache($clear_fileid);
								unset($file);
							}
						}

						# Now the template - the content.
						# This is handled by page_template - so it can get over-written if need be.
						# If a page has multiple database tables for whatever reason, I can't tell from here. But the template can because it will have an over-riding function :)
						$template = &$this->get_template();
						$template->newid($this->replaceid);

						# Remap the file references back to their originals.
						$dupe_map = array();
						$dupe_map['file'] = $file_list;
						$dupe_map['page'] = array($replaceid, $this->id);

						$template->remap_link_ids($dupe_map);

						if (!rename($this->data_path, $old_page->data_path)) {
							$message .= "Unable to move data path\n";
						} else {
							rmdir($this->data_path);
						}

						$site = &$this->get_site();
						$site->clear_page_index();

						$replaceid = $this->replaceid;

						# Now let's clean up after ourselves.
						$qry = "DELETE FROM page WHERE pageid='0'";
						$db->delete($qry);
						$qry = "DELETE FROM file WHERE pageid='0'";
						$db->delete($qry);

						$qry = "DELETE FROM site_design_customisation WHERE customisationid='0.page'";
						$db->delete($qry);

						$old_template->delete();
						
						# Lastly, the new page must forget it's safe-editing.
						# Since we're now "old_page" we need to add a status to ourselves :)
						$old_page->add_status('L');
						$old_page->clear_cache();
					} else {
						$message .= "Unable to make safe-edited page live.";
						return array(false,trim(ereg_replace("[\r\n]+","\n",$message)),$replaceid);
					}
					return array(true,trim(ereg_replace("[\r\n]+","\n",$message)),$replaceid);
				}
				return array(true);
				break;
			case 'R': # Live, to be Replaced

				$session = &get_mysource_session();

				$dupe_map = &$session->get_var('dupe_map');
				$dupe_message = &$session->get_var('dupe_message');

				$replacementid = $this->dupe($this->siteid,$this->parentid,true,true,true,false,true,$dupe_map,$dupe_message);

				$message .= $dupe_message."\n";
				$replacement_page = &$this->get_page($replacementid);
				$message .= $replacement_page->set_replaceid($this->id)."\n";
				$message .= $replacement_page->set_orderno($this->orderno)."\n";
				$replacement_page->clear_cache();

				$site = &$this->get_site();
				$site->update_page_index_status($this->id, 'R', true);

				$site->update_page_index_settings($replacementid, array('replaceid' => $this->id), true);

				$session->unset_var('dupe_map');
				$session->unset_var('dupe_message');

				return array(true,trim(ereg_replace("[\r\n]+","\n",$message)),$replacement_page->id);
				break;
			case 'D': # Disabled
				if($this->replaceid) return array(false,'This page is a designated SafeEdit replacement page and cannot be disabled. Please cancel the SafeEdit.');
				return array(true);
				break;
			case 'A': # Archived
				if($this->replaceid) return array(false,'This page is a designated SafeEdit replacement page and cannot be archived. Please cancel the SafeEdit.');
				return array(true);
				break;
			case 'E': # Up for Review
				# Lets try and mail all the site administrators
				$users_system= &get_users_system();
				$admin_mail = "The page at:\n\n\t".$this->get_url()."\n\nhas had it's status changed to '".$PAGE_STATUSES[$new_status]."'.\n\nYou can now go to the backend at:\n\n\t".$this->web_system->get_backend_url()."/".$this->get_backend_href()."\n\nand process the page accordingly.";
				//send email to site admins
				if (!empty($site->adminids_parameters)) {
					while(list($id, $unserialized)=each($site->adminids_parameters)) {
						$parameters = unserialize($unserialized);
						$user = $users_system->get_user($id);
						if (array_key_exists('email_adminids',$parameters)) $email_flag = $parameters['email_adminids'];
						else $email_flag=0;
						if ($email_flag) {
							if($user->email) {
								mail($user->email,'MySource Page Status Change',$admin_mail,'From: '.$session->user->email);
							}
						}
					}
				}
				// turning off the feature to email super admins indefinetely or at least until the parameter thing is sorted out
				//else mail($this->web_system->master_email,"MySource Page Status Change",$admin_mail,"from: ".$session->user->email);
				//send email to page admins
				if (!empty($this->adminids_parameters)) {
					while(list($id, $unserialized)=each($this->adminids_parameters)) {
						$parameters = unserialize($unserialized);
						$user = $users_system->get_user($id);
						if (array_key_exists('email_adminids',$parameters)) $email_flag = $parameters['email_adminids'];
						else $email_flag=0;
						if ($email_flag) {
							if($user->email) {
								mail($user->email,'MySource Page Status Change',$admin_mail,'From: '.$session->user->email);
							}
						}
					}
				}
				return array(true);
				break;
			default:
				return array(false);
				break;
		}
	}

	 #################################################################################
	# Sets the replacement id - the page that this page wll replace when it goes live
	function set_replaceid($replaceid=0) {
		$replaceid = max(0,(int) $replaceid);
		if($replaceid == $this->replaceid) return '';
		$this->replaceid = $replaceid;
		$db = &$this->get_db();
		$db->update("UPDATE page SET replaceid='$replaceid' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('replaceid' => $this->replaceid), true);
		$this->clear_cache();
		if($replaceid) {
			return "Page ID $this->id marked as replacement.";
		} else {
			return "Page '$this->name' has replaced SafeEdited page.";
		}
	}


	 ################################################################
	# Explicitly sets the orderno of a page - usually done elsewhere
	function set_orderno($orderno=0) {
		$orderno = (int)$orderno;
		if($orderno == $this->orderno) return '';
		$this->orderno = $orderno;
		$db = &$this->get_db();
		$db->update("UPDATE page SET orderno='$orderno' WHERE pageid='$this->id'");
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('orderno' => $this->orderno), true);
		$this->clear_cache();
		return "Page '$this->name' been reordered.";
	}

	/**
	* Sets the Sub pages auto-reordering scheme
	*
	* @access public
	* @param string $scheme - the auto ordering scheme
	* @return string
	*/
	function set_subpage_auto_order($scheme=0) {
		if ($scheme == $this->subpage_auto_order) return '';
		if (is_string($scheme) || is_null($scheme)) {
			$db = &$this->get_db();
			$this->subpage_auto_order = $scheme;
			$db->update("UPDATE page SET subpage_auto_order=".($this->subpage_auto_order?"'".$this->subpage_auto_order."'":'NULL')." WHERE pageid='$this->id'");
			$this->clear_cache();
			return "Page $this->name ID $this->id - Auto Re-ordering scheme is now updated";
		}
		return "Failed to update Auto Re-ordering scheme for Page $this->name ID $this->id";
	}


	 ###########################################
	# Update the list URLs pointing to this site
	function update_dirs($new_dirs=array(), $save_page_index_cache=true) {
		array_clear_blanks($new_dirs);
		$db = &$this->get_db();
		# Remove the old ones
		if (!empty($this->dirs)) {
			foreach($this->dirs as $dir) {
				if(!in_array($dir,$new_dirs)) {
					$message .= $this->_remove_dir($dir)."\n";
				}
			}
		}
		$dir_count = 0;
		$old_this_dirs = $this->dirs;
		$this->dirs = array();
		if (!empty($new_dirs)) {
			foreach($new_dirs as $dir) {
				# Um this needs more useful checking too
				if(in_array($dir,$old_this_dirs)) {
					if($db->update("UPDATE page_dir SET orderno='$dir_count' WHERE dir='".addslashes($dir)."' AND pageid='$this->id'")) {
						$reordered = true;
					}
					$this->dirs[] = $dir;
				} else {
					$message .= $this->_add_dir($dir,$dir_count)."\n";
				}
				$dir_count++;
			}
		}

		if($reordered) {
			$message .= "Virtual paths reordered on page '$this->name'.\n";
		}
		$site = &$this->get_site();
		$site->update_page_index_dirs($this->id, $this->dirs, $save_page_index_cache);
		$this->clear_cache();
		return trim($message);
	}


	 #############################################
	# Sets a dir as pointing to a particular page
	# SHOULD NEVER BE CALLED EXCEPT BY UPDATE_DIRS (above)
	function _add_dir($dir='',$orderno=0) {
		# Check this thing out
		$dir = str_replace("&", " and ", $dir);

		# This gets rid of european characters and replaces them with english chars.
		$funny_characters = array('à','À','á','Á','â','Â','ã','Ã','ä','Ä','å','Å','æ', 
		'Æ','ç','Ç','è','È','é','É','ê','Ê','ë','Ë','ì','Ì','í','Í','î','Î','ï','Ï','ñ', 
		'Ñ','ò','Ò','ó','Ó','ô','Ô','õ','Õ','ö','Ö','ø','Ø','ù','Ù','ü','Ü','ú','Ú','ÿ'); 
		$notsofunny_characters = array('a','A','a','A','a','A','a','A','a','A','a','A','a', 
		'A','c','C','e','E','e','E','e','E','e','E','i','I','i','I','i','I','i','I','n', 
		'N','o','O','o','O','o','O','o','O','o','O','o','O','u','U','u','U','u','U','y'); 
		$dir = str_replace($funny_characters, $notsofunny_characters, $dir);

		$dir = ereg_replace("[ \t\n\r]+","_",$dir); # Spaces! Grr!
		# http://www.w3.org/Addressing/URL/5_URI_BNF.html
		$dir = ereg_replace("[^a-zA-Z0-9\-\_\@\.\&\!\*\(\)\,]","",$dir);
		$dir = str_replace("\\","",$dir); # Backslashes! GRR!
		if(!$dir) return "";
		$site = &$this->get_site();
		$site_index = &$site->get_page_index();
		$unique = false;
		while(!$unique) {
			$unique = true;
			if (!empty($site_index[$this->parentid]['childids'])) {
				foreach($site_index[$this->parentid]['childids'] as $siblingid) {
					if(is_array($site_index[$siblingid]['dirs']) && in_array($dir,$site_index[$siblingid]['dirs'])) { # Already exists!
						$dir = increment_filename($dir);
						$unique = false;
						break;
					}
				}
			}
		}
		$db = &$this->get_db();
		if($this->web_system->register_page_dir($this->siteid,$this->id,$dir)) {
			if ($db->insert("INSERT INTO page_dir (pageid,dir,orderno) VALUES('$this->id','".addslashes($dir)."','$orderno')")) {
				$this->dirs[] = $dir;
				$site_index[$this->id]['dirs'] = $this->dirs;
				return "Added virtual path '$dir' to page '$this->name.'";
			}
			$this->web_system->unregister_page_dir($this->siteid,$this->id,$dir);
		}
		return "Could not add the virtual path '$dir' to page '$this->name'.";
	}


	 #####################################################
	# Stops a URL from pointing to a particular site
	# SHOULD NEVER BE CALLED EXCEPT BY UPDATE_DIRS (above)
	function _remove_dir($dir='') {
		if(!$dir) return '';
		$db = &$this->get_db();
		for(reset($this->dirs); is_int($i = key($this->dirs)); next($this->dirs)) {
			if($dir == $this->dirs[$i]) {
				unset($this->dirs[$i]);
				$removed = 1;
				break;
			}
		}
		if ($removed && $db->delete("DELETE FROM page_dir WHERE pageid='$this->id' AND dir='".addslashes($dir)."'")) {
			$this->web_system->unregister_page_dir($this->siteid,$this->id,$dir);
			return "Removed virtual path '$dir' from page '$this->name'.";
		}
		return "Could not remove the virtual path '$dir' from page '$this->name'.";
	}

	 ########################################################
	# Okay, lets add a future move the the page action table
 	function add_parentid($parentid=0,$datetime='') {
		if(!$parentid && $parentid != '0') return '';
		if($parentid == $this->id) return 'You can\'t make a page a subpage of itself.';

		$last_parentid = $this->get_last_parentid($datetime);
		$last_parent = &$this->get_page($last_parentid);

		if($last_parentid == $parentid) return 'You tried to move a page to the same position.';
		$subpageids = $last_parent->get_all_subpageids();
		if(in_array($parentid,$subpageids)) return 'You tried move a page underneath one of its subpages.';
		$db = &$this->get_db();
		$site = &$this->get_site();
		if($datetime <= date('Y-m-d H:i:s')) return 'Unable to add future page parentid in the past.';
		$parentids = &$this->get_actions('parentid');
		if($parentids[str_replace(':[0-9][0-9]$','',$datetime)]) return 'A future page parentid is already set this for this time.';

		$session = &get_mysource_session();
		$db->insert("INSERT INTO page_action (pageid,date,action,action_value,userid,log) VALUES ('$this->id','$datetime','parentid','$parentid','".$session->user->id."','')");
		$next_action = $this->get_next_action_date(date('Y-m-d H:i:s'));
		$this->set_next_action($next_action);
		$site->clear_page_index();
		$this->clear_cache();
		return 'Future page parentid added.';

	}

	 ############################################
	# Remove future parentid from this page
	function remove_parentid($datetime='') {
		$db = &$this->get_db();
		if($datetime <= date('Y-m-d H:i:s')) 'Unable to delete past page parentid.';
		if($db->delete("DELETE FROM page_action WHERE pageid='$this->id' AND date='$datetime' AND action='parentid'")) {
			$next_action = $this->get_next_action_date(date('Y-m-d H:i:s'));
			$this->set_next_action($next_action);
			$this->clear_cache();
			return 'Future page parentid deleted.';
		} else return 'Unable to delete future page parentid.';
	}

	function site_move($siteid=0) {
		if ($siteid <= 0) return array(false,'You can\'t move a page to a non-existent site.');

		# Doing a site move
		if($siteid != $this->siteid) {
			# if this page is particpating in SafeEditing, then can't move to another site
			if ($this->status() == 'R' || $this->replaceid) {
				return array(false,'You can\'t move this page to another site, it is participating in a SafeEdit');
			}

			# First, we need to get the current site and the new site
			$site = &$this->get_site();
			$new_site = &$this->get_site($siteid);

			# Check we have access..
			if (!$site->admin_access() || !$new_site->admin_access()) {
				return array(false,"Sorry, you don't have enough access to move this page.");
			}

			$db = &$this->get_db();

			$orderno = $new_site->prepare_page_auto_order_no($new_site->subpage_auto_order, 0, $this->name);

			# Let's update our details first. Since we're moving to the top level, we don't have a parentid or a level.
			$query = "UPDATE page SET siteid='$siteid', parentid=0, level=0, orderno=$orderno WHERE pageid='$this->id'";
			$db->update($query);

			# Grab the subpages first in case the page_index gets stuffed up by the update_dirs call below.
			$subpages = &$this->get_all_subpageids();

			# We'll need these so we can register the url's with the new site.
			$old_dirs = $this->dirs;
			$this->update_dirs(array());

			if (!empty($subpages)) {

				# Clear subpage caches.
				foreach($subpages as $subpageid) {
					$this->clear_cache($subpageid);
					$this->web_system->forget_page($subpageid);
				}

				$subpage_id_list = implode(",",$subpages);
				# Adjust their levels. Parentids aren't touched - the heirarchy is exactly the same as before.
				# We're just moving up in the world
				$query = "UPDATE page SET siteid='$siteid', level=(level-$this->level) WHERE pageid IN(" . $subpage_id_list . ")";
				$db->update($query);
			}

			# Clear all the caches we can find.
			$this->clear_cache();

			$new_site->clear_cache();
			$new_site->clear_page_index(true);

			$site->clear_cache();
			$site->clear_page_index(true);

			# Set the siteid for the update_dirs call.
			$this->siteid = $siteid;

			# Put our virtual paths back.. this time, they're on the new site.
			$this->update_dirs($old_dirs);

			# We need to get the sites to update their site->page_index'es so this works as it should.
			$this->restrict_data_path();

			return array(true,"Page '$this->name' and it's children have been moved from site '".$site->name."' to site '".$new_site->name."'");
		}
	}

	 #############################################################
	# Sets the parentid essentially moves the page.
	function page_move($parentid=0, $save_cache=true) {
		$parentid = max(0,(int) $parentid);

		if($parentid == $this->parentid) return array(MYSOURCE_ERROR_CODE_ERROR,'You can\'t move a page to the same position.');
		$subpageids = $this->get_all_subpageids();
		if($this->id == $parentid) {
			return array(MYSOURCE_ERROR_CODE_ERROR,'You can\'t make a page a subpage of itself. This would destroy the very fabric of the space-time continum.');
		}
		if(in_array($parentid,$subpageids)) {
			return array(MYSOURCE_ERROR_CODE_ERROR,'You tried move a page underneath one of its subpages. This would have been terribly confusing, so it hasn\'t happened.');
		}

		$site  = &$this->get_site();

		$from = '';
		$to   = '';

		$index = &$site->get_page_index();
		if(!isset($index[$parentid])) {
			return array(MYSOURCE_ERROR_CODE_ERROR, "Page '$this->name' could not be moved - specified parent does not exist in site.");
		}

		global $IN_BACKEND;
		if ($IN_BACKEND && !$this->admin_access()) {
			return array(MYSOURCE_ERROR_CODE_ERROR, "Page could not be moved. You don't have admin access access to the current page.");
		}

		$new_parent = &new Page($parentid);
		if ($IN_BACKEND && !$new_parent->admin_access()) {
			unset($new_parent);
			return array(MYSOURCE_ERROR_CODE_ERROR, "Page could not be moved. You don't have admin access access to the new page.");
		}
		unset($new_parent);
		
		# Okay, before we do this, let's unregister all our urls
		$old_dirs = $this->dirs;
		$this->update_dirs(array(), $save_cache);

		if($this->parentid) {
			$from .= "beneath page '" . $index[$this->parentid]['short_name']."'";
		} else {
			$from .= 'at the site root';
		}
		if ($parentid == 0) {
			$to .= 'at the site root';
			$parent_obj = &$site;
			$level = 0;
		} else {
			$to .= "beneath page '" . $index[$parentid]['short_name']."'";
			$parent_obj = &$this->get_page($parentid);
			$level = $index[$parentid]['level'] + 1;
		}

		$db = &$this->get_db();

		$this->orderno = $site->prepare_page_auto_order_no($parent_obj->subpage_auto_order, $parentid, $this->name);

		$site->move_page_in_page_index($this->id, $this->parentid, $parentid, $this->orderno, $save_cache);
		$this->parentid = $parentid;
		$db->update("UPDATE page SET parentid='$parentid', level='$level', orderno='$this->orderno' WHERE pageid='$this->id'");
		$this->update_childrens_level($this->id, $level);

		$this->restrict_data_path();

		# We always need to clear the page cache after such a change
		$this->clear_cache();

		# Remember what our directory names were
		$this->update_dirs($old_dirs, $save_cache); # If there are any clashes, they should be handled

		# we don't always need to write the cache.
		if ($save_cache) {
			$site->write_page_index_to_cache();
		}

		return array(MYSOURCE_ERROR_CODE_NONE,"Page '$this->name' has been moved from $from, to $to.");
	}

	/**
	* Foreach subpage of $pageid (and it's subpages and so on) it updates the level to be level+1
	*
	* @param  int $pageid Pageid to update all it's childrens levels
	* @param  int $level The level value for $pageid
	* @access public
	* @return array
	*/
	function update_childrens_level($pageid=0, $level=0) {
		$subpageids = $this->get_subpageids($pageid);
		$level++;
		foreach($subpageids as $subpageid) {
			$db = &$this->get_db();
			$db->update("UPDATE page SET level='$level' WHERE pageid='$subpageid'");
			$this->update_childrens_level($subpageid, $level);
		}
	}

	/**
	* Returns a list of the childids of this page. This will ALWAYS return an array
	*
	* @param  int $pageid Pageid to get the children from
	* @access public
	* @return array
	*/
	function get_subpageids($pageid=0) {
		if(!$pageid) $pageid = $this->id;
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		if (is_array($index[$pageid]['childids'])) {
			return $index[$pageid]['childids'];
		} else {
			return array();
		}
	}

	/**
	* Returns a list of ALL the childids of this page or fed in page. This will ALWAYS return an array
	*
	* @param int $pageid The pageid of the page to start from
	* @access public
	* @return array
	*/
	function get_all_subpageids($pageid=0) {
		if(!$pageid) $pageid = $this->id;
		$site  = &$this->get_site();
		$index = &$site->get_page_index();
		$result = $index[$pageid]['childids'];
		if (!is_array($result)) $result = array();
		if (!empty($index[$pageid]['childids'])) {
			foreach($index[$pageid]['childids'] as $childid) {
				$result = array_merge($result,$this->get_all_subpageids($childid));
			}
		}
		return ($result) ? $result : array();
	}

	 ########################################
	# Sets whether the site is public or not
	function set_public($public=false, $save_page_index_cache=true) {
		$public = (int) $public;
		if($public == $this->public) return '';
		$this->public = $public;
		$db = &$this->get_db();
		$db->update("UPDATE page SET public='$public' WHERE pageid='$this->id'");

		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('public' => $this->public), true);
		$this->clear_cache();

		# Tell the site page index about our changes
		$site = &$this->get_site($this->siteid);
		$site->update_page_index_public($this->id, $public, $save_page_index_cache);

		$this->restrict_data_path();

		return "Page '$this->name' is ".(($public)?"now":"no longer")." available to the public.";
	}


	 ###########################################
	# Update the list of allowed grants
	function update_access_grants($new_grants=array(), $save_page_index_cache=true) {
		list($a,$r) = array_compare($new_grants, $this->access_grants);
		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_access_grant($add, $save_page_index_cache=true)."\n";
			}
		}
		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_access_grant($rem)."\n";
			}
		}
		return trim($message);
	}

	 #######################################
	# Adds a new access grant
	function add_access_grant($groupid=0, $save_page_index_cache=true) {
		if(!$groupid || $groupid <= 0) return 'Access group not specified for access granting.';
		if(in_array($groupid,$this->access_grants)) return '';
		$site = &$this->get_site();
		$name = $site->access_groups[$groupid];
		if(!$name) {
			$general_access_groups = &$this->web_system->get_general_access_group_list();
			$name = $general_access_groups[$groupid];
		}
		if(!$name) $name = $groupid;
		$this->access_grants[] = $groupid;
		$db = &$this->get_db();
		if (!$db->insert("INSERT INTO page_access_grant (groupid, pageid) VALUES ('$groupid','$this->id')")) {
			return "Unable to grant access to $name: database error.";
		}
		$site = &$this->get_site();
		$site->update_page_index_access_grants($this->id, $this->access_grants, $save_page_index_cache);
		$this->clear_cache();
		return "Access granted to access group '$name' on page '$this->name'.";
	}


	 #######################################
	# Removes an access grant
	function remove_access_grant($groupid=0) {
		if(!$groupid || $groupid <= 0) return 'Access group not specified for access denying.';
		if(!in_array($groupid,$this->access_grants)) return '';
		$site = &$this->get_site();
		$name = $site->access_groups[$groupid];
		if(!$name) {
			$general_access_groups = &$this->web_system->get_general_access_group_list();
			$name = $general_access_groups[$groupid];
		}
		if(!$name) $name = $groupid;
		$db = &$this->get_db();
		if (!empty($this->access_grants)) {
			foreach($this->access_grants as $k => $id) {
				if ($id == $groupid) {
					unset($this->access_grants[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM page_access_grant WHERE groupid='$groupid' AND pageid='$this->id'")) {
			return "Unable to deny access to $name: database error.";
		}
		$site = &$this->get_site();
		$site->clear_page_index();
		$this->clear_cache();
		return "Access denied to access group '$name' on page '$this->name'.";
	}


	function update_editorids($new_editorids=array(), $new_readonly_editorids=array()) {
		list($a,$r) = array_compare($new_editorids, $this->editorids);
		list($a2,$r2) = array_compare($new_readonly_editorids, $this->readonly_editorids);

		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_editorid($rem)."\n";
			}
		}

		if (!empty($r2)) {
			foreach($r2 as $rem) {
				$message .= $this->remove_editorid($rem)."\n";
			}
		}

		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_editorid($add)."\n";
			}
		}

		if (!empty($a2)) {
			foreach($a2 as $add) {
				$message .= $this->add_editorid($add,1)."\n";
			}
		}
		return trim($message);
	}


	 ########################
	# Adds a new site editor
	function add_editorid($userid=0, $readonly = 0) {
		if(!$userid) return 'Editor ID not specified for assignment.';
		if(in_array($userid,$this->editorids)||in_array($userid,$this->readonly_editorids)||in_array($userid, $this->adminids)) return '';
		$db = &$this->get_db();
		if($readonly) $this->readonly_editorids[] = $userid;
		else          $this->editorids[] = $userid;
		$readonly = (int) $readonly;
		if (!$db->insert("INSERT INTO page_editor (userid, pageid, readonly) VALUES ('$userid','$this->id','$readonly')")) {
			return 'Unable to add page editor: database error.';
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$editor = &$users_system->get_user($userid);
		$session = &get_mysource_session();
		if($session->user->id == $userid) $session->editor_pages[$this->siteid][] = $this->id;
		return $editor->name()." assigned as ".(($readonly)?"read-only ":"read/write ")."page editor for '$this->name'.";
	}


	 ############################
	# Removes a new site editor
	function remove_editorid($userid=0) {
		if(!$userid) return 'Editor ID not specified for removal.';
		if(!in_array($userid,$this->editorids)&&!in_array($userid,$this->readonly_editorids)) return '';
		$db = &$this->get_db();
		if (!empty($this->editorids)) {
			foreach($this->editorids as $k => $id) {
				if ($id == $userid) {
					unset($this->editorids[$k]);
					break;
				}
			}
		}
		if (!empty($this->readonly_editorids)) {
			foreach($this->readonly_editorids as $k => $id) {
				if ($id == $userid) {
					$readonly = 1;
					unset($this->readonly_editorids[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM page_editor WHERE userid='$userid' AND pageid='$this->id'")) {
			return 'Unable to remove page editor: database error.';
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$editor = &$users_system->get_user($userid);
		return $editor->name()."'s  ".(($readonly)?"read-only ":"read/write ")."page editor privelidges have been revoked for '$this->name'.";
	}


	 #######################################
	# update the parameters
		/*
		  Want a standard way to update parameters?, look no further. Pass me the name of the
		  parameter and an array with all userids that had the parameter checkbox CHECKED. I'll
		  loop through them, compare them to the previous values and update if necessary. I might
		  need a bit of tweaking if you want me to work with other form elements.
		*/

	function update_parameter_adminids($parameter_name='', $positives=array()) {
		$users_system = &get_users_system();
		$db = &$this->get_db();
		$message = '';
		if (!empty($this->adminids_parameters)) {
			while(list($id, $params)=each($this->adminids_parameters)) {
				$parameters = unserialize($params);
				//get current value
				if (array_key_exists($parameter_name,$parameters)) $current = $parameters['email_adminids'];
				else $current=0;
				//get checked ids
				if (in_array($id,$positives)) $checked = 1;
				else $checked=0;
				//compare and change if necessary
				if ($current!=$checked) {
					$parameters[$parameter_name] = $checked;
					$serialized_params = serialize($parameters);
					$this->adminids_parameters[$id] = $serialized_params;
					$db->update("UPDATE page_admin SET parameters='".addslashes($serialized_params)."' where userid='$id' AND pageid='$this->id'");
					$username = &$users_system->get_user($id);
					$message .= "$parameter_name for ".$username->name()." is now set to ".(($checked)?"on<BR>\n":"off<BR>\n");
				}
			}
		}
		$this->clear_cache();
		return $message;
	}


	 #####################################
	# Updates list of page administrators
	function update_adminids($new_adminids=array()) {
		list($a,$r) = array_compare($new_adminids, $this->adminids);
		$message = '';
		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_adminid($rem)."\n";
			}
		}

		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_adminid($add)."\n";
			}
		}
		return trim($message);
	}


	 ################################
	# Adds a new page administrator
	function add_adminid($userid=0) {
		if(!$userid) return 'Administrator ID not specified for assignment.';
		//preset some parameters
		$preset_parameters ='';
		$preset_parameters['email_adminids'] = 1;
		$parameters = serialize($preset_parameters);
		$db = &$this->get_db();
		$this->adminids[] = $userid;
		$this->adminids_parameters[$userid] = $parameters;
		if (!$db->insert("INSERT INTO page_admin (userid, pageid, parameters) VALUES ('$userid','$this->id','".addslashes($parameters)."')")) {
			return 'Unable to add page administrator: database error.';
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$administrator = &$users_system->get_user($userid);
		return $administrator->name()." assigned as page administrator for '$this->name'.";
	}


	 ###################################
	# Removes a new page administrator
	function remove_adminid($userid=0) {
		if(!$userid) return 'Administrator ID not specified for removal.';
		if(!in_array($userid,$this->adminids)) return '';
		$db = &$this->get_db();
		if (!empty($this->adminids)) {
			foreach($this->adminids as $k => $id) {
				if ($id == $userid) {
					unset($this->adminids[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM page_admin WHERE userid='$userid' AND pageid='$this->id'")) {
			return 'Unable to remove page administrator: database error.';
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$administrator = &$users_system->get_user($userid);
		return $administrator->name()."'s page administrator privelidges have been revoked for '$this->name'.";
	}



	  ##############################################
	 # Mark a page as updated, and delete its cache
	function updated() {
		$db = &$this->get_db();
		$db->update("UPDATE page SET last_update=now() WHERE pageid='$this->id'");
		$this->last_update = date('Y-m-d h:i:s');
		$this->clear_cache();
		$site = &$this->get_site();
		$site->updated();
	}


	  ############################################################################
	 # Returns an array of pageids representing trunk-to-twig the pages parentage
	function &get_lineage($short_name=true) {
		$name = ($short_name) ? 'short_name' : 'name';
		if(!isset($this->lineage)) {
			$site = &$this->get_site();
			$index= &$site->get_page_index();
			$pageid = $this->id;
			$result = array();
			$result[$pageid] = $index[$pageid][$name];
			while ($pageid = $index[$pageid]['parentid']) {
				$result[$pageid] = $index[$pageid][$name];
			}
			$this->lineage = array_reverse($result,1);
		}
		return $this->lineage;
	}


	 ######################################################
	# Duplicates a page and potentionally all its subpages
	function dupe($siteid=0, $parentid=-1, $ignore_subpages=false, $ignore_page_dirs=false, $ignore_max_pages=false, $num_pages=false, $remap=true, &$dupe_map, &$dupe_message, $save_page_index_cache=true) {
		$session = &get_mysource_session();
		if(!$siteid) $siteid = $this->siteid;
		if($parentid < 0) $parentid = $this->parentid;
		$site = &$this->get_site($siteid);

		# If this is the first page we are duping we need to make sure that the first pages new order id is a maximum of all it's brother pages + 1 otherwise that first new page will have the same orderid as the first duped page which shouldn't be
		if (empty($dupe_map)) {
			$dupe_map = $session->get_var('dupe_map');
			if ($parentid == 0) {
				$parent_obj = &$site;
			} else {
				$parent_obj = &$this->get_page($parentid);
			}
			$db = &$this->get_db();
			$orderno = $site->prepare_page_auto_order_no($parent_obj->subpage_auto_order, $parentid, $this->name);
		} else {
			$orderno = $this->orderno;
		}
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		
		if ($ignore_max_pages) {
			# create the page without checking if this would
			# go beyond the max pages limit for the site
			$new_page =& new Page();
			$new_page->create($this->name,$this->template,$siteid,$parentid, 'U', $save_page_index_cache, $ignore_page_dirs);
		} else {
			list($error_code, $msg, $new_pageid) = $site->create_page($this->name,$this->template,$parentid, 'U', $save_page_index_cache, $ignore_page_dirs);

			if ($error_code) {
				$dupe_message = $msg;
				$session->set_var('dupe_message', $dupe_message);
				return;
			}
			$new_page = &$this->get_page($new_pageid);
		}

		if(!$new_page->id) {
			$dupe_message .= "Page duping failed for page '$this->name'.\n";
			$session->set_var('dupe_message', $dupe_message);
			return;
		}

		# Copy a whole bunch of things to do with this current page to the new page in one go. It's faster and saves database hits
		$settings = array('name' => $this->name, 'short_name' => $this->short_name, 'description' => $this->description, 'keywords' => $this->keywords, 'default_languages' => $this->default_languages, 'default_charset' => $this->default_charset, 'public' => $this->public, 'visible' => $this->visible, 'usessl' => $this->usessl, 'orderno' => $orderno, 'subpage_auto_order' => $this->subpage_auto_order);
		$new_page->update_page_settings($settings);

		$new_access_grants = $this->access_grants;
		if (!empty($new_access_grants)) {
			foreach($new_access_grants as $k => $id) {
				if($newid = $dupe_map['access_group'][$id]) $new_access_grants[$k] = $newid;
			}
		}
		$new_page->update_access_grants($new_access_grants);

		# dupe page and admin ids
		$new_page->update_adminids($this->adminids);
		$new_page->update_editorids($this->editorids,$this->readonly_editorids);

		if (copy_directory($this->data_path, $new_page->data_path)) {
			$dupe_message .= "Page '$this->name' ($this->id) data directory copied successfully.\n";
		} else {
			$dupe_message .= "Page '$this->name' data directory could not be copied!\n";
		}
		if (!empty($this->file_index)) {
			# Dupe files
			foreach($this->file_index as $fileid) {
				$file = &$this->get_file($fileid);
				$file->dupe($new_page->id, $fileid, $dupe_map, $dupe_message);
			}
		}

		# now that we have duped the files link up the imageid
		$new_page->set_image($dupe_map['file'][$this->imageid]);

		# Duplicate the design
		if ($this->designid) {
			$design = &$this->get_design();
			if ($design->save_customisation($new_page->id.".".get_class($this))) {
				$dupe_message .= "Design Customisation Duped.\n";
			}
			$dupe_message .= $new_page->set_design($this->designid)."\n";
		}#end if

		# Dupe the page template
		$template = &$this->get_template();
		$template->dupe($new_page->id, $remap);

		 #######################################
		# if we want the page dirs add them
		if(!$ignore_page_dirs) {
			# Give the new page some directorys
			$new_page->update_dirs($this->dirs,$save_page_index_cache);
		}

		 ###################################
		# Dupe the subpages as well?
		if (!$ignore_subpages) {
			if ($num_pages === false) {
				# Dupe everything
				$this->dupe_pages($siteid,$new_page->id,false,$dupe_map,$dupe_message, $save_page_index_cache);
			} else {
				# we will handle the rest ourselves
				# so just return the id of the new
				# parent page
				$session->set_var('dupe_map', $dupe_map);
				$session->set_var('dupe_message', $dupe_message);
				return $new_page->id;
			}
		}

		if ($remap) {
			$new_page->dupe_remap(false, $dupe_map);
		}
		$this->dupe_cleanup($new_page->id);

		$session->set_var('dupe_map', $dupe_map);
		$session->set_var('dupe_message', $dupe_message);

		return $new_page->id;
	}

	function dupe_pages($siteid=0, $new_pageid=0, $num_pages=false, &$dupe_map, &$dupe_message, $save_page_index_cache=true) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');

		if ($num_pages === false) {
			# Dupe everything

			# Get the subpages
			$subpageids = $this->get_subpageids();
			if (!empty($subpageids)) {
				foreach($subpageids as $pageid) {
					# This should handle all the subpages and their files etc.
					$page = &$this->web_system->get_page($pageid);
					$dupeid .= $page->dupe($siteid,$new_pageid,false,false,false,false,true,$dupe_map,$dupe_message, $save_page_index_cache);
					$dupe_map['page'][$pageid] = $dupeid;
					$this->web_system->forget_page($pageid); # so we don't blow the memory out
				}
			}
		} else {
			$index = $this->get_all_subpageids();
			# Don't need to check for empty cause get_all_subpageids will return an array no matter what
			foreach ($index as $pageid) {
				if ($pageid <= 0) {
					continue;
				}
				if ($pageid == $this->id) {
					continue;
				}
				if (isset($dupe_map['page'][$pageid])) {
					# page already duped
					continue;
				}

				if ($num_pages <= 0) {
					# we are done
					$session->set_var('dupe_message', $dupe_message);
					$session->set_var('dupe_map', $dupe_map);
					return;
				}

				$page = &$this->web_system->get_page($pageid);

				# check the dupe map to find out
				# what the new parentid is
				if ($page->parentid == $this->id) {
					$new_parentid = $new_pageid;
				} else {
					$new_parentid = $dupe_map['page'][$page->parentid];
					if (!$new_parentid) {
						# the parent hasnt been duped yet find some other page
						continue;
					}
				}
				$dupeid = $page->dupe($siteid,$new_parentid,true,false,false,false,false,$dupe_map,$dupe_message, $save_page_index_cache); # ignore subpages
				$dupe_map['page'][$pageid] = $dupeid;
				$this->web_system->forget_page($pageid); # so we don't blow the memory out
				$num_pages--;
			}

			$session->set_var('dupe_map', $dupe_map);
			$session->set_var('dupe_message', $dupe_message);
		}
	}

	function dupe_remap($num_pages=false, &$dupe_map) {
		$session  = &get_mysource_session();
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');
	
		if ($num_pages === false) {
			# Remap everything
			$template = $this->get_template();
			$template->remap_link_ids($dupe_map);

			# Get the subpages
			$subpageids = $this->get_subpageids();
			if (!empty($subpageids)) {
				foreach($subpageids as $pageid) {
					# This should handle all the subpages and their files etc.
					$page = &$this->web_system->get_page($pageid);
					$template = $page->get_template();
					$template->remap_link_ids($dupe_map);
					$this->web_system->forget_page($pageid); # so we don't blow the memory out
				}
			}
		} else {
			$index = &$this->get_all_subpageids();
			if (!empty($index)) {
				foreach ($index as $pageid) {
					if ($pageid <= 0) continue;
					if ($num_pages <= 0) return;

					$dupe_pageid = $dupe_map['page'][$pageid];
					if (!$dupe_pageid) continue;
					$page = &$this->web_system->get_page($dupe_pageid);
					$template = $page->get_template();
					$template->remap_link_ids($dupe_map);
					$this->web_system->forget_page($dupe_pageid); # so we don't blow the memory out
					$num_pages--;
				}
			}
		}
	}

	function dupe_specified_pages($siteid=0, $new_pageid=0, $num_pages=0, $pages=array(), &$dupe_map, &$dupe_message, $save_page_index_cache=true) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');

		$pages_keys = array_keys($pages);

		# Don't need to check for empty cause get_all_subpageids will return an array no matter what
		foreach ($pages_keys as $key) {
			$pageid = $pages[$key];
			if ($num_pages <= 0) {
				# we are done
				$session->set_var('dupe_map', $dupe_map);
				$session->set_var('dupe_message', $dupe_message);
				return $pages;
			}

			$page = &$this->web_system->get_page($pageid);

			# check the dupe map to find out
			# what the new parentid is
			if ($page->parentid == $this->id) {
				$new_parentid = $new_pageid;
			} else {
				$new_parentid = $dupe_map['page'][$page->parentid];
			}

			$dupeid = $page->dupe($siteid,$new_parentid,true,false,false,false,false,$dupe_map,$dupe_message, $save_page_index_cache); # ignore subpages
			# Take that page of the to do list
			unset($pages[$key]);
			$dupe_map['page'][$pageid] = $dupeid;
			$this->web_system->forget_page($pageid); # so we don't blow the memory out
			$num_pages--;
		}

		$session->set_var('dupe_map', $dupe_map);
		$session->set_var('dupe_message', $dupe_message);
		return $pages;
	}

	function dupe_cleanup($new_pageid=0, &$dupe_message) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');

		$new_page = &$this->get_page($new_pageid);
		$new_page->clear_cache();
		$dupe_message .= "Page '$this->name' duplicated.\n";
	}

	function delete($preserve_replacement=false, $num_pages=false, $save_page_index_cache=true) {
		if($this->status() == 'R' && !$preserve_replacement) {
			list($tmp_message) = $this->add_status('L', '', $save_page_index_cache); # This will also delete the replacement page.. mwahaha;
			$message .= $tmp_message."\n";
		}

		$site = &$this->get_site();

		if ($num_pages === false) {
			# Delete everything at once

			# Delete subpages first
			$subpageids = $this->get_subpageids();
			if (!empty($subpageids)) {
				foreach($subpageids as $pageid) {
					# This should handle all the subpages and their files etc. (RECURSIVE)
					$page = &$this->web_system->get_page($pageid);
					$message .= $page->delete()."\n";
				}
			}

			if ($save_page_index_cache) {
				$site->write_page_index_to_cache();
			}

			$site->clear_page_index();
			$message .= $this->delete_cleanup();
		} else {
			# only delete $num_pages pages from the site
			$index = $this->get_all_subpageids();
			$page_index = $site->get_page_index();

			foreach ($index as $pageid) {
				if ($pageid <= 0) continue;
				if (!empty($page_index[$pageid]['childids'])) continue;
				$page = &$this->web_system->get_page($pageid);
				$message .= $page->delete_cleanup($save_page_index_cache)."\n";
				$site->remove_page_from_page_index($pageid, $save_page_index_cache);
				$num_pages--;
				if ($num_pages <= 0) {
					# we are done
					return ereg_replace("[\n]+","\n",$message);
				}
			}
		}

		return ereg_replace("[\n]+","\n",$message);
	}


	 #############################################
	# Deletes everything except the subpages
	function delete_cleanup($save_page_index_cache=true) {
		# Remove virtual paths
		$this->update_dirs(array(), $save_page_index_cache);

		if (!empty($this->file_index)) {
			# Delete all the file attachments
			foreach($this->file_index as $fileid) {
				$file = &$this->get_file($fileid);
				$message .= $file->delete()."\n";
			}
		}

		delete_directory($this->data_path);

		 #####################################
		# Let's kill our customised design
		if ($this->designid) {
			$design = &$this->get_design();
			$design->delete_customisation();
		}#end if

		# Delete the template
		$template = &$this->get_template();
		$message .= $template->delete()."\n";

		# Grants, grants and more grants
		$db = &$this->get_db();
		$db->delete("DELETE FROM page_access_grant WHERE pageid='$this->id'");
		$db->delete("DELETE FROM page_editor WHERE pageid='$this->id'");
		$db->delete("DELETE FROM page_admin WHERE pageid='$this->id'");
		$db->delete("DELETE FROM page WHERE pageid='$this->id'");
		$db->delete("DELETE FROM page_action WHERE pageid='$this->id'");

		# Uh oh, WE'RE CANCELLING A SafeEdit REPLACEMENT
		if($this->replaceid > 0) {
			$replaced_page = &$this->get_page($this->replaceid);
			if($replaced_page->id) {
				list($tmp_message) = $replaced_page->add_status('L', '', $save_page_index_cache);
				$message .= $tmp_message."\nSafeEdit cancelled.\n";
			}
		} # OR

		$message .= "(PageID: $this->id) '$this->name' deleted.\n";
		$this->clear_cache();
		if ($save_page_index_cache) {
			# If nothing special going on lets just do this to be on the safe side
			$site = &$this->get_site();
			$site->clear_page_index();
		}
		unset($this);
		return ereg_replace("[\n]+","\n",$message);
	}


	   ##############################################
	  # Changes the page ID of an entire page.
	 # This is for the SafeEdit function
	############################################################
	# I have a bad feeling about this, but it seems necessary :)
	function newid ($newid=0) {
		$newid = (int) $newid;

		# Remember where we should be found
		$dirs = $this->dirs;
		$this->update_dirs(array());
		$this->clear_cache();

		# The page
		$db = &$this->get_db();
		if(!$db->update("UPDATE page SET pageid='$newid' WHERE pageid='$this->id'")) {
			return "Unable to change ID fror page '$this->name'.";
		}

		$site = &$this->get_site();
		$site->clear_page_index();

		# The subpages
		$db->update("UPDATE page SET parentid='$newid' WHERE parentid='$this->id'");
		$subpageids = $this->get_subpageids();
		if (!empty($subpageids)) {
			foreach($subpageids as $subpageid) {
				$this->clear_cache($subpageid);
			}
		}

		# Update the template information for page
		$template = &$this->get_template();
		$template->newid($newid);

		# The files - TO DO
		$db->update("UPDATE file SET pageid='$newid' WHERE pageid='$this->id'");
		if (!empty($this->file_index)) {
			foreach($this->file_index as $fileid) {
				$file = &$this->get_file($fileid);
				$file->clear_cache();
			}
		}

		# The readers
		$db->update("UPDATE page_access_grant SET pageid='$newid' WHERE pageid='$this->id'");

		# The writers
		$db->update("UPDATE page_editor SET pageid='$newid' WHERE pageid='$this->id'");
		$db->update("UPDATE page_admin  SET pageid='$newid' WHERE pageid='$this->id'");

		# The file system
		$new_data_path = get_data_path($this->effective_unrestricted(), 'page/'.$newid);
		if (!rename($this->data_path,$new_data_path)) {
			report_error(__FILE__,__LINE__,"Unable to rename page folder ($this->data_path --> $new_data_path) while changing pageid.");
			$this->id = $newid;
			$this->delete();
		}
		$this->data_path = $new_data_path;

		$this->id = $newid;

		# Refresh the virtual paths
		$this->update_dirs($dirs);

		$this->clear_cache();
		return "Page '$this->name''s ID has been changed to $this->id";
	}

	 ######################################################
	# The the current user an read/write admin of this page?
	function admin_access() {
		$site = &$this->get_site();
		$session = &get_mysource_session();
		if ($site->admin_access()) return true;
		return in_array($session->user->id,$this->adminids);
	}

	 ######################################################
	# The the current user an read/write editor of this page?
	function editor_access() {
		$site = &$this->get_site();
		$session = &get_mysource_session();
		if ($this->admin_access() || $site->editor_access()) return true;
		return in_array($session->user->id,$this->editorids);
	}


	 #####################################################
	# The the current user a read-only editor of this page
	# (read/write access also acceptable)
	function readonly_editor_access() {
		$session = &get_mysource_session();
		if ($this->editor_access()) return true;
		return in_array($session->user->id,$this->readonly_editorids) && !in_array($this->effective_status(),array('D','A'));
	}

	  ################
	 # Write-access?
	function write_access() {
		$site = &$this->get_site();
		switch($this->effective_status()) {
			# Only admins may edit live, upfroreview or pending-approval pages
			case 'L': case 'E': case 'P': return $this->admin_access();
			# Editors may only edit Under Consturction ages
			case 'U': return $this->editor_access();
			# These statuses are off limits to all
			case 'D': case 'A': case 'R': default: return false;
		}
	}

	  #########################################
	 # Can the current user delete this page?
	function delete_access() {
		$site = &$this->get_site();
		return (
			($site->admin_access() && $this->effective_status() != 'R') # Admins can, sure
			|| (
				$site->editor_access() # Editors can, yes, but only if the page is U or P
				&&  ($this->effective_status() == 'U' || $this->effective_status() == 'P')
				&& !count($this->get_subpageids()) # Also not subpages !
			)
		);
	}


	 ################################################################
	# If the person allowed to view information about/on this page?
	function read_access() {
		if($this->readonly_editor_access()) return true;
		$site = &$this->get_site();
		return $site->page_read_access($this->id);
	}


	 ##############################################################################
	# Determines what rights the current user has when it comes to status-changing
	function change_status_access() {
		# Determine the options they have for changing the page status
		$site = &$this->get_site();
		switch($this->status() ) {
			case 'L':
				if($this->editor_access()) {
					$change_status['R'] = 'SafeEdit';
					$change_status['E'] = 'Up for Review';
				}
				if ($this->admin_access()) {
					$change_status['U'] = 'Place under construction';
					$change_status['D'] = 'Disable';
					$change_status['A'] = 'Archive';
				}
				break;
			case 'R':
				if($this->editor_access()) {
					$change_status['L'] = 'Cancel SafeEdit';
				}
				break;
			case 'P':
				if($this->admin_access()) {
					$change_status['L'] = 'Approve and make live';
					$change_status['U'] = 'Reject and place under construction';
					if (!$this->replaceid) {
						$change_status['D'] = 'Reject and disable';
						$change_status['A'] = 'Reject and archive';
					}
				}
				if($this->editor_access() && !$this->admin_access()) {
					$change_status['U'] = 'Keep working';
				}
				break;
			case 'U':
				if($this->admin_access()) {
					$change_status['L'] = 'Make live';
					$change_status['P'] = 'Place in pending approval';
					if (!$this->replaceid) {
						$change_status['D'] = 'Disable';
						$change_status['A'] = 'Archive';
						$change_status['E'] = 'Up for Review';
					}
				}
				if($this->editor_access() && !$this->admin_access()) {
					$change_status['P'] = 'Apply for approval';
				}
				break;
			case 'D':
				if($this->admin_access()) {
					$change_status['L'] = 'Enable and make live';
				}
				if($this->editor_access()) {
					$change_status['U'] = 'Enable for working';
				}
				break;
			case 'A':
				if($this->admin_access()) {
					$change_status['L'] = 'Restore and make live';
				}
				if($this->editor_access()) {
					$change_status['U'] = 'Restore for working';
				}
				break;
			case 'E':
				if($this->admin_access()) {
					$change_status['L'] = 'Approve and make live';
					$change_status['U'] = 'Reject and place under constuction';
					if (!$this->replaceid) {
						$change_status['D'] = 'Reject and disable';
						$change_status['A'] = 'Reject and archive';
					}
				}
				if($this->editor_access() && !$this->admin_access()) {
					$change_status['L'] = 'Remove Review Status';
				}
				break;
			default:
				break;

		}
		return $change_status;
	}

	  ###################################################
	 # Print a link to an image on this page
	function image_tag($file='',$alt='',$width=0,$height=0,$extra='',$pageid=0) {
		if ($pageid && $pageid != $this->pageid) {
			$page = &$this->get_page($pageid);
			if ($page->id) {
				return $page->image_tag($file,$alt,$width,$height,$extra);
			}
		}

		# if directories are involved split the name from the dir
		$dirs = (strstr($file, '/')) ? dirname($file) : '';

		$src = $this->data_path.'/'.$file;
		$rel = $this->get_file_href($dirs);

		return $this->web_system->image_tag($src,$rel,$alt,$width,$height,$extra);
	}

	  ###################################################
	 # From the src ( without the extension ) returns the file name of
	# the image that exists
	function get_image($file='',$pageid=0) {

		if ($pageid && $pageid != $this->pageid) {
			$page = &$this->get_page($pageid);
			if ($page->id) {
				return $page->get_image($file);
			}
		}
		return get_image($this->data_path.'/'.$file);
	}


	###########################################################################
	# FRONTEND STUFF
	# If passed in an access reject, then we are printing out a forbidden 403 page
	function print_frontend($force_send=false) {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		if(!$this->read_access()) {
			$access_reject_message = $session->get_var('access_reject_message');
			if(!$access_reject_message) $access_reject_message = 'Access to this page is restricted.';
			$session->login_screen($this->name, $access_reject_message);
		}

		if($fileid = $this->web_system->current_fileid && !$force_send) { # Ooh, someone wants a file on this page -- CHANGED
			# Actually, lets just send files through anyway
			$file = $this->web_system->get_file();
			send_cacheable_file($file->data_path.'/'.$file->filename);
			# if we are meant to log file hits, then do so
			if ($system_config->log_visitors && $system_config->statistics_reporter && $file->log_hits == 'Y') {
				$stat_reporter = &get_statistics_reporter('file');
				$stat_reporter->log_hit(array('fileid' => $file->id));
			}#end if
			return;
		} else { # Just send the page if we're at the right spot
			$this->url_leftovers = ''; # Danglin directories on the end of our URL
			if($force_send || $this->web_system->verify_page_url($this->id,$this->web_system->current_url(),$this->url_leftovers)) {
				$template = &$this->get_template();

				if($this->default_charset)
					header("Content-type: text/html; charset=$this->default_charset");
				if($this->default_languages)
					header("Content-language: $this->default_languages");

				header('Last-Modified: ' . gmdate('D, d M Y H:i:s',$this->get_last_update_timestamp()) . ' GMT');
				# Should we make sure that no caching occurs ?
				if(!$template->moderate_caching()) {
					# Ensure this page (or file) doesn't get cached along the line
					header('Expires: '.gmdate('r',time()-86400).' GMT'); # Date in the past
					header('Cache-Control: no-cache, must-revalidate'); # HTTP/1.1
					header('Pragma: no-cache');
				}

				# Okay then, send it through
				# Let the template take care of itself
				$template->print_frontend();

				# if we are meant to log page hits, then do so
				if ($system_config->log_visitors && $system_config->statistics_reporter) {
					$stat_reporter = &get_statistics_reporter('page');
					$stat_reporter->log_hit(array('pageid' => $this->id));
				}#end if
			} else {
			$query_string = $_SERVER['QUERY_STRING'];
			$query_string = str_replace("p={$this->id}",'',$query_string);
			$query_string = trim($query_string,' ?&');
			header('Location: '.$this->get_url().(($query_string) ? "?$query_string" :''));
			}
		}
	}

	 ##############################################################
	# depending on our effective publicness restrict the data path
	function restrict_data_path() {
		restrict_data_path($this->effective_unrestricted(), 'page/'.$this->id);
		# update the data path to represent our new status
		$this->data_path = get_data_path($this->effective_unrestricted(), 'page/'.$this->id);

		$subpageids = $this->get_subpageids();
		foreach($subpageids as $pageid) {
			 ###########################################################
			# This should handle all the subpages
			$page = &$this->get_page($pageid);
			$page->restrict_data_path();
			unset($page);
			$this->web_system->forget_page($pageid); # so we don't blow the memory out
		}
	}#end restrict_data_path()

	 ###############################################################
	# return the UNIX timestamp for the last_update of this page
	function get_last_update_timestamp() {
		$db = &$this->get_db();
		return $db->datetime_to_timestamp($this->last_update);
	}

	 ################################################
	# sets the site design that this page has
	function set_design($designid=0) {
		if (!$designid && !$this->designid) return '';
		if ($designid == $this->designid)   return '';

		$message = '';

		# we're removing our design
		if (!$designid) {
			$design = &$this->get_design();
			if ($design->delete_customisation($designid)) {
				$this->designid = 0;
				$message .= "Design Customisation Removed\n";
			}
		# else we must be setting a design
		} else {

			# if there is an existing design try and convert it
			if ($this->designid) {
				$design = &$this->get_design();
				$message .= $design->convert_customisation($designid);
				# conversion succeeded
				if ($designid == $design->id) {
					$this->site_design = &$design;
				}

			}#end if

			$this->designid = $designid;
			$design = &$this->get_design();

			$message .= "Design set to '$design->name'";

		}#end if

		$db = &$this->get_db();
		$sql = "UPDATE page SET designid = '$this->designid' WHERE pageid='$this->id'";
		$db->update($sql);

		# because the effective_design_pageid is setup by the site's page index we need to clear that array
		$site = &$this->get_site();
		$site->update_page_index_settings($this->id, array('designid' => $this->designid), true);
		$this->clear_cache();
		return $message;

	}#end set_design()

	 #####################################################################
	# Returns a reference to the design object that this page should use
	function &get_design() {

		if(get_class($this->site_design) != 'site_design' || $this->get_file_href('site_design/') != $this->site_design->custom_file_href) {

			# if we have a designid use our design
			if ($this->designid) {
    			global $INCLUDE_PATH;
    			include_once("$INCLUDE_PATH/site_design.inc");
    			$this->site_design =& new Site_Design($this->designid, $this->id.'.'.get_class($this), $this->data_path.'/site_design', $this->get_file_href('site_design/'));

			# must be something further up the hierarchy
			} else {
        		$site   = &$this->get_site();
        		$index  = &$site->get_page_index();
				# if there is a page above us that has our design get it
        		if ($pageid = $index[$this->id]['effective_design_pageid']) {
    				$page = &$this->get_page($pageid);
					$this->site_design = &$page->get_design();
				# the good old site will have a design for us
				} else {
					$this->site_design = &$site->get_design();
				}#end if

			}#end if
		}#end if
		return $this->site_design;

	}#end get_design()

	 ##########################################
	# sets the image of the page
	function set_image($imageid=0) {
		if ($imageid == '') $imageid = 0;
		if($imageid == $this->imageid) return '';

		# if they are trying to set the image, check it exists
		if ($imageid) {
			$file = &$this->get_file($imageid);
			# if not an file tell them to go away
			if (!$file->id) return Array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to set the Page Image, the id you passed was not a file');
			# if not an image tell them to go away
			if (!is_image($file->filename)) return Array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to set the Page Image, the file you selected is not an image');
		}

		$db = &$this->get_db();
		$this->imageid = $imageid;
		$db->update("UPDATE page SET imageid='".addslashes($this->imageid)."' WHERE pageid='$this->id'");
		$this->clear_cache();
		return Array(MYSOURCE_ERROR_CODE_NONE, 'Image '.(($this->imageid == '' || $this->imageid == 0) ? 'unset' : 'set to '.$file->filename).' for page "'.$this->name.'".');
	}#end set_image()


	#########################################################################################
	#BACKEND STUFF

	 ###############################################
	# Returns a reference to the page_backend object,
	# this may already be setup by the page.
	function &_get_page_backend() {

		if (!$this->_page_backend || get_class($this->_page_backend) != 'page_backend') {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/page_backend.inc");
			$this->_page_backend = new Page_Backend($this);
		}
		return $this->_page_backend;
	}

	 #########################################################################
	# Some Aliases for fns in page_Backend()
	function &setup_backend() {
		$sb = &$this->_get_page_backend();
		return $sb->setup_backend();
	}
	function print_backend() {
		$sb = &$this->_get_page_backend();
		$sb->print_backend();
	}

	/**
	* Returns the page objects name
	*
	* @param bool $long Long name you want?
	* @access public
	* @return string
	*/
	function get_page_name($long=true) {
		if ($long) {
			return $this->name;
		} else {
			return $this->short_name;
		}
	}

}#end class
?>
