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
## Desc: This class represents the parent of the different types that a table cell cen be
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type.inc,v $
## $Revision: 2.23 $
## $Author: achadszinow $
## $Date: 2003/12/01 01:37:09 $
#######################################################################

#---------------------------------------------------------------------#


/**
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type extends Object {

	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type() {
	}#end constructor

	 ###########################################################################3
	# try and grab any data from the existing type that we think we can use
	function convert(&$existing_type) {
	}#end convert()
	 
	 ##############################
	# Delete this bastard
	function delete() {
		unset($this);
		$this = new BodyCopy_Table_Cell_Type();
		return true;
	}#end delete()

	 ###################################
	# paints the table cell contents
	function paint($from_backend = false, &$keyword_replacements,$abs=false) {
		echo 'Development Cell Type Contents';
	}#end paint()


	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		return;
	}

	#####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		return true;
	}

	 #########################################################################
	# returns true if there is nothing to print (ie no content) for the cell
	function empty_cell() {
		return true;
	}#end empty_cell()

	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table cell type
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
	}
	 
	 ############################################################
	# function to initialise some things need by this cell type
	# NOTE : should be able to be called without an instance of the object
	#        ie. NO references to $this
	#        eg.  BodyCopy_Table_Cell_Type_SOMENAME::init_backend();
	function init_backend() {
	}#end init_backend()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name, $tableid, $rowid, $cellid) {
		print_bodycopy_icon("javascript: alert('Cell Type Under Development');", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit');
	}#end print_backend()

	 ###############################################
	# process the input created by print_backend()
	function update() {
	}#end update()


	 #########################################################
	# escape some html so it can be placed in a js string
	function js_string_escape($str='', $quote_str='\'') {
		$str = addslashes($str);
		$str = str_replace("\r", "\\r", $str);
		$str = str_replace("\n", "\\n", $str);
		$str = eregi_replace('(</)(SCRIPT>)', '\\1'.$quote_str.' + '.$quote_str.'\\2', $str);
		$str = eregi_replace('(<!)(--)',      '\\1'.$quote_str.' + '.$quote_str.'\\2', $str);
		$str = eregi_replace('(--)(>)',       '\\1'.$quote_str.' + '.$quote_str.'\\2', $str);
		return $str;
	}#end js_string_escape()

	 #############################################################
	# replaces all './[spf]=[0-9]+' with their proper file path
	# If absolute is set, then absolute URLs are used
	function replace_hrefs($html='') {
		if (trim($html) == '') return '';

		$web = &get_web_system();

		$e = '/\\.\\/\\?f=([0-9]+)/';
		preg_match_all($e, $html, $matches);
		$replace_fileids = $matches[1];
		if (!empty($replace_fileids)) {
			foreach($replace_fileids as $fileid) {
				$file = &$web->get_file($fileid);
				if (!$file->id) continue;
				$e = "(/?\./\\?f=$file->id)([^0-9]+)";
				$html = ereg_replace($e, (($GLOBALS['ABSOLUTE'])?$file->get_url():$file->get_href()).'\\2', $html);
			}#end foreach
		}

		$e = '/\\.\\/\\?p=([0-9]+)/';
		preg_match_all($e, $html, $matches);
		$replace_pageids = $matches[1];
		if (!empty($replace_pageids)) {
			foreach($replace_pageids as $pageid) {
				$page = &$web->get_page($pageid);
				if (!$page->id) continue;
				$e = "(/?\./\\?p=$page->id)([^0-9]+)";
				$html = ereg_replace($e, (($GLOBALS['ABSOLUTE'])?$page->get_url():$page->get_href()).'\\2', $html);
			}#end foreach
		}

		$site = $web->get_site();
		$html = ereg_replace('\\./\\?(s=[0-9]+)', (($GLOBALS['ABSOLUTE'])?$site->get_url(true):$site->get_href(true)).'\\1', $html);

		return $html;

	}#end replace_hrefs()


	 ####################################################################
	# Used during major duplications to realign links to files and pages
	# to the new copies of the files and pages that go along with the page.
	function remap_link_ids_in_html($html = '', &$map) {
		if (trim($html) == '') return '';

		$web = &get_web_system();

		$e = '/\\.\\/\\?f=([0-9]+)/';
		preg_match_all($e, $html, $matches);
		$replace_fileids = $matches[1];
		if (!empty($replace_fileids)) {
			foreach($replace_fileids as $fileid) {
				if(!$newid = $map['file'][$fileid]) continue;
				$e = "(\./\\?f=$fileid)([^0-9]+)";
				$html = ereg_replace($e, './?f='.$newid.'\\2', $html);
			}#end foreach
		}

		$e = '/\\.\\/\\?p=([0-9]+)/';
		preg_match_all($e, $html, $matches);
		$replace_pageids = $matches[1];
		if (!empty($replace_pageids)) {
			foreach($replace_pageids as $pageid) {
				if(!$newid = $map['page'][$pageid]) continue;
				$e = "(\./\\?p=$pageid)([^0-9]+)";
				$html = ereg_replace($e, './?p='.$newid.'\\2', $html);
			}#end foreach
		}

		return $html;

	}#end remap_link_ids_in_html()


	 #########################################################
	# paint's out the html string replacing as needed 
	function paint_raw_html($html='', &$keyword_replacements, $from_backend=false, $abs=false) {

		$html = $this->replace_hrefs(replace_keywords($html, $keyword_replacements),$abs);
		
		# if we ain't from the backend jsut print 
		if (!$from_backend) {
			echo $html;
		
		} else {
			$form_tags = Array('form', 'input' , 'select', 'option', 'text_area');
			foreach($form_tags as $tag) {
				if (stristr($html, '<'.$tag) !== FALSE) {
				?>
					<span class="warning">
						This cell contains form elements, therefore you cannot view the 
						content in the backend, you can only view the content of this cell 
						by previewing on the frontend
					</span>
				<?
					return;
				}#end if
			}#end for
			echo $html;
		}
	}#end paint_raw_html()

	 #################################################
	# return all the keywords that are in the bodycopy
	# in the form "%keyword%"
	function get_keywords(&$keywords) {
		# for the kids to fill in
	}

	 ################################################
	# gets all the anchors from the bodycopy
	function get_anchors(&$anchors) {
		# for the kids to fill in
	}

	 #########################################################
	# get's a list of anchors from the passed html
	function get_raw_html_anchors($html='', &$anchors) {

		$matches = NULL;
		if (preg_match_all('/<a name=["\']([a-z0-9_]+)["\']><\\/a>/i', $html, $matches, PREG_PATTERN_ORDER)) {
			array_append($anchors, $matches[1]);
		}

	}#end get_raw_html_anchors()


}#end Class BodyCopy_Table_Cell_Type
?>