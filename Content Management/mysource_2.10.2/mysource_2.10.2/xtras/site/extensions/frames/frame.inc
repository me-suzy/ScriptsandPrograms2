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
## File: xtras/page/templates/standard/standard.inc
## Desc: A page template class.. the standard template is the most generic you can get
## $Source: /home/cvsroot/xtras/site/extensions/frames/frame.inc,v $
## $Revision: 2.4 $
## $Author: gsherwood $
## $Date: 2003/02/27 03:34:24 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design.inc");
#---------------------------------------------------------------------#

class Site_Extension_Frames_Frame extends WebObject {

	var $site_extension; # reference to our parent template
	var $table_name = '';
	var $data_path  = '';

	var $id = 0;        # the frame id
	var $siteid = 0;    # the site that this frame belongs to
	var $name   = '';   # the name of the frame
	var $designid = 0;  # the id of site design to use when displaying this frame
	var $site_design;   # the unserialised version of the site design, not stored

	 ##############################
	# Constructor
	function Site_Extension_Frames_Frame(&$site_extension, $siteid, $frameid) {
		WebObject::WebObject();
		$this->site_extension = &$site_extension;
		$this->table_name = $this->site_extension->table_name."_frame";
		if ($siteid && isset($frameid)) {
			$this->load($siteid, $frameid);
		}
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element("site_extension", $result);
		array_remove_element("data_path",     $result);
		array_remove_element("site_design",   $result);
		return $result;
	}

	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($siteid, $frameid, $name='') {
		if (!$siteid || !isset($frameid) || trim($name) == '') {
			$this->_set_error("You need to specify a siteid, frameid and a frame name to create a frame.",__FILE__,__LINE__);
			return 0;
		}

		$sql = "INSERT INTO $this->table_name
				(siteid, frameid, name)
				VALUES
				('$siteid', '$frameid', '".addslashes($name)."')";

		$db = &$this->get_db();
		if ($db->insert($sql)) {
			$this->load($siteid, $frameid);
			create_directory($this->data_path);
		}
		return "Frame Created";
	}


	 ################################################
	# Loads the frame details, from cache or database
	function load($siteid, $frameid) {
		if ($siteid <= 0 && !($siteid = $this->siteid)) { # Tries "re"loading
			$this->_set_error("Attempt to load frame without a valid siteid.",__FILE__,__LINE__);
			return 0;
		}
		if ($frameid < 0 && !($frameid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load frame without a valid frameid.",__FILE__,__LINE__);
			return 0;
		}

		 #################
		# Check the cache 
		$site_extension = &$this->site_extension;
		if ($this->load_from_cache($siteid.".".$frameid)) {
			$this->site_extension = &$site_extension;
			$this->data_path = $this->site_extension->data_path."/$this->id";
			return $frameid;
		}

		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();
		$sql = "SELECT frameid, siteid, name, designid
				FROM $this->table_name 
				WHERE siteid  = '$siteid'
				  AND frameid = '$frameid'";
		list(
			$this->id,
			$this->siteid,
			$this->name,
			$this->designid
			) = $db->single_row($sql);

		 #####################################
		# Create a new record if none exists
		if (!$this->siteid) { # can't use $this->id because we can have zero for a frameid
			return false;
		}

		$this->data_path = $this->site_extension->data_path."/$this->id";

		 ###################
		# Save to the cache 
		$this->save_to_cache($siteid.".".$frameid);
		return $frameid;
	}

	 #######################################################
	# Removes the frame utterly from existance !
	function delete()   {
		$db = &$this->get_db();
		$sql = "DELETE FROM ".$this->table_name." 
				WHERE siteid  = '$this->siteid'
				  AND frameid = '$this->id'";
		if ($db->delete($sql)) {
			# Let's kill our customised design
			$design = &$this->get_design();
			$design->delete_customisation();
			delete_directory($this->data_path);
			$this->clear_cache($this->siteid.".".$this->id);
			unset($this);
			return true;
		} else {
			return false;
		}
	}#end delete()

	 #######################################################
	# Duplicates this entire frame to a new siteid
	function dupe($new_siteid)   {
		$db = &$this->get_db();

		$design  = &$this->get_design();
		$dupe_ok = $design->dupe_customisation($new_siteid.".".$this->id.".".get_class($this));

		$sql = "INSERT INTO ".$this->table_name."
				(siteid, frameid, name, designid)
				VALUES
				('$new_siteid', '".addslashes($this->id)."', '".addslashes($this->name)."', '".addslashes($this->designid)."')";

		$dupe_ok = ($db->insert($sql)) ? $dupe_ok : false;

		return $dupe_ok;

	}#end dupe()

	 #########################################################################
	# Returns a href to a file associated with this frame
	function get_file_href($filename='') {
		return $this->site_extension->get_file_href("$this->id/$filename");
	}

	 ######################################
	# Updates the name 
	function set_name($name) {
		if($name == $this->name) return "";
		if(trim($name) == "")    return "";

		$db = &$this->get_db();

		# check the name isn't already in use for this site 
		# NOTE: use of STRCMP to make it a case-sensitive search
		$sql = "SELECT count(*) 
				FROM $this->table_name
				WHERE siteid  = '$this->siteid'
				  AND STRCMP(name, '".addslashes($name)."') = 0";

		if ($db->single_element($sql)) return "Unable to change frame name, it's already in use for this site";

		$sql = "UPDATE $this->table_name
				   SET name = '$name' 
				WHERE siteid  = '$this->siteid'
				  AND frameid = '$this->id'";
		if ($db->update($sql)) {
			$this->name = $name;
			$this->clear_cache($this->siteid.".".$this->id);
			return "Frame Name Updated";
		}
		return "";

	}#end set_name()

	 ############################
	# Changes the site  
	function set_design($designid) {
		if(!$designid)
			return "Unable to set new design: ID not specified";
		if($designid == $this->designid)
			return "";

		$message = "";

		# if there is an existing design try and convert it
		if ($this->designid) {
			$design = &$this->get_design();
			$message .= $design->convert_customisation($designid);
			# conversion succedded
			if ($designid == $design->id) { 
				$this->site_design = &$design;
			}

		}#end if

		$this->designid = $designid;
		$design = &$this->get_design();

		$db = &$this->get_db();
		$sql = "UPDATE $this->table_name 
				SET designid = '$designid' 
				WHERE siteid  = '$this->siteid'
				  AND frameid = '$this->id'";
		$db->update($sql);

		$design = &$this->get_design();
		$message .= "Design set to '$design->name'";
		$this->clear_cache($this->siteid.".".$this->id);

		return $message;

	}#end set_design()


	 ################################################
	# Returns a reference to this sites design object
	function &get_design() {
		if(get_class($this->site_design) != "site_design") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/site_design.inc");
			$this->site_design =& new Site_Design($this->designid, $this->siteid.".".$this->id.".".get_class($this), $this->data_path, $this->get_file_href());
		}
		return $this->site_design;

	}#end get_design()

