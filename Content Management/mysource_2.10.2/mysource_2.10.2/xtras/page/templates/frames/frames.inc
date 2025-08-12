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
## Desc: A page template that acts as a thin client to the frames site extension, 
##       only real use is so that people can hit the homepage of a site with out 
##       needing the /?mysource_site_extension=frames business
## $Source: /home/cvsroot/xtras/page/templates/frames/frames.inc,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:32 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
#---------------------------------------------------------------------#

class Page_Template_Frames extends Page_Template {

	 ##############################
	# Constructor
	function Page_Template_Frames($pageid) {
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

		if (!Page_Template::load($pageid)) return 0;

		 ###################
		# Save to the cache 
		$this->save_to_cache($pageid);
		return $pageid;
	}

	# delete, dupe and new_id need no change from the default

     ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {

		$backend = &$this->setup_backend();
		$backend->print_header();		

		$backend->open_section();

		$backend->open_field("&nbsp;", "top");
		?>
			There isn't anything to edit here.<br>
			To edit the frames, you need to edit the frames site extension
		<?

		$backend->print_footer();		
	}

	 ########################################################
	# Call the extension and get it to print what it needs
	function print_frontend() {
		$site = &$this->get_site();
		$site_ext = &$site->get_extension("frames");
		$site_ext->print_frontend();
	}

	  ##########################################################################
	 # print_body() shouldn't EVER get called
	function print_body() {
	}#end print_body();

}#end class

?>