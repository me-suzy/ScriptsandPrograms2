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
## File: include/site.inc
## Desc: Functions for dealing with "site"s in the system
## $Source: /home/cvsroot/mysource/include/site.inc,v $
## $Revision: 2.95.2.2 $
## $Author: achadszinow $
## $Date: 2004/05/25 01:33:03 $
#######################################################################

#---------------------------------------------------------------------#

/**
* This class represents a web site. This could be an internet
* or intranet site. A Site is one of the core units of the MySource
* Web system.
*
* The backend interface is dealt with by the Site_Backend class, see that for
* more details on this 
*
* @access public
* @see Site_Backend
*
*/
class Site extends WebObject {

	var $data_path = '';  # Directory where this site keeps its files.

	# Administrative
	var $id = 0;
	var $name = '';
	var $description = '';

	# Designid and potentially loaded unserialized design object
	var $designid = 0;
	var $site_design; # Unserialized

	# Multilingual
	var $default_languages = '';
	var $default_charset = '';

	# The pages in a nicely accessible array
	var $page_index = array();
	var $index_pageid = 0; #  and the page first displayed when visiting the site

	# Urls pointed to this site
	var $urls = array();

	# Security - read
	var $public = 1; # Can people who aren't logged in view this site?
	var $access_groups = array(); # $id => $name Groups created within this site
	var $access_grants = array(); # A subset of $access_groupids - those granted access

	# Secuirty - write
	var $adminids       = array(); # People authorized to administrate the site
	var $editorids      = array(); # People authorized to edit parts of the site
	var $page_editorids = array(); # People authorized to edit certain pages on the site
	var $page_adminids  = array(); # People authorized to admin certain pages on the site

	# Maximum number of pages and files allowed to be created or uploaded to the site (0 = no limit)
	var $max_pages = 0;
	var $max_files = 0;
	var $max_file_bytes = 0; # Maximum total size of file archive

	# Allowed xtras etc.
	var $allowed_extensions = array(); # An array of all the site extension xtras permitted on the site
	var $allowed_templates  = array(); # An array of all the page template xtras permitted on the site
	var $allowed_designids  = array(); # An array of all the site designs permitted on the site

	var $extensions = array(); # An array of extension objects.. created as needed

	var $last_update = ''; # Timestamp of the last time anything was changed on the site.

	var $subpage_auto_order=0; # auto ordering of subpages

	var $not_found_pageid = 0; # A pageid of the page to display if it is not found
	var $forbidden_pageid = 0; # A pageid of th epage to be displayed if a user does not have access

	var $_site_backend;  # an object holding the current site_backend for this site


