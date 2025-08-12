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
## Desc: Parent class for web extension xtra classes
## $Source: /home/cvsroot/mysource/include/web_extension.inc,v $
## $Revision: 2.11 $
## $Author: achadszinow $
## $Date: 2003/07/02 22:39:30 $
#######################################################################
#---------------------------------------------------------------------#

class Web_Extension extends WebObject {
	
	# Used for caching purposes, set to the code name as there can only be one web extension
	var $id=0;

	# A string representing the prefix to the tables that this will use
	var $table_prefix='';

	# A string representing the codename of this extention - automatic
	var $codename='';

	# A string representing the name of this extention - automatic
	var $name='';

	# The version number of this xtra instance
	var $version='';

	# The path to this Xtra's directory
	var $xtra_path='';

	# Directory where this extension keeps its files.
	var $data_path='';

	 ##############################
	# Constructor
	function Web_Extension() {
		WebObject::WebObject();
		$this->set_generated_information();
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element("codename",$result);
		array_remove_element("name",$result);
		array_remove_element("version",$result);
		array_remove_element("table_prefix",$result);
		array_remove_element("data_path",$result);
		array_remove_element("xtra_path",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		WebObject::__wakeup();
		$this->set_generated_information();
	}

	 ##################################################
	# Handy little wrappers
	function &get_site($siteid=0) {
		$site = &$this->web_system->get_site($siteid);
		return $site;
	}

	function &get_page($pageid=0) {
		return $this->web_system->get_page($pageid);
	}

	function &get_extension_description() {
		global $XTRAS;
		return $XTRAS->description('web/extensions',$this->codename);
	}

	 #####################################################################################################
	# Returns whether this object is public or not, used to decide how to get the images piped through
	function public() {
		return false; # let's be more secure by default
	}

	 ################################################################
	# returns true if the current user can view the passed file
	function file_view_access($file="") {
		return false; # more secure by default
	}# file_view_access()

	 #########################################################################
	# Returns a href to a file associated with this extension
	function get_file_href($filename="") {
		# if we are public then anyone can view our files so no piping
		if ($this->public()) {
			return data_href("web/extensions/$this->codename/$filename");
		# OK so we are going to need some security on this
		} else {
			return web_href("mysource_action=send_file&type=web&file=extensions/$this->codename/$filename", true);
		}
	}#end get_file_href()

	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		return $this->web_system->get_backend_href()."&extension_edit=$this->codename";
	}

	/**
	* Gets a backend url for the extension
	*
	* @access  public
	* @returns string
	*/
	function get_backend_url() {
		return $this->web_system->get_backend_url()."/web.php?extension_edit=$this->codename";
	}

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS, $XTRAS_PATH;
		$this->codename     = $XTRAS->codename("web/extensions",ereg_replace("^web_extension_","",get_class($this)));
		$this->name         = $XTRAS->name("web/extensions",$this->codename);
		$this->version      = $XTRAS->version("web/extensions",$this->codename);
		$this->table_name   = "xtra_web_extension_$this->codename";
		$this->table_prefix = $this->table_name."_";
		$this->xtra_path    = "$XTRAS_PATH/web/extensions/$this->codename";
		$this->id           = &$this->codename;
		
		# update the data path to represent our new status
		$this->data_path = get_data_path($this->public(), "web/extensions/".$this->codename);

	}# set_generated_information()

	  ###################################################################
	 # Prints a html image tag
	function image_tag($file='', $alt='', $width='', $height='', $extra='', $link='', $target='') {

		$src = $this->data_path."/".$file;
		$rel = $this->get_file_href();
		return $this->web_system->image_tag($src, $rel, $alt, $width, $height, $extra, $link, $target);

	}#end image_tag()
	 
	 ############################
	# Sets up the backend for use
	function &setup_backend() {

		 ###################################
		# Setup the backend a little
		$backend = &$this->web_system->setup_backend(); # Backend refrence
		$backend->set_title("Web Extension - $this->name");
		$backend->set_tab("web_extension","web.php?extension_edit=$this->codename",$this->name,$this->get_extension_description(),"xtra");
		$backend->set_active_tab("web_extension");

		$backend->set_heading("$this->name v$this->version","xtra");
		$backend->set_subheading($this->name);
		$backend->set_hidden_field("action","");
		$backend->set_hidden_field("extension_edit",$this->codename);

		return $backend;
	}


     ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$backend = &$this->setup_backend();
		$backend->print_header();
		$backend->open_section("This web extension has no options.");
		$backend->print_footer();		
	}

	 #####################################
	# Lets get the URL for this extension
	function get_url($start_query_string=false) {
		$site = &$this->get_site();
		return $site->get_url(true).'mysource_web_extension='.$this->codename.(($start_query_string) ? '&' : '');
	}


	 ################################################
	# Prints or does various things on the front-end
	function print_frontend() {
		# Fill me in, my children.
	}

	 ##############################################################
	# depending on our effective publicness restrict the data path
	function restrict_data_path() {
		restrict_data_path($this->public(), "web/extensions/".$this->codename);
		$this->set_generated_information();
	}

	 ###################################
	# create any needed directories 
	function init_paths() {
		if (!is_dir($this->data_path)) create_directory($this->data_path);
	}

}#end class

?>
