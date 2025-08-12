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
## File: include/file.inc
## Desc: Functions for dealing with "file"s in the system
## $Source: /home/cvsroot/mysource/include/file.inc,v $
## $Revision: 2.48.2.2 $
## $Author: achadszinow $
## $Date: 2004/05/24 04:46:23 $
#######################################################################
#
include_once("$INCLUDE_PATH/html_form.inc");
include_once("$INCLUDE_PATH/text.inc");
#---------------------------------------------------------------------#


 ###################################################################
# This class represents a file attachment.
class File extends WebObject {

	# Administrative
	var $id = 0;
	var $pageid = 0; # The page this file attached to
	var $siteid = 0; # The site this file is attached to
	var $data_path = ''; # Directory where this file is stored.

	# Descriptive
	var $filename = '';
	var $description = '';
	var $keywords = '';

	# Order among peers
	var $orderno = 0;
	
	# Other stats
	var $type = '';
	var $size = 0;
	var $created = '0000-00-00';
	var $modified = '0000-00-00';
	# these to only filled in if the file is an image
	var $image_width = '';
	var $image_height = '';

	# Visible.. does it appear on the page?
	var $visible = 'Y';

	# Do we force logging of downloads
	var $log_hits = 'N';

	# For reference when safe-editing of pages.
	var $replaceid = 0;

