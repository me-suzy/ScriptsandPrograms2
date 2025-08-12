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
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type_iframe.inc,v $
## $Revision: 2.9.4.1 $
## $Author: tbarrett $
## $Date: 2004/05/12 07:49:47 $
#######################################################################
#---------------------------------------------------------------------#

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types']['bodycopy_table_cell_type_iframe']
		= Array('name' => 'iFrame', 
				'description' => 'If you want to include a frame in a page with the contents of another site, this is the cell type to do so. Not supported in Netscape 4 or lower.');

   ####################################################################
  # this indicates to the class when it's printing the edit interface
 # whether another instance of it has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL_TYPE']['iframe']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type_iFrame extends BodyCopy_Table_Cell_Type {
	
	var $url = 'http://mysource.squiz.net';  # the iframe location
	var $width = 400; # the iframe width
	var $height = 200; # the iframe height
	var $scroll = 'Auto'; # Allow  the iframe to scroll ?
	var $html = '';  # raw html code used for converting this file type

	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type_iFrame() {
	}#end constructor

	 #########################################
	# set the html variable
	function set_html($html='') {
		if ($this->html == $html) return '';
		$this->html = $html;
	}

	 #########################################
	# set the url variable
	function set_url($url='') {
		if ($this->url == $url) return '';
		$this->url = $url;
		return 'iFrame url updated';
	}

	 #########################################
	# set the width variable
	function set_width($width=0) {
		if ($this->width == $width) return '';
		$this->width = $width;
		return 'iFrame width updated';
	}

	 #########################################
	# set the height variable
	function set_height($height=0) {
		if ($this->height == $height) return '';
		$this->height = $height;
		return 'iFrame height updated';
	}

	 #########################################
	# set the height variable
	function set_scroll($scroll=0) {
		if ($this->scroll == $scroll) return '';
		$this->scroll = $scroll;
		return 'iFrame scrolling updated';
	}

	 ##############################
	# Delete this bastard
	function delete() {
		unset($this);
		$this = new BodyCopy_Table_Cell_Type_iFrame();
		return true;
	}#end delete()

	 ###################################
	# paints the html
	function paint($from_backend=false, &$keyword_replacements) {
		if ($from_backend) {
			?>
			<span class="warning">This cell is an iframe.  Problematic iframes can prevent you from being able to use the backend properly, so this frame is not being displayed here.  To see the contents of this cell, preview the page.</span>
			<?
		} else {
			$url    = $this->url;
			$width  = $this->width;
			$height = $this->height;
			$scroll = $this->scroll;
			?><iframe src="<?= $url ?>" width="<?= $width ?>" height="<?= $height ?>" scrolling="<?= $scroll ?>"></iframe><?
		}
	}#end paint()

	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		$this->url = $this->remap_link_ids_in_html($this->url,$map);
	}


	#####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		return false;
	}


	 #########################################################################
	# returns true if there is nothing to print (ie no content) for the cell
	function empty_cell() {
		return ($this->url == '');
	}#end empty_cell()

	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		?> 
			retVal["url"]    = var_serialise('<?=$this->js_string_escape($this->url,    '\'')?>');
			retVal["width"]  = var_serialise('<?=$this->js_string_escape($this->width,  '\'')?>');
			retVal["height"] = var_serialise('<?=$this->js_string_escape($this->height, '\'')?>');
			retVal["scroll"] = var_serialise('<?=$this->js_string_escape($this->scroll, '\'')?>');
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
		if (!$_BODYCOPY['TABLE_CELL_TYPE']['iframe']['edit_initialised']) {
			?><script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_table_cell_type_iframe.js"></script><?
			$_BODYCOPY['TABLE_CELL_TYPE']['iframe']['edit_initialised'] = true;

		}#endif

	}#end init_backend()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		
		# initilaise the backend for this cell type
		$this->init_backend();

		# print out the edit button
		print_bodycopy_icon("javascript: bodycopy_edit_table_cell_type_iframe('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit_content');

	}#end print_backend()

	 ###############################################
	# process the input created by print_backend()
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved; 
		global $_BODYCOPY;

		$msg = Array();

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['url']) != '') {
			if ($tmp_msg = $this->set_url($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['url'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}
		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['width']) != '') {
			if ($tmp_msg = $this->set_width($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['width'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}
		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['height']) != '') {
			if ($tmp_msg = $this->set_height($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['height'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}
		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['scroll']) != '') {
			if ($tmp_msg = $this->set_scroll($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['scroll'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		# save the html so this cell can be converted
		# to another cell type later
		ob_start();
		$this->paint();
		$this->html = ob_get_contents();
		ob_end_clean();

		return $msg;

	}#end update()

}#end Class BodyCopy_Table_Cell_Type_iFrame

?>