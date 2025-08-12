<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
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
## Desc: Lets the user select a page to have displayed on every page
## $Source: /home/cvsroot/xtras/site/design_areas/metadata/individual.inc,v $
## $Revision: 1.2 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:38 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Metadata_Individual extends Site_Design_Area {

	function Site_Design_Area_Metadata_Individual(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->_set_var('group', 'all', 'text', '');

	}#end constructor

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a group, otherwise we don't know who we are
		if (!$this->get_val('group')) {
			$this->_set_error('Metadata Area without a group', __FILE__, __LINE__);
			unset($this);
			return false;
		}

		return true;

	}#end create()


	function paint() {

		$group = $this->get_val('group');
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/metadata.inc");
		$web_system = &get_web_system();
		$page = &$web_system->get_page();
		$metadata = new Metadata($page->siteid, $page->id);
		$metadata->print_frontend($group);

	}#end paint()

}#end class 

?>