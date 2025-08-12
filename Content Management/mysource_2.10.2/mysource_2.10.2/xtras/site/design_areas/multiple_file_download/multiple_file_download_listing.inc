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
## $Source: /home/cvsroot/xtras/site/design_areas/multiple_file_download/multiple_file_download_listing.inc,v $
## $Revision: 1.2.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/14 05:34:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Multiple_File_Download_Listing extends Site_Design_Area {

	function Site_Design_Area_Multiple_File_Download_Listing(&$_owner) {

		$this->Site_Design_Base($_owner);

	}#end Site_Design_Area_Multiple_File_Download_Listing

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "multiple_file_download");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element){
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

		foreach($this->contents as $element){
			switch($element[_type]) {
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
					switch($element[operation])	{
						case "print" : 
							$this->print_val($element,$fileid);
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
	function get_val($name, $fileid) {
        global $WEB_PATH;
		$web = &get_web_system();
		$file = &$web->get_file($fileid);

		switch(strtolower($name)) {


			case 'checkbox':
				$str = "<input type=\"checkbox\" name=\"file_list[]\" value=\"$fileid\">";
				return $str;
			
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

			case "link" : 
				return $file->get_href();
			break;

			case "thumb" : 
				$page = &$web->get_page($file->pageid);
				return $page->image_tag("thumbs/file_thumb_$fileid",$file->filename,0,0,"border=0");
			break;

			case "type" :
				return $file->type;
			break;

			case "type_description" :
				$file_types_config = &get_config("file_types");
				return $file_types_config->file_types[$file->type];
			break;

			case "name" :
				return $file->filename;
			break;

			case "name_no_extension" :
				# resturn the name of the file without
				# the extension
				$last_dot = strrpos($file->filename,'.');
				if (!$last_dot) return $file->filename;
				return substr($file->filename, 0, strrpos($file->filename,'.'));
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
	function print_val(&$tag, &$fileid) {
		echo $this->_print_modified($tag, $this->get_val($tag[attributes][name], $fileid));
	}
}#end class Site_Design_Area_Results_FileResult
?>