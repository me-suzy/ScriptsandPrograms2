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
## Desc: This allows the editing of the Files in a table cell
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type_nest_content.inc,v $
## $Revision: 2.17 $
## $Author: achadszinow $
## $Date: 2003/12/01 01:37:09 $
#######################################################################
#---------------------------------------------------------------------#

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types']['bodycopy_table_cell_type_nest_content']
		= Array('name' => 'Nest Content', 
				'description' => 'This allows the nesting of another page contents into a cell');

   ####################################################################
  # this indicates to the class when it's printing the edit interface
 # whether another instance of it has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL_TYPE']['nest_content']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type_Nest_Content extends BodyCopy_Table_Cell_Type {

	var $siteid = 0;          # the site id (stored rather than work it out each time)
	var $pageid = 0;          # the page we want to nest
	var $variables = '';      # variables to submit to the nest content page
	var $submit_type = '';    # how to submit the variables
	var $restrict_links = ''; # keep internal links inside this page?
	var $html = '';           # raw html code used for converting this file type

	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type_Nest_Content() {
	}#end constructor

	 #########################################
	# set the pageid variable
	function set_siteid($siteid=0) {
		if ($this->siteid == $siteid) return '';
		# make sure we dont' reference ourselves
		$web  = &get_web_system();
		$page = &$web->get_page();
		if ($site->id == $siteid) return '';
		$this->siteid = $siteid;
		return 'Site updated';
	}

	 #########################################
	# set the pageid variable
	function set_pageid($pageid=0) {
		if ($this->pageid == $pageid) return '';
		# make sure we dont' reference ourselves
		$web  = &get_web_system();
		$page = &$web->get_page();
		if ($page->id == $pageid) return '';
		$this->pageid = $pageid;
		return 'Page updated';
	}

	 #########################################
	# set the variables variable
	function set_variables($variables='') {
		if ($this->variables == $variables) return '';
		$this->variables = $variables;
		return 'Page Variables updated';
	}

	 #########################################
	# set the submit type variable
	function set_submit_type($submit_type='') {
		if ($this->submit_type == $submit_type) return '';
		$this->submit_type = $submit_type;
		return 'Page Submit Type updated';
	}

	 #########################################
	# set the restrict links variable
	function set_restrict_links($restrict_links='') {
		if ($this->restrict_links == $restrict_links) return '';
		$this->restrict_links = $restrict_links;
		return 'Restrict Links updated';
	}

	 ##############################
	# Delete this bastard
	function delete() {
		unset($this);
		$this = new BodyCopy_Table_Cell_Type_Nest_Content();
		return true;
	}#end delete()

	 ###################################
	# paints the html
	function paint($from_backend=false, &$keyword_replacements) {
		# if there's no page the exit
		if (!$this->pageid) return;

		$web = &get_web_system();
		$page = &$web->get_page($this->pageid);
		if ($page->id) {
			if ($page->read_access()){

				if (!$this->restrict_links) {
					$old_current_pageid = $web->current_pageid;
					$web->current_pageid = $page->id;
				}

				# set any variables that we jave been asked to set
				if ($this->variables) {
					$old_vars = array();
					foreach(split("[\r\n]+",$this->variables) as $val) {
						list($variable,$value) = explode(':',$val);
						if (trim($variable) == '') continue;
						switch ($this->submit_type) {
							case 'get-post' :
								$old_vars[$variable] = $_REQUEST[$variable];
								$_REQUEST[$variable] = $value;
								break;
							case 'get' :
								$old_vars[$variable] = $_GET[$variable];
								$_GET[$variable] = $value;
								break;
							case 'post' :
								$old_vars[$variable] = $_POST[$variable];
								$_POST[$variable] = $value;
								break;
							case 'session' :
								$session = &get_mysource_session();
								$old_vars[$variable] = $session->get_var($variable);
								$temp = $value;
								$session->set_var($variable,$temp);
								unset($temp);
								break;
							default :
								global $$variable;
								$old_vars[$variable] = $$variable;
								$$variable = $value;
								break;
						}
					}
				}

				$template = &$page->get_template();
				# if we aren't in the backend just print
				if (!$from_backend) {
					$temp = false;
					$template->print_body($temp,$keyword_replacements);
					unset($temp);
				# OK, we are in the backend, let's grab the html from the template 
				# and send it to paint_raw_html()
				} else {
					ob_start();
					$template->print_body();
					$html = ob_get_contents();
					ob_end_clean();
					$tmp = Array();
					$this->paint_raw_html($html, $tmp, true);
				}

				if (!$this->restrict_links) $web->current_pageid = $old_current_pageid;

				# return any variables we set to their old values
				if ($this->variables) {
					foreach($old_vars as $variable => $value) {
						switch ($this->submit_type) {
							case 'get-post' :
								$_REQUEST[$variable] = $value;
								break;
							case 'get' :
								$_GET[$variable] = $value;
								break;
							case 'post' :
								$_POST[$variable] = $value;
								break;
							case 'session' :
								$session = &get_mysource_session();
								$temp = $value;
								$session->set_var($variable,$temp);
								unset($temp);
								break;
							default :
								global $$variable;
								$$variable = $value;
								break;
						}
					}
				}
			}
		}#end if

	}#end paint()

	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		if(isset($map['page'][$this->pageid])) {
			$this->pageid = $map['page'][$this->pageid];
			# Make sure the siteid is ok too
			$web = &get_web_system();
			$page = &$web->get_page($this->pageid);
			$this->siteid = $page->siteid;
		}
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
		if ($this->pageid) {
			$web = &get_web_system();
			$page = &$web->get_page($this->pageid);
			return ($page->id <= 0);
		} else {
			return true;
		}
		
	}#end empty_cell()


	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		?> 
		retVal["siteid"] = var_serialise('<?=$this->js_string_escape($this->siteid, '\'')?>');
		retVal["pageid"] = var_serialise('<?=$this->js_string_escape($this->pageid, '\'')?>');
		retVal["variables"] = var_serialise('<?=$this->js_string_escape($this->variables, '\'')?>');
		retVal["submit_type"] = var_serialise('<?=$this->js_string_escape($this->submit_type, '\'')?>');
		retVal["restrict_links"] = var_serialise('<?=$this->js_string_escape($this->restrict_links, '\'')?>');
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
		if (!$_BODYCOPY['TABLE_CELL_TYPE']['page']['edit_initialised']) {
			?><script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_table_cell_type_nest_content.js"></script><?
			$_BODYCOPY['TABLE_CELL_TYPE']['page']['edit_initialised'] = true;

		}#endif

	}#end init_backend()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		
		# initilaise the backend for this cell type
		$this->init_backend();

		# print out the edit button
		print_bodycopy_icon("javascript: bodycopy_edit_table_cell_type_nest_content('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit_content');

	}#end print_backend()

	 #########################################################
	# process the input created by print_backend()'s popup
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved;
		global $_BODYCOPY;

		$msg = Array();
		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['siteid']) != '') {
			if ($tmp_msg = $this->set_siteid($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['siteid'])) {
				$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['pageid']) != '') {
			if ($tmp_msg = $this->set_pageid($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['pageid'])) {
				$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['variables']) != '') {
			if ($tmp_msg = $this->set_variables($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['variables'])) {
				$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['submit_type']) != '') {
			if ($tmp_msg = $this->set_submit_type($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['submit_type'])) {
				$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['restrict_links']) != '') {
			if ($tmp_msg = $this->set_restrict_links($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['restrict_links'])) {
				$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
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
}#end Class 
?>