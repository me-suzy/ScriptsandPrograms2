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
## File: xtras/page/templates/pullcontent/pullcontent.inc
## Desc: A page which pulls it contents from another MySource page
## $Source: /home/cvsroot/xtras/page/templates/pullcontent/pullcontent.inc,v $
## $Revision: 2.15 $
## $Author: dofford $
## $Date: 2004/03/04 23:07:12 $
#######################################################################
# Portions Copyright 2001, University Communications Services
#                          The University of Western Australia
#
# Contact: <cwis@uwa.edu.au>
# Modified by Mark Tearle <mtearle@ucs.uwa.edu.au>
#
# Based on Redirect xtra
#
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
#---------------------------------------------------------------------#

# Shoved in here so this old template works inthe new system
class Page_Template_PullContent extends Page_Template {

	 ##############################################################
	# A few variables used by this object
	var $content_pageid;
	var $to_siteid; # Not stored in database
	var $siteid;
	var $title;
	var $subpage_emulation;
	var $extra_querystring;
	var $submit_variables;
	var $submit_type;
	var $restrict_links;

	 ##############################
	# Constructor
	function Page_Template_PullContent($pageid, &$system) {
		return Page_Template::Page_Template($pageid, &$system);
	}

	 ######################################################################
	# Create a new record in the database and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id) return false;
		$db = &$this->get_db();
		# Set the content_pageid to the index page of the current site
		$site = &$this->get_site();
		$db->update("UPDATE $this->table_name SET content_pageid='$site->index_pageid' WHERE pageid='$pageid'");
		$id = $this->load($pageid);
		return $message;
	}

	 ################################################
	# Loads the temaplte details, from cache or database
	function load($pageid) {
		if ($pageid <= 0 && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name page template without a valid pageid.",__FILE__,__LINE__);
		}

		 #################
		# Check the cache 
		if ($this->load_from_cache($pageid)) return $pageid;


		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();
		list($this->id,$this->content_pageid,$this->title,$this->subpage_emulation, $this->extra_querystring, $this->submit_variables, $this->submit_type, $this->restrict_links) = $db->single_row("SELECT pageid, content_pageid, title, subpage_emulation, extra_querystring, submit_variables, submit_type, restrict_links FROM $this->table_name WHERE pageid='$pageid'");

		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		 ###################
		# Save to the cache 
		$this->save_to_cache($pageid);
		return $pageid;
	}

	/**
	* Remaps stuff that page_template won't be able to decide if it should be remapped or not
	*
	* @param   array $dupe_map The dupe map array
	* @access  public
	* @returns void
	*/
	function template_defined_remapper($dupe_map) {
		$from_page_ids = array_keys($dupe_map['page']);
		if (in_array($this->content_pageid, $from_page_ids)) {
			$this->set_content_pageid($dupe_map['page'][$this->content_pageid]);
		}
	}

	 ##################################################
	# Grabs keywords out of the title and bodycopy etc
	function extract_keywords() {
		# Recrusion ! (potentially)
		global $REDIRECT_KEYWORD_EXTRACTING_PAGEIDS;
		if ($REDIRECT_KEYWORD_EXTRACTING_PAGEIDS[$this->id]) return ''; # Recursion prevention
		$REDIRECT_KEYWORD_EXTRACTING_PAGEIDS[$this->id] = 1;
		$page     = &$this->get_page($this->content_pageid);
		$template = &$page->get_template();
		return $this->title . ' ' . $template->extract_keywords();
	}

	 ############################################################################
	# A little helper function with returns what site the content page belngs to
	function content_siteid() {
		$page = &$this->get_page($this->content_pageid);
		return $page->siteid;
	}

	 ##############################################
	# Set the page title for this particular page.
	function set_title($title) {
		if ($title == $this->title) return '';
		$db = &$this->get_db();
		$page = &$this->get_page();
		$this->title = $title;
		if ($db->update("UPDATE $this->table_name SET title = '".addslashes($this->title)."' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return 'Title Updated.';
		} else {
			return '';
		}
	}

	 #################################
	# Change the content_pageid of the page
	function set_content_pageid($content_pageid) {
		if ($content_pageid == $this->content_pageid || $content_pageid == $this->id) return '';

		$db = &$this->get_db();
		$page = &$this->get_page();

		 ##########################################
		# Find any pages that pull content from us.
		# This is to prevent a loops
		$new_content_page = &$this->get_page($content_pageid);
		if($new_content_page->template == $page->template) {
			$loop = array();
			$pullerids = array($this->id);
			while(count($pullerids = &$db->single_column("SELECT pageid FROM $this->table_name WHERE content_pageid IN (".implode("','",$pullerids).") AND pageid != content_pageid"))) {
				$loop = array_merge($loop, $pullerids);
			}
			$template = &$new_content_page->get_template();
			if($template->check_pull_loop($loop)) {
				return "Sorry, pulling content from this page would create an infinite content-pulling loop which could rupture the very fabric of existance itself.";
			}
		}
	
		$this->content_pageid = $content_pageid;
		if ($db->update("UPDATE $this->table_name SET content_pageid='$this->content_pageid' WHERE pageid='$this->id'"))  {
			$this->clear_cache();
			return 'Pull Content page updated.';
		} else {
			return '';
		}
	}

	 ######################################################
	# Checks for a pulloop, return true of one is detected
	function check_pull_loop($loop) {
		if(in_array($this->content_pageid,$loop)) return true;
		$contentpage = &$this->get_page($this->content_pageid);
		$page = &$this->get_page();
		if($contentpage->id && $contentpage->template == $page->template) {
			$loop[] = $this->id;
			$template = &$contentpage->get_template();
			if($template->check_pull_loop($loop)) return true;
		}
		return false;
	}


	 ########################################################
	# Sets a boolean for whether we emulate subpages or not
	function set_subpage_emulation($subpage_emulation) {
		if($this->subpage_emulation == $subpage_emulation) return;
		$db = &$this->get_db();
		$this->subpage_emulation = $subpage_emulation;
		if ($db->update("UPDATE $this->table_name SET subpage_emulation='$subpage_emulation' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return 'Subpage emulation turned '.(($this->subpage_emulation)?'on.':'off.');
		}
		return false;
	}

	 ########################################################
	# Sets extra query string information that can be passed to other pages.
	function set_extra_querystring($extra_querystring) {
		if($this->extra_querystring == $extra_querystring) return;
		$db = &$this->get_db();
		$this->extra_querystring = $extra_querystring;
		if ($db->update("UPDATE $this->table_name SET extra_querystring='".addslashes($extra_querystring)."' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return array(MYSOURCE_ERROR_CODE_NONE, 'Extra Query String Information Updated');
		}
		return array(MYSOURCE_ERROR_CODE_ERROR, 'Extra Query String Information Not Updated');
	}

	 ########################################################
	# Sets variables to submit to the pulled page.
	function set_submit_variables($submit_variables) {
		if($this->submit_variables == $submit_variables) return;
		$db = &$this->get_db();
		$this->submit_variables = $submit_variables;
		if ($db->update("UPDATE $this->table_name SET submit_variables='".addslashes($submit_variables)."' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return array(MYSOURCE_ERROR_CODE_NONE, 'Submit Variables Updated');
		}
		return array(MYSOURCE_ERROR_CODE_ERROR, 'Submit Variables Not Updated');
	}

	 ########################################################
	# Sets the way the variables are to be submitted
	function set_submit_type($submit_type) {
		if($this->submit_type == $submit_type) return;
		$db = &$this->get_db();
		$this->submit_type = $submit_type;
		if ($db->update("UPDATE $this->table_name SET submit_type='".addslashes($submit_type)."' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return array(MYSOURCE_ERROR_CODE_NONE, 'Submit Type Updated');
		}
		return array(MYSOURCE_ERROR_CODE_ERROR, 'Submit Type Not Updated');
	}

	 #############################################################################
	# Sets a boolean for whether we are keeping internal links within this page
	function set_restrict_links($restrict_links) {
		if($this->restrict_links == $restrict_links) return;
		$db = &$this->get_db();
		$this->restrict_links = $restrict_links;
		if ($db->update("UPDATE $this->table_name SET restrict_links='$restrict_links' WHERE pageid='$this->id'")) {
			$this->clear_cache();
			return 'Restrict links turned '.(($this->restrict_links)?'on.':'off.');
		}
		return false;
	}


	 ##############################################
	# Ensures pull_content subpages are created for
	# each subpage of the page that is being pulled
	function refresh_emulated_subpages($remove_extraneous) {
		$here_page = &$this->get_page();
		$here_subpageids = $here_page->get_subpageids();

		$there_page = &$this->get_page($this->content_pageid);
		$there_subpageids = $there_page->get_subpageids();

		$current_pullmap = array();
		$pulled_there_subpageids = array();

		# Go through our current subpages and find the ones that are pulling content right.
		foreach($here_subpageids as $here_subpageid) {
			$here_subpage = &$this->get_page($here_subpageid);
			if($here_subpage->template == $here_page->template) {
				$here_subpage_template = &$here_subpage->get_template();
				if(in_array($here_subpage_template->content_pageid,$there_subpageids)) {
					# Hooray this page is pulling content from the right page!
					# Or is it? It might be a duplicate
					if(!in_array($here_subpage_template->content_pageid, $pulled_there_subpageids)) {
						$current_pullmap[$here_subpageid] = $here_subpage_template->content_pageid;
						$pulled_there_subpageids[] = $here_subpage_template->content_pageid;
						continue;
					}
				}
			}
			if($remove_extraneous) {
				$ms[] = $here_subpage->delete();
			}
		}
		$missing_there_subpageids = array_diff($there_subpageids, $pulled_there_subpageids);
		# Add the missing ones
		foreach($missing_there_subpageids as $missing_there_subpageid) {
			$missing_there_subpage = &$this->get_page($missing_there_subpageid);
			$new_here_subpage = new Page();
			$ms[] = $new_here_subpage->create($missing_there_subpage->name, 'pullcontent', $here_page->siteid, $here_page->id);
			
			$current_pullmap[$new_here_subpage->id] = $missing_there_subpageid;
			$fromid = $new_here_subpage->id;
			$toid = $missing_there_subpageid;
			
			$from_page = &$this->get_page($fromid);
			$to_page   = &$this->get_page($toid);
			$from_template = &$from_page->get_template();
			$to_template   = &$to_page->get_template();
			$ms[] =	$from_page->set_name($to_page->name,$to_page->short_name);
			$ms[] = $from_page->set_description($to_page->description);
			$ms[] = $from_page->set_orderno($to_page->orderno);
			$ms[] = $from_page->update_dirs($to_page->dirs);
			$ms[] = $from_template->set_content_pageid($toid);
			$ms[] = $from_template->set_subpage_emulation(1);
			$ms[] = $from_template->refresh_emulated_subpages($remove_extraneous);
		}
		return implode("\n",$ms);
	}



	 ##############################################
	# Ensures pull_content subpages are created for
	# each subpage of the page that is being pulled
	function pull_subpages($pageid,$parentid) {
		if ($pageid == $this->id) return;
		$page = &$this->get_page($this->id);
		$contentpage=&$this->get_page($pageid);
		$subpages = $contentpage->get_subpageids();
		while (list ($key, $id) = each ($subpages)) {
			if ($id == $this->id) continue;
			$oldpage = &$this->get_page($id);
			$oldpagesub = $oldpage->get_subpageids();
			$newpage = new Page();
			$newpage->create($oldpage->name,"pullcontent",$page->siteid,$parentid);
			$newtemplate = &$newpage->get_template();
			$newtemplate->set_content_pageid($oldpage->id);
			if (is_array($oldpagesub) && (count($oldpagesub) > 0)) {
				# we only need to remember the subpage emulation if the page has subpages itself.
				$newtemplate->set_subpage_emulation($this->subpage_emulation);
				$this->pull_subpages($oldpage->id,$newpage->id);
			}
		}
		unset($page);
		$this->web_system->forget_page($pageid);
		return true;
	}/*

	 ##################
	# Removes subpages.
	function remove_subpages($pageid) {
		$page = &$this->get_page($pageid);
		$subpages = $page->get_subpageids();
		while (list ($key, $id) = each ($subpages)) {
			if ($id == $pageid) continue;
			$removepage = &$this->get_page($id);
			$removepage->delete();
		}
		return "Subpages removed successfully.";
	}
	*/

	 ##################################################################
	# Prints the backend for the user 
	function print_backend() {

		$backend = &$this->setup_backend();
		$site = &$this->get_site();
		$page = &$this->get_page();
		$db = &$this->get_db();

		switch($_POST['action']) {
		case 'Commit':

			# Set the content pageid &/or the title.
			$backend->add_message($this->set_title($_POST['title']));
			$backend->add_message($this->set_content_pageid($_POST['content_pageid']));
			if (((int) $this->content_pageid <= 0)) {
				$new_site = &$this->get_site($_POST['to_siteid']);
				$this->set_content_pageid($new_site->index_pageid);
			}
			$backend->add_message($this->set_extra_querystring($_POST['extra_querystring']));
			$backend->add_message($this->set_submit_variables($_POST['submit_variables']));
			$backend->add_message($this->set_submit_type($_POST['submit_type']));
			$backend->add_message($this->set_restrict_links($_POST['restrict_links']));


			 ###################
			# Subpage emulation
			$backend->add_message($this->set_subpage_emulation($_POST['subpage_emulation']));

			if($this->subpage_emulation) {
				$backend->add_message($this->refresh_emulated_subpages($_POST['remove_subpages']));
			}

			/*
			# remove the old subpages first.. (only if they are site admin's or higher)
			if (($remove_subpages == 1) && $site->admin_access()) {
				$backend->add_message($this->remove_subpages($this->id));
				# if we remove subpages, we want to reset the "emulate subpage" field.
				$this->set_subpage_emulation(0);
			}

			# If we change the page emulation status (update that first) AND we're not already # removing pages (which updates the emulation status)
			if ($subpage_emulation != $this->subpage_emulation && (!$remove_subpages)) {
				if ($this->set_subpage_emulation($subpage_emulation)) {
					$backend->add_message("Sub Page Emulation Updated");
				}
				if ($this->subpage_emulation == 1) {
					if ($this->pull_subpages($content_pageid,$this->id)) {
						$backend->add_message("Subpages content pulled");
					}
				}
			}
			# or if we are emulating a different tree...
			if ($this->content_pageid != $content_pageid) {
				if (($this->subpage_emulation == 1) && (!$remove_subpages)) {
					if ($this->pull_subpages($content_pageid,$this->id)) {
						$backend->add_message("Subpages content pulled");
					}
				}
			}
			*/

			$this->updated();

			break;
		case 'New Site':
			$new_site = &$this->get_site($_POST['to_siteid']);
			$this->set_content_pageid($new_site->index_pageid);
			$this->updated();
			break;
		default:
			break;
		}

		$backend->print_header();

		$backend->open_section('Target Page');
		
		$backend->open_field('Page Title','one_liner');
		echo text_box('title',$this->title, 30, 128,'class=backend_data');
		echo "<span class=smallprint><br>If blank, will use the pulled page's title.</span>";

		$backend->open_field('Site:','one_liner');

		$session = &get_mysource_session();
		$sites = $this->web_system->get_editable_sites($session->user->id);

		$sites[$site->id] = '[THIS SITE] '.$sites[$site->id];

		echo combo_box('to_siteid',$sites,$this->content_siteid(),"onchange=\"alert('Redirecting to a different site...');form.action.value='New Site';form.submit();\"");

		$backend->open_field('Page:','one_liner');

		echo combo_box('content_pageid',$this->web_system->page_array_with_sticks($this->content_siteid()),$this->content_pageid,'class=backend_data');
		
		$backend->open_field('Emulate Subpages','one_liner');
		echo("<input type=\"checkbox\" name=\"subpage_emulation\" value=\"1\"");
		echo ($this->subpage_emulation == "1") ? ' CHECKED' : '';
		echo ('>');
		echo("<span class=smallprint><br>NOTE: If this option is on, committing this screen will update and sychronise the subpages to match those of the pulled page.");

		# if they are site admin(s) at least, they can remove the subpages.
		if ($site->admin_access()) {
			$backend->open_field('Remove Subpages','one_liner');
			echo("<input type=\"checkbox\" name=\"remove_subpages\" value=\"1\" onClick=\"if(document.edit.remove_subpages.value=checked) { confirm('Reminder: this option will delete all subpages and their content. Uncheck this value before you commit if you do not wish to delete the subpages on this page.')}\">");
			echo("<span class=smallprint><br>WARNING: this option will permanently remove all extraneous subpages from <b>this</b> page. This will ensure that the subpages match the subpages of the pulled page, and ONLY those subpages.");
		}

		echo("<p><a href=\"".$_SERVER['PHP_SELF']."?p=$this->content_pageid&template_edit=1\">Edit the contents of the pulled page.</a>");

		$backend->open_field('Extra Page Information','one_liner');
		echo text_box('extra_querystring',$this->extra_querystring, 40, 128,'class=backend_data');
		echo "<span class=smallprint><br>This allows you to add an extra query string to the contents of the pulled page.</span>";

		$backend->open_section('Custom Variables To Submit');
		$backend->open_field('Submit Variables', 'one_liner');
		echo text_area('submit_variables', $this->submit_variables, 30, 5);
		$backend->open_field('Submit Type', 'one_liner');
		echo combo_box('submit_type', array('global' => 'Global', 'get-post' => 'GET/POST', 'get' => 'GET', 'post' => 'POST', 'session' => 'Session'), $this->submit_type);
		echo '<span class=backend_fineprint>Enter the variables to submit as KEY:VALUE pairs.<br>Then select the method you want these variables passed to the Nest Content page.</span>';
		$backend->open_field('Keep pulled pages internal links in this page?', 'one_liner');
		echo combo_box('restrict_links', array('0'=>'No','1'=>'Yes'), $this->restrict_links);

		$backend->print_commit_button('Commit', "document.edit.action.value='Commit';document.edit.submit();");

		$backend->print_footer();
	}

	 ################################
	# Returns the title of tge page
	function get_title() {
		if($this->title) return $this->title;
		$page = &$this->web_system->get_page($this->content_pageid);
		if($page->id) {
			$template = &$page->get_template();
			return $template->get_title();
		}
	}

	 #########################################################
	# Don't moderate caching on this page. Use PHP default
	function moderate_caching() {
		$page = &$this->web_system->get_page($this->content_pageid);
		if($page->id) {
			$template = &$page->get_template();
			return $template->moderate_caching();
		}
		return true;
	}

	 ################################
	# Prints the body of the page
	function print_body() {
		$page = &$this->web_system->get_page($this->content_pageid);
		if($page->id) {
			$template = &$page->get_template();

			if (!$this->restrict_links) {
				$old_current_pageid = $web->current_pageid;
				$web->current_pageid = $page->id;
			}

			# set any variables that we jave been asked to set
			if ($this->submit_variables) {
				$old_vars = array();
				foreach(split("[\r\n]+",$this->submit_variables) as $val) {
					list($variable,$value) = explode(':',$val);
					if (trim($variable) == '') continue;
					switch ($this->submit_type) {
						case 'get-post' :
							$old_vars[$variable] = $_REQUEST[$variable];
							$_REQUEST[$variable] = $value;
							break;
						case 'get' :
							$old_vars[$variable] = $_GET[$variable];
							$_GET[$variable] = $value;
							break;
						case 'post' :
							$old_vars[$variable] = $_POST[$variable];
							$_POST[$variable] = $value;
							break;
						case 'session' :
							$session = &get_mysource_session();
							$old_vars[$variable] = $session->get_var($variable);
							$temp = $value;
							$session->set_var($variable,$temp);
							unset($temp);
							break;
						default :
							global $$variable;
							$old_vars[$variable] = $$variable;
							$$variable = $value;
							break;
					}
				}
			}

			$template->print_body($this->extra_querystring);

			if (!$this->restrict_links) $web->current_pageid = $old_current_pageid;

			# return any variables we set to their old values
			if ($this->submit_variables) {
				foreach($old_vars as $variable => $value) {
					switch ($this->submit_type) {
						case 'get-post' :
							$_REQUEST[$variable] = $value;
							break;
						case 'get' :
							$_GET[$variable] = $value;
							break;
						case 'post' :
							$_POST[$variable] = $value;
							break;
						case 'session' :
							$session = &get_mysource_session();
							$temp = $value;
							$session->set_var($variable,$temp);
							unset($temp);
							break;
						default :
							global $$variable;
							$$variable = $value;
							break;
					}
				}
			}
			
		}
	}

}

?>