	 ##################################################################
	# Prints the backend for the user
	function print_backend($tab_prefix, $is_index_frame=false) {

		$backend = &$this->get_backend();
		$db = &$this->get_db();

		$backend->set_tab("site_extension_".$this->codename."_frame", $tab_prefix.$this->id, $this->id." - ".$this->name, "Edit the properties of this frame","xtra");
		$backend->set_active_tab("site_extension_".$this->codename."_frame");

		$site_designs = &$this->web_system->get_site_design_list();
		$site = &$this->site_extension->get_site();

		if (!$is_index_frame && $site_designs[$this->designid] && in_array($this->designid, $site->allowed_designids)) {
			$backend->set_tab("site_extension_".$this->codename."_frame_custom_design", $tab_prefix.$this->id."&design_edit=1", "Custom Design", "Customise the design used by this frame","xtra");
		}#end if


		 ##########################################################
		# Display the edit interface for the design if asked for
		if($_REQUEST['design_edit']) {
			$backend->set_active_tab('site_extension_'.$this->codename.'_frame_custom_design');
			$design = &$this->get_design();
			$design->print_user_backend($tab_prefix.$this->id.'&design_edit=1');
			return;
		}

		switch($_REQUEST['action']) {
			case 'Commit':
				$backend->add_message($this->set_name(gpc_stripslashes($_REQUEST['name'])));
				if (!$is_index_frame) {
					$backend->add_message($this->set_design($_REQUEST['designid']));
				}
				break;
			default:
				break;
		}

		$backend->set_hidden_field('design_edit');
		$backend->print_header();

		$backend->open_section();

		$backend->open_field('Name', 'one_liner');
		echo text_box('name', $this->name, 50, 255, 'class=backend_data');
		
		$backend->open_field('Design', 'one_liner');
		if ($is_index_frame) {
			?>
				Unable to set the design because this frame is the index frame,<br>
				it will be the frame that displays the main content,<br>
				so the design is the one set by the site
			<?
		} else {
			$designs = array();
			foreach($site->allowed_designids as $designid) {
				if ($site_designs[$designid]) $designs[$designid] = $site_designs[$designid];
			}
			asort($designs);
			echo combo_box('designid', $designs, $this->designid, 'class=backend_data'); 
		}

		$backend->print_commit_button('Commit',"document.edit.action.value='Commit';document.edit.submit();");
		$backend->print_footer();

	}#end print_backend()

	 ################################################
	# OK, let's create our frameset
	function print_frontend() {
		$design = &$this->get_design();
		$design->paint($this);
	}#end print_frontend()

	 ##########################################################################
	# we don't want to do anything in print_body(), it should NEVER get called
	function print_body() {
	}

}#end class

?>