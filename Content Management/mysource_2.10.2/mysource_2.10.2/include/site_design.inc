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
## File: include/site_design.inc
## Desc: An object that looks after the site design portion of the core
## $Source: /home/cvsroot/mysource/include/site_design.inc,v $
## $Revision: 2.54 $
## $Author: bvial $
## $Date: 2004/03/10 05:52:47 $
#######################################################################
include_once("$INCLUDE_PATH/site_design_base.inc");
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

 ##################################################################################
# NOTE : when constructing a Site_Design instance you MUST create it by reference
#        eg. $blah =& new Site_Design(1, "58.site");
class Site_Design extends Site_Design_Base {

	# Var
	var $web_system; # Reference to the object that controls the current web system;


	 #############################
	# Database fields
	var $id=0;		# really designid
	var $name='';
	var $last_modified='';
	var $creatorid='';
	var $modifierid='';
	var $public = true;

	 ##############################################################################################
	# If this object is the original version of the design this will be blank,
	# else if this is a customisation it is our unique identifier for the site_design_customisation table
	var $customisationid = '';

	 #############################
	# normal vars
	var $image_root='';			# the image root used by the user in their code
	var $css_file='';			# the file designated to be the stylesheet file
	var $contents=array();		# array holding the top level contents of the page
	var $design_areas=array();	# array holding the design area objects

	 ##############################
	# path vars
	var $data_path = '';	# the data path that the design refers to for the files
							# uploaded with the parse file
	var $file_href = '';	# temp path, set by get_file_href(), not stored

	var $custom_data_path = '';	# the data path that the design for files that it has created
								# defaults to under site's data path, but can be set to some where else
								# (custom images, coloured images etc)
	var $custom_file_href = '';	# the web path used to get to the $this->custom_data_path

	var $_clear_generated_path = false;	# whether or not we need to clear the $this->data_path.'/generated'
										# dir when we load next time


	 #######################################
	# Runtime
	var $painter;  # A reference to an object of any type which has callable functions for various things
	var $image_rollovers = array(); # An array of 'name' => 'image' to be printed by the footer for image rollovers

	 ##########################################################################
	# An array of special set vars that are excluded from errors when being set
	# and are not editable
	var $special_set_vars = array('bodycopy_styles');


	 ##################################################################################
	# NOTE : when constructing a Site_Design instance you MUST create it by reference
	#        eg. $blah =& new Site_Design(1, "58.site");
	function Site_Design($designid=0, $customisationid='', $custom_data_path='', $custom_file_href='') {


		 #################################
		# Establish link to mother system
		$this->web_system = &get_web_system();

		 #################################################
		# Load the design information if an id is specified
		# if we have a customisation then try to load it
		if ($customisationid && $designid) {
			$this->load_customisation($customisationid, $designid, $custom_data_path, $custom_file_href);
		# else if we have a design id try loading that
		} else if ($designid) {
			$this->load($designid, $custom_data_path, $custom_file_href);
		# oh well, just set the data_paths up
		} else {
			$this->set_data_paths($custom_data_path, $custom_file_href);
		}

	}#end Site_Design()

	 ######################################################################
	# Create a site design record in the database and load it into this object
	function create($name='') {

		$db = &$this->web_system->get_db();
		$session = &get_mysource_session();

		if (intval($db->single_element("SELECT count(*) FROM site_design WHERE name='$name'")) > 0) {
			return "Unable to create site design: $name already in use";
		} else {

			$sql = "INSERT INTO site_design
					(name, last_modified, creatorid, modifierid, public)
					VALUES
					('$name', now(), '".$session->user->id."', '".$session->user->id."', 1)";

			if (!($designid = $db->insert($sql))) {
				return "Unable to create a new design area";
			}#end if

			$this->load($designid);

			# Create directory for stuff!
			$old_umask = umask(0);
			if (!mkdir($this->data_path,0775)) {
				$this->_set_error("Unable to create new site design dir: ".$this->data_path,__FILE__,__LINE__);
			} else {
				# Create a directory for images in the web path
				if (!mkdir($this->data_path."/images",0775)) {
					$this->_set_error("Unable to create new site design dir: ".$this->data_path."/images",__FILE__,__LINE__);
				}
			}
			umask($old_umask);

			$message = "New site design \"$name\" created.";

			$session->unset_var('site_design_list');

			$this->load($designid);
			return $message;
		}

	}#end create()


