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
## File: xtras/page/templates/redirect/redirect.inc
## Desc: A page template class.. a page which redirects to another page, site, or url
## $Source: /home/cvsroot/xtras/page/templates/redirect/redirect.inc,v $
## $Revision: 2.14 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:38 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
require_once("$INCLUDE_PATH/parameter_set.inc");
#---------------------------------------------------------------------#

# Shoved in here so this old template works inthe new system
class Page_Template_Redirect extends Page_Template {

	 ##############################################################
	# A few variables used by this object
	var $parameters = array();

	 ##############################
	# Constructor
	function Page_Template_Redirect($pageid, &$system) {
		return Page_Template::Page_Template($pageid, &$system);
	}

	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id) return false;
		$db = &$this->get_db();
		# Set the title to the title of the current page.
		$page = &$this->get_page($pageid);

		$db->update("UPDATE $this->table_name SET parameters = '".addslashes(serialize($this->parameters))."' WHERE pageid='$pageid'");
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

		 ########################
		# Load from the database
		$db = &$this->get_db();
		
		 ########################
		# Load from the database
		$db = &$this->get_db();
		list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM $this->table_name WHERE pageid='$pageid'");
		$this->parameters = unserialize($tmp);

		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		$this->set_generated_information();

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
		$to_pageid = &$this->get_value('to_pageid');
		if (in_array($to_pageid, $from_page_ids)) {
			$to_pageid = $dupe_map['page'][$to_pageid];
			$to_siteid = &$this->get_value('to_site');
			$to_page = &$this->get_page($to_pageid);
			$to_siteid = $to_page->siteid;
			$this->save_parameters();
		}
	}

	/**
	* Grabs the keywords out of the contents.
	*
	* if the page is redirecting to an external URL, the page is buffered and keywords extracted
	* from that. If the page is redirecting to another MySource page, then it uses that page's
	* extract keywords function to get keywords. 
	*
	* @access public
	* @returns string
	*/
	function extract_keywords() {
		if($this->parameters['to_url']) {
			ob_start();
			readfile("$this->to_url/");
			$html = ob_get_contents();
			ob_end_clean();
			return strip_tags_padded($html);
		} else { # Recrusion ! (potentially)
			global $REDIRECT_KEYWORD_EXTRACTING_PAGEIDS;
			if ($REDIRECT_KEYWORD_EXTRACTING_PAGEIDS[$this->id]) return ''; # Recursion prevention
			$REDIRECT_KEYWORD_EXTRACTING_PAGEIDS[$this->id] = 1;
			$page     = &$this->get_page($this->parameters['to_pageid']);
			if(!$page->id) return ''; #no page exists, no keywords
			$template = &$page->get_template();
			return $template->extract_keywords();
		}
	}

	 #################################
	# Change the to_pageid of the page
	function set_to_pageid($to_pageid, $found_in_post_vars) {
		$current_to_page = &$this->get_value('to_pageid');
		if (!$found_in_post_vars) return;
		if ($to_pageid == $current_to_page || $to_pageid == $this->id) return;

		$new_to_page = &$this->get_page($to_pageid);
		$page = &$this->get_page();
		# if we are redirecting to a redirect
		if ($this->check_for_loop($to_pageid, $page->id)) {
			return 'Sorry, redirecting to this page would create an infinite redirecting loop which could rupture the very fabric of existance itself.';
		}

		$current_to_page = $to_pageid;
		return 'Redirect page updated.';
	}

	/**
	* Returns true if it find a loop in redirect pages
	*
	* @param   int $to_pageid		Can be current page or initially the next page to check
	* @param   int $original_pageid The very first redirect pageid
	* @access  public
	* @returns bool
	*/
	function check_for_loop($to_pageid, $original_pageid) {
		$to_page = &$this->get_page($to_pageid);
		$original_page = &$this->get_page($original_pageid);
		$original_page = $original_page->get_template();
		if($to_page->template == $original_page->template) {
			if ($to_pageid == $original_pageid) return true;
			$new_redirect_template = $to_page->get_template();
			$new_to_pageid = $new_redirect_template->get_value('to_pageid');
			return $new_redirect_template->check_for_loop($new_to_pageid, $original_pageid);
		} else {
			return false;
		}
	}

	 ##################################################################
	# Prints the backend for the user 
	function print_backend() {
		$db = &$this->get_db();
		$backend = &$this->setup_backend();
		$backend->set_hidden_field('action');
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,'template_screen', $this->get_backend_href());
		if($pset->process($backend)) {
			$this->save_parameters();
		}

		# prints the rest of the tabs at the top
		$pset->print_backend($backend);
		return;
	}

	 ################################################
	# Prints or does various things on the front-end
	function print_frontend() {
		$page = &$this->get_page();
		$site = &$this->get_site();

		if($this->parameters['to_url']) { # Go to external URL
			$location = valid_url($this->parameters['to_url']);
		} elseif ((int) $this->parameters['to_pageid']) { # GO to another page in the system
			$site_index = &$site->get_page_index();
			if(isset($site_index[$this->parameters['to_pageid']])) {
				$location = $this->web_system->get_page_url($site->id,(int)$this->parameters['to_pageid']); 
			} else {
				$location = $this->web_system->get_page_url($this->parameters['to_site'], (int)$this->parameters['to_pageid']); 
			}
			if (trim($this->parameters['extra_url'])) {
				$location .= trim($this->parameters['extra_url']);
			}
		}

		if (!$location) {
			$location = $this->web_system->get_site_url($site->id);
		}

		if ($this->new_window_is_yes()) {
			$toolbar	= ((in_array('toolbar',		$this->parameters['window_options']))?'0':'1');
			$location2	= ((in_array('location',	$this->parameters['window_options']))?'0':'1');
			$scrollbars = ((in_array('scrollbars',	$this->parameters['window_options']))?'0':'1');
			$resizable	= ((in_array('resizable',	$this->parameters['window_options']))?'0':'1');
			$menubar	= ((in_array('menubar',		$this->parameters['window_options']))?'0':'1');
			$status		= ((in_array('status',		$this->parameters['window_options']))?'0':'1');
			$width		= $this->parameters['width'];
			$height		= $this->parameters['height'];

			echo "<html><body><script language=javascript>";
			echo "page = window.open('".$location."','".time()."','toolbar=".$toolbar.",menubar=".$menubar.",location=".$location2.",status=".$status.",scrollbars=".$scrollbars.",resizable=".$resizable.(($width)?",width=".$width:'').(($height)?",height=".$height:'')."');";
			echo("history.go(-1);");
			echo "</script></body></html>";
		} else {
			header("Location: $location");
		}
	}

	 ################################################
	# Prints the "body" of a page.. the main section
	function print_body() {
		echo "<p>This page is a redirect template. It does not have any body copy.</p>";
	}

	/**
	* Gets an array of sites
	*
	* @access public
	* @return array
	*/
	function get_site_array() {
		$session = &get_mysource_session();
		$site = &$this->get_site();
		$sites = array();
		$sites = $this->web_system->get_editable_sites($session->user->id);
		$sites[$site->id] = '[THIS SITE] '.$sites[$site->id];
		return $sites;
	}

	/**
	* Gets the page array of the siteid
	*
	* @access public
	* @return array
	*/
	function get_page_array() {
		return $this->web_system->page_array_with_sticks($this->parameters['to_site']);
	}

	/**
	* Returns true if new window is set to y
	*
	* @access public
	* @return bool
	*/
	function new_window_is_yes() {
		return ($this->parameters['new_window'] == 'Y');
	}

	/**
	* Gets the value in the parameters
	*
	* @param string $code The name
	* @access public
	* @return mixed
	*/
	function &get_value($code) {
		return $this->parameters[$code];
	}
}

?>