	 ##############################
	# Constructor
	function Site($siteid=0) {

		WebObject::WebObject();

		 #################################################
		# Load the information if an id is specified
		if ($siteid) {
			return $this->load($siteid);
		}
		unset($this->page_index);
	}

	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element("_site_backend",$result);
		array_remove_element("site_design",$result);
		return $result;
	}#end __sleep()

	 ######################################################################
	# Create a new site record in the database and load it into this object
	function create($name='',$designid=0) {
		$db = &$this->get_db();
		$session = &get_mysource_session();
		if (!$name)
			return "Unable to create site: please specify a name.";
		while($db->single_element("SELECT siteid FROM site WHERE name='".addslashes($name)."'")) {
			$name = increment_name($name," ");
		}
		if (!$siteid = $db->insert("INSERT INTO site (name) VALUES ('".addslashes($name)."')"))
			return "Unable to create site: database error.";

		# Okay, we have a site record.. inform the web system
		# lets pack stuff into it.
		$this->load($siteid);
		$this->web_system->register_new_site($this->id,$this);

		# Make a directories.
		$old_umask = umask(0);
		if (!create_directory($this->data_path)) {
			$this->delete();
			return false;
		}
		# Create a directory for images in the web path.
		if (!create_directory($this->data_path."/site_design")) {
			return "Unable to create site. Couldn't create data directory.";
		}

		umask($old_umask);
		$message = "Site $name created.\n";

		# Acknowledge the creator
		$message .= $this->add_adminid($session->user->id)."\n";

		# Set up the design, and allow it
		$message .= $this->update_allowed_designids(array($designid))."\n";
		$message .= $this->set_design($designid)."\n";

		# Allow some standard template Xtras
		$message .= $this->update_allowed_templates(array('standard','form','redirect','pullcontent'))."\n";

		# Allow some standard site extension Xtras

		# Public to begin with, to be nice
		$message .= $this->set_public(1)."\n";

		# Create a new page

		$index_page =& new Page();
		$index_page_result = $index_page->create("Welcome to $this->name","standard",$this->id,0);
		$message   .= $index_page_result[1];
		$message   .= $this->set_index_pageid($index_page->id);

		#$privacy_page =& new Page();
		#$privacy_page_result = $privacy_page->create("Privacy","standard",$this->id,0);
		#$message   .= $privacy_page_result[1];

		$this->clear_cache();

		return $message;
	}


	 ################################################
	# Loads all the site information into the object
	function load($siteid=0) {

		if (!$siteid && !($siteid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load site without a valid siteid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($siteid)) {
			 ##############################
			# Remember where we put things
			$this->data_path = get_data_path($this->public, "site/".(int)$siteid);
			return $siteid;
		}

		 #######################################
		# Okay, no cache load from the database
		$db = &$this->get_db();
		list(
			$this->id,
			$this->name,
			$this->description,
			$this->index_pageid,
			$this->not_found_pageid,
			$this->forbidden_pageid,
			$this->designid,
			$this->public,
			$this->max_pages,
			$this->max_files,
			$this->max_file_bytes,
			$this->default_languages,
			$this->default_charset,
			$this->last_update,
			$this->subpage_auto_order
		) = $db->single_row("SELECT siteid, name, description, index_pageid, not_found_pageid, forbidden_pageid, designid, public, max_pages, max_files, max_file_bytes, default_languages, default_charset, last_update, subpage_auto_order FROM site WHERE siteid='$siteid'");

		if($this->id != $siteid) {
			$this->_set_error("Unable to load site. Record does not appear to be in the database for siteid: $siteid",__FILE__,__LINE__);
			return;
		}

		# Load more stuff!
		$this->urls           = $db->associative_array("SELECT url,protocol FROM site_url WHERE siteid='$this->id' ORDER BY orderno");
		$this->access_groups  = $db->associative_array("SELECT groupid, name FROM access_group WHERE siteid='$this->id' ORDER BY name");
		$this->access_grants  = $db->single_column("SELECT groupid FROM site_access_grant WHERE siteid='$this->id'");
		$this->adminids       = $db->single_column("SELECT userid FROM site_admin WHERE siteid='$this->id'");
		$this->editorids      = $db->single_column("SELECT userid FROM site_editor WHERE siteid='$this->id'");
		$this->page_editorids = $db->single_column("SELECT page_editor.userid FROM page_editor LEFT JOIN page ON page_editor.pageid=page.pageid WHERE page.siteid='$this->id'");
		$this->page_editorids = $db->associative_column("SELECT page.pageid, page_editor.userid
														FROM page_editor LEFT JOIN page
														ON page_editor.pageid=page.pageid
														WHERE page.siteid='$this->id'");
		$this->page_adminids  = $db->associative_column("SELECT page.pageid, page_admin.userid
														FROM page_admin LEFT JOIN page
														ON page_admin.pageid=page.pageid
														WHERE page.siteid='$this->id'");
		$this->allowed_extensions = $db->single_column("SELECT extension
														FROM site_allowed_extension
														WHERE siteid='$this->id'");
		$this->allowed_templates  = $db->single_column("SELECT template
														FROM site_allowed_template
														WHERE siteid='$this->id'");
		$this->allowed_designids  = $db->single_column("SELECT designid
														FROM site_allowed_designid
														WHERE siteid='$this->id'");
		// parameters is serialized
		$this->adminids_parameters = $db->associative_array("SELECT userid, parameters
															 FROM site_admin
														     WHERE siteid='$this->id'");

		 ###################
		# Save to the cache
		$this->save_to_cache();

		# Some handy filey prefixes
		$this->data_path = get_data_path($this->public, "site/".(int)$siteid);

		return $siteid;

	}


	 ################################################################################
	# Returns an array of ids of all people allowed to work on some part of this site
	function staffids() {
		$r = array();

		if (!empty($this->adminids)) {
			foreach($this->adminids as $userid) {
				$r[] = $userid;
			}
		}

		if (!empty($this->editorids)) {
			foreach($this->editorids as $userid) {
				$r[] = $userid;
			}
		}

		if (!empty($this->page_editorids)) {
			foreach($this->page_editorids as $pageid => $userids) {
				if (!empty($userids)) {
					foreach($userids as $userid) {
						$r[] = $userid;
					}
				}
			}
		}

		if (!empty($this->page_adminids)) {
			foreach($this->page_adminids as $pageid => $userids) {
				if (!empty($userids)) {
					foreach($userids as $userid) {
						$r[] = $userid;
					}
				}
			}
		}

		return array_unique($r);
	}


	 ################################################
	# Clears the page index - changes have been made
	function clear_page_index($wipe_local_page_index=true) {
		global $CACHE;
		$CACHE->clear($this->id,'site_page_index');
		if ($wipe_local_page_index) {
			$this->page_index = array();
		}
	}


	 ################################
	# Returns a reference to a page
	function &get_page($pageid=0) {
		return $this->web_system->get_page($pageid);
	}

	 ##########################################
	# Returns a URL that'll point to this file
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function get_url($start_query_string=false,$force_secure=0) {
		$href = $this->web_system->get_site_url($this->id,$force_secure);
		if ($start_query_string) {
			# if there is a question mark then append an '&' otherwise append a '?'
			# also if the last char is a slash don't need to add another one
			$href .= (strchr($href, "?")) ? "&" : ((substr($href, -1) == "/") ? "" : "/")."?";
		}#end if
		return $href;
	}

	 ####################################################
	# Returns a relative HREF to this page on the front
	function get_href($start_query_string=false) {
		$href = $this->web_system->get_site_href($this->id);
		if ($start_query_string) {
			# if there is a question mark then append an '&' otherwise append a '?'
			# also if the last char is a slash don't need to add another one
			$href .= (strchr($href, "?")) ? "&" : ((substr($href, -1) == "/") ? "" : "/")."?";

		}#end if
		return $href;
	}

	 #########################################################################
	# Returns a href to a file associated with this site (e.g. a custom image)
	function get_file_href($filename='') {
		# if we are public then anyone can view our files so no piping
		if ($this->public) {
			return data_href("site/$this->id/$filename");
		# OK so we are going to need some security on this
		} else {
			return (($GLOBALS['ABSOLUTE'])?$this->get_url(true)."mysource_action=send_file&type=site&s=$this->id&file=$filename":$this->get_href(true)."mysource_action=send_file&type=site&s=$this->id&file=$filename");
		}
	}


	 #############################################
	# Returns a the href to this object's backend
	function get_backend_href() {
		global $EDIT_DIR;
		return "$EDIT_DIR/site.php?s=$this->id";
	}

	/**
	* Adds a page to the page index array
	*
	* @param boolean $save_page_index_cache Whether to save it to the page index cache or not
	* @param int $pageid The pageid of the page to add to the index
	* @param int $siteid The siteid of the page
	* @param int $parentid The parentid of the page
	* @param string $short_name The short name of the page
	* @param string $name The name of the page
	* @param string $template The template of the page
	* @param boolean $public Is the page public
	* @param string $description Page description (meta-data)
	* @param boolean $visible Is the page visible
	* @param boolean $usessl Whether the page uses ssl or not
	* @param int $designid The pages design
	* @param int $replaceid The replacement id of the page (safe editing)
	* @access public
	* @return void
	*/
	function add_page_to_page_index($save_page_index_cache, $pageid, $siteid, $parentid, $short_name, $name, $template, $public, $description, $visible, $usessl, $designid, $replaceid=0, $orderno) {
		global $CACHE;
		# If $save_page_index_cache is false and it
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			$dirs = $this->_get_page_dirs($pageid);
			$status = $this->_get_page_status($pageid);
			$access_grants = $this->_get_access_grants($pageid);
			$this->add_page_to_index_page($pageid, $siteid, $parentid, $short_name, $name, $template, $public, $description, $visible, $usessl, $designid, $dirs, $status, $access_grants, $replaceid, $orderno);
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index(false);
			}
		} else {
			# Rebuild the whole thing and start again
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Moves a page within the page index
	*
	* @param int $pageid The pageid of the page that's moving
	* @param int $old_parentid The pageid of the old parent
	* @param int $new_parentid The pageid of the new parent
	* @param boolean $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function move_page_in_page_index($pageid, $old_parentid, $new_parentid, $new_orderno, $save_page_index_cache=true) {
		# remove itself from parents childid list
		# update its parentid
		# add itself to its parents childid list
		# set my level to my parents level + 1
		global $CACHE;
		# If $save_page_index_cache is false and it
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			array_remove_element($pageid, $this->page_index[$old_parentid]['childids']);
			$this->page_index[$pageid]['parentid'] = $new_parentid;
			$this->_update_page_index_childids($new_parentid, $pageid, $new_orderno);
			$this->page_index[$pageid]['level'] = $this->page_index[$new_parentid]['level'] + 1;
			$this->page_index[$pageid]['orderno'] = $new_orderno;
			// A page is effectively_public if :-
			//    Its parent is effective public
			//    AND It is live
			//    AND It is public

			// Need to work out the publicness of the page now that its moved
			// If the new parent is the root then its public otherwise actually check
			$parent_public = ($new_parentid > 0) ? $this->page_index[$new_parentid]['effective_public'] : 1;
			$is_public = $this->page_index[$pageid]['public'];
			$is_live =  (in_array($this->page_index[$pageid]['effective_status'], array('L','R','E')));

			if ($is_public == 1 && $parent_public == 1 && $is_live == 1) {
				$this->page_index[$pageid]['effective_public'] = 1;
			} else {
				$this->page_index[$pageid]['effective_public'] = 0;
			}

			$this->update_child_levels($pageid, $save_page_index_cache);
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index();
			}
		} else {
			# Rebuild the whole thing and start again
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	function update_child_levels($pageid, $save_page_index_cache=true) {
		global $CACHE;
		# If $save_page_index_cache is false and it
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			if (empty($this->page_index[$pageid]['childids'])) return;
			foreach ($this->page_index[$pageid]['childids'] as $childid) {
				$this->page_index[$childid]['level'] = $this->page_index[$pageid]['level'] + 1;
				# Update the levels of this page and it's children
				$this->update_child_levels($childid, $save_page_index_cache);
			}
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index();
			}
		} else {
			# Rebuild the whole thing and start again
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Removes a page from the page index. This function doesn't care if the page has children so be careful
	*
	* @param  int  $pageid The pageid to remove
	* @param  bool $save_page_index_cache Should we write the changes to the cache?
	* @access public
	* @return void
	*/
	function remove_page_from_page_index($pageid, $save_page_index_cache=true) {
		global $CACHE;
		# If $save_page_index_cache is false and it
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			$parentid = $this->page_index[$pageid]['parentid'];
			$childkey = array_search($pageid, $this->page_index[$parentid]['childids']);
			unset($this->page_index[$parentid]['childids'][$childkey]);
			unset($this->page_index[$pageid]);
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index(false);
			}
		} else {
			# Rebuild the whole thing and start again
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Writes this->page_index to the cache
	*
	* @access public
	* @return void
	*/
	function write_page_index_to_cache() {
		global $CACHE;
		$CACHE->write($this->page_index, $this->id, 'site_page_index');
	}

	/**
	* Updates the page index cache for status
	*
	* @param int $pageid The pageid of the page
	* @param string $new_status The new status
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function update_page_index_status($pageid, $new_status, $save_page_index_cache=true) {
		$settings = array('status' => $new_status);
		$this->update_page_index_settings($pageid, $settings, $save_page_index_cache);

		# This case happens while creating a page. Page index comes together at odds and ends and this gets run before the page actually gets added to page index. Oh well. Anyway it's cool not to worry about the children because there are none.

		# We still need to work out the parent if doesn't have any - so the page_index effective_* variables get set correctly.
		if (isset($this->page_index[$pageid]['parentid'])) {
			$parentid = $this->page_index[$pageid]['parentid'];
		} else {
			$parentid = 0;
		}
		# Handle effective status of this page and it's children
		$this->set_page_index_effectiveness($parentid);

		# Write the cache if we need to.
		if ($save_page_index_cache) $this->write_page_index_to_cache();
	}

	/**
	* Updates the page index cache for dirs
	*
	* @param int $pageid The pageid of the page
	* @param array $dirs The dirs to update with
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function update_page_index_dirs($pageid, $dirs, $save_page_index_cache=true) {
		$settings = array('dirs' => $dirs);
		$this->update_page_index_settings($pageid, $settings, $save_page_index_cache);
	}

	/**
	* Updates the page index cache for any type of setting
	*
	* @param int $pageid The pageid of the page
	* @param array $settings The key is the setting key and the data is the setting to set it to
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function update_page_index_settings($pageid=0, $settings=array(), $save_page_index_cache=true) {
		global $CACHE;
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			foreach($settings as $field_name => $setting) {
				$this->page_index[$pageid][$field_name] = $setting;
			}
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index(false);
			}
		} else {
			# Rebuild the whole thing if the page index at 0 is stuffed
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Updates pageids access grants and effective access grants
	*
	* @param int $pageid The pageid of the page
	* @param array $grants An array of grant ids
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function update_page_index_access_grants($pageid, $grants, $save_page_index_cache=true) {
		global $CACHE;
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			$this->page_index[$pageid]['access_grants'] = $grants;
			$parentid = $this->page_index[$pageid]['parentid'];
			$this->set_page_index_effective_access_grants($parentid);
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index(false);
			}
		} else {
			# Rebuild the whole thing if the page index at 0 is stuffed
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Updates pageids access grants and effective access grants
	*
	* @param int $pageid The pageid of the page
	* @param boolean $public Whether the page is now public or not
	* @param int $save_page_index_cache Whether to save it to the page index cache or not
	* @access public
	* @return void
	*/
	function update_page_index_public($pageid, $public, $save_page_index_cache=true) {
		global $CACHE;
		if(isset($this->page_index[0]) || $this->page_index = $CACHE->read($this->id,'site_page_index')) {
			$this->page_index[$pageid]['public'] = $public;
			$parentid = $this->page_index[$pageid]['parentid'];
			$this->set_page_index_effectiveness($parentid);
			if ($save_page_index_cache) {
				$this->write_page_index_to_cache();
			} else {
				# clear it because we know it's dirty
				$this->clear_page_index(false);
			}
		} else {
			# Rebuild the whole thing if the page index at 0 is stuffed
			$this->clear_page_index();
			$index = $this->get_page_index();
		}
	}

	/**
	* Updates the Page Index Child ids for a given page
	*
	* @param int $pageid - The parent page id
	* @param int $childid - The child pageid
	* @param int $orderno - the order no
	* @access private
	* @return void
	*/
	function _update_page_index_childids($pageid, $childid, $orderno) {
		if (in_array($childid, $this->page_index[$pageid]['childids'])) return;
		$childids = &$this->page_index[$pageid]['childids'];

		if (!is_array($childids)) {
			$childids = array();
		} else {
			for (end($childids); null !== ($corderno = key($childids)); prev($childids)) {
				$id = $childids[$corderno];
				if ($corderno >= $orderno) {
					$childids[$corderno+1] = $id;
				}
			}
		}
		$this->page_index[$pageid]['childids'][$orderno] = $childid;
	}

	/**
	* Builds a page array for adding to the page index cache
	*
	* @param int $pageid The pageid of the page to build
	* @access public
	* @return void
	*/
	function add_page_to_index_page($pageid, $siteid, $parentid, $short_name, $name, $template, $public, $description, $visible, $usessl, $designid, $dirs, $status, $access_grants, $replaceid=0, $orderno) {
		# The new pages entry
		$this->page_index[$pageid]['pageid'] = $pageid;
		$this->page_index[$pageid]['parentid'] = $parentid;
		$this->page_index[$pageid]['short_name'] = $short_name;
		$this->page_index[$pageid]['name'] = $name;
		$this->page_index[$pageid]['template'] = $template;
		$this->page_index[$pageid]['template'] = $template;
		$this->page_index[$pageid]['public'] = $public;
		$this->page_index[$pageid]['description'] = $description;
		$this->page_index[$pageid]['visible'] = $visible;
		$this->page_index[$pageid]['usessl'] = $usessl;
		$this->page_index[$pageid]['replaceid'] = $replaceid;
		$this->page_index[$pageid]['orderno'] = $orderno;
		$this->_update_page_index_childids($parentid, $pageid, $orderno);
		if ($designid) {
			$this->page_index[$pageid]['designid'] = $designid;
		} else {
			$this->page_index[$pageid]['designid'] = $this->page_index[$parentid]['designid'];
		}
		$this->page_index[$pageid]['template'] = $template;

		$this->page_index[$pageid]['dirs'] = $dirs;
		if (!$status) {
			$status = 'U';
		}
		$this->page_index[$pageid]['status'] = $status;
		if (!$access_grants) {
			$access_grants = array();
		}
		$this->page_index[$pageid]['access_grants'] = $access_grants;
		if ($parentid == 0) {
			$this->page_index[$pageid]['level'] = 0;
			$this->page_index[$pageid]['effective_status'] = $status;
			$this->page_index[$pageid]['effective_public'] = $public;
			$this->page_index[$pageid]['effective_visible'] = $visible;
			$this->page_index[$pageid]['effective_design_pageid'] = $designid;
			$this->page_index[$pageid]['effective_access_grants'] = $this->access_grants;
		} else {
			$this->page_index[$pageid]['level'] = $this->page_index[$parentid]['level'] + 1;
			$this->page_index[$pageid]['effective_visible'] = $this->page_index[$parentid]['effective_visible'];
			$this->page_index[$pageid]['effective_design_pageid'] = $this->page_index[$parentid]['effective_design_pageid'];
			$this->page_index[$pageid]['effective_access_grants'] = $this->page_index[$parentid]['effective_access_grants'];
			$rank = array('L'=>1,'E'=>1,'R'=>1,'P'=>2,'U'=>3,'D'=>4,'A'=>5);
			if($rank[$status] < $rank[ $this->page_index[$parentid]['effective_status'] ]) {
				$this->page_index[$pageid]['effective_status'] = $this->page_index[$parentid]['effective_status'];
			} else {
				$this->page_index[$pageid]['effective_status'] = $status;
			}
			$this->page_index[$pageid]['effective_public'] = $this->page_index[$parentid]['effective_public'];
		}
	}

	/**
	* Gets the virtual paths for a page
	*
	* @param int $pageid The pageid of the page
	* @access public
	* @return array
	*/
	function _get_page_dirs($pageid) {
		$db = &$this->get_db();
		$dirs = $db->single_column("SELECT dir FROM page_dir, page WHERE page_dir.pageid=page.pageid AND page.pageid='$pageid'");
		return $dirs;
	}

	/**
	* Gets the status of the page
	*
	* @param int $pageid The pageid of the page
	* @access public
	* @return string
	*/
	function _get_page_status($pageid) {
		$db = &$this->get_db();
		$status = $db->single_element("SELECT action_value FROM page_action WHERE pageid= '$pageid' AND now() >= date AND action='status'");
		return (($status)?$status:'U');
	}

	/**
	* Gets the access grants of the page
	*
	* @param int $pageid The pageid of the page
	* @access public
	* @return array
	*/
	function _get_access_grants($pageid) {
		$db = &$this->get_db();
		$grants = $db->single_column("SELECT groupid FROM page_access_grant WHERE pageid='$pageid'");
		return $grants;
	}

	 #################################################################
	# Returns a reference to an array of pages, with some useful info
	function &get_page_index() {
		if(empty($this->page_index)) {
			global $CACHE;
			if(!$this->page_index = $CACHE->read($this->id,'site_page_index')) {
				$db = &$this->get_db();
				$this->page_index = $db->associative_array("SELECT pageid, parentid, short_name, name, template, public, description, visible, usessl, designid, level, replaceid, orderno FROM page WHERE siteid='$this->id' ORDER BY orderno");
				for(reset($this->page_index); NULL !== ($id = key($this->page_index)); next($this->page_index)) {
					if ((int) $id > 0) {
						$this->page_index[ $this->page_index[$id]['parentid'] ]['childids'][$this->page_index[$id]['orderno']] = $id;
					}
				}

				if (!empty($this->page_index[0])) {
					foreach($this->page_index[0]['childids'] as $id) {
						$this->_set_effective_design_pageids($id);
					}
				}

				if(!isset($this->page_index[0])) $this->page_index[0] = array(); # Blank
				# Assign directories to pages
				$dirs = $db->single_column("SELECT concat(page.pageid,'?',dir) FROM page_dir, page WHERE page_dir.pageid=page.pageid AND page.siteid='$this->id'");

				# Single column always returns an array (even empty) so it's cool to run a foreach without checking
				foreach($dirs as $dir) {
					list($id,$dir) = explode("?",$dir);
					$this->page_index[$id]['dirs'][] = $dir;
				}

				# This query is not wonderful.....but MySql does not support sub-selects....maybe two queries would be better?
				$page_statuses = $db->associative_array("SELECT page.pageid,page_action.action_value FROM page_action,page WHERE page_action.pageid=page.pageid AND page.siteid='$this->id' AND now() >= date AND action='status' ORDER BY date ASC");

				# Associative_array always returns an array (even empty) so it's cool to run a foreach without checking
				foreach($page_statuses as $id => $status) {
					$this->page_index[$id]['status'] = (($status)?$status:"U");
				}

				# Access grants - (in case this isn't already big enough)
				$grants = $db->single_column("SELECT concat(page.pageid,' ',page_access_grant.groupid)
											  FROM page LEFT JOIN page_access_grant
											  ON page.pageid=page_access_grant.pageid
											  WHERE page.siteid='$this->id'
											  AND page.pageid IS NOT NULL
											  AND page_access_grant.groupid IS NOT NULL");

				# Single column always returns an array (even empty) so it's cool to run a foreach without checking
				foreach($grants as $grant) {
					list($pid,$gid) = explode(" ",$grant);
					$this->page_index[$pid]['access_grants'][] = $gid;
				}

				# Effective Statuses, that is a pages status is affected by its parents.
				$this->set_page_index_effectiveness(0);
				$CACHE->write($this->page_index,$this->id,'site_page_index');
			}
			$this->restrict_page_index();
		}
		return $this->page_index;
	}
	 ######################################################################
	# recursive fn that sets the level of the page in the menu hierarchy
	function _set_page_index_level($pageid=0, $level=0) {
		$this->page_index[$pageid]['level'] = $level;

		if (isset($this->page_index[$pageid]['childids']) && !empty($this->page_index[$pageid]['childids'])) {
			foreach($this->page_index[$pageid]['childids'] as $id) {
				$this->_set_page_index_level($id, $level + 1);
			}
		}
	}#end _set_page_index_level()

	 ##################################################################################
	# recursive fn that sets the pageid that pages are meant to get their designs from
	function _set_effective_design_pageids($pageid=0, $design_pageid=0) {
		if ($this->page_index[$pageid]['designid']) {
			$design_pageid = $pageid;
		}

		$this->page_index[$pageid]['effective_design_pageid'] = $design_pageid;

		if (isset($this->page_index[$pageid]['childids']) && !empty($this->page_index[$pageid]['childids'])) {
			foreach($this->page_index[$pageid]['childids'] as $id) {
				$this->_set_effective_design_pageids($id, $design_pageid);
			}
		}
	}#end _set_effective_design_pageids()

	 ##########################################################################
	# This recursive function acts on the page_index - it determines
	# the "effective" page status and access_grants - bases on parents settings
	function set_page_index_effectiveness($parentid=0) {
		$index = &$this->page_index; # Only allowed direct access here OKAY!?
		if($parentid > 0) {
			$status        = $index[$parentid]['effective_status'];
			$public        = $index[$parentid]['effective_public'];
			$access_grants = &$index[$parentid]['effective_access_grants'];
			$visible       = $index[$parentid]['effective_visible'];
		} else {
			$status        = 'L';
			$public        = $this->public;
			$access_grants = &$this->access_grants;
			$visible       = true;
		}
		 #######################################################
		# Page statuses can overrule eachother, in this order:
		# Pending approval pages render all live children pending
		# UC renders all pending children UC
		# Disabled pages render all nonarchived children disabled
		# Archives pages render all their children archived
		$rank  = array('L'=>1,'E'=>1,'R'=>1,'P'=>2,'U'=>3,'D'=>4,'A'=>5);
		$child_pageids = &$index[$parentid]['childids'];
		if (!empty($child_pageids)) {
			foreach($child_pageids as $pageid) {
				$page = &$index[$pageid];
				if($rank[$status] > $rank[ $page['status'] ]) {
					$page['effective_status'] = $status;
				} else {
					$page['effective_status'] = $page['status'];
				}
				if (!$public) {
					$page['effective_public'] = 0;
				} else {
					$page['effective_public'] = $page['public'];
				}
				if (!$visible) {
					$page['effective_visible'] = 0;
				} else {
					$page['effective_visible'] = $page['visible'];
				}
				if (is_array($page['access_grants'])) {
					if ($public) {
						$page['effective_access_grants'] = $page['access_grants'];
					} else {
						$page['effective_access_grants'] = array_intersect($access_grants,$page['access_grants']);
					}
				} else {
					$page['effective_access_grants'] = array();
				}
				if (count($page['childids'])) {
					$this->set_page_index_effectiveness($pageid);
				}
			}
		}
	}

	/**
	* Like function set_page_index_effectiveness however it only works on access grants
	*
	* @param int $parentid The parent id to start working from
	* @access public
	* @return void
	*/
	function set_page_index_effective_access_grants($parentid=0) {
		$index = &$this->page_index; # Only allowed direct access here OKAY!?
		if($parentid > 0) {
			$access_grants = &$index[$parentid]['effective_access_grants'];
			$public        = $index[$parentid]['effective_public'];
		} else {
			$access_grants = &$this->access_grants;
			$public        = $this->public;
		}

		$child_pageids = &$index[$parentid]['childids'];
		if (!empty($child_pageids)) {
			foreach($child_pageids as $pageid) {
				$page = &$index[$pageid];
				if (is_array($page['access_grants'])) {
					if ($public) {
						$page['effective_access_grants'] = $page['access_grants'];
					} else {
						$page['effective_access_grants'] = array_intersect($access_grants,$page['access_grants']);
					}
				} else {
					$page['effective_access_grants'] = array();
				}
				if (isset($page['childids'][0])) {
					$this->set_page_index_effectiveness($pageid);
				}
			}
		}
	}

	 ###################################################
	# Returns an array of the "top" parentless pageids
	function get_top_pageids() {
		$pages = &$this->get_page_index();
		return $pages[0]['childids'];
	}


	 ##########################################
	# Changes the name of the site
	function set_name($name='') {
		if(!$name) return "Your site must have a name.";
		if($name == $this->name) return "";
		$db = &$this->get_db();
		if($db->single_element("SELECT siteid FROM site WHERE name='".addslashes($name)."' AND siteid != '$this->id'"))
			return "There is already a site with the name $name: $siteid";
		$this->name = $name;
		$db->update("UPDATE site SET name='".addslashes($this->name)."' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Site renamed to '$this->name'.";
	}


	 ##########################################
	# Changes the description of the site
	function set_description($description='') {
		if($description == $this->description) return "";
		$db = &$this->get_db();
		$this->description = $description;
		$db->update("UPDATE site SET description='".addslashes($this->description)."' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Description updated for site '$this->name'.";
	}


	 ##########################################
	# Changes the default languages of the site
	function set_default_languages($default_languages='') {
		if($default_languages == $this->default_languages) return "";
		$db = &$this->get_db();
		$this->default_languages = $default_languages;
		$db->update("UPDATE site SET default_languages='".addslashes($this->default_languages)."' WHERE siteid='$this->id'");
		$this->clear_cache();
		if($this->default_languages) {
			$languages_config = &get_config("languages");
			$desc = $languages_config->name_list($this->default_languages);
		} else {
			$desc = "System Default";
		}
		return "Default languages for site '$this->name' set to: $desc.";
	}

	 #####################################################
	# Returns the effective default languages of the site
	function &get_effective_default_languages() {
		if($this->default_languages) {
			return $this->default_languages;
		} else {
			$system_config = &get_system_config();
			return $system_config->default_language;
		}
	}

	 ##########################################
	# Changes the default languages of the site
	function set_default_charset($default_charset='') {
		if($default_charset == $this->default_charset) return "";
		$db = &$this->get_db();
		$this->default_charset = $default_charset;
		$db->update("UPDATE site SET default_charset='".addslashes($this->default_charset)."' WHERE siteid='$this->id'");
		$this->clear_cache();
		if($this->default_charset) {
			$charsets_config = &get_config("charsets");
			return "Default character set for site '$this->name' set to {$charsets_config->charsets[$this->default_charset]}.";
		} else {
			return "Default character set for site '$this->name' set to the System Default.";
		}
	}


	 #########################################################
	# Returns the effective default character set of the site
	function &get_effective_default_charset() {
		if($this->default_charset) {
			return $this->default_charset;
		} else {
			$system_config = &get_system_config();
			return $system_config->default_charset;
		}
	}

	 ######################################
	# Updates the index_pageid of the site
	function set_index_pageid($pageid=0) {
		if(!$pageid) return "Unable to set unspecified index page.";
		if($pageid == $this->index_pageid) return "";
		$index = &$this->get_page_index();
		if (!isset($index[$pageid])) return "Unable to set index page. Page not found on site '$this->name'.";
		$name = $index[$pageid]['name'];
		$this->index_pageid = $pageid;
		$db = &$this->get_db();
		$db->update("UPDATE site SET index_pageid='$this->index_pageid' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Page '$name' is now the index page for site '$this->name'";
	}

	 #####################################
	# Updates the not_found_pageid
	function set_not_found_pageid($pageid=0) {
		if($pageid == $this->not_found_pageid) return;
		if($pageid) {
			$index = &$this->get_page_index();
			if (!isset($index[$pageid])) return "Unable to set not found page. Page not found on site '$this->name'.";
			$name = $index[$pageid]['name'];
		} else {
			$name = "[None]";
		}
		$this->not_found_pageid = $pageid;
		$db = &$this->get_db();
		$db->update("UPDATE site SET not_found_pageid='$this->not_found_pageid' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Page '$name' is now the not found page for site '$this->name'";
	}

	 #####################################
	# Updates the forbidden_pageid
	function set_forbidden_pageid($pageid=0) {
		if($pageid == $this->forbidden_pageid) return;
		if($pageid) {
			$index = &$this->get_page_index();
			if (!isset($index[$pageid])) return "Unable to set forbidden page. Page not found on site '$this->name'.";
			$name = $index[$pageid]['name'];
		} else {
			$name = "[None]";
		}
		$this->forbidden_pageid = $pageid;
		$db = &$this->get_db();
		$db->update("UPDATE site SET forbidden_pageid='$this->forbidden_pageid' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Page '$name' is now the forbidden page for site '$this->name'";
	}

	/**
	* Sets the Sub pages auto-reordering scheme
	*
	* @access public
	* @param string $scheme - the auto ordering scheme
	* @return string
	*/
	function set_subpage_auto_order($scheme=0) {
		if ($scheme == $this->subpage_auto_order) return;
		if (is_string($scheme) || is_null($scheme)) {
			$db = &$this->get_db();
			$this->subpage_auto_order = $scheme;
			$db->update("UPDATE site SET subpage_auto_order=".($this->subpage_auto_order?"'".$this->subpage_auto_order."'":'NULL')." WHERE siteid='$this->id'");
			$this->clear_cache();
			return "Site $this->name ID $this->id - Auto Re-ordering scheme is now updated";
		}
		return "Failed to update Auto Re-ordering scheme for Site $this->name ID $this->id";
	}

	 #########################
	# Changes the site design
	function set_design($designid=0) {
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
		$sql = "UPDATE site
				SET designid = '".$this->designid."'
				WHERE siteid='$this->id'";
		$db->update($sql);

		$message .= "Design set to '$design->name'";

		# if the design isn't in the allowed list, add it

		if (!empty($this->allowed_designs) && !in_array($this->designid, $this->allowed_designs)) {
			$message .= "\n".$this->add_allowed_designid($this->designid);
		}

		$this->clear_cache();
		return $message;

	}#end set_design()

	 ################################################
	# Returns a reference to this sites design object
	function &get_design() {
		if(get_class($this->site_design) != "site_design" || $this->get_file_href('site_design/') != $this->site_design->custom_file_href) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/site_design.inc");
			$this->site_design =& new Site_Design($this->designid, $this->id.".".get_class($this), $this->data_path."/site_design", $this->get_file_href("site_design/"));
		}
		return $this->site_design;
	}


	 ###########################################
	# Update the list of site admins
	function update_adminids($new_adminids=0) {
		$message = "";
		list($a,$r) = array_compare($new_adminids, $this->adminids);

		if (!is_array($a)) $a = array();
		if (!is_array($r)) $r = array();

		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_adminid($add)."\n";
			}
		}
		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_adminid($rem)."\n";
			}
		}
		return trim($message);
	}

	 #######################################
	# Adds a new site administrator
	function add_adminid($userid=0) {
		if(!$userid) return "Administrator ID not specified for assignment.";
		if(in_array($userid,$this->adminids)) return "";
		$db = &$this->get_db();
		//preset some parameters
		$preset_parameters ='';
		$preset_parameters['email_adminids'] = 1;
		$parameters = serialize($preset_parameters);
		$this->adminids[] = $userid;
		$this->adminids_parameters[$userid] = $parameters;
		if (!$db->insert("INSERT INTO site_admin (userid, siteid, parameters) VALUES ('$userid','$this->id', '".addslashes($parameters)."')")) {
			return "Unable to add site administrator: database error.";
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$admin = &$users_system->get_user($userid);
		return $admin->name()." assigned as site administator for site '$this->name'.";
	}

	 #######################################
	# update the parameters
		/*
		  Want a standard way to update parameters?, look no further. Pass me the name of the
		  parameter and an array with all userids that had the parameter checkbox CHECKED. I'll
		  loop through them, compare them to the previous values and update if necessary. I might
		  need a bit of tweaking if you want me to work with other form elements.
		*/

	function update_parameter_adminids($parameter_name='', $positives='') {

		$users_system = &get_users_system();
		$db = &$this->get_db();
		$message = '';

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
				$db->update("UPDATE site_admin SET parameters='".addslashes($serialized_params)."' where userid='$id' AND siteid='$this->id'");
				$username = &$users_system->get_user($id);
				$message .= "$parameter_name for ".$username->name()." is now set to ".(($checked)?"on<BR>\n":"off<BR>\n");
			}
		}
		$this->clear_cache();
		return $message;
	}

	 #######################################
	# Removess a new site administrator
	function remove_adminid($userid=0) {
		if(!$userid) return "Administrator ID not specified for removal.";
		if(!in_array($userid,$this->adminids)) return "";
		$db = &$this->get_db();
		if (!empty($this->adminids)) {
			foreach($this->adminids as $k => $id) {
				if ($id == $userid) {
					unset($this->adminids[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM site_admin WHERE userid='$userid' AND siteid='$this->id'")) {
			return "Unable to remove site administrator: database error.";
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$admin = &$users_system->get_user($userid);
		return $admin->name()."'s site administatration privelidges have been revoked for site '$this->name'.";
	}


	 ##########################################
	# Update the list of site editorss
	function update_editorids($new_editorids=0) {
		list($a,$r) = array_compare($new_editorids, $this->editorids);

		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_editorid($add)."\n";
			}
		}

		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_editorid($rem)."\n";
			}
		}
		return trim($message);
	}


	 ########################
	# Adds a new site editor
	function add_editorid($userid=0) {
		if(!$userid) return "Editor ID not specified for assignment.";
		if(in_array($userid,$this->editorids)) return "";
		$db = &$this->get_db();
		$this->editorids[] = $userid;
		if (!$db->insert("INSERT INTO site_editor (userid, siteid) VALUES ('$userid','$this->id')")) {
			return "Unable to add site editor: database error.";
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$editor = &$users_system->get_user($userid);
		return $editor->name()." assigned as site editor for site '$this->name'.";
	}


	 ############################
	# Removes a new site editor
	function remove_editorid($userid=0) {
		if(!$userid) return "Editor ID not specified for removal.";
		if(!in_array($userid,$this->editorids)) return "";
		$db = &$this->get_db();
		if (!empty($this->editorids)) {
			foreach($this->editorids as $k => $id) {
				if ($id == $userid) {
					unset($this->editorids[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM site_editor WHERE userid='$userid' AND siteid='$this->id'")) {
			return "Unable to remove site editor: database error.";
		}
		$this->clear_cache();
		$users_system = &get_users_system();
		$editor = &$users_system->get_user($userid);
		return $editor->name()."'s site editor privelidges have been revoked for site '$this->name'.";
	}


	 ###########################################
	# Update the list of allowed site extensions
	function update_allowed_extensions($new_extensions='') {
		list($a,$r) = array_compare($new_extensions, $this->allowed_extensions);
		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_allowed_extension($add)."\n";
			}
		}
		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_allowed_extension($rem)."\n";
			}
		}
		return trim($message);
	}


	 #######################################
	# Adds a new allowed extension
	function add_allowed_extension($extension='') {
		if(!$extension) return "Extension not specified for allowing.";
		if(in_array($extension,$this->allowed_extensions)) return "";
		global $XTRAS;
		$name = $XTRAS->name("site/extensions",$extension);
		if(!$name) $name = $extension;
		$db = &$this->get_db();
		$this->allowed_extensions[] = $extension;
		if (!$db->insert("INSERT INTO site_allowed_extension (extension, siteid) VALUES ('$extension','$this->id')")) {
			return "Unable to allow $name site extension: database error.";
		}
		$this->clear_cache();
		return "$name extension now allowed on site '$this->name'.";
	}


	 #######################################
	# Removess a new site administrator
	function remove_allowed_extension($extension='') {
		if(!$extension) return "Extension not specified for removal.";
		if(!in_array($extension,$this->allowed_extensions)) return "";
		global $XTRAS;
		$name = $XTRAS->name("site/extensions",$extension);
		if(!$name) $name = $extension;
		$db = &$this->get_db();
		if (!empty($this->allowed_extensions)) {
			foreach($this->allowed_extensions as $k => $id) {
				if ($id == $extension) {
					unset($this->allowed_extensions[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM site_allowed_extension WHERE extension='$extension' AND siteid='$this->id'")) {
			return "Unable to allow $name extension: database error.";
		}
		$this->clear_cache();
		return "$name extension now disallowed on site '$this->name'.";
	}



	 ###########################################
	# Update the list of allowed page templates
	function update_allowed_templates($new_templates='') {
		if(empty($new_templates)) return "You must allow at least one page template.";
		list($a,$r) = array_compare($new_templates, $this->allowed_templates);
		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_allowed_template($add)."\n";
			}
		}
		if (!empty($r)) {
			foreach($r as $rem) {
				$message .= $this->remove_allowed_template($rem)."\n";
			}
		}
		return trim($message);
	}

	 #######################################
	# Adds a new site administrator
	function add_allowed_template($template='') {
		if(!$template) return "Template not specified for allowing.";
		if(in_array($template,$this->allowed_templates)) return "";
		global $XTRAS;
		$name = $XTRAS->name("page/templates",$template);
		if(!$name) $name = $template;
		$db = &$this->get_db();
		$this->allowed_templates[] = $template;
		if (!$db->insert("INSERT INTO site_allowed_template (template, siteid) VALUES ('$template','$this->id')")) {
			return "Unable to allow $name page template: database error.";
		}
		$this->clear_cache();
		return "$name template pages now allowed on site '$this->name'.";
	}


	 #######################################
	# Removess a new site administrator
	function remove_allowed_template($template='') {
		if(!$template) return "Template not specified for removal.";
		if(!in_array($template,$this->allowed_templates)) return "";
		global $XTRAS;
		$name = $XTRAS->name("page/templates",$template);
		if(!$name) $name = $template;
		$db = &$this->get_db();
		if (!empty($this->allowed_templates)) {
			foreach($this->allowed_templates as $k => $id) {
				if ($id == $template) {
					unset($this->allowed_templates[$k]);
					break;
				}
			}
		}
		if (!$db->delete("DELETE FROM site_allowed_template WHERE template='$template' AND siteid='$this->id'")) {
			return "Unable to allow $name page template: database error.";
		}
		$this->clear_cache();
		return "$name template pages now disallowed on site '$this->name'.";
	}



	 ###########################################
	# Update the list of allowed designids
	function update_allowed_designids($new_designids=0) {
		if(empty($new_designids)) return "You must allow at least one site design.";
		list($a,$r) = array_compare($new_designids, $this->allowed_designids);
		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_allowed_designid($add)."\n";
			}
		}
		if (!empty($r)) {
			foreach($r as $rem) {
				if ($rem != $this->designid) {
					$message .= $this->remove_allowed_designid($rem)."\n";
				} else {
					$message .= "Can't Remove allowed design $rem, it is the design currently being used for this site\n";
				}#end if
			}
		}
		return trim($message);
	}


	 #######################################
	# Adds a new alklowed designid
	function add_allowed_designid($designid=0) {
		if(!$designid) return "Design not specified for allowing.";
		if(in_array($designid,$this->allowed_designids)) return "";
		$designs = &$this->web_system->get_site_design_list();
		$name = $designs[$designid];
		if(!$name) $name = $designid;
		$this->allowed_designids[] = $designid;
		$db = &$this->get_db();
		if (!$db->insert("INSERT INTO site_allowed_designid (designid, siteid) VALUES ('$designid','$this->id')")) {
			return "Unable to allow $name design: database error.";
		}
		$this->clear_cache();
		return "$name design now allowed on site '$this->name'.";
	}


	 #######################################
	# Removes an allowed design
	function remove_allowed_designid($designid=0) {
		if(!$designid) return "designid not specified for removal.";
		if(!in_array($designid,$this->allowed_designids)) return "";
		$designs = &$this->web_system->get_site_design_list();
		$name = $designs[$designid];
		if(!$name) $name = $designid;
		if (!empty($this->allowed_designids)) {
			foreach($this->allowed_designids as $k => $id) {
				if ($id == $designid) {
					unset($this->allowed_designids[$k]);
					break;
				}
			}
		}
		$db = &$this->get_db();
		if (!$db->delete("DELETE FROM site_allowed_designid WHERE designid='$designid' AND siteid='$this->id'")) {
			return "Unable to allow $name design: database error.";
		}
		$this->clear_cache();
		return "$name design now disallowed on site '$this->name'.";
	}


	 ########################################
	# Sets whether the site is public or not
	function set_public($public=false) {
		$public = (int) $public;
		if ($public == $this->public) return "";
		# try and move the directory
		if (!restrict_data_path($public, "site/".$this->id)) return "";

		$this->public = $public;
		$db = &$this->get_db();
		$db->update("UPDATE site SET public='$public' WHERE siteid='$this->id'");
		$this->clear_page_index();
		$this->clear_cache();

		# update the data path to represent our new status
		$this->data_path = get_data_path($this->public, "site/".$this->id);

		# we also need to make sure that all our pages are in the right directory
		$top_pages = &$this->get_top_pageids();
		if (!empty($top_pages)) {
			foreach($top_pages as $pageid) {
				$page = &$this->get_page($pageid);
				if ($page->id) {
					$page->restrict_data_path();
				}
				$this->web_system->forget_page($pageid);
			}
		}

		return "Site '$this->name' is ".(($public)?"now":"no longer")." available to the public.";
	}


	 ###########################################
	# Update the list of allowed grants
	function update_access_grants($new_grants='') {
		list($a,$r) = array_compare($new_grants, $this->access_grants);
		if (!empty($a)) {
			foreach($a as $add) {
				$message .= $this->add_access_grant($add)."\n";
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
	function add_access_grant($groupid=0) {
		if(!$groupid) return "Access group not specified for access granting.";
		if(in_array($groupid,$this->access_grants)) return "";
		$name = $this->access_groups[$groupid];
		if(!$name) {
			$general_access_groups = &$this->web_system->get_general_access_group_list();
			$name = $general_access_groups[$groupid];
		}
		if(!$name) $name = $groupid;
		$this->access_grants[] = $groupid;
		$db = &$this->get_db();
		if (!$db->insert("INSERT INTO site_access_grant (groupid, siteid) VALUES ('$groupid','$this->id')")) {
			return "Unable to grant access to $name: database error.";
		}
		$this->clear_page_index();
		$this->clear_cache();
		return "Access granted to access group '$name' on site '$this->name'.";
	}

	 #######################################
	# Removes an access group's permissions
	function remove_access_grant($groupid=0) {
		if(!$groupid) return "Access group not specified for access denying.";
		if(!in_array($groupid,$this->access_grants)) return "";
		$name = $this->access_groups[$groupid];
		if(!$name) {
			$general_access_groups = &$this->web_system->get_general_access_group_list();
			$name = $general_access_groups[$groupid];
		}
		if(!$name) $name = $groupid;
		if (!empty($this->access_grants)) {
			foreach($this->access_grants as $k => $id) {
				if ($id == $groupid) {
					unset($this->access_grants[$k]);
					break;
				}
			}
		}
		$db = &$this->get_db();
		if (!$db->delete("DELETE FROM site_access_grant WHERE groupid='$groupid' AND siteid='$this->id'")) {
			return "Unable to deny access to $name: database error.";
		}
		$this->clear_page_index();
		$this->clear_cache();
		return "Access denied to access group '$name' on site '$this->name'.";
	}


	 ###################################################
	# Creates a new access group and adds it to the site
	function new_access_group($name='') {
		$group   = new Access_Group(0, $this);
		$message = $group->create($name,$this->id);
		if($group->id) {
			$this->access_groups[$group->id] = $name;
		}
		$this->clear_cache();
		return $message;
	}


	 #############################################
	# Sets the various maximum alloweds on a site
	function set_maximums($max_pages=0, $max_files=0, $max_file_bytes=0) {
		$error_code = MYSOURCE_ERROR_CODE_NONE;
		$message    = '';
		if ($this->max_pages != $max_pages) {
			$page_index = &$this->get_page_index();
			$current_page_count = count($page_index) - 1; # ignore the 0 (zero) pageid

			# if they are trying to set the max pages to lower than the current number of pages
			# tell the them to piss off
			if ($max_pages && $current_page_count > $max_pages) {
				$error_code = MYSOURCE_ERROR_CODE_ERROR;
				$message .= "Unable to set the maximum number of pages to $max_pages because there is currently $current_page_count pages in the site, delete some pages first.\n";
			} else {
				$this->max_pages = $max_pages;
				if($this->max_pages) $message .= "Maximum of $this->max_pages pages allowed on site '$this->name'.\n";
				else                 $message .= "Unlimited pages allowed on site '$this->name'.\n";
			}
		}
		if ($this->max_files != $max_files) {
			$this->max_files = $max_files;
			if($this->max_files) $message .= "Maximum of $this->max_files files allowed on site '$this->name'.\n";
			else                 $message .= "Unlimited files allowed on site '$this->name'.\n";
		}
		if ($this->max_file_bytes != $max_file_bytes) {
			$this->max_file_bytes = $max_file_bytes;
			if($this->max_file_bytes) $message .= "Maximum of ".(($this->max_file_bytes/1024)/1024)." megabytes worth of files allowed on site '$this->name'.\n";
			else                      $message .= "Unlimited file archive size allowed on site '$this->name'.\n";
		}
		$db = &$this->get_db();
		$db->update("UPDATE site SET max_pages='$this->max_pages', max_files='$this->max_files', max_file_bytes='$this->max_file_bytes' WHERE siteid='$this->id'");
		$this->clear_cache();
		return Array($error_code, trim($message));
	}


	 ###########################################
	# Update the list URLs pointing to this site
	function update_urls($new_urls='') {
		$db = &$this->get_db();
		# Remove the old ones
		if (!empty($this->urls)) {
			foreach($this->urls as $url => $protocol) {
				if(!in_array($url,array_keys($new_urls))) {
					$message .= $this->remove_url($url)."\n";
				}
			}
		}
		$url_count = 0;
		$old_this_urls = $this->urls;
		$this->urls = array();
		if (!empty($new_urls)) {
			foreach($new_urls as $url => $protocol) {
				$url = eregi_replace("^[a-z]*://","",$url);
				$url = ereg_replace("\/+$","",$url);
				# Um this needs more useful checking too
				if(in_array($url,array_keys($old_this_urls))) {
					if($db->update("UPDATE site_url SET orderno='$url_count',protocol='$protocol' WHERE url='".addslashes($url)."' AND siteid='$this->id'")) {
						$reordered = true;
					}
					$this->urls[$url] = $protocol;
				} else {
					$message .= $this->add_url($url,$protocol,$url_count)."\n";
				}
				$url_count++;
			}
		}
		if($reordered) $message .= "URLs reordered on site '$this->name'.\n";
		$this->clear_cache();
		return trim($message);
	}


	 #############################################
	# Sets a URL as pointing to a particular site
	function add_url($url='',$protocol='',$orderno=0) {
		$url = ereg_replace("[ \t\n\r]+","_",$url); # Spaces! Grr!

		# check to see is this url contains a port number at the end
		# if so split them up so we can check the integrity of the url below
		if (ereg("^(.+)(:[0-9]+)(.*)$", $url, $matches)) {
			$domain = $matches[1];
			$port   = $matches[2];
			$path   = $matches[3];
		} else {
			list($domain, $path) = explode("/", $url, 2);
			if ($path) $path = '/'.$path;
			$port = "";
		}#end if

		# check the integrity
		# http://www.w3.org/Addressing/URL/5_URI_BNF.html
		$e = "[^a-zA-Z0-9\$\_\@\.\&\!\*\(\)\,\~\/\-]";
		$domain = ereg_replace($e,"",$domain);
		$path   = ereg_replace($e,"",$path);
		# Backslashes! GRR!
		$domain = str_replace("\\","",$domain);
		$path   = str_replace("\\","",$path);
		if(!$url) return "";

		$url = $domain.$port.$path;

		if($this->urls[$url]) {
			return "The URL '$url' already points to site '$this->name'.";
		}
		$db = &$this->get_db();
		if($name = $db->single_element("SELECT site.name FROM site, site_url WHERE site.siteid=site_url.siteid AND site_url.url='".addslashes($url)."'")) {
			return "Site '$name' is already using the URL '$url'.\n";
		}
		if($this->web_system->register_site_url($this->id,$url)) {
			$this->urls[$url] = $protocol;
			if ($db->insert("INSERT INTO site_url (siteid,url,protocol,orderno) VALUES('$this->id','".addslashes($url)."','$protocol','$orderno')")) {
				return "Added URL '$url' to site '$this->name'";
			}
			$this->web_system->unregister_site_url($this->id,$url);
		}
		return "Could not add the URL '$url' to site '$this->name'.";
	}


	 #####################################################
	# Stops a URL from pointing to a particular site
	function remove_url($url='') {
		if(!$url) return "";
		$db = &$this->get_db();
		if(isset($this->urls[$url])) {
			unset($this->urls[$url]);
			if ($db->delete("DELETE FROM site_url WHERE siteid='$this->id' AND url='".addslashes($url)."'")) {
				$this->web_system->unregister_site_url($this->id,$url);
				# Inform the pages, they'd like to know this
				return "Removed URL '$url' from site '$this->name'";
			}
		}
		return "Could not remove the URL '$url' from site '$this->name'.";
	}

	  ########################
	 # Mark a site as updated
	function updated() {
		$db = &$this->get_db();
		$db->update("UPDATE site SET last_update=now() WHERE siteid='$this->id'");
		$this->last_updated = date("Y-m-d h:i:s");
		$this->clear_cache();
	}


	 #############################
	# Duplicates this entire site
	function dupe() {
		$new_siteid = $this->dupe_prepare();
		$session = &get_mysource_session();
		$dupe_message = &$session->get_var('dupe_message');
		if (!$dupe_message) $dupe_message = '';

		$dupe_map = &$session->get_var('dupe_map');
		if (!$dupe_map) $dupe_map = '';

		$this->dupe_pages($new_siteid, false, $dupe_map, $dupe_message);
		$this->dupe_remap(false, $dupe_map, Array());
		$this->dupe_cleanup($new_siteid, $dupe_map, $dupe_message);
	}


	function dupe_prepare() {
		$session = &get_mysource_session();
		$dupe_message = '';
		$dupe_map = array();

		$new_site = new Site(0,$this->web_system);

		$new_site->create($this->name,$this->designid)."\n";

		# Delete the pesky index page that has been created
		$new_site_top_pageids = $new_site->get_top_pageids();
		if (!empty($new_site_top_pageids)) {
			foreach($new_site_top_pageids as $pageid) {
				$page = &$this->get_page($pageid);
				$page->delete(false, false, false);
			}
		}

		$new_site->set_description($this->description);
		$new_site->set_public($this->public);
		$new_site->set_maximums($this->max_pages,$this->max_files,$this->max_file_bytes);
		$new_site->set_default_languages($this->default_languages);
		$new_site->set_default_charset($this->default_charset);
		$new_site->set_subpage_auto_order($this->subpage_auto_order);

		# Don't worry about site URLs, not our problem, don't want dupes of those anyway.

		# Duplicate the design
		$design = &$this->get_design();
		$dupe_message .= $new_site->set_design($this->designid)."\n";
		if ($design->save_customisation($new_site->id.'.'.get_class($this))) {
			   $dupe_message .= "Design Customisation Updated.\n";
		}

		# Dupe the access groups that exist for this site
		if (!empty($this->access_groups)) {
			foreach($this->access_groups as $groupid => $name) {
				$group = new Access_Group($groupid,$this->web_system);
				$group->dupe($new_site->id);
			}
		}

		$new_access_grants = $this->access_grants;
		if (!empty($new_access_grants)) {
			foreach($new_access_grants as $k => $id) {
				if($newid = $dupe_map['access_group'][$id]) $new_access_grants[$k] = $newid;
			}
		}

		$new_site->update_access_grants($new_access_grants);
		$new_site->update_allowed_extensions($this->allowed_extensions);
		$new_site->update_allowed_templates($this->allowed_templates);
		$new_site->update_allowed_designids($this->allowed_designids);
		$new_site->update_adminids($this->adminids);
		$new_site->update_editorids($this->editorids);

		if (copy_directory($this->data_path, $new_site->data_path)) {
			$dupe_message .= "Site '$this->name' data directory copied successfully.\n";
		} else {
			$dupe_message .= "Site '$this->name' data directory could not be copied!\n";
		}

		$session->set_var('dupe_map', $dupe_map);
		$session->set_var('dupe_message', $dupe_message);
		return $new_site->id;
	}


	 ######################
	# Duplicate the pages
	function dupe_pages($siteid=0, $num_pages=false, &$dupe_map, &$dupe_message, $save_page_index_cache=true) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');

		$new_site = &$this->web_system->get_site($siteid);

		if ($num_pages === false) {
			# Dupe everything

			# Get the top pages
			$top_pageids = $this->get_top_pageids();
			if (!empty($top_pageids)) {
				foreach($top_pageids as $pageid) {
					# This should handle all the subpages and their files etc
					$page = &$this->web_system->get_page($pageid);
					$page->dupe($new_site->id,0,false,false,false,false,true,$dupe_map,$dupe_message, false); # Page will remember page_mapping in $dupe_map
					$this->web_system->forget_page($pageid); # so we don't blow the memory out
				}
			}
		} else {
			# the dupe map will allow us to match up the pages
			# we are dupeing from this site with the new parents
			# in the new site - even across browser refreshes
			$index = &$this->get_page_index();
			if (!empty($index)) {
				foreach ($index as $data) {
					$pageid = (int) $data['pageid'];
					if ($pageid <= 0) continue;
					if (isset($dupe_map['page'][$pageid])) {
						# page already duped
						continue;
					}

					if ($num_pages <= 0) {
						# we are done
						$session->set_var('dupe_map', $dupe_map);
						return;
					}

					if ($data['parentid'] > 0) {
						# check the session dupe map to find out
						# what the new parentid is
						$new_parentid = $dupe_map['page'][$data['parentid']];
						if (!$new_parentid) {
							# the parent hasnt been duped yet
							continue;
						}
					} else {
						# a top level page
						$new_parentid = 0;
					}

					$page = &$this->web_system->get_page($pageid);
					$dupeid = $page->dupe($new_site->id,$new_parentid,true,false,false,false,false,$dupe_map,$dupe_message, $save_page_index_cache); # ignore subpages
					$dupe_map['page'][$pageid] = $dupeid;
					$num_pages--;
				}
			}
			$session->set_var('dupe_map', $dupe_map);
			$session->set_var('dupe_message', $dupe_message);
		}
	}

	function dupe_specified_pages($siteid=0, $num_pages=0, $pages=array(), &$dupe_map, &$dupe_message, $save_page_index_cache=true) {
		$session = &get_mysource_session();
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');
		$index = $this->get_page_index();
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

			if ($index[$pageid]['parentid'] > 0) {
				# check the session dupe map to find out
				# what the new parentid is
				$new_parentid = $dupe_map['page'][$index[$pageid]['parentid']];
			} else {
				# a top level page
				$new_parentid = 0;
			}
			$page = &$this->web_system->get_page($pageid);
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

	function dupe_remap($num_pages=false, &$dupe_map, &$remap_list) {
		$session = &get_mysource_session();
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');

		if ($num_pages === false) {
			# Dupe everything

			# Get the top pages
			$top_pageids = $this->get_top_pageids();
			if (!empty($top_pageids)) {
				foreach($top_pageids as $pageid) {
					# This should handle all the subpages and their files etc
					$page = &$this->web_system->get_page($pageid);
					$page->dupe_remap(false, $dupe_map, $remap_list);
					$this->web_system->forget_page($pageid); # so we don't blow the memory out
				}
			}

		} else {
			# the dupe map will allow us to match up the pages
			# we are dupeing from this site with the new parents
			# in the new site - even across browser refreshes
			$index = &$this->get_page_index();
			if (!empty($index)) {

				foreach ($index as $data) {
					$pageid = (int) $data['pageid'];
					if ($pageid <= 0) continue;
					if (in_array($pageid, $remap_list)) continue;
					if ($num_pages <= 0) return;
					$dupe_pageid = $dupe_map['page'][$pageid];
					$page = &$this->web_system->get_page($dupe_pageid);
					if(!$page->id) {
						# This could happen if one of the create pages failed
						continue;
					}
					$template = $page->get_template();
					$template->remap_link_ids($dupe_map);
					$this->web_system->forget_page($dupe_pageid);
					$remap_list[] = $pageid;
					$num_pages--;
				}
			}
		}
	}


	function dupe_cleanup($siteid, &$dupe_map, &$dupe_message) {
		$session = &get_mysource_session();
		if (empty($dupe_map)) $dupe_map = $session->get_var('dupe_map');
		if (trim($dupe_message) == '') $dupe_message = $session->get_var('dupe_message');

		# Set the index page
		$new_site = &$this->web_system->get_site($siteid);
		$new_site->set_index_pageid($dupe_map['page'][$this->index_pageid]);
		$new_site->set_not_found_pageid($dupe_map['page'][$this->not_found_pageid]);
		$new_site->set_forbidden_pageid($dupe_map['page'][$this->not_found_pageid]);

		if (!empty($this->allowed_extensions)) {
			# Now do the site extensions
			foreach($this->allowed_extensions as $extension) {
				$extension = &$this->get_extension($extension);
				$extension->dupe($new_site->id);
			}
		}
		$new_site->cached = true;
		$new_site->clear_cache();

		$dupe_map['site'][$this->id] = $new_site->id;

		$dupe_message .= "Site '$this->name' duplicated.\n";

		return $new_site->id;
	}


	 #############################################
	# Deletes all pages in the site
	function delete($num_pages=false, $save_page_index_cache=true) {

		$message = '';

		if ($num_pages === false) {
			# Delete everything at once

			# Delete pages first - starting with the top..
			$top_pageids = $this->get_top_pageids();
			if (!empty($top_pageids)) {
				foreach($top_pageids as $pageid) {
					# This should handle all the subpages and their files etc.
					$page = &$this->web_system->get_page($pageid);
					$message .= $page->delete()."\n";
				}
			}
			$message .= $this->delete_cleanup();

		} else {
			# only delete $num_pages pages from the site
			$index = $this->get_page_index();
			if (!empty($index)) {
				foreach ($index as $data) {
					$pageid = (int) $data['pageid'];
					if ($pageid <= 0) continue;
					if (!empty($data['childids'])) continue;
					$page = &$this->web_system->get_page($pageid);
					$message .= $page->delete_cleanup($save_page_index_cache)."\n";
					$this->remove_page_from_page_index($pageid, $save_page_index_cache);
					$num_pages--;
					if ($num_pages <= 0) {
						# we are done
						return ereg_replace("[\n]+","\n",$message);
					}
				}
			}
		}
		return ereg_replace("[\n]+","\n",$message);
	}


	 #############################################
	# Deletes everything except the pages
	function delete_cleanup() {
		global $XTRAS;

		$message = '';

		 ########################################################
		# DELETE ALL THE FILES HERE ONCE FILE MANAGER IMPLEMENTED

		# Now do the site extensions
		$extensions = array_keys($XTRAS->list_type("site/extensions"));
		if (!empty($extensions)) {
			foreach($extensions as $extension) {
				if(in_array($extension,$this->allowed_extensions)) {
					$extension = &$this->get_extension($extension);
					$extension->delete();
				}
			}
		}

		 #####################################
		# Let's kill our customised design
		$design = &$this->get_design();
		$design->delete_customisation();

		 ####################################
		# Okay, lets get rid of the directory.
		delete_directory($this->data_path);

		 #########################################
		# And all the site-specific access groups
		if (!empty($this->access_groups)) {
			foreach($this->access_groups as $groupid => $name) {
				$group = new Access_Group($groupid, $this->web_system);
				$message .= $group->delete()."\n";
			}
		}

		# Grants, grants and more grants
		$this->update_access_grants(array());
		$this->update_allowed_extensions(array());

		# We must do the templates and designs one at a time since bulk deleteing won't work.
		$x = $this->allowed_templates;
		if (!empty($x)) {
			foreach($x as $y) {
				$this->remove_allowed_template($y);
			}
		}
		$x = $this->allowed_designids;
		if (!empty($x)) {
			foreach($x as $y) {
				$this->remove_allowed_designid($y);
			}
		}

		$this->update_adminids(array());
		$this->update_editorids(array());

		$this->update_urls(array());

		$db = &$this->get_db();
		$db->delete("DELETE FROM site WHERE siteid='$this->id'");
		$message .= "Site '$this->name' deleted.\n";
		$this->clear_cache();
		unset($this);
		return ereg_replace("[\n]+","\n",$message);
	}



	 ###############################################
	# Returns a reference to the backend object,
	# this may already be setup by the site.
	function &get_backend() {
		return $this->web_system->get_backend(); # Backend refrence
	}


	 #######################################################################
	# Returns a reference to a site extension object for the current site
	function &get_extension($extension='') {
		if(!$extension) return;
		$class_name = "site_extension_$extension";
		if(get_class($this->extensions[$extension]) != $class_name) {
			global $XTRAS_PATH;
			include_once("$XTRAS_PATH/site/extensions/$extension/$extension.inc");
			$this->extensions[$extension] = new $class_name($this->id);
		}
		return $this->extensions[$extension];
	}

	 #######################################################################
	# Returns a an array of site extension codes that the current
	# user can edit for this site
	function get_editable_extensions() {

		if($this->admin_access()) {
			return $this->allowed_extensions;
		}

		return Array();
	}

		 #################################################
	    # Removes some information from the page_index  #
	   # that the user should not be aware of          #############
	  # Due to read restrictions, affects only the childid lists  #
	 # Doesn't affect the child lists
	#############################################################

	function restrict_page_index($pageid=0) {
		if ($this->editor_access()) return true;
		$index = &$this->get_page_index();
		$childids = &$index[$pageid]['childids'];
		if (!empty($childids)) {
			foreach($childids as $key => $subpageid) {
				if (!$this->page_read_access($subpageid)) {
					$rem_pages[] = $key;
				} else {
					$this->restrict_page_index($subpageid); # Don't check subpages
				}
			}
		}
		if (!empty($rem_pages)) {
			rsort($rem_pages);
			foreach($rem_pages as $rem_page) {
				unset($childids[$rem_page]);
			}
		}
	}



	 ####################################################################################
	# Checks the page_index to see whether the current user has access_to a certain page
	function page_read_access($pageid=0) {

		if($this->editor_access()) return true;

		$session = &get_mysource_session();
		
		if(isset($session->user->id)) { # Page editors and admins can see their pages.
			$userid = $session->user->id;
			if(in_array($userid,$this->page_editorids[$pageid])) return true;
			if(in_array($userid,$this->page_adminids[$pageid]))  return true;
		}

		$access_reject_message = $session->get_var("access_reject_message");
		if (!$pageid) $pageid = $this->web_system->current_pageid;
		$index = &$this->get_page_index();
		$page = &$index[$pageid];

		if ($page['effective_status'] != 'L' && $page['effective_status'] != 'R' && $page['effective_status'] != 'E') {
			switch($page['effective_status']) {
			case 'U':
				$access_reject_message = "This page is currently under construction.";
				break;
			case 'P':
				$access_reject_message = "This page is currently pending approval.";
				break;
			case 'D':
				$access_reject_message = "This page is currently disabled.";
				break;
			case 'D':
				$access_reject_message = "This page has been archived.";
				break;
			default:
				$access_reject_message = "This page not currently live.";
				break;
			}
			return false;
		}

		if ($page['effective_public']) return true;

		if ($session->logged_in()) {
			switch($session->user->web_status) {
			case 'A': # Active web status.
				if (!empty($session->access_groups)) {
					foreach($session->access_groups as $groupid) {
						if(in_array($groupid,$page['effective_access_grants'])) return true;
					}
				}
				$access_reject_message = "Access to this page is restricted to certain users only.";
				return false;
			case 'E':
				$access_reject_message = "Access to this page is restricted, and your account has expired.";
				return false;
				break;
			case 'L':
				$access_reject_message = "Access to this page is restricted, and your account has been locked.";
				return false;
				break;
			case 'P':
				$access_reject_message = "Access to this page is restricted, and your account is pending approval.";
				return false;
				break;
			default:
				$access_reject_message = "Access to this page is restricted.";
				return false;
				break;
			}
		}

		$access_reject_message = "Access to this page is restricted.";
		return false;
	}


	 ###################################
	# Are we allowed to view this site?
	function read_access() {
		$session = &get_mysource_session();

		# Is this site open to anyone?
		if (!$this->public) {
			if (!$session->logged_in() ||
					!(count(array_intersect($session->access_groups,$this->access_grants))
						|| $this->admin_access())
				) {

				return false;
			}
		}
		return true;
	}


	 #############################################
	# The the current user an admin of this site?
	function admin_access() {
		if (superuser('web')) return true;
		$session = &get_mysource_session();
		return in_array($session->user->id,$this->adminids);
	}


	 #############################################
	# The the current user an editor of this site?
	function editor_access() {
		if ($this->admin_access()) return true;
		$session = &get_mysource_session();
		return in_array($session->user->id,$this->editorids);
	}


	 ##########################################################
	# The the current user an editor of any pages on the site?
	function page_editor_access($pageid=0) {
		$session = &get_mysource_session();
		if(!$session->user->id) return false;
		if ($this->editor_access() || $this->page_admin_access($pageid)) return true;
		if(!$pageid) {
			for(reset($this->page_editorids); $key = key($this->page_editorids); next($this->page_editorids)) {
				if(in_array($session->user->id,$this->page_editorids[$key])) return true;
			}
			return false;
		}
		return in_array($session->user->id,$this->page_editorids[$pageid]);
	}

	 ##########################################################
	# The the current user an admin of any pages on the site?
	function page_admin_access($pageid=0) {
		$session = &get_mysource_session();
		if(!$session->user->id) return false;
		if ($this->admin_access()) return true;
		if(!$pageid) {
			for(reset($this->page_adminids); $key = key($this->page_adminids); next($this->page_adminids)) {
				if(in_array($session->user->id,$this->page_adminids[$key])) return true;
			}
			return false;
		}
		return in_array($session->user->id,$this->page_adminids[$pageid]);
	}


	 #####################################################
	# The the current user any sort of editor of this site
	function staff_access() {
		return $this->page_editor_access(); # The lowest form
	}


	 ####################################################
	# Returns a list of all the pages in the current site
	# of the type "ecommerce_trolley"
	function get_pages_of_template($template='') {
		$r = array();
		$index = &$this->get_page_index();
		for(reset($index); $pageid = key($index); next($index)) {
			if($index[$pageid]['template'] == $template) {
				$r[$pageid] = $index[$pageid]['name'];
			}
		}
		return $r;
	}


	 ########################################
	# Print a link to an image on this site
	function image_tag($file='',$alt='',$width='',$height='',$extra='',$siteid='') {
		if (!$siteid) {
			$siteid = $this->id;
		}
		$src = "$this->data_path/$file";
		$rel = $this->get_file_href();
		return $this->web_system->image_tag($src,$rel,$alt,$width,$height,$extra);
	}


	#########################################################################################
	#FRONTEND STUFF
	function print_frontend() {
		# Strip out some of the data in $SITE so that only information regarding pages
		# that the user is allowed to view is shown. E.G. Menu bar
		$this->restrict_page_index();

		 ############################################
		# Now let's set some of out default headers
		if($this->default_charset)
			header("Content-type: text/html; charset=$this->default_charset");
		if($this->default_languages)
			header("Content-language: $this->default_languages");

		 #######################################################
		# Does a web extension want to handle this request?
		$mysource_web_extension = $_REQUEST['mysource_web_extension'];
		if ($mysource_web_extension) {

			# Only do this if the Xtra has been registered
			global $XTRAS;
			if($XTRAS->name("web/extensions","$mysource_web_extension")) {
				$web_extension = &$this->web_system->get_extension($mysource_web_extension);
				# The frontend may or may not exit, depending on whether it prints its own page.
				if(method_exists($web_extension, 'print_frontend')) {
					$web_extension->print_frontend();
				}
			}
		}

		 #######################################################
		# Does a users extension want to handle this request?
		$mysource_users_extension = $_REQUEST['mysource_users_extension'];
		if ($mysource_users_extension) {

			# Only do this if the Xtra has been registered
			global $XTRAS;
			if($XTRAS->name("users/extensions","$mysource_users_extension")) {
				$users_system = &get_users_system();
				$users_extension = &$users_system->get_extension($mysource_users_extension);
				# The frontend may or may not exit, depending on whether it prints its own page.
				if(method_exists($users_extension, 'print_frontend')) {
					$users_extension->print_frontend();
				}
			}
		}

		 #################################
		# Does one of our site extensions want to handle this request?
		$mysource_site_extension = $_REQUEST['mysource_site_extension'];
		if ($mysource_site_extension) {
			# Only do this if the Xtra has been registered
			global $XTRAS;
			if($XTRAS->name("site/extensions","$mysource_site_extension")) {
				# Only do this if this is an allowed extension on the site
				if(in_array($mysource_site_extension,$this->allowed_extensions)) {
					# Only do this if we have access to this site
					if($this->read_access()) {
						$site_extension = &$this->get_extension($mysource_site_extension);
						if(method_exists($site_extension, 'print_frontend')) {
							$site_extension->print_frontend();
						}
						# The frontend may or may not exit, depending on whether it prints its own page.
					}
				}
			}
		}
		$page = &$this->get_page();
		if(!$page->id) {
			$frontend = &$this->web_system->get_frontend();
			$frontend->page_not_found();
		} else {
			$page->print_frontend(false);
		}
	}


	#########################################################################################
	#BACKEND STUFF

	 ###############################################
	# Returns a reference to the site_backend object,
	# this may already be setup by the site.
	function &_get_site_backend() {

		if (!$this->_site_backend || get_class($this->_site_backend) != 'site_backend') {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/site_backend.inc");
			$this->_site_backend = new Site_Backend($this);
		}
		return $this->_site_backend;
	}

	 #########################################################################
	# Some Aliases for fns in Site_Backend()
	function &setup_backend() {
		$sb = &$this->_get_site_backend();
		return $sb->setup_backend();
	}
	function print_backend() {
		$sb = &$this->_get_site_backend();
		$sb->print_backend();
	}
	function print_page_hierarchy(&$pageids) {
		$sb = &$this->_get_site_backend();
		$sb->print_page_hierarchy($pageids);
	}
	function print_floating_site_map() {
		$sb = &$this->_get_site_backend();
		$sb->print_floating_site_map();
	}


	 #####################################################
	# Attempts to create a page for this site,
	# assuming that we don't excede the max page count
	function create_page($name='',$template='',$parentid=0,$status='U',$save_page_index_cache=true, $ignore_page_dirs=false) {
		$page_index = &$this->get_page_index();
		$current_page_count = count($page_index) - 1; # ignore site root entry

		# if we have already got the maximum number of pages, tell them to bugger off
		if($this->max_pages > 0 && $current_page_count >= $this->max_pages) {
			return Array(MYSOURCE_ERROR_CODE_ERROR, "Unable to create page '$name', this would exceed the maximum number of pages allowed on this site", 0);
		# else create the page
		} else {
			$new_page =& new Page();
			list($error_code, $msg) = $new_page->create($name, $template, $this->id,$parentid,$status,$save_page_index_cache, $ignore_page_dirs);
			return Array($error_code, $msg, $new_page->id);
		}#end if
	}#end create_page()

	/**
	* Given a particular auto_order scheme this function retruns the appropriate order_no
	*
	* @access public
	* @param string $scheme - the auto ordering scheme
	* @param int $parentid - the id of the parent
	* @param int $pageid - the page id
	* @return int $orderno
	*/
	function prepare_page_auto_order_no($scheme, $parentid=0, $name) {
		$db = &$this->get_db();
		$update_orders = false;

		switch($scheme) {
			case 'az':
				$orderno = $db->single_element("SELECT MAX(orderno) + 1 FROM page WHERE parentid = $parentid  AND siteid = $this->id AND name < '".addslashes($name)."' ORDER BY name");
				$update_orders = true;
			break;
			case 'za':
				$orderno = $db->single_element("SELECT MAX(orderno) + 1 FROM page WHERE parentid = $parentid AND siteid = $this->id AND name > '".addslashes($name)."' ORDER BY name DESC");
				$update_orders = true;
			break;
			default:
				$orderno = $db->single_element("SELECT MAX(orderno) + 1 FROM page WHERE parentid = $parentid AND siteid = $this->id");
			break;
		}

		if ($orderno < 1 ) {
			$orderno = 0;
		}
		if ($update_orders) {
			$db->update("UPDATE page SET orderno = orderno + 1 WHERE parentid = $parentid AND siteid = $this->id AND orderno >= $orderno");
		}
		if ($orderno == 0) return 1;
		return $orderno;
	}
}#end class
?>