	 ##############################
	# Constructor
	function File($fileid=0) {
		
		WebObject::WebObject();

		# Set the data path here as objects without records in the database need it
		$page = &$this->get_page();
		$this->pageid    = $page->id;
		$this->siteid    = $page->siteid;
		$this->data_path = $page->data_path;

		 #################################################
		# Load the information if an id is specified
		if ($fileid) {
			return $this->load($fileid);
		}
	}

	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element('data_path',$result);
		return $result;
	}#end __sleep()


	# Returns a reference to this files page  object
	function &get_page($pageid=0) {
		if(!$pageid) $pageid = $this->pageid;
		return $this->web_system->get_page($pageid);
	}

	# Returns a reference to this files site object
	function &get_site($siteid=0) {
		if(!$siteid && !($siteid = $this->siteid)) {
			$page = &$this->get_page();
			$siteid = $page->siteid;
		}
		return $this->web_system->get_site($siteid);
	}

	 ##########################################
	# Returns a URL that'll point to this file
	function get_url() {
		$page = &$this->get_page($this->pageid);
		return $this->web_system->get_file_url($this->siteid,$this->pageid,$this->id,(($page->usessl)?true:false));
	}

	 ####################################################
	# Returns a relative HREF to this page on the front
	function get_href() {
		return $this->web_system->get_file_href($this->siteid,$this->pageid,$this->id);
	}

	 ######################################################################
	# Create a new file record in the database and load it into this object
	function create($pageid=0,$filename='',$description='',$keywords='',$visible='Y',$orderno=0,$log_hits='N', $replaceid=0) {
		$db = &$this->get_db();
		$page = &$this->get_page($pageid);	
		if (!$page->id)
			return "Unable to create file: invalid pageid.";
		if(!$filename)
			return "Unable to create file: invalid filename.";
		if($visible != 'Y' && $visible != 'N') {
			$visible = 'Y';
		}
		if($log_hits != 'Y' && $log_hits != 'N') {
			$log_hits = 'N';
		}
		if(!$orderno) {
			$orderno = 1 + $db->single_element("SELECT max(orderno) FROM file WHERE pageid='$page->id'");
		}
		$this->data_path = $page->data_path;

		# Can only create an object if the file exists on disk
		if (file_exists("$this->data_path/$filename")) {
			# Update page's modification date
			# Return new fileid
			if(!$fileid = $db->insert("INSERT INTO file (pageid, filename, description, keywords, visible, log_hits, orderno, replaceid) VALUES ('$page->id','".addslashes($filename)."','".addslashes($description)."','".addslashes($keywords)."','".addslashes($visible)."','".addslashes($log_hits)."','".addslashes($orderno)."','".addslashes($replaceid)."')"))
				return "Unable to create file: database error.";
		} else {
			return "Unable to create new file. Perhaps you have strange characters in the filename?";
			#report_error(__FILE__,__LINE__,"Attempt to create a file record without the file in place: $this->data_path/$filename");
		}
		# Okay, we have a file record.. inform the web system
		# lets pack stuff into it.
		$page->clear_cache();
		$page->updated();
		$page->load();

		$this->load($fileid);
		
		$this->web_system->register_new_file($this->id,&$this);
		$this->web_system->register_file_name($this->siteid,$this->pageid,$this->id,$this->filename);

		$message .= "File $filename created.\n";
		return ereg_replace("[\n\r]+","\n",$message);
	}



	 ################################################
	# Loads all the file information into the object
	function load($fileid=0) {

		$fileid = abs((int)$fileid);

		if (!$fileid && !($fileid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load file without a valid fileid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($fileid)) {
			$page = &$this->get_page($this->pageid);
			$this->data_path = $page->data_path;
			return $fileid;
		}

		 #######################################
		# Okay, no cache load from the database
		$db = &$this->get_db();
		list(
			$this->id,
			$this->pageid,
			$this->filename,
			$this->description,
			$this->keywords,
			$this->orderno,
			$this->visible,
			$this->log_hits,
			$this->replaceid
		) = $db->single_row("SELECT fileid, pageid, filename, description, keywords, orderno, visible, log_hits, replaceid FROM file WHERE fileid='$fileid'");

		if($this->id != $fileid) {
			return;
		}

		# Load some more stuff
		$page = &$this->get_page($this->pageid);
		$this->siteid = $page->siteid;
		$this->type = $this->get_file_type();
		$file_types_config = &get_config('file_types');
		if (!$file_types_config->file_types[$this->type]) $this->type = '';

		$this->data_path = $page->data_path;

		if (!is_file("$this->data_path/$this->filename")) return $fileid;

		$file_stat      = stat("$this->data_path/$this->filename");
		$this->size     = $file_stat[7];
		$this->created  = $file_stat[10];
		$this->modified = $file_stat[9];

		if (is_image($this->filename)) {
			$image_size = GetImageSize($this->data_path.'/'.$this->filename);
			if ($image_size !== NULL) {
				$this->image_width  = $image_size[0];
				$this->image_height = $image_size[1];
			}
		}

		 ###################
		# Save to the cache 
		$this->save_to_cache($this->id);

		return $fileid;
		
	}


	 ##########################################
	# Modified cache-saver and loading
	function clear_cache($id=0) {
		if(!$id) $id = $this->id;
		WebObject::clear_cache($id);
		$page = &$this->get_page();
		$page->clear_cache();
	}

	 ################################
	# Get the file type of this file
	function get_file_type($filename='') {
		if(empty($filename)) $filename = $this->filename;
		return get_file_type($filename);
	}

	 #############################
	# Returns a pointer to a file
	function &get_file($fileid=0) {
		if(!$fileid) return $this; # Idiot
		return $this->web_system->get_file($fileid);
	}


	 #######################################################
	# Should the file have its access unrestricted or not?
	function effective_unrestricted() {
		$page = &$this->get_page();
		return $page->effective_unrestricted(); 
	}

	 #####################################
	# Set the filename
	function set_filename($filename='',$new_upload=false) {
		if(!$filename) return 'Your file must have a name.';
		$old_filename = $filename;
		# Check this dodgy filename out
		$filename = url_friendly_filename($filename);
		$system_config = &get_system_config();
		if(!$system_config->check_file_legality($filename)) {
			return 'Illegal filename.';
		}
		if($new_upload && $filename != $old_filename && $filename != $this->filename) {
			while(file_exists("$this->data_path/$filename"))
				$filename = increment_filename($filename);
			rename("$this->data_path/$old_filename","$this->data_path/$filename");
		}
		if($filename == $this->filename) return '';
		# Check to see it doesn't already exist
		$db = &$this->get_db();
		if($db->single_element("SELECT f1.fileid FROM file f1, file f2 WHERE f1.filename='".addslashes($filename)."' AND f1.pageid=f2.pageid AND	f2.fileid='$this->id' AND f1.fileid != '$this->id'")) {
			return "Already a file with that name. Rename failed.";
		}
		if($this->web_system->register_file_name($this->siteid,$this->pageid,$this->id,$filename)) {
			$this->web_system->unregister_file_name($this->siteid,$this->pageid,$this->id,$this->filename);
			# If file is newly uploaded, then don't want to copy an old file into it
			if(!$new_upload) {
				if(file_exists("$this->data_path/$this->filename")) {
					rename("$this->data_path/$this->filename","$this->data_path/$filename");
				}
			} else {
				unlink("$this->data_path/$this->filename");
			}
			$db->update("UPDATE file SET filename='".addslashes($filename)."' WHERE fileid='$this->id'");
			$this->filename = $filename;
			$this->type = $this->get_file_type($filename);
			$this->clear_cache();
			$this->load();
			return "File renamed to $filename";
		}
		return "Unable to rename file.";
	}

	 #############################
	# Set this files description
	function set_description($description='') {
		if($description == $this->description) return "";
		$db = &$this->get_db();
		$db->update("UPDATE file SET description='".addslashes($description)."' WHERE fileid='$this->id'");
		$this->description = $description;
		$this->clear_cache();
		return "Description updated for file '$this->filename'.";
	}

	 ##############################
	# Set this files keywords
	function set_keywords($keywords='', $force=false) {
		if($keywords == $this->keywords) return "";
		if(!$force) {
			$page = &$this->get_page();
			$site = &$this->get_site();
			$keywords = retrieve_keywords($keywords,"$this->filename $page->name $page->description $description $site->name");
			if($keywords == $this->keywords) return "";
		}
		$db = &$this->get_db();
		$this->keywords = $keywords;
		$db->update("UPDATE file SET keywords='".addslashes($this->keywords)."' WHERE fileid='$this->id'");
		$this->clear_cache();
		return "Keywords updated for file '$this->filename'.";
	}

	 #####################################################
	# Extracts keywords from the file itself, if possible
	function extract_keywords() {
		global $XTRAS, $XTRAS_PATH;
		if($XTRAS->name("file/keyword_indexing",$this->type)) {
			include_once("$XTRAS_PATH/file/keyword_indexing/".$this->type."/run.inc");
			if($output = extract_words("$this->data_path/$this->filename")) {
				$page = &$this->get_page();
				$keywords = retrieve_keywords($output);
			} 
		}
		return $keywords;
	}

	 ################################################################
	# Explicitly sets the orderno of a file - usually done elsewhere
	function set_orderno($orderno=0) {
		if(!$orderno) return "";
		if($orderno == $this->orderno) return "";
		$db = &$this->get_db();
		$db->update("UPDATE file SET orderno='$orderno' WHERE fileid='$this->id'");
		$this->orderno = $orderno;
		$this->clear_cache();
		return "File '$this->filename' been reordered.";
	}

	 ########################################
	# Change the visibility of a file
	function set_visible($visible='Y') {
		if(!$visible) return "";
		if($visible != "Y" && $visible != "N") return "";

		if($visible == $this->visible) return "";
		$db = &$this->get_db();
		$db->update("UPDATE file SET visible='$visible' WHERE fileid='$this->id'");
		$this->visible = $visible;
		$this->clear_cache();
		return "File visibility updated.";
	}


	 ########################################
	# Change the forced logging of a file
	function set_log_hits($log_hits='N') {
		$log_hits = strtoupper($log_hits);
		if(!$log_hits) return "";
		if($log_hits != "Y" && $log_hits != "N") return "";
		if($log_hits == $this->log_hits) return "";

		$return_code = MYSOURCE_ERROR_CODE_NONE;
		$return_msg = '';

		if ($log_hits == 'Y') {
			$system_config = &get_system_config();
			if (!$system_config->log_visitors || $system_config->statistics_reporter == '') {
				$return_code = MYSOURCE_ERROR_CODE_WARNING;
				$return_msg .= "Even though this option is switched on, MySource is not currently logging any statistics.\n";
			}
		}

		$db = &$this->get_db();
		$db->update("UPDATE file SET log_hits='$log_hits' WHERE fileid='$this->id'");
		$this->log_hits = $log_hits;
		$this->clear_cache();
		$return_msg .= "File logging updated.";
		return array($return_code, $return_msg);
	}

	 ######################################
	# Set this files type
	function set_type($filename='') {
		if(!$filename) $filename = $this->filename;
		$this->type = $this->get_file_type($filename);
		$this->clear_cache();
		return;
	}

	 ####################################################
	# Set new pageid and move the file accordingly
	function set_pageid($new_pageid=0) {
		if(!$new_pageid) return "";
		if($new_pageid == $this->pageid) return "";
		$page     = &$this->get_page($this->pageid);
		$new_page = &$this->get_page($new_pageid);
		if (!$new_page->id) return "Unable to move file '$this->filename', invalid pageid '$new_pageid'.";

		$db = &$this->get_db();
		# only if we have an ID 
		if ($this->id) {
			if (rename("$this->data_path/$this->filename", "$new_page->data_path/$this->filename")) {
				# update url registry
				$this->web_system->unregister_file_name($page->siteid,$this->pageid,$this->id,$this->filename);
				# Now move the thumbnail, if there is one
				if (file_exists("$this->data_path/thumbs/file_thumb_$this->id.gif")) {
					rename("$this->data_path/thumbs/file_thumb_$this->id.gif", "$new_page->data_path/thumbs/file_thumb_$this->id.gif");
				}
				if (file_exists("$this->data_path/thumbs/file_thumb_$this->id.jpg")) {
					rename("$this->data_path/thumbs/file_thumb_$this->id.jpg", "$new_page->data_path/thumbs/file_thumb_$this->id.jpg");
				}
				$db->update("UPDATE file SET pageid='$new_pageid' WHERE fileid='$this->id'");
				$this->pageid = $new_pageid;
				$this->data_path = $new_page->data_path;
				$this->web_system->register_file_name($new_page->siteid,$this->pageid,$this->id,$this->filename);
				$page->clear_cache();
				$page->load();
				$this->clear_cache();
				$this->load();
				$new_page->clear_cache();
				$new_page->load();
				return "File '$this->filename' moved to page '$new_page->name'.";
			} else {
				return "Unable to move file '$this->filename' to page '$new_page->name'.";
			}#end if rename

		}#en if this->id
	}

	 ####################################
	# Dupe this file
	function dupe($new_pageid=0, $old_fileid=0, &$dupe_map, &$dupe_message) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');

		$db = &$this->get_db();
		$new_file = new File();
		$new_file->create($new_pageid,$this->filename,$this->description,$this->keywords,$this->visible,$this->orderno,$this->log_hits, $old_fileid);
		if(!$new_file->id) {
			$dupe_message .= "File duping failed for file '$this->filename'.\n";
			return 0;
		} else {
			$dupe_map['file'][$this->id] = $new_file->id;
			$new_file->clear_cache();
			return $new_file->id;
		}
	}

	 ###################################
	# Set new id for this file - BROKEN
	function newid ($newid=0) {
		$newid = (int) $newid;
		$this->clear_cache();

		# The file
		$db = &$this->get_db();
		if(!$db->update("UPDATE file SET fileid='$newid' WHERE fileid='$this->id'")) {
			return "Unable to change ID fror file '$this->filename'.";
		}

		$this->id = $newid;
		$this->clear_cache();
		return "File '$this->filename''s ID has been changed to $this->id";
	}

	 #############################################
	# Delete file from database and disk
	function delete() {
		# Delete from disk
		if (file_exists("$this->data_path/$this->filename")) {
			unlink("$this->data_path/$this->filename");
		}

		# Delete from database
		$db = &$this->get_db();
		if (!$result = $db->delete("DELETE FROM file WHERE fileid='$this->id'")) {
			report_error(__FILE__,__LINE__,"Unable to delete file database record: $this->id");
		}

		# Unregister the URL
		$this->web_system->unregister_file_name($this->siteid,$this->pageid,$this->id,$this->filename);

		$message .= "(FileID: $this->id) '$this->filename' deleted.\n";
		$this->clear_cache();
		unset($this);
		return ereg_replace("[\n]+","\n",$message);
	}

	 ####################################################
	# Prints the file backend
	function print_backend($hide_backend_tabs=false, $upload_box_only=false, $process_only=false,$disable_security=false) {
		global $XTRAS;
		$db      = &$this->get_db();
		$site    = &$this->get_site();
		$page    = &$this->get_page();
		$backend = &$page->setup_backend(); # Backend reference

		$session = &get_mysource_session();

		# In some cases we want people without write access to upload files
		if(!$disable_security) {

			 ####################################################################
			# SECURITY - Only let in those who have permission to work on a site
			if (!$session->logged_in()) {
				$session->login_screen($backend->title,"You must be logged in and have permission to edit the page <i>$this->name</i> in order to proceed.");
			}

			 ##################################
			# Can the user access this file  
			if (!$page->write_access()) {
				$session->login_screen($backend->title,'You do not have permission to work on this page.');
			}
		}

		 #########################################
		# Process a submitted form
		global $XTRAS_PATH;
		$action = $_POST['action'];
		$filename = $_POST['filename'];
		$description = $_POST['description'];
		$visible = $_POST['visible'];
		$log_hits = $_POST['log_hits'];
		$keywords = $_POST['keywords'];
		$extract_keywords = $_POST['extract_keywords'];
		$force_keywords = $_POST['force_keywords'];
		$new_pageid = $_POST['new_pageid'];
		switch($action) {
 			case 'Commit':
				$thefile_name = '';
				# Attempt to save the uploaded file
				$commit_msg = commit_file_upload('thefile', false, false, $this->filename, $this->data_path, $this->filename, &$thefile_name);

				# If this not a record and they have not uploaded anything
				if(!$thefile_name && !$this->id) {
					$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, $commit_msg);
					break;
				}
				$new_file_object = false;
				# They have uploaded something
				# lets clear the cache
				$this->clear_cache();
				if($thefile_name) {
					# If the file that they uploaded has a different name 
					# from the current one delete the old file
					if(isset($this->filename) && ($this->filename != $thefile_name) && is_file("$this->data_path/$this->filename")) {
						# Delete the old file
						unlink("$this->data_path/$this->filename");
					}
					# Commit the file
					$backend->add_message($commit_msg);
					# Is a new file
					if(!$this->id) {
						# set this so that anything below, that needs to, can know this is a new object
						$new_file_object = true; 
						# Create a file object
						$backend->add_message($this->create($page->id, gpc_stripslashes($thefile_name)));
					} #end if
					
					# If they have uploaded a new file AND changed the filename
					if($this->filename != $filename && trim($filename) != "") {
						$this->set_filename(gpc_stripslashes($thefile_name), true);
						$backend->add_message($this->set_filename(gpc_stripslashes($filename)));
					} else {
						$backend->add_message($this->set_filename(gpc_stripslashes($thefile_name), true));
					}

				} elseif ($filename) {
					# Filename is set and they have not uploaded anything
					$backend->add_message($this->set_filename(gpc_stripslashes($filename)));
				}
				$backend->add_message($this->set_description(gpc_stripslashes($description)));
				# If they want us to extract keywords from the file
				$keywords = gpc_stripslashes($keywords);
				if($extract_keywords) {
					if($new_keywords = $this->extract_keywords()) {
						$keywords .= " $new_keywords";
					} else {
						$backend->add_message("Could not extract any keywords from the file.");
					}
				}
				$backend->add_message($this->set_keywords($keywords, $force_keywords));
				$backend->add_message($this->set_visible($visible));
				$backend->add_message($this->set_log_hits($log_hits));

				# Lets do the thumbnails
				$upload_result = process_image_upload('thumbnail', "$this->data_path/thumbs/file_thumb_$this->id", false);
				$backend->add_message($upload_result['message']);
				# If this has been uploaded before a fileid has been created, 
				# we'll need to rename it
				if ($new_file_object) {
					# If the images directory exists
					create_directory($this->data_path.'/thumbs');
					if($dir = opendir("$this->data_path/thumbs"))  {
						while (false !== ($dir_file = readdir($dir))) { 
							if (substr($dir_file, 0, 12) == "file_thumb_.") {
								rename("$this->data_path/thumbs/$dir_file", "$this->data_path/thumbs/file_thumb_$this->id.".get_file_type($dir_file));
							}#end if
						}#end while
						closedir($dir); 
					}
				}

				# Shall we Move this page?
				if($this->pageid != $new_pageid) {
					$backend->add_message($this->set_pageid($new_pageid));
					$relocation = "page.php?p=$this->pageid&file_edit=1&fileid=$this->id";
					$backend->set_relocation($relocation);
				}
				$page->updated();
				
				break;
			case "Delete":
				$relocation = "page.php?p=$this->pageid&PARAMETER_SCREEN=page_attachments";
				if($this->id) $backend->add_message($this->delete());
				$backend->set_relocation($relocation);
				$page->updated();
				break;
		} # End switch

		# if we only wanted to process - return now
		if ($process_only) return;

		 ############################
		# Setup the backend a little
		$backend->set_title((($this->id) ? $this->filename : '[ New ]')." - Edit File");
		$backend->set_tab("file","page.php?pageid=$page->id&file_edit=1",(($this->id)?$this->filename:"[New]"),"Edit File","file");
		$backend->set_active_tab("file");

		# Put in a tab to create a new file if we are not already creating one   
		if ($this->id) {
			$backend->set_tab('newfile', 'page.php?p='.$this->pageid.'&file_edit=1', 'New File', 'Create a New File', 'file');
		}

		$backend->set_hidden_field("action","Commit");
		$backend->set_hidden_field("fileid",$this->id);
		$backend->set_hidden_field("pageid",$this->pageid);
		$backend->set_hidden_field("file_edit","true");
		$backend->set_hidden_field("current_userid",$session->user->id);

		$backend->set_heading("Edit File","file");
		$backend->set_subheading((($this->id)?$this->filename:"[New]"));
		$backend->set_id_string($this->id);
	
		# Do not have a dupe function if we are editing a file
		if($this->id) {
			$backend->remove_toolbar_button("dupe");
			$backend->remove_toolbar_button("dupe_one");
		}

		if(!empty($site->urls) && $this->id) {
			$backend->set_toolbar_button("preview", $this->get_href(), "Preview this file on the frontend.", "preview", "_blank");
		}
		
		if($page->delete_access()) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this file?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this file","delete");
		}


		 #################
		# Here we go now.
		if ((int) $this->id <= 0) {
			$backend->remove_toolbar_button('dupe');
			$backend->remove_toolbar_button('dupe_one');
			$backend->remove_toolbar_button('preview');
			$backend->remove_toolbar_button('delete');
		}


		if ($hide_backend_tabs) {
			$backend->hide_tabs();
		}
		$backend->print_header();
	
		if($this->id && file_exists($this->data_path."/".$this->filename)) $backend->open_field("Overwrite?",'one_liner');
		else $backend->open_field("Upload<br><span class=backend_fineprint>(required)",'one_liner');
		echo file_upload("thefile","$this->data_path",20000000);

		if (!$this->id) {
			$backend->print_commit_button("Commit","if(document.edit.thefile.value.length > 5){alert('Please wait while your file is transferred, this may take a while.');}document.edit.submit();");
			$backend->print_footer();
			return;
		}

		if (!$upload_box_only) {
			$backend->open_field("Move to page");
			$pages_array = $this->web_system->page_array_with_sticks();
			echo combo_box("new_pageid",$pages_array,(($this->pageid)?$this->pageid:$this->web_system->current_pageid),"class=data");

			$backend->open_field("Filename",'one_liner');
			echo text_box("filename",$this->filename, 40, 128, "class=data");

			$backend->open_field("Description","top");
			echo text_area("description",$this->description, 39, 6, 2000,"class=data");

			$backend->open_field("Keywords","top");
			echo text_area("keywords",$this->keywords, 39, 6, 2000,"class=data");
			if($XTRAS->name("file/keyword_indexing",$this->type)) {
				echo("<input type=hidden name=extract_keywords>");
				$backend->print_icon_button("javascript: document.edit.extract_keywords.value=1;document.edit.submit();","Extract keywords from the file.","page");
			}

			echo("<span class=backend_fineprint><br><input type=checkbox name=force_keywords>If changed, do not process for duplicates etc.</span>");

			$backend->open_field('File Type','one_liner');
			$file_types_config = &get_config("file_types");
			echo $file_types_config->file_types[$this->type];

			$backend->open_field("File Size",'one_liner');
			echo (($this->size) ? easy_filesize($this->size) : "Empty");

			$backend->open_field("Last Updated",'one_liner');
			echo (($this->modified) ? readable_datetime($this->modified) : "Never");

			$system_config = &get_system_config();

			if ($system_config->log_visitors && $system_config->statistics_reporter) {
				$backend->open_field("Log Hits?",'one_liner');
				echo "<input type=radio name=log_hits value=\"Y\"". (($this->log_hits=="Y" || !$this->log_hits)? " checked":"").">Yes<input type=radio name=log_hits value=\"N\"".(($this->log_hits=="N")?" checked":"").">No";
				echo("<span class=backend_fineprint><br>Logging hits will increase the time it takes to download this attachment.</span>");
			}

			$backend->open_field("Visible?",'one_liner');
			echo "<input type=radio name=visible value=\"Y\"". (($this->visible=="Y" || !$this->visible)? " checked":"").">Yes<input type=radio name=visible value=\"N\"".(($this->visible=="N")?" checked":"").">No";

			$backend->open_field('Thumbnail','top');
			echo image_upload(
							'thumbnail',
							"$this->data_path/thumbs/file_thumb_$this->id",
							$page->get_file_href("thumbs/"),
							20000,100,100);

		} else {
			?><input type="hidden" name="new_pageid" value="<?=(($this->pageid)?$this->pageid:$this->web_system->current_pageid)?>"><?

			?><input type="hidden" name="filename" value="<?=$this->filename?>"><?
			?><input type="hidden" name="description" value="<?=$this->description?>"><?
			?><input type="hidden" name="keywords" value="<?=$this->keywords?>"><?

			?><input type="hidden" name="visible" value="<?=$this->visible?>"><?
			?><input type="hidden" name="log_hits" value="<?=$this->log_hits?>"><?

		}#end if

		$backend->print_commit_button("Commit","if(document.edit.thefile.value.length > 5){alert('Please wait while your file is transferred, this may take a while.');}document.edit.submit();");

		$backend->print_footer();
	}

}
?>
