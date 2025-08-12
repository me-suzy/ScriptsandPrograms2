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
## $Source: /home/cvsroot/xtras/site/design_areas/page_lineage/page_lineage.inc,v $
## $Revision: 2.12 $
## $Author: bvial $
## $Date: 2003/11/23 22:33:15 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Page_Lineage extends Site_Design_Area {

	function Site_Design_Area_Page_Lineage(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up some default variables, 
		# so unless they write over them we have something to play with
		$this->_set_var("class", "smallprint", "text", "stylesheet class to use when printing the list");

		$this->_set_var("divider",					" : ",	"text",		"the text that divides the pages in the lineage");
		$this->_set_var("prefix_with_home_link",	false,	"boolean",	"whether or not to start the list with a link to the site's index page");
		$this->_set_var("home_link_text",			"Home",	"text",		"The text to use for the home link");
		$this->_set_var("prefix_with_divider",		false,	"boolean",	"whether or not to start the list with a divider");
		$this->_set_var("suffix_with_divider",		true,	"boolean",	"whether or not to end the list with a divider");
		$this->_set_var("show_links",				true,	"boolean",	"whether or not to you want the page lineage to be hyperlinked");
		$this->_set_var("pages_to_print",			0,		"text",		"How many pages deep to print, (zero, blank, empty means print all)");			

	}#end Site_Design_Area_Page_Lineage()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);

		# no additional attributes needed
		return;

	}#end create()


	function paint(&$tag_attributes, &$web_system) {

		$site = &$this->get_site();
		$page_index = &$site->get_page_index();
		$page = &$this->get_page();
		$page_lineage = array_keys($page->get_lineage());

		$class = ($this->get_val('class')) ? "class='".$this->get_val("class")."'" : "";
		$links = Array();
		$show_links = $this->get_val('show_links');

		# if we want a homepage link and we aren't on the home page
		if ($this->get_val('prefix_with_home_link') && $page->id != $site->index_pageid) {
			$links[] = (($show_links)?'<a '.$class.' href="'.$this->get_val('page_link', $site->index_pageid).'">':'').$this->get_val('home_link_text').(($show_links)?'</a>':'');
		}

		if (count($page_lineage) > 1) {
			for($i = 0; $i < count($page_lineage); $i++) {
				if($this->get_val('pages_to_print') > 0 && $i >= $this->get_val('pages_to_print')) break;
				$pageid = $page_lineage[$i];
				if (($pageid == $page->id) || ($this->get_val('prefix_with_home_link') && $pageid == $site->index_pageid)) {
					continue;
				}
				$links[] = (($show_links)?'<a '.$class.' href="'.$this->get_val('page_link', $pageid).'">':'').$page_index[$pageid]['name'].(($show_links)?'</a>':'');
			}
		}

		if (count($links)) {

			$divider = $this->get_val('divider');
			?> 
			<span <?=$class?>>
			<?

			if ($this->get_val('prefix_with_divider')) echo $divider;
			echo implode($divider, $links);
			if ($this->get_val('suffix_with_divider')) echo $divider;

			?> 
			</span>
			<?

		}#end if

	}#end paint()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		return "Change the appearance of the list of the pages above the current page";

	}#end get_description()


}#end class Site_Design_Area_Page_Lineage

?>