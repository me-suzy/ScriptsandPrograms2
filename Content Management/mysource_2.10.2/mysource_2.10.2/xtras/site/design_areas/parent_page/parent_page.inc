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
## File: xtras/site/designs_areas/page_lineage/page_lineage.inc
## Desc: this class is used to print the page lineage 
## $Source: /home/cvsroot/xtras/site/design_areas/parent_page/parent_page.inc,v $
## $Revision: 1.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:13 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Parent_Page extends Site_Design_Area {

	function Site_Design_Area_Parent_Page(&$_owner) {
		$this->Site_Design_Base($_owner);
	}#end Site_Design_Area_Page_Lineage()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);

		if ($this->get_val('print')) {
			# no additional attributes needed
			$ret_val = Array(
							# the name of the get_val variable we want
							'name'		=> 'page_'.$this->get_val('print'), 
							# whether it needs JS escaping
							'escape'	=> $this->get_val('escape'),
							# whether, if the page we are printing is a top level page
							# (ie has no parent) to print the site's details instead 
							# of the current pages
							'use_site'	=> $this->get_val('use_site')
							);
			$this->delete_var('print');
			$this->delete_var('escape');
			$this->delete_var('use_site');
			return $ret_val;
		} else {
			$this->_set_error("Parent Page without a 'print' attribute", __FILE__, __LINE__);
			return;
		}#end if

	}#end create()


	function paint(&$tag_attributes) {
		# let's just create an array as though it is a normal print tag
		$tag = Array('attributes' => $tag_attributes);
		$page = &$this->get_page();

		# if there is a parent
		if (!$page->parentid && $tag['attributes']['use_site']) {
			$tag['attributes']['name'] = 'site'.substr($tag['attributes']['name'], 4);
			$this->print_val($tag, '');
		} else {
			$this->print_val($tag, $page->parentid);
		}

	}#end paint()

}#end class 

?>