	 ################################################
	# Loads the design infomation, from database
	function load($designid=0, $custom_data_path='', $custom_file_href='') {
		if ($designid <= 0 && !($designid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load site design information without a valid designid.",__FILE__,__LINE__);
			$this =& new Site_Design(0, "", $this->custom_data_path, $this->custom_file_href);
			return false;
		}

		 ########################
		# Load from the database
		$sql = "SELECT designid, name, design, last_modified, creatorid, modifierid, public
				FROM site_design
				WHERE designid='$designid'";
		$db = &$this->web_system->get_db();
		$row = $db->single_row($sql);

		if (!$row) { # EEk! Clear.
			$this =& new Site_Design(0, "", $this->custom_data_path, $this->custom_file_href);
			return false;
		}

		if ($row['design']) {
			$unpacked = $this->unpack($row['design']);
		}

		$this->id            = $row['designid'];
		$this->name          = $row['name'];
		$this->last_modified = $row['last_modified'];
		$this->creatorid     = $row['creatorid'];
		$this->modifierid    = $row['modifierid'];
		$this->public        = $row['public'];

		 ##############################
		# Remember where we put things
		$this->set_data_paths($custom_data_path, $custom_file_href);

		return $designid;

	}#end load()

	 ################################################
	# Attemps to load a customised site design
	function load_customisation($customisationid='', $designid=0, $custom_data_path='', $custom_file_href='') {

		if (!$customisationid || !$designid) {
			$this->_set_error("Unable to load customised version of site design - you need to define a customisationid and a designid", __FILE__, __LINE__);
			return;
		}

		$site_design = NULL;
		$packed = "";

		$load_uncustomised = false;
		$save_cache = true;

		# Read the PACKED version from the cache
		global $CACHE;
		if ($packed = $CACHE->read($customisationid, "custom_site_design")) {
			$save_cache = false;
		} else {
			$db = &$this->web_system->get_db();
			# Read the PACKED version from the database
			$sql = "SELECT design
					FROM site_design_customisation
					WHERE customisationid = '$customisationid'";
			$packed = $db->single_element($sql);
		}#end if

		if ($packed) {
			$this->unpack($packed);
			# OK, so we have unpacked ourselves and found that we are the wrong design
			if ($this->id != $designid) {
				$load_uncustomised = true;
			}#end if
		} else {
			$load_uncustomised = true;
		}#end packed

		# if, for whatever reason we are required to load an uncustomised version of the design, do so here
		if ($load_uncustomised) {
			# now load an uncustomized design object
			if (!$this->load($designid, $custom_data_path, $custom_file_href)) return false;
			$this->save_customisation($customisationid);
		}#end if

		 ##############################
		# Remember where we put things
		$this->set_data_paths($custom_data_path, $custom_file_href);

		$this->customisationid = $customisationid;
		if ($this->_clear_generated_path) {
			$save_cache = ($save_cache && !$this->clear_generated_path());
		}

		# only save the cache when we need to
		if ($save_cache) {
			$CACHE->write($this->pack(), $this->customisationid, "custom_site_design");
		}

	}#end load_customisation()


	 ######################################################################
	# Delete this object
	function delete() {

		$db = &$this->web_system->get_db();
		$deleted = $db->delete("DELETE FROM site_design WHERE designid='$this->id'");

		if ($deleted) {

			# remove the storage directory
			delete_directory($this->data_path);

			# now kill this instance of the object
			unset($this);

			return "Design Deleted";

		}#end if deleted

	}#end delete()

	 ############################
	# Duplicate this site design
	function dupe() {

		$db = &$this->web_system->get_db();
		$msg = Array();

		# get a unique name
		$name = $this->name;
		do {
			$name = increment_name($name, ' ');
		} while (intval($db->single_element("SELECT count(*) FROM site_design WHERE name='$name'")) > 0);

		$dupe_design = new Site_Design();
		$msg[] = $dupe_design->create($name);
		if ($dupe_design->id) {

			$dupe_design->set_public($this->public);
			copy_directory($this->data_path, $dupe_design->data_path);

			$packed = $this->pack();
			$sql = "UPDATE site_design
					SET design = '".addslashes($packed)."'
					WHERE designid = '".$dupe_design->id."'";
			$db->update($sql);

			$msg[] = "Design Duplicated.";
		}#endif

		return Array($dupe_design->id, implode("\n", $msg));

	}#end dupe()


	 ########################################
	# Change the name of the design
	function set_name($name='') {
		if ($name == $this->name) return "";
		$db = &$this->web_system->get_db();
		if ($db->single_element("SELECT count(*) as num FROM site_design WHERE name='".addslashes($name)."' AND designid != '$this->id'")) {
			return "Unable to rename site design: \"$name\" already in use";
		} else {
			$this->name = $name;
			$db->update("UPDATE site_design SET name='".addslashes($this->name)."' WHERE designid='$this->id'");
			$this->save();
			return "Name changed to ".$this->name;
		}#end if

	}#end set_name()


	 ################################################
	# Change whether this design is public or not
	function set_public($public=false) {
		$public = ($public) ? 1 : 0;
		if ($public == $this->public) return "";
		$this->public = $public;
		$db = &$this->web_system->get_db();
		$db->update("UPDATE site_design SET public='".$this->public."' WHERE designid='$this->id'");
		restrict_data_path($this->public, "site/design/".$this->id);
		$this->save();
		$this->set_data_paths();
		return "Public turned ".(($this->public) ? "on" : "off");
	}#end set_public()


	  ############################################################
	 # Reset the owner for this object, then reset the design area's
	# owner to this object
	function reset_owner(&$owner) {
		if (!empty($this->design_areas)) {
			for(reset($this->design_areas);
				$key = key($this->design_areas);
				next($this->design_areas)) {

				$this->design_areas[$key]->reset_owner($this);

			}#end for
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	 ######################################################################
	# Unset the owner for this object and all the design areas beneath it
	function &unset_owner() {
		if (!empty($this->design_areas)) {
			for(reset($this->design_areas);
				$key = key($this->design_areas);
				next($this->design_areas)) {

				$this->design_areas[$key]->unset_owner();

			}#end for
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();

		if (!empty($this->design_areas)) {
			for(reset($this->design_areas);
				$key = key($this->design_areas);
				next($this->design_areas)) {

				$tmp->design_areas[$key] = &$this->design_areas[$key]->copy();

			}#end for
		}

		return $tmp;

	}#end copy()


	   ##################################################################################
	  # Converts the object into a nice string
	 # replaces the design area objects with the packed versions of themselves and
	# the include file need to find their class definitions
	function pack() {

		$this->unset_owner();

		unset($this->file_href);
		unset($this->file_url);

		unset($this->web_system);
		$tmp_painter = &$this->painter;
		unset($this->painter);

		# we don't want the custom dirs because we want them to be set each time, incase they change
		$tmp_custom_data_path = $this->custom_data_path;
		unset($this->custom_data_path);
		$tmp_custom_file_href = $this->custom_file_href;
		unset($this->custom_file_href);

		$packed_design_areas = array();

		if (!empty($this->design_areas)) {
			for(reset($this->design_areas);
				$key = key($this->design_areas);
				next($this->design_areas)) {

				$packed_design_areas[$key] = $this->design_areas[$key]->pack();

			}#end for
		}

		$old_design_areas = $this->design_areas;
		$this->design_areas = $packed_design_areas;

		$packed = serialize($this);

		$this->design_areas = $old_design_areas;
		$this->custom_data_path = $tmp_custom_data_path;
		$this->custom_file_href = $tmp_custom_file_href;
		$this->painter    = &$tmp_painter;
		$this->web_system = &get_web_system();
		$null = null;
		$this->reset_owner($null);

		return $packed;

	}#end pack()


	   ##################################################################################
	  # Takes a string, and unpacks it into the object itself.
	 # Includes the design areas class definitions
	# then creates the design area objects from the packed versions
	function unpack($stringified) {

		# if there ain't no content create a blank one
		if (!trim($stringified)) {
			$this =& new Site_Design(0, "", $this->custom_data_path, $this->custom_file_href);
			return true;
		}

		$new_object = unserialize($stringified);
		if (get_class($new_object) != get_class($this)) {
			$this->_set_error("Attempt to unpack site design yielded an object which did not appear to be a ".get_class($this).": <br>".htmlspecialchars($stringified), __FILE__, __LINE__);
			return false;
		}

		$packed_design_areas = $new_object->design_areas;
		$new_object->design_areas = Array();

		if (!empty($packed_design_areas)) {
			for(
					reset($packed_design_areas);
					$key = key($packed_design_areas);
					next($packed_design_areas)
				)
			{

				if (Site_Design::_include_design_area($key)) {
					eval('$new_object->design_areas[$key] = Site_Design_Area_'.$key.'::unpack($packed_design_areas[$key], \'Site_Design_Area_'.$key.'\');');
					# if something went wrong, remove element
					if ($new_object->design_areas[$key] === null) unset($new_object->design_areas[$key]);
				}#end if

			}#end for
		}

		$new_object->web_system = &get_web_system();
		# cast to int to remove any padding
		$new_object->id = (int) $new_object->id;
		# set up the custom directories from the current paths
		$new_object->set_data_paths($this->custom_data_path, $this->custom_file_href);

		$this = $new_object;
		$null = null;
		$this->reset_owner($null);

		return true;

	}#end unpack()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Base::update($old_this)) return;

		if (!empty($this->design_areas)) {
			for(reset($this->design_areas); NULL !== ($key = key($this->design_areas)); next($this->design_areas)) {
				if ($this->design_areas[$key]->customisable && $old_this->design_areas[$key]) {
					$this->design_areas[$key]->update($old_this->design_areas[$key]);
				}

			}#end for
		}

	}#end update()


	 ###################################################################################
	# Returns a reference to the painter object.. general the object that called design
	function &get_painter() {
		return $this->painter;
	}


	 #################################################
	# Returns a pointer to the jupload manager
	function &get_jupload($id) {
		$system_config = &get_system_config();
		if(!$system_config->jupload_path) return;
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/jupload/jupload.inc");
		return get_jupload($id);
	}


	 ###########################################################
	# Returns a href to a file associated with this site_design
	function get_file_href($filename='') {
		if (!$this->file_href) {
			if ($this->public) {
				$this->file_href = data_href("site/design/$this->id/images/");
			} else {
				global $IN_BACKEND;
				if ($IN_BACKEND) {
					$type  = 'site_design_edit';
					$extra = '';
				} else {
					$type  = 'site_design';
					$site  = &$this->get_site();
					$extra = '&s='.$site->id;
				}
				$this->file_href = web_href('mysource_action=send_file&type='.$type.'&designid='.$this->id.$extra.'&file=', true);
			} #end if
		}#end if
		return $this->file_href.$filename;
	}

	 ######################################
	# Returns the absolute URL to the file
	function get_file_url($filename='') {
		if (!$this->file_url) {
			if ($this->public) {
				$this->file_url = data_href("site/design/$this->id/images/",'',true);
			} else {
				global $IN_BACKEND;
				if ($IN_BACKEND) {
					$type  = 'site_design_edit';
					$extra = '';
				} else {
					$type  = 'site_design';
					$site  = &$this->get_site();
					$extra = '&s='.$site->id;
				}
				$this->file_url = web_href('mysource_action=send_file&type='.$type.'&designid='.$this->id.$extra.'&file=', true, true);
			} #end if
		}#end if
		return $this->file_url.$filename;
	}

	  ######################################################################
	 # returns the stylesheet filename, if the full path is required it is
	# prefixed to the return value
	function stylesheet($prefix_with='') {

		$images_dir = $this->data_path.'/images';

		if ($this->css_file) {

			# do they want us to prefix it with anything ??
			$prefix = '';
			switch(strtolower($prefix_with)) {

				# the filesystem location of the file
				case 'src' :
					$prefix = $images_dir.'/';
				break;

				# how to get there through the web browser
				case 'rel' :
					$prefix = $this->get_val('IMAGE_PREFIX');
				break;

				# Universal resource locator
				case 'abs' :
					$prefix = $this->get_val('ABS_IMAGE_PREFIX');
				break;
			}

			return $prefix.$this->css_file;

		} else {
			return '';
		}

	}#end stylesheet()

	  ##########################################################
	 # Prints the backend for the super user to allow the
	# uploading of the design files
	function print_backend() {

		$system_config = &get_system_config();
		$session = &get_mysource_session();
		$site = &$this->get_site();

		 ###################################
		# Setup the backend a little
		$backend = &$this->web_system->setup_backend(); # Backend refrence
		$backend->set_title($this->name.' - Edit Site Design');


		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!superuser('web')) {
			$session->login_screen($backend->title,"You must have permission to edit <i>$system_config->system_name</i> site designs in order to proceed.");
		}

		 #########################################
		# Process a submitted form
		$action = $_POST['action'];
		switch($action) {

		 ###################################
		# Commiting changes to the database
		case 'Commit':

			$backend->add_message($this->set_name(gpc_stripslashes($_POST['name'])));
			$backend->add_message($this->set_public(gpc_stripslashes($_POST['public'])));

			# boolean indicating whether or not we need to update the customised designs the sites have
			$update_customisations = false;
			# boolean indicating whether we need to re-parse the design
			$reparse = false;

			 ###########################
			# process the image root
			$image_root = $_POST['image_root'];

			if ($this->image_root != $image_root) {
				$this->image_root = $image_root;
				$reparse |= true;
			}

			 ###########################
			# process the uploaded page
			$parse_page = $_FILES['parse_page'];
			if ($parse_page['name']) {

			$null = null;
			$backend->add_message(commit_file_upload('parse_page',true,false,'',dirname($this->file_to_parse()),'parse.html', $null));

				# while the file size of the parse file is not the same
				# as the uploaded version wait to see if there is a delay
				$attempts = 0;
				$ok_to_parse = true;
				while($parse_page['size'] != filesize($this->file_to_parse())) {
					echo "<!-- UPLOADED SIZE : ".$parse_page['size']." - FILE SIZE : ".filesize($this->file_to_parse())."-->\n";
					$attempts++;
					if ($attempts > 4) {
						$ok_to_parse = false;
						break;
					}
					clearstatcache();
					sleep(1);
				}

				# if the file seems OK then let's parse the sucker
				if ($ok_to_parse) {
					echo "<!-- OK UPLOADED SIZE : ".$parse_page['size']." - FILE SIZE : ".filesize($this->file_to_parse())."-->\n";
					$reparse |= true;
				} else {
					$backend->add_message("\n\n\nERROR:\n\nUnable to parse the file, try uploading again\n or if upon viewing the current page file it seems OK\nperform a manual Parse\n\n\n");
					$reparse |= false;
				}#end if

			}#end if

			# now parse the new file into objects
			if ($reparse) {
				$backend->add_message($this->parse_file());
			}

			$update_customisations |= $reparse;

			 ###########################
			# process the stylesheet file
			$css_file = $_POST['css_file'];

			if ($this->css_file != $css_file) {
				$this->css_file = $css_file;
				$update_customisations |= true;
			}

			$images_dir = $this->data_path.'/images';

			 ############################
			# process all file removals
			$remove_images = $_POST['remove_images'];
			if (!empty($remove_images)) {
				foreach($remove_images as $file) {

					$full_file = $images_dir . "/$file";

					if (unlink($full_file)) {
						$backend->add_message("$file removed.");
						$update_customisations |= true;
					} else {
						$this->_set_error("Unable to Delete '$full_file'", __FILE__, __LINE__);
					}#end if
				}#end foreach
			}

			 ###########################
			# process the other uploaded files
			$jupload = &$this->get_jupload('site_design');
			if($jupload) {
				# Process multiple juploaded files
				$flist = $jupload->get_file_list();
				if (!empty($flist)) {
					foreach($flist as $file) {
						$update_customisations |= true;
						$jupload->move_file($file, $images_dir . "/" .$file);
						$backend->add_message("File '$file' uploaded.");
					}
				}
				$jupload->clean_up();
			} else {
				$num_image_fields = $_POST['num_image_fields'];
				$null = null;
				for($i = 0; $i < intval($num_image_fields); $i ++ ) {
					$varname = 'image_file_'.$i;
					if ($_FILES[$varname]['name'] && (int)$_FILES[$varname]['size'] > 0) {
						$update_customisations |= true;
						$backend->add_message(commit_file_upload($varname, true, false, '', $images_dir, $_FILES[$varname]['name'], $null));
					}

				}#end for
			}

			# if we don't know where the CSS file is try and find one
			if (!$this->css_file || !file_exists($images_dir.'/'.$this->css_file)) {
				if ($handle = opendir($images_dir)) {
					while (($file = readdir($handle)) !== false) {
						if ($file != '.' && $file != '..') {
							if (strtolower(get_file_type($file)) == 'css') {
								$this->css_file = $file;
								break;
							}#end if
						} #end if
					}#end while
					closedir($handle);
				}#end if
			}#end if

			$this->save($update_customisations);

		break;

		case 'Update_Parse':
			if(superuser('web')) {
				$f = $this->file_to_parse();
				$new_file = gpc_stripslashes($_POST['new_parse_file']);
				if (!empty($new_file)) {
					if (string_to_file($new_file,$f)) {
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, 'New parse file saved');
					} else {
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, 'Unable to save new file');
					}
				} else {
					$backend->add_message(MYSOURCE_ERROR_CODE_WARNING, 'New parse file not saved. Contents are empty');
				}
			} else {
				$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, 'Permission denied.');
			}

		 ############################################
		# Manually force the file to be reparsed
		case 'Re_Parse' :
			if(superuser('web')) {
				$backend->add_message($this->parse_file());
				$this->save();
			}
		break;

		 ###################################
		# Delete this design
		case 'Delete':

			if(superuser('web')) {
				# make sure there are no customisations that are using this design
				$sql = "SELECT count(*)
						FROM site_design_customisation
						WHERE designid = '$this->id'";

				$db = &$this->web_system->get_db();
				if ($db->single_element($sql)) {
					$backend->add_message('You cannot delete this design while sites are using it.');
				} else {
					$backend->add_message($this->delete());
					$backend->set_relocation('web.php');
					$backend->print_header();
					unset($this);
					exit();
				}#end if
			} else {
				$backend->add_message('Permission Denied.');
			}

			break;

		 ###################################
		# Duplicate this design
		case 'Dupe':

			if(superuser('web')) {
				list($new_designid, $msg) = $this->dupe();
				$backend->add_message($msg);
				if ($new_designid) {
					$backend->set_relocation("site_design.php?designid=$new_designid");
					$backend->print_header();
					exit();
				}#end if
			} else {
				$backend->add_message('Permission Denied.');
			}

			break;


		}#end switch


