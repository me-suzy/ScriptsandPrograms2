<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/design_areas/searchresults/result.inc
## Desc: this is a single result object
## $Source: /home/cvsroot/xtras/site/design_areas/searchresults/fileresult.inc,v $
## $Revision: 2.13 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:03 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_SearchResults_FileResult extends Site_Design_Area {

	function Site_Design_Area_SearchResults_FileResult(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;
		$this->_set_var("divider",	" : ",	"text",		"the text that divides the pages in the lineage");
		$this->_set_var("show_links", true,	"boolean",	"whether or not to you want the page lineage to be hyperlinked");
	}#end Site_Design_Area_Banner_Individual()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "searchresults");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case "TAG"  :        # must be a print tag, we'll deal with it later, 
				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML" :        # nothing to do to this yet, so just add to contents array
						$this->contents[] = $element;
				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	function paint($fileid,&$search_results) { 

		foreach($this->contents as $element)
		{

			switch($element[_type])
			{
				# plain html element just echo its contents
				case "HTML" :
					echo $element[contents];

				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :

					switch($element[operation])
					{
						case "print" : 
							$this->print_val($element,$fileid,$search_results);
						break;
					}

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="") {
	}#end print_user_backend()


	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $fileid, &$search_results) {
        global $WEB_PATH;
		$web = &get_web_system();
		$db  = &$web->get_db();
		$file = &$web->get_file($fileid);
		$page = &$web->get_page($file->pageid);
		$site = &$web->get_site($page->siteid);

		switch(strtolower($name)) {

            case "lineage" :
				# The history of this page.. to add context
				$divider = Site_Design_Area::get_val('divider');
				$show_links = Site_Design_Area::get_val('show_links');
				if ($search_results[search_domain] == "A"); { # Show site if many sites in search domain
					$str = '';
					if ($show_links) $str .=  "<a href=\"".$site->get_href()."\">";
					$str .=  "<b>$site->name</b>";
					if ($show_links) $str .=  "</a>";
					$str .= " $divider ";
				}

				$page_lineage = &$page->get_lineage();
				
				foreach($page_lineage as $temp_pageid => $temp_page_name) {
					if ($page->id == $temp_pageid) break;
					$temp_pageid = (int) $temp_pageid;
					if ($show_links) $str .=  "<a href=\"".$web->get_page_href($site->id,$temp_pageid)."\">"; 
					$str .= $temp_page_name;
					if ($show_links) $str .= "</a>";
					$str .= " $divider ";
				}  
				if ($show_links) $str .=  "<a href=\"".$page->get_href()."\">"; 
				$str .= $page->short_name;
				if ($show_links) $str .= "</a>";
				$str .= " $divider ";
				return $str;
			break;

			case "index" :
				$str = ($search_results[current_result] + 1);
				return $str;
			break;

			case "thumb" : 
				return $page->image_tag("thumbs/file_thumb_$fileid",$file->filename,0,0,"border=0",$page->id,$page->siteid);
			break;

			case "icon" :
				$file_types_config = &get_config("file_types");
				if ($file->type && file_exists("$WEB_PATH/__lib/images/file_types/$file->type.gif")) {
					$str = "<img src=\"".lib_href("images/file_types/$file->type.gif")."\" alt=\"{$file_types_config->file_types[$file->type]}\" width=32 height=32>";
				} else {
					$str = "<img src=\"".lib_href("images/file_types/file.gif")."\" alt=\"File\" width=32 height=32>";
				}
				return $str;
			break;

			case "icon_file" :
				if ($file->type && file_exists("$WEB_PATH/__lib/images/file_types/$file->type.gif")) {
					$str = lib_href("images/file_types/$file->type.gif");
				} else {
					$str = lib_href("images/file_types/file.gif");
				}
				return $str;
			break;

			case "type" :
				return $file->type;
			break;

			case "type_description" :
				$file_types_config = &get_config("file_types");
				return $file_types_config->file_types[$file->type];
			break;

			case "link" : 
				if ($search_results['link_files_to_page']) {
					return $page->get_href();
				} else {
					return $file->get_href();
				}
			break;

			case "name" :
				return $file->filename;
			break;

			case "size" :
				return easy_filesize($file->size);
			break;
			
			case "updated" :
				return readable_datetime($file->modified);
			break;

			case "updated_easy" :
				return easy_datetime($file->modified);
			break;

			case "description" :
				return $file->description;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, &$fileid, &$search_results) {
		echo $this->_print_modified($tag, $this->get_val($tag[attributes][name], $fileid, $search_results));
	}
}#end class Site_Design_Area_Results_FileResult
?>