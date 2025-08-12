<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
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
## Desc: This allows the editing of raw html into a table cell
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type_wysiwyg.inc,v $
## $Revision: 2.21 $
## $Author: csmith $
## $Date: 2003/05/16 05:19:22 $
#######################################################################
#---------------------------------------------------------------------#

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types']['bodycopy_table_cell_type_wysiwyg']
		= Array('name' => 'WYSIWYG', 
				'description' => "This 'What You See is What You Get' interface allows anyone to enter their content in similiar way to a word processor.\nNOTE: Dependant on Internet Explorer 5.5 and above");

   ####################################################################
  # this indicates to the class when it's printing the edit interface
 # whether another instance of it has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL_TYPE']['wysiwyg']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type_Wysiwyg extends BodyCopy_Table_Cell_Type {
	
	var $html = '';  # the html code

	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type_Wysiwyg() {
	}#end constructor

	 #########################################
	# set the html variable
	function set_html($html='') {
		if ($this->html == $html) return '';
		$this->html = $html;
		return 'WYSIWYG contents updated';
	}

	 ###########################################################################3
	# try and grab any data from the existing type that we think we can use
	function convert(&$existing_type) {
		# if they have a html variable grab it for ourselves
		if (isset($existing_type->html)) {
			$this->html = $existing_type->html;
		}
	}#end convert()

	 ##############################
	# Delete this bastard
	function delete() {
		unset($this);
		$this = new BodyCopy_Table_Cell_Type_Wysiwyg();
		return true;
	}#end delete()

	 ###################################
	# paints the html
	function paint($from_backend = false, &$keyword_replacements) {
		$this->paint_raw_html($this->html, $keyword_replacements, $from_backend);
	}#end paint()


	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		$this->html = $this->remap_link_ids_in_html($this->html,$map);
	}


	#####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		$e = '/\\.\\/\\?f='.$fileid.'/';
		preg_match_all($e, $this->html, $matches);
		$used = $matches[0];
		if ($used) return true;
		return false;
	}

	 #################################################
	# return all the keywords that are in the bodycopy
	# in the form '%keyword%'
	function get_keywords(&$keywords) {
		array_append($keywords, retrieve_keywords_replacements($this->html));
	}

	 ################################################
	# gets all the anchors from the bodycopy
	function get_anchors(&$anchors) {
		$this->get_raw_html_anchors($this->html, $anchors);
	}

	 #########################################################################
	# returns true if there is nothing to print (ie no content) for the cell
	function empty_cell() {
		return ($this->html == '');
	}#end empty_cell()

	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		?> 
		retVal["html"] = var_serialise('<?=$this->js_string_escape($this->html, "'")?>');
		<?
	}#end print_backend_js_data()

	 ############################################################
	# function to initialise some things need by this cell type
	# NOTE : should be able to be called without an instance of the object
	#        ie. NO references to $this
	#        eg.  BodyCopy_Table_Cell_Type_SOMENAME::init_backend();
	function init_backend() {
		global $_BODYCOPY;
		
		# do some thing for the first time we print this object on this page
		if (!$_BODYCOPY['TABLE_CELL_TYPE']['wysiwyg']['edit_initialised']) {
			?><script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_table_cell_type_wysiwyg.js"></script><?
			# Also init the RAW HTML just incase the use can't use the WYSIWYG editor
			BodyCopy_Table_Cell_Type_RawHTML::init_backend();
			$_BODYCOPY['TABLE_CELL_TYPE']['wysiwyg']['edit_initialised'] = true;

		}#endif

	}#end init_backend()

	 
	 #########################################################
	# prints the backend interface for this table cell type
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {

		# initilaise the backend for this cell type
		$this->init_backend();

		# print out the edit button
		print_bodycopy_icon("javascript: bodycopy_edit_table_cell_type_wysiwyg('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit_content');

	}#end print_backend()

	 ###############################################
	# process the input created by print_backend()
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved; 
		global $_BODYCOPY;

		$msg = Array();

		if (isset($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['html'])) {
			if ($tmp_msg = $this->set_html($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['html'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		return $msg;

	}#end update()

}#end Class BodyCopy_Table_Cell_Type_Wysiwyg

?>