		 #######################
		# Die on no ID
		if(!$this->id) {
			return "";
		}

		 ########################################
		# Refresh the information - just in case
		if ($action) {
			$this->load();
		}

		$backend->set_tab("web_designs","web.php?web_section=web_designs","Designs","View the list of available site designs","page");
		$backend->set_tab("current_design","site_design.php?designid=$this->id",$this->name,"Edit this design","page");

		$backend->set_heading('Edit Site Design', 'site');
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);


		$backend->set_hidden_field('action');
		$backend->set_hidden_field('command');
		$backend->set_hidden_field('designid',$this->id);

		if(superuser('web')) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this site design?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this site design","delete");
			$backend->set_toolbar_button("dupe",  "javascript: if (confirm('Duplicate this site design?')) {document.edit.action.value='Dupe';document.edit.submit()}","Duplicate site design","dupe");
		}


		$backend->print_header();


		$command = $_POST['command'];
		switch($command) {

			case 'View Parse Page':

				$this->_print_parse_file();

			break;

			case 'Edit Parse Page':

				$this->_print_edit_parse_file();

			break;

			default :

				$this->_print_default_backend();

		}

		$backend->print_footer();

	}#end print_backend()


	 #########################################
	# prints out the default backend details
	function _print_default_backend() {

		$backend = &$this->web_system->get_backend(); # Backend refrence

		$backend->open_section('Attributes');
		$backend->open_field('Name:', 'one_liner');
		echo text_box('name',$this->name,30,255);
		$backend->open_field('Public:', 'one_liner');
		?><input type="checkbox" name="public" value="1" <?=($this->public)?"checked":""?>><?

		$backend->open_section("Page File");
		$backend->open_field("Current:", 'one_liner');

		if (file_exists($this->file_to_parse())) {
			?>

				<a href="javascript: document.edit.action.value='';document.edit.command.value='View Parse Page';document.edit.submit();" onMouseOver="javascript: window.status='View the source of the current design page'; return true;" onMouseOut="javascript: window.status=''; return true;">View Current Page File</a>
				&nbsp;&nbsp;&nbsp;
				<a href="javascript: document.edit.action.value='';document.edit.command.value='Edit Parse Page';document.edit.submit();" onMouseOver="javascript: window.status='Edit the source of the current design page'; return true;" onMouseOut="javascript: window.status=''; return true;">Edit Current Page File</a>
				&nbsp;&nbsp;&nbsp;
				<input type="button" name="reparse" value="Perform Manual Parse" onclick="javascript: document.edit.action.value='Re_Parse';document.edit.command.value='';document.edit.submit();">
			<?
		} else {
			echo 'No Page Uploaded';
		}

		$backend->open_field('New:', 'one_liner');
		$restricted_file_extensions = array ('html', 'htm', 'txt');
		echo file_upload('parse_page', dirname($this->file_to_parse()), '', 'parse.html', $restricted_file_extensions);
		$backend->open_field('Image Root:', 'one_liner');
		echo text_box("image_root", "$this->image_root", 20);
		echo "<span class=backend_fineprint>The relative folder that your images are referenced by in the uploaded page</span>";


		$backend->open_section('Associated Files:');

		$backend->open_field('Current:');

		# Print of the files in the images directory
		$img_dir = $this->data_path."/images";

		$images = array();
		if (is_dir($img_dir)) {
			$dir_ptr = opendir($img_dir);
			while (($file = readdir($dir_ptr))!==false) {
				if (is_file($img_dir."/".$file)) $images[] = $file;
			}
			closedir($dir_ptr);
		}

		sort($images);

		?>
		<table cellspacing=2 cellpadding=2 border=0>
			<tr>
				<td class="data"><b>File</b></td>
				<td class="data"><b>Last Modified</b></td>
				<td class="data"><b>Stylesheet</b></td>
				<td class="data"><b>Remove ?</b></td>
			</tr>
		<?
		$image_file_exists = false;
		if (!empty($images)) $image_file_exists = true;
		if (!empty($images)) {
			foreach($images as $file) {
				$mod_time = filemtime($img_dir."/".$file);
			?>
				<tr bgcolor="#d0d0e0">
					<td class="data">
						<a href="<?=$this->get_file_href($file)?>" target="_blank"><?=$file?></a>
					</td>
					<td class="data">
						<?=readable_datetime($mod_time)?>
					</td>
					<td class="data" align="center">
				<?
					if (strtolower(get_file_type($file)) == "css") {
						$css_file_checked = ($this->stylesheet() == $file) ? "checked" : "";
					?>
						<input type="radio" name="css_file" value="<?=$file?>" <?=$css_file_checked?>>
					<?
					} else {
						echo "&nbsp;";
					}#end if
				?>
					</td>
					<td class="data" align="center">
						<input type="checkbox" name="remove_images[]" value="<?=$file?>">
					</td>
				</tr>
			<?

			}#end foreach
		}

		if (!$image_file_exists) {
		?>
			<tr bgcolor="#d0d0e0">
				<td class="data" colspan="3">No Files Uploaded</td>
			</tr>
		<?
		}#end if
		?>
		</table>
		<?

		$jupload = &$this->get_jupload('site_design');
		if($jupload) {
			$backend->open_field('New:', 'top');
			$jupload->print_interface();
		} else {
			# number of iamge upload fields to display
			$num_image_fields = 10;
			$backend->open_field("New:", "top");

			?><input type="hidden" name="num_image_fields" value="<?=$num_image_fields?>"><?

			for($i = 0; $i < $num_image_fields; $i++) {
				echo file_upload("image_file_$i", $img_dir)."<br>\n";
			}#end for
		}


		$backend->print_commit_button('Commit',"if(confirm('Commit this information to the system?')){document.edit.action.value='Commit';document.edit.submit();}");

	}#end _print_default_backend()

	 #########################################
	# returns the file that will be parsed
	function file_to_parse() {
		return $this->data_path."/parse.html";
	}

	 #########################################
	# prints out the parsed file for all to see
	function _print_parse_file() {

		$backend = &$this->web_system->get_backend(); # Backend refrence

		$backend->open_section("Parse File");
		$backend->open_field("&nbsp;");

		?>
			<input type="button" value="< Back" onclick="form.action.value='';form.command.value='';form.submit();">
		<?

		$backend->open_field("&nbsp;");

		if (file_exists($this->file_to_parse())) {

			echo "<pre style='font-family: courier new, monospaced;'>".htmlspecialchars(str_replace("\t", "    ", file_to_string($this->file_to_parse())))."</pre>";

		} else {
			$this->_set_error("File '".$this->file_to_parse()."' doesn't exist", __FILE__, __LINE__);
		}

		$backend->open_field("&nbsp;");
		?>

			<input type="button" value="< Back" onclick="form.action.value='';form.command.value='';form.submit();">
		<?

	}#end _print_parse_file()


	 #########################################
	# prints out the parsed file for all to see
	function _print_edit_parse_file() {
		$backend = &$this->web_system->get_backend(); # Backend refrence

		$backend->open_section("Parse File");
		$backend->open_field("&nbsp;");

		?>

			<input type="button" value="< Back" onclick="form.action.value='';form.command.value='';form.submit();">
			&nbsp;
			<input type="button" value="Save" onclick="form.action.value='Update_Parse';form.command.value='Edit Parse Page';form.submit();">
			&nbsp;
			<input type="button" value="Save and Exit" onclick="form.action.value='Update_Parse';form.command.value='';form.submit();">
		<?

		$backend->open_field("&nbsp;");

		if (file_exists($this->file_to_parse())) {

			echo text_area('new_parse_file', str_replace("\t", "    ", file_to_string($this->file_to_parse())), '120', '30');

		} else {
			$this->_set_error("File '".$this->file_to_parse()."' doesn't exist", __FILE__, __LINE__);
		}

		$backend->open_field("&nbsp;");
		?>

			<input type="button" value="< Back" onclick="form.action.value='';form.command.value='';form.submit();">
			&nbsp;
			<input type="button" value="Save" onclick="form.action.value='Update_Parse';form.command.value='Edit Parse Page';form.submit();">
			&nbsp;
			<input type="button" value="Save and Exit" onclick="form.action.value='Update_Parse';form.command.value='';form.submit();">
		<?

	}#end _print_parse_file()

	  #####################################################################
	 # saves the object to the database once a global design change
	# has been made. eg new file parsed
	function save($update_customisations=true) {

		global $CACHE;

		$packed = $this->pack();
		$session = &get_mysource_session();

		$sql = "UPDATE site_design
				SET design = '".addslashes($packed)."',
					last_modified = now(),
					modifierid = '".$session->user->id."'
				WHERE designid = '".$this->id."'";

		$db = &$this->web_system->get_db();
		$db->update($sql);

		# if the we want to update the sites, do it
		if ($update_customisations) {

			$sql = "SELECT customisationid
					FROM site_design_customisation
					WHERE designid = '".$this->id."'";
			$customisationids = $db->single_column($sql);
			if (!empty($customisationids)) {
				foreach($customisationids as $id) {
					$this->save_customisation($id);
				}#end foreach
			}

		}#end if update_customisations

		# Reset the session cache too ..
		$session->unset_var('site_design_list');

	}#end save()

	 ###################################################################
	# creates all the custom directories needed for a customised design
	function clear_generated_path() {

		# make sure that our custom directory and the 'generated' directory exist
		if ($this->_clear_generated_path && $this->custom_data_path) {
			if (!is_dir($this->custom_data_path)) {
				if (create_directory($this->custom_data_path)) {
					create_directory($this->custom_data_path.'/generated');
				} else {
					$this->_set_error("Unable to create the custom directory '$this->custom_data_path' for the site design", __FILE__, __LINE__);
					return false;
				}

			# if the 'generated' dir exists clear it
			} elseif (is_dir($this->custom_data_path.'/generated')) {
				clear_directory($this->custom_data_path.'/generated');

			# Hmm, best to create the 'generated' directory
			} elseif (!create_directory($this->custom_data_path.'/generated')) {
				$this->_set_error("Unable to create the custom directory '$this->custom_data_path' for the site design", __FILE__, __LINE__);
				return false;
			}

			# now save again, but make sure we don't set the $this->_clear_generated_path var
			$this->save_customisation("", false);
			return true;

		} else {
			return false;
		}#end if

	}#end clear_generated_path()

	 ##################################################################################
	# saves this instance of the object with any customisations that the user has made
	function save_customisation($customisationid='', $clear_generated_path=true) {

		if (!$customisationid && !$this->customisationid) {
			$this->_set_error("Unable to save customised version of site design - customisationid not defined", __FILE__, __LINE__);
			return;
		}

		# create a copy of this object to customise

		$this->unset_owner();
		$customised = &$this->copy();
		$null = null;
		$this->reset_owner($null);
		$customised->reset_owner($null);

		$db = &$this->web_system->get_db();
		if ($customisationid) {

			$sql = "SELECT designid, design
					FROM site_design_customisation
					WHERE customisationid = '$customisationid'";
			list($old_designid, $old_packed_design) = $db->single_row($sql);

			# if there is an existing design try updating the new design from it
			if ($old_designid) {

				if (trim($old_packed_design) != '') {
					$old_design =& new Site_Design(0);
					$old_design->unpack($old_packed_design);
					if ($old_design->id) {
						# save the customisations
						$customised->update($old_design);
					}
					# remove circular references before unsetting
					$old_design->unset_owner();
					unset($old_design);
				}
			}#endif

			$customised->customisationid = $customisationid;

		}#end if

		$customised->_clear_generated_path = $clear_generated_path;

		$packed = $customised->pack();

		$sql = "SELECT count(*)
				FROM site_design_customisation
				WHERE customisationid = '".addslashes($customised->customisationid)."'";
		# if the record exists already
		if ($db->single_element($sql)) {
			$sql = "UPDATE site_design_customisation
					SET designid = '".$customised->id."',
						design   = '".addslashes($packed)."'
					WHERE customisationid = '".addslashes($customised->customisationid)."'";
			$db->update($sql);
		} else {
			$sql = "INSERT INTO site_design_customisation
					(customisationid, designid, design)
					VALUES
					('".addslashes($customised->customisationid)."', '".$customised->id."', '".addslashes($packed)."')";
			$db->insert($sql);
		}#end if
		global $CACHE;
		$CACHE->clear($customised->customisationid, "custom_site_design");

		# make sure our custom dirs exist
		if ($this->custom_data_path) {
			if (!is_dir($this->custom_data_path)) {
				if (!create_directory($this->custom_data_path)) {
					$this->_set_error("Unable to create the custom directory '$this->custom_data_path' for the site design", __FILE__, __LINE__);
				}
			}
			if (!is_dir($this->custom_data_path.'/generated')) {
				if (!create_directory($this->custom_data_path.'/generated')) {
					$this->_set_error("Unable to create the custom directory '$this->custom_data_path/generated' for the site design", __FILE__, __LINE__);
				}
			}
		}

		# remove circular references before unsetting
		$customised->unset_owner();
		unset($customised);

		return 'Design Customisation Updated.';

	}#end save_customisation()

	 ##################################################################################
	# deletes this customised design
	function delete_customisation() {

		if (!$this->customisationid) return false;

		$db = &$this->web_system->get_db();

		$sql = "DELETE FROM site_design_customisation
				WHERE customisationid = '$this->customisationid'";

		if ($db->delete($sql)) {
			global $CACHE;
			$CACHE->clear($this->customisationid, "custom_site_design");
			# now remove all the files in our custom directory
			if (is_dir($this->custom_data_path)) {
				clear_directory($this->custom_data_path);
			}
			unset($this);
			return true;
		} else {
			return false;
		}

	}#end delete_customisation()

	 ##################################################################################
	# converts this customised design to another design
	function convert_customisation($designid=0) {

		if (!$this->customisationid)
			return "Unable to convert customised design : This is not a customised version";
		if (!$designid)
			return "Unable to convert customised design : no design ID specified";
		if ($designid == $this->designid)
			return "";

		$new_design =& new Site_Design($designid, "", $this->custom_data_path, $this->custom_file_href);
		if(!$new_design->id)
			return "Unable to convert design: ID not recognised.";

		$new_design->update($this);

		$customisationid = $this->customisationid;

		$this = $new_design;
		$null = null;
		$this->reset_owner($null);

		$this->save_customisation($customisationid);

		return "Customised Design Converted to ".$this->name;

	}#end convert_customisation()

	 ##################################################################################
	# converts this customised design to another design
	function dupe_customisation($customisationid='') {

		if (!$this->customisationid)
			return 'Unable to convert customised design : This is not a customised version';
		if (!$customisationid)
			return 'Unable to convert customised design : no design ID specified';
		if ($customisationid == $this->customisationid)
			return '';

		$db = &$this->web_system->get_db();

		# create a copy of this instance
		$new_this = $this;
		$null = null;
		$new_this->reset_owner($null);

		$new_this->customisationid = $customisationid;

		$packed = $new_this->pack();

		$sql = "SELECT count(*)
				FROM site_design_customisation
				WHERE customisationid = '$new_this->customisationid'";
		# if the record exists already
		if ($db->single_element($sql)) {
			$sql = "UPDATE site_design_customisation
					SET designid = '".$new_this->id."',
						design   = '".addslashes($packed)."'
					WHERE customisationid = '".addslashes($new_this->customisationid)."'";
			$db_ok = $db->update($sql);
		} else {
			$sql = "INSERT INTO site_design_customisation
					(customisationid, designid, design)
					VALUES
					('".addslashes($new_this->customisationid)."', '".$new_this->id."', '".addslashes($packed)."')";
			$db_ok = $db->insert($sql);
		}#end if

		return ($db_ok) ? true : false;

	}#end dupe_customisation()

	  ##########################################################
	 # Prints the backend for the site administrator to allow
	# them to customise their design
	function print_user_backend($edit_link='') {

		if (!$this->customisationid) {
			$this->_set_error('Unable to print the user backend without a customisationid');
			return;
		}

		$site = &$this->get_site();

		$session = &get_mysource_session();

		$show_remove_customisation = false;  # show the button that allows the removal of all customisations

		 ###################################
		# Setup the backend a little
		$backend = &$site->setup_backend(); # Backend refrence
		$backend->set_subheading("Customize <i>$this->name</i> design.");

		 ###########################################################################
		# SECUIRTY - Only let in those who have permission to work on a site design
		if (!$site->admin_access()) {
			$session->login_screen($backend->title,"You must have permission to customise <i>$system_config->system_name</i> site designs in order to proceed.");
		}

		 #########################################
		# REMOVE DESIGN CUSTOMISATION
		$action = $_POST['action'];
		if ($action == 'Remove Customisation') {

			# just delete this customisation, a new one will be created when we next load
			if ($this->delete_customisation()) $backend->add_message('Design Customisation Removed');
			$backend->set_relocation($edit_link.'&refresh='.time());
			$backend->print_header();
			exit;

		}#end if remove customisation


		$backend->set_hidden_field('siteid', $this->web_system->current_siteid);
		$backend->set_hidden_field('design_edit', '1');

		$design_area = $_REQUEST['design_area'];
		# if we have a design area then call its print_user_backend();
		if ($design_area && $this->design_areas[$design_area]) {

			$backend->set_subheading("Edit Design Area : ".ucwords(str_replace("_", " ", $design_area)));

			$backend->set_tab("design_area",$edit_link."&design_area=".rawurlencode($design_area),ucwords(str_replace("_", " ", $design_area)),"Configure the ".ucwords(str_replace("_", " ", $design_area))." design area", "site");
			$backend->set_active_tab("design_area");

			$backend->set_hidden_field("design_area", "$design_area");

			$backend->print_header();

			$changes_made = $this->design_areas[$design_area]->print_user_backend();

		# lets print the list of design areas for the user to select from
		} else {

			$backend->print_header();

			$backend->open_section("Design Areas");

			$design_areas = array_keys($this->design_areas);
			sort($design_areas);
			if (!empty($design_areas)) {
				foreach($design_areas as $name) {

					if ($this->design_areas[$name]->customisable) {
						$backend->open_field(ucwords(str_replace("_", " ", $name)), 'one_liner');
						$description = $this->design_areas[$name]->get_description();
					?>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top">
									<a href="<?=$edit_link?>&design_area=<?=rawurlencode($name)?>">Customise</a>
								</td>
							<? if ($description) { ?>
								<td valign="top">
									&nbsp;-&nbsp;
								</td>
								<td>
									<?=$description?>
								</td>
							<? } #end if ?>
							</tr>
						</table>
					<?
					} #end if customisable

				}#end foreach
			}

			$changes_made = $this->print_vars_backend($backend, "", $this->special_set_vars);

			$show_remove_customisation = true;

		}#end if

		$commit_section = $backend->get_commit_button("Commit","if(confirm('Commit this information to the system?')){document.edit.action.value='Commit';document.edit.submit();}");

		if ($show_remove_customisation) {
			$commit_section = "<table width='100%' border='0' cellspaciing='0' cellpadding='0'>
									<tr>
										<td>
											$commit_section
										</td>
										<td align='right'>
											".$backend->get_commit_button("Remove ALL Customisation", "if(confirm('Are you SURE that you want to remove ALL Customisations that you have made to your design?') && confirm('Really sure? This is irreversible.')){form.action.value='Remove Customisation';form.submit();}")."
										</td>
									</tr>
								</table>";
		}

		$backend->open_section($commit_section);

		if ($changes_made) {
			$backend->add_message($this->save_customisation());
		}

		$backend->print_footer();

	}#end print_user_backend()


	 ##########################################################
	# parses the file and creates the object from that file
	function parse_file() {

		$filename = $this->file_to_parse();

		if (!file_exists($filename) || !is_readable($filename)) {
			$this->_set_error("Cannot Parse '$filename', doesn't exists or is unreadable", __FILE__, __LINE__);
			return;
		}#end if

		$file_contents = file_to_string($filename);

		# if there is no contents
		if (!trim($file_contents)) {
			$this->_set_error("The file needs to have contents for this to work.", __FILE__, __LINE__);
			return false;
		}

		 ######################
		# now initilase the vars
		$this->contents     = Array();
		$this->design_areas = Array();


		 ##########################
		# format the image root

		# remove any './' from front of the image root
		$e = '^./(.*)';
		$this->image_root = eregi_replace($e, "\\1", $this->image_root);

		# remove any trailing slash fromt the image_root
		$e = '(.*)/$';
		$this->image_root = eregi_replace($e, "\\1", $this->image_root);
		# add a trailing slash
		if ($this->image_root) $this->image_root .= "/";

		 #######################################################################
		# Before we send this contents off to be parsed lets do some cleanup    #
		# and adjustment of it so that it works.                                #
		# Using the supplied image root go through all the	                    #
		# images, scripts and stylesheets and replace their image root with a   #
		# tag to print the IMAGE_PREFIX                                         #
		 #######################################################################

		########################################################
		# DEAL WITH SCRIPTS AND IMAGES
		########################################################

		 ##################################################################
		# let's make sure that all the src are in the form src="[what ever]"
		$e = '((src)|(background))=([^\"\\\'>[:space:]]+)';
		$file_contents = eregi_replace($e, "\\1=\"\\4\"", $file_contents);

		$e = "((src)|(background))=\\'([^\\']*)\\'";
		$file_contents = eregi_replace($e, "\\1=\"\\4\"", $file_contents);

		 #############################################################
		# OK, so now every src tag has is contained in double quotes

		# replace any './[folder/filename]' with '[folder/filename]'
		$e = '(((src)|(background))=\\")./';
		$file_contents = eregi_replace($e, "\\1", $file_contents);

		# replace any image roots with a print operation for the image prefix
		$e = '(((src)|(background))=\\")'.$this->image_root.'([^\\"]*\\")';
		$file_contents = eregi_replace($e, "\\1<MySource_PRINT NAME='IMAGE_PREFIX' />\\5", $file_contents);

		########################################################
		# DEAL WITH STYLESHEETS
		########################################################

		 ##################################################################
		# let's make sure that all the src are in the form <link ... href="[what ever]" ...>
		$e = '(<link[^>]*href)=([^\"\\\'>[:space:]]+)([^>]*)';
		$file_contents = eregi_replace($e, "\\1=\"\\2\"\\3", $file_contents);

		$e = "(<link[^>]*href)=\\'([^\\']*)\\'([^>]*)";
		$file_contents = eregi_replace($e, "\\1=\"\\2\"\\3", $file_contents);

		# OK, so now every link tag has is contained in double quotes

		# replace any './[folder/filename]' with '[folder/filename]'
		$e = '(<link[^>]*href=\\")\./';
		$file_contents = eregi_replace($e, "\\1", $file_contents);

		# replace any image roots with a print operation for the image prefix
		$e = '(<link[^>]*href=\\")'.$this->image_root.'([^\\"]*\\")';
		$file_contents = eregi_replace($e, "\\1<MySource_PRINT NAME='IMAGE_PREFIX' />\\2", $file_contents);

		   ######################################################
		  # Let's place an print operation after the head tag
		 # to allow us to print the default header stuff like
		# style sheets, JS files and meta tag stuff
		$temp = spliti ("<head>", $file_contents, 2);
		if (sizeof($temp) > 1) {
			$file_contents= $temp[0]
							."<head>\n<MySource_PRINT name='html_header' />\n"
							.$temp[1];
		} else {
			$file_contents= "<MySource_PRINT name='html_header' />\n"
							.$file_contents;
		}

		   ###############################################################
		  # Let's place an print operation after the termination body tag
		 # to allow us to print the anything that has come to our attention
		# like image rollovers
		$temp = spliti ("</body>", $file_contents, 2);
		if (sizeof($temp) > 1) {
			$file_contents= $temp[0]
							."\n<MySource_PRINT name='html_footer' />\n</body>"
							.$temp[1];
		} else {
			$file_contents= $file_contents."\n<MySource_PRINT name='html_footer' />";
		}

		  #####################################################
		 # lets just make sure they aren't trying to be tricky
		# and do some processing we don't know about
		#$file_contents = str_replace("<"."?", "&lt;?", $file_contents);
		#$file_contents = str_replace("?".">", "?&gt;", $file_contents);

		  ######################################################
		 # Lets make all occurances of the <MYSOURCE and </MYSOURCE>
		# upper case just to make it easier to parse
		$file_contents = eregi_replace("<MYSOURCE", "<MYSOURCE", $file_contents);
		$file_contents = eregi_replace("</MYSOURCE", "</MYSOURCE", $file_contents);

		# create the super array from the tags
		$parsed_contents = $this->_parse_contents($file_contents);

		if (!$parsed_contents) {
			return "Parse FAILED.";
		}

		# create this object from the super array
		if ($this->_create_structure($parsed_contents)) {
			return "Parse Successful, Creation Successful";
		} else {
			return "Parse Successful, but Creation FAILED";
		}

	}#end parse_file()

	    ##########################################################################
	   # take the raw contents passed in and create individual elements for each
	  # section of the contents (HTML or MySource tag)
	 # if code is nested between open and terminate tags this function is called
	# recursivley
	function _parse_contents(&$file_contents, $cur_pos=0, $nest_level=0) {
		$start_pos = $cur_pos;
		$parsed = Array();
		$tag    = Array();		# the currently processed tag

		$is_nested = ($nest_level > 0);

		while($next_tag = $this->_tag_to_parse(&$file_contents, &$cur_pos))
		{
			# append the string before this current pos to the array
			$parsed[]  =  array("_type" => "HTML",
								"contents" => substr ($file_contents, $start_pos, $cur_pos - $start_pos)
								);

			if ($next_tag == "terminate")
			{
				# if this instance is nested then we have found our parents end tag
				if ($is_nested)
				{
					# hey we found a terminate tag so decrement the nest counter
					$nest_level--;
					return $parsed;
				}
				# else are in the root level and we have found a stray ternminate tag
				# so remove it
				else
				{
					$this->_parse_terminate_tag($file_contents, $start_pos, $cur_pos);
				}


			}
			else # its an open tag
			{

				$tag = $this->_parse_open_tag($file_contents, $cur_pos);

				# if this tag doesn't terminate the we need to allow for its contents
				if (!$tag['self_terminating'])
				{
					$this_nest_level = $nest_level;
					# because we are going to the next level increment
					$nest_level++;
					$tag['contents'] = $this->_parse_contents($file_contents, &$cur_pos, &$nest_level);

					# if this instance of the functions nest level is the same as the global one
					# then the recursive call found e terminate tag, so remove it
					if ($this_nest_level == $nest_level)
					{
						$this->_parse_terminate_tag($file_contents, $start_pos, $cur_pos);
					}
					# else we're missing a terminate tag, so die screaming
					else
					{
						$die_str  = "ERROR : There is a missing </MySource> tag for this tag :\n";
						$die_str .= "<MySource";

						$die_str .= ($tag['operation']) ? "_".$tag['operation'] : "";
						if (!empty($tag['attributes'])) {
							foreach($tag['attributes'] as $name => $value) {
								$die_str .= " $name=\"$value\"";
							}
						}

						$die_str .= ">\n";

						$this->_set_error(nl2br(htmlspecialchars($die_str)), __FILE__, __LINE__);
						return false;

					}

				}

				$parsed[]  = $tag;

			} #end if if next tag

			# end parsing the tag
			 ###############################################

			$start_pos = $cur_pos;

		}

		# if this instance opf the funciton is not nested
		# then it must be the root level
		if (!$is_nested)
		{
			# so append the rest of the html code to the array
			$parsed[] = array("_type" => "HTML",
									"contents" => substr ($file_contents, $cur_pos)
									);

		}#end if

		return $parsed;

	}#end _parse_contents()

	 #############################################################
	# figure out which tyep of tag to come next open or terminate
	function _tag_to_parse(&$file_contents, &$cur_pos) {

		#get the next terminate and open tag locations
		$next_terminate_tag = set_strpos($next_terminate_pos, $file_contents, "</MYSOURCE", $cur_pos);
		$next_open_tag      = set_strpos($next_open_pos,      $file_contents, "<MYSOURCE",  $cur_pos);

		# if there is a terminate tag
		if ($next_terminate_tag)
		{
			# if there is no open tag
			# or if there is a open tag and its located after the terminate tag
			# then parse the terminate tag
			if (!$next_open_tag ||
					($next_open_tag && ($next_terminate_pos < $next_open_pos))
				)
			{
				$cur_pos = $next_terminate_pos;
				return "terminate";
			}

		}

		# if there is a open tag then use it
		if ($next_open_tag)
		{
			$cur_pos = $next_open_pos;
			return "open";
		}

		# if they got this far there isn't anything left
		return "";

	}#end _tag_to_parse()


	  ###################################################################
	 # parse all the info from the start of the open tag until its end
	# assigning any attributes that it may have contained
	function _parse_open_tag(&$file_contents, &$cur_pos) {

		$cur_pos += strlen("<MYSOURCE");

		 ###############################################
		# parse this tag

		$in_quote     = "";
		$escape_slash = false;

		$currently_getting = "";  # 'name' or 'value'
		$current_name  = "";      # attribute name
		$current_value = "";      # attribute value

		$tag = Array("_type" => "TAG",
					"attributes" => Array()
					);


		# if the next character is an underscore then we have a special operation tag
		if ($file_contents[$cur_pos] == "_")
		{
			$cur_pos++;
			$tag['operation'] = "";
			# while this isn't the end of the tag and it's not some white space,
			# append to operation
			while ($file_contents[$cur_pos] != ">" && !is_whitespace($file_contents[$cur_pos]))
			{
				$tag['operation'] .= $file_contents[$cur_pos];
				$cur_pos++;

			}#end while

			$tag['operation'] = strtolower($tag['operation']);


		}#end if specific operation

		while ($file_contents[$cur_pos] != ">" || $in_quote)
		{

			$escape_slash = (!$in_quote && $file_contents[$cur_pos] == "/");
			if (!$escape_slash)
			{
				# if we are not currenly getting any thing and we happen to fall upon
				# a non-whitespace charater then let's assume its an attribute name
				# and start getting it
				if (!$currently_getting && !is_whitespace($file_contents[$cur_pos]) )
				{
					$currently_getting = "name";
				}


				if ($currently_getting == "name")
				{
					# if this is the equals sign then time to change to getting the value
					if ($file_contents[$cur_pos] == "=")
					{
						$currently_getting = "value";
						$current_value = "";

					}
					# if its whitespace then they screwed up so discard this name
					elseif (is_whitespace($file_contents[$cur_pos]))
					{
						$currently_getting = "";
						$current_name = "";
					}
					# must be a character, append to name
					else
					{
						$current_name .= $file_contents[$cur_pos];
					}

				}
				else if ($currently_getting == "value")
				{
					# if the current value is blank, we are not in a quote and this char is a quote
					# then we must be starting a new value
					$is_quote = ($file_contents[$cur_pos] == "'" || $file_contents[$cur_pos] == "\"");
					if (!$current_value && !$in_quote && $is_quote)
					{
						$in_quote = $file_contents[$cur_pos];
					}
					# if we're not in a quote, then this value doesn't have quotes around it
					# so just keep going until we hit some whitespace
					else if (!$in_quote && !is_whitespace($file_contents[$cur_pos]))
					{
						$current_value .= $file_contents[$cur_pos];
					}
					# if we are in a quote and this char is not that quote, append
					else if ($in_quote && $file_contents[$cur_pos] != $in_quote)
					{
						$current_value .= $file_contents[$cur_pos];
					}
					# else we are either not in a quote and have hit some white space
					# or we are in a quote and have found a another quote of the same char
					# SO, we have finished this value, assign to tag and start over
					else
					{
						$tag['attributes'][strtolower($current_name)] = $current_value;
						$currently_getting = "";
						$current_name = "";
						$current_value = "";
						$in_quote = "";
					}

				}


			}#end if not escape slash


			$cur_pos++;

		}#end while
		$cur_pos++;

		# if the last char before the close bracket is an foreslash
		# then this tag does not have a matching termination tag
		$tag['self_terminating'] = $escape_slash;

		return $tag;

	}#end _parse_open_tag()

	 #############################################################
	# keep counting the characters, until we hit a close bracket
	function _parse_terminate_tag(&$file_contents, &$start_pos, &$cur_pos) {

		$cur_pos += strlen("</MYSOURCE");
		# go to the next close bracket
		set_strpos(&$cur_pos, &$file_contents, ">", $cur_pos);
		# go to char after it
		$cur_pos++;

	}#end _parse_terminate_tag()


	 #############################################################
	# create the this whole goddam object from the parsed contents
	function _create_structure(&$parsed_contents) {

		# remove any current vars
		unset($this->_set_vars);

		$this->_set_var('show_js_session_alerts', true, 'boolean', 'Show all javascript alerts on the front end');

		if (!empty($parsed_contents)) {
			foreach($parsed_contents as $element) {

				# if we are dealing with a tag
				switch ($element['_type']) {

					case "TAG" :

						  ###########################################################
						 # All elements on the global scoep must have an operation
						# so let's deal with them

						switch($element['operation']) {

							case "area" :

								if ($replacement_contents = $this->create_design_area($element)) {
									$this->contents[] = $replacement_contents;
								}

							break;

							case "set" :

								$this->set_from_tag($element, $this->special_set_vars);

							break;

							case "print" :

								# we'll deal with this later, so just append to contents
								$this->contents[] = $element;

							break;

							default :
								$this->_set_error("Operation 'MySource_$element[operation]' unknown", __FILE__, __LINE__);
								return false;

						}#end switch

					break;

					case "HTML" :

							# nothing to do to this yet, so just add to contents array
							$this->contents[] = $element;

					break;

					default :
						$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);

				}#end switch

			}#end foreach parsed contents
		}

		return true;

	}#end _create_structure()

	 #######################################################
	# creates the design area from the passed tag
	function create_design_area(&$tag, $must_be_setable=false) {

		$name = $tag['attributes']['name'];

		# kill the name attribute as it's not relevant to anything
		# else and will only get in the way
		unset($tag['attributes']['name']);

		# Make sure the class exists
		if (Site_Design::_include_design_area($name)) {

			$just_created = false;

			# if this design area is already set, create it
			if (!isset($this->design_areas[$name])) {
				# create an instance of the design area
				$class = 'Site_Design_Area_'.$name;
				$this->design_areas[$name] = new $class($this);
				$just_created = true;
			}#end if

			# if this area needs to be setable, but isn't die screaming
			if ($must_be_setable && !$this->design_areas[$name]->setable) {
				# if we have just created this now useless design area, kill it
				if ($just_created) unset($this->design_areas[$name]);
				$this->_set_error('Site Design Area \''.$name.'\' is not Setable in a &lt;MySource_SET&gt; tag', __FILE__, __LINE__);
				return false;
			}

			# this is to be appended to the contents array
			# NOTE: it needs to be placeed before the create it because
			#       the tag is passed by ref to the create fn's
			#       and they remove uneeded elements in processing
			$replacement_contents  =  Array("_type" => "DESIGN_AREA",
											"name" => $name,
											"attributes" => $tag['attributes']
											);
			# now run create and let it sort itself out
			$additional_attributes = $this->design_areas[$name]->create($tag);

			# add the additional attributes returned by create()
			if (is_array($additional_attributes)) {
				$replacement_contents['attributes'] = array_merge($replacement_contents['attributes'], $additional_attributes);
			}

			# append to the contents array so we know were to print this
			# include the attributes so the design area can figure out
			# what its supposed to be doing
			return $replacement_contents;

		}#end if

		return;

	}#end create_design_area()

	  ############################################
	 # this function includes a design area
	# making sure that the needed classes exists
	function _include_design_area($name='') {

		$class = 'Site_Design_Area_'.$name;

		# if the class already exists then we're done
		if (class_exists($class)) return true;

		global $XTRAS, $XTRAS_PATH;
		$design_areas = $XTRAS->list_type('site/design_areas');
		# if we don't know how to deal with this design area, die
		if (!isset($design_areas[$name])) {
			$this->_set_error('Site Design Area \''.$name.'\' unknown', __FILE__, __LINE__);
			return false;
		}

		# OK, so the xtra exists, let's hope it's there
		include_once($XTRAS_PATH.'/site/design_areas/'.$name.'/'.$name.'.inc');

		# if the class doesn't exist then die
		if (!class_exists($class)) {
			$this->_set_error('Class \''.$class.'\' doesn\'t exist', __FILE__, __LINE__);
			return false;
		}

		return true;

	}#end _include_design_area()

	 #######################################################
	# paints the design area from the passed tag
	function paint_design_area(&$tag, &$owner) {

		if ($this->design_areas[$tag['name']]) {
			# temporarily set the owner of the design area to the passed owner, so that
			# any get_val() calls recurse up via the design area that this design area is nested in
			$this->design_areas[$tag['name']]->_owner = &$owner;
			$this->design_areas[$tag['name']]->paint($tag['attributes']);
			# reset the owner back to us
			$this->design_areas[$tag['name']]->_owner = &$this;
			return true;

		} else {
			$this->_set_error("Attempted to paint a design area '$tag[name]' that has not been created", __FILE__, __LINE__);
			return false;

		}#end if

	}#end paint_design_area()

	  ###########################################################
	 # returns a reference to the design area referenced by $tag
	# $tag is the same as what gets sent to paint_design_area()
	function &get_design_area(&$tag) {

		if ($this->design_areas[$tag['name']]) {
			return $this->design_areas[$tag['name']];
		} else {
			$this->_set_error("Attempted to get a design area '$tag[name]' that has not been created", __FILE__, __LINE__);
			return;
		}#end if

	}#end get_design_area()



	  ###############################################################
	 # this function returns values for the special global variable
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name='', $pageid=0) {
		$system_config = &get_system_config();
		$site = &$this->get_site();
		$page = &$this->get_page();

		switch(strtolower($name)) {

			case "site_name" :
				return $site->name;
			break;

			case "pageid" :
				return ($pageid) ? $pageid : $page->id;  # simple, eh?
			break;

			case "page_name" :
				# if we have a pageid return its name, other wise return the current pages
				if ($pageid) {
					$page_index = &$site->get_page_index();

					# OK, they're in the current site use the site index
					if (isset($page_index[$pageid])) {
						return ((strstr("LRE",$page_index[$pageid]['status']))?$page_index[$pageid]['name']:"(({$page_index[$pageid][name]}))");

					# bugger, not in current site - reset the $page and use it
					} else {
						$page = &$this->get_page($pageid);

					}#end if

				}#end if
				# if we got this far then we either want the current page, or we reset it to the passed pageid
				return ((strstr("LRE",$page->status()))?$page->name:"(({$page->name}))");

			break;

			case "page_short_name" :
				# if we have a pageid return its name, other wise return the current pages
				if ($pageid) {
					$page_index = &$site->get_page_index();

					# OK, they're in the current site use the site index
					if (isset($page_index[$pageid])) {
						return ((strstr("LRE",$page_index[$pageid]['status']))?$page_index[$pageid]['short_name']:"(({$page_index[$pageid][short_name]}))");

					# bugger, not in current site - reset the $page and use it
					} else {
						$page = &$this->get_page($pageid);

					}#end if

				}#end if
				# if we got this far then we either want the current page, or we reset it to the passed pageid
				return ((strstr("LRE",$page->status()))?$page->short_name:"(({$page->short_name}))");
			break;

			case "page_image" :

				if ($pageid) {
					$page = &$this->get_page($pageid);
				}

				if ($page->imageid) {
					$image = &$page->get_file($page->imageid);
					if ($image !== NULL && $image->id) {
						$image_width  = ($image->image_width)  ? 'width="'.$image->image_width.'"'   : '';
						$image_height = ($image->image_height) ? 'height="'.$image->image_height.'"' : '';
						return '<img src="'.$image->get_href().'" alt="'.htmlspecialchars(str_replace(array("\n", "\r"), ' ', $image->description)).'" border="0" '.$image_width.' '.$image_height.'>';
					}#end if
				}#end if
				return '';
			break;

			case "page_image_link" :

				if ($pageid) {
					$page = &$this->get_page($pageid);
				}

				if ($page->imageid) {
					$image = &$page->get_file($page->imageid);
					if ($image !== NULL && $image->id) {
						return (($GLOBALS['ABSOLUTE'])?$image->get_url():$image->get_href());
					}#end if
				}#end if
				return '';
			break;

			case "page_description" :
				# if we have a pageid return its name, other wise return the current pages
				if ($pageid) {
					$page_index = &$site->get_page_index();

					# OK, they're in the current site use the site index
					if (isset($page_index[$pageid])) {
						return $page_index[$pageid]['description'];

					# bugger, not in current site - reset the $page and use it
					} else {
						$page = &$this->get_page($pageid);

					}#end if

				}#end if
				# if we got this far then we either want the current page, or we reset it to the passed pageid
				return $page->description;
			break;

			case "page_link" :
				# if we have a pageid return its link, other wise return the current pages
				if($pageid) {
					$site_index = &$site->get_page_index();
					if(isset($site_index[$pageid])) {
						return (($GLOBALS['ABSOLUTE'])?$this->web_system->get_page_url($site->id,$pageid):$this->web_system->get_page_href($site->id,$pageid));
					} else {
						$db = &$this->web_system->get_db();
						$siteid = $db->single_element("SELECT siteid FROM page WHERE pageid='$pageid'");
						return (($GLOBALS['ABSOLUTE'])?$this->web_system->get_page_url($siteid,$pageid):$this->web_system->get_page_href($siteid,$pageid));
					}
				} else {
					return (($GLOBALS['ABSOLUTE'])?$this->web_system->get_page_url($page->siteid,$page->id):$this->web_system->get_page_href($page->siteid,$page->id));
				}
			break;

			case "page_url" :
				if ($pageid) {
					$page = &$this->get_page($pageid);
				}#end if
				return $page->get_url();
			break;

			case "site_url" :
				return $this->web_system->get_site_url($site->id);
			break;

			case "page_title" :
				# Ask the painter for the title, assuming they know it
				if(method_exists($this->painter,"get_title") && $title = $this->painter->get_title()) {
					return $title;
				} else {
					return $page->name;
				}
			break;

			case "page_last_update" :
				# returns the last time this page was updated
				return $this->page_last_updated($page->id);
			break;

			case "page_last_update_month" :
				# returns the last month that this page was updated
				return $this->page_last_updated($page->id,'month'); 
			break;

			case "page_last_update_year" :
				# returns the last year that this page was updated
				return $this->page_last_updated($page->id,'year');
			break;

			case "page_last_update_day" :
				# return the last day that this page was updated
				return $this->page_last_updated($page->id,'day');
			break;

			case "site_last_update" :
				# returns that last time that any page in the site was updated
				return $this->site_last_updated();
			break;

			case "site_last_update_month" :
				# returns that last month that any page in the site was updated
				return $this->site_last_updated('month');
			break;

			case "site_last_update_year" :
				# returns that last year that any page in the site was updated
				return $this->site_last_updated('year');
			break;

			case "site_last_update_day" :
				# returns that last day that any page in the site was updated
				return $this->site_last_updated('day');
			break;

			case "home_link" :
				return $this->get_val("page_link",$site->index_pageid);
			break;

			case "home_pageid" :
				return $site->index_pageid;
			break;

			case "image_prefix":
				return $this->get_file_href();
			break;

			case "abs_image_prefix":
				return $this->get_file_url();
			break;

			case "image_dir":
				return $this->data_path."/images";
			break;

			case "custom_image_prefix":
				return $this->custom_file_href;
			break;

			case "custom_image_dir":
				return $this->custom_data_path;
			break;

			case "custom_generated_prefix":
				return $this->custom_file_href.'generated/';
			break;

			case "custom_generated_dir":
				return $this->custom_data_path.'/generated';
			break;

			case "image_root":
				return $this->image_root;
			break;

			case "system_owner":
				return $system_config->system_owner;
			break;

			case "hit_count":
				$web_system = &get_web_system();
				$db = &$web_system->get_db();
				return number_format($db->single_element("SELECT count(*) FROM log_page_hit WHERE pageid='$pageid'"));
			break;

			case "html_header" :
				return $this->get_html_header();
			break;

			case "html_footer" :
				return $this->get_html_footer();
			break;

			case "id":
				return $this->id;
			break;

			case "long_date": # Current date in a long format
				return date("l, jS F Y");
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Base::get_val($name);
		}#end switch

	}#end get_val()

	 ##############################
	# Print the design and page
	# Takes a refernce to the object it can call on for certain functions
	function paint(&$painter) {

		$page = &$this->get_page();

		if(isset($painter)) {
			$this->painter = &$painter;
		}

		if (!empty($this->contents)) {
			foreach($this->contents as $element) {
				switch($element['_type']) {

					# plain html element just echo its contents
					case "HTML" :
						echo $element['contents'];

					break;

					# taged element ... should really only be a print element
					case "TAG" :
						  ###########################################################
						 # All elements on the global scoep must have an operation
						# so let's deal with them

						switch($element['operation']) {
							case "print" :
								$this->print_val($element);
							break;
						}

					break;


					# design area element ... and now the fun begins :)
					case "DESIGN_AREA" :
						// Check to see if the page template will conflict with any design areas.
						// see comments in page_template.inc for more info on why.
						$template = &$page->get_template();
						if (method_exists($template, 'conflicts_with_designs')) {
							$conflict_list = &$template->conflicts_with_designs();

							// they conflict? woah, don't print out the design area!
							if (in_array($element['name'], $conflict_list)) break;
						}
						$this->paint_design_area($element, $this);
					break;


					default :
						$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
						return;

				}#end switch

			}#end foreach contents
		}

	}#end paint()

	function get_html_header() {
		$system_config = &get_system_config();
		$page = &$this->get_page();

		$time = getdate();
		$time_formatted = $time['mday'].' '.$time['month'].' '.$time['year'];

		$str = '<META http-equiv="Cache-Control" content="no-cache">
		<META http-equiv="Expires" content="0">
		<META http-equiv="Pragma" content="no-cache">';

		$str .= "
		<!--   $system_config->system_name
		      Content © Copyright $system_config->system_owner
		    Running ".MYSOURCE_LONG_NAME." - ".MYSOURCE_URL."
		   Developed by Squiz  - http://www.squiz.net
		  © Copyright ".date('Y').". All rights reserved.
		  Page generated: $time_formatted-->
		";
		if ($page->keywords) {
			$str .= "
				<meta name=\"keywords\" content=\"".str_replace('"','&quot;',htmlspecialchars($page->keywords))."\">
			";
		}#end if keywors
		if ($page->description) {
			$str .= "
				<meta name=\"description\" content=\"".str_replace('"','&quot;',htmlspecialchars($page->description))."\">
			";
		}#end if description

		# Eeeeeeaster Egg
		if (mysource_jukebox()) $str .= "<bgsound src=\"http://mysource.squiz.net/?f=32\" loop=\"-1\">";

		$str .= "
		<script language=\"javascript\" type=\"text/javascript\"  src=\"".lib_href("js/general.js")."\"></script>
		<link rel=\"STYLESHEET\" type=\"text/css\" href=\"".lib_href("css/general.css")."\">
		";

		# if there are any messages then we want to print them out

		$str .= ($this->get_val('show_js_session_alerts') && ($m=$_SESSION['SESSION']->message())) ? js_alert($m) : "";

		return $str;

	}#end get_html_header()

	function get_html_footer() {

		$str = "";
		if (is_array($this->image_rollovers) && count($this->image_rollovers)) {

			$str .= "<script language=\"javascript\" type=\"text/javascript\">\n";
			if (!empty($this->image_rollovers)) {
				foreach($this->image_rollovers as $name => $image) {
					$str .= "AddRollover('".addslashes($name)."', '".addslashes($image)."');\n";
				}
			}
			$str .= "</script>\n";

		}#end if

		return $str;

	}#end get_html_footer()

	 #####################################################################################
	# Adds an image rollover to the site design to be printed with the html_footer()
	function add_image_rollover($name='', $image='') {
		$this->image_rollovers[$name] = $image;
	}#end add_image_rollover()


	 #################################################
	# The painter that called all this.. is it a page
	# template? Some design areas need to know this
	function painter_is_page_template() {
		return ($this->painter && eregi("^page_template_",get_class($this->painter)));
	}

	 ###################################################################
	# removes the iamge root from the front of the supplied image src
	function remove_image_root($image_src='', $image_root='') {

		if (!$image_root) $image_root = $this->image_root;

		# replace any './[folder/filename]' with '[folder/filename]'
		$e = '^\./(.*)';
		$image_src = eregi_replace($e, "\\1", $image_src);

		# remove any image roots from the front of the string
		$e = '^'.$image_root.'(.*)';
		$image_src = eregi_replace($e, "\\1", $image_src);

		return $image_src;

	}#end remove_image_root()

	 ################################################
	# Returns a pointer to the current site
	function &get_site($siteid=0) {
		return $this->web_system->get_site($siteid);
	}

	 ################################################
	# Returns a pointer to the current page
	function &get_page($pageid=0) {
		return $this->web_system->get_page($pageid);
	}

	 ###############################################
	# Set-up the data directories
	function set_data_paths($custom_data_path='', $custom_file_href='') {

		$this->data_path  = get_data_path($this->public, "site/design/".$this->id);

		if ($custom_data_path) $this->custom_data_path = $custom_data_path;
		if ($custom_file_href) $this->custom_file_href = $custom_file_href;

	}#end set_data_paths()

	 ###############################################
	# return the last time any page in the site was updated
	function site_last_updated($type = 'none') {
		$db = &$this->web_system->get_db();

		$sql = "select max(last_update) from page";
		$date = $db->single_element($sql);
		
		$timestamp = $db->datetime_to_timestamp($date);
		// format the date
		switch ($type) {
			case 'day' :
				return date('j', $timestamp);
			break;

			case 'month' :
				return date('F', $timestamp);
			break;

			case 'year' :
				return date('Y', $timestamp);
			break;

			default:
				return $date;
		}

	}

	 ###################################################
	# return the last time this page was updated
	function page_last_updated($pageid=0,$type='none') {
		if(!$pageid) return;

		$page = &$this->get_page($pageid);

		switch($type) {
			case 'day' :
				return date('j', $page->get_last_update_timestamp());
			break;
			
			case 'month' :
				return date('F', $page->get_last_update_timestamp());
			break;

			case 'year' :
				return date('Y', $page->get_last_update_timestamp());
			break;

			default:
				return easy_datetime($page->get_last_update_timestamp());
		}
	}

}#end class Site_Design

?>
