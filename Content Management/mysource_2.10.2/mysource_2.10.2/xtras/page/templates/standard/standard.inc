<?php
    ##############################################
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
## $Source: /home/cvsroot/xtras/page/templates/standard/standard.inc,v $
## $Revision: 2.19 $
## $Author: bvial $
## $Date: 2004/01/28 23:02:55 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
require_once("$INCLUDE_PATH/parameter_set.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
#---------------------------------------------------------------------#

class Page_Template_Standard extends Page_Template {

	 ##############################################################
	# A title and a bodycopy. Simple requirments for a simple page
	var $parameters = array();

	 ##############################
	# Constructor
	function Page_Template_Standard($pageid) {
		return Page_Template::Page_Template($pageid);
	}


	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id)	return false;
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

	 ##############################################
	# Grabs keywords out of the title and bodycopy
	function extract_keywords() {
		$bodycopy = new BodyCopy($this->parameters['bodycopy'], 'bodycopy');
		// Get the possible keywords
		if (empty($this->parameters['title'])) {
			$contents = $bodycopy->get_html();
		} else {
			$contents = $this->parameters['title'] .' ' . $bodycopy->get_html();
		}
		// strip out the tags
		$contents = strip_tags($contents);
		// strip out the whitespace
		$contents = trim(preg_replace("/[\s]+/", " ", $contents));

		return $contents;
	}

	 ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$backend = &$this->setup_backend();
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,'template_screen', $this->get_backend_href());

		if($pset->process($backend)) {
			$this->save_parameters();
		}

		# prints the rest of the tabs at the top
		$pset->print_backend($backend);
		return;
	}

	/**
	* Returns an associaive array of universal replacements. These can be used to replace special keywords in copy
	*
	* @access  public
	* @returns array
	*/
	function &get_universal_replacements($descs) {
		$k = &Page_Template::get_universal_replacements();
		$d = &Page_Template::get_universal_replacements(1);
		if($descs) return $d;
		return $k;
	}

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		return $this->parameters['title'];
	}

	 ##########################################################################
	# Define print_body() - this *should* get called by the site's design xtra
	# The keyword replacements are the second argument for consistancy between
	# templates
	function print_body($undef, &$keyword_replacements) {
		$keyword_replacements = array_merge($this->get_universal_replacements(), $keyword_replacements, $_GET);
		$bodycopy = new BodyCopy($this->get_value('bodycopy'), 'bodycopy');
		$bodycopy->add_replace_keywords($keyword_replacements);
		$bodycopy->paint();
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

	/**
	* Returns an array of data for exporting
	*
	* @access public
	* @return array
	*/
	function export_data() {
		return array('title'=>$this->parameters['title'],'bodycopy'=>$this->parameters['bodycopy']);
	}

	/**
	* Given a data array tries to import it into this template
	*
	* @param array $data The data
	* @access public
	* @return void
	*/
	function import_data($data) {
		if (!empty($data)) {
			foreach($data as $code => $datum) {
				$code = strtolower(eregi_replace("[^a-z]+","",$code));
				if ($code == 'title' || $code == 'name') {
					$this->parameters['title'] = $datum;
				} elseif ($code == bodycopy) {
					$this->parameters['bodycopy'] = $datum;
				}
			}
			$this->save_parameters();
		}
	}
}
?>
