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
## Desc: Allows slightly more user friendlyness the RawHTML by using the old 
##       BodyCopy style for the text
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type_richtext.inc,v $
## $Revision: 2.21 $
## $Author: bvial $
## $Date: 2003/09/07 23:16:24 $
#######################################################################
#---------------------------------------------------------------------#

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types']['bodycopy_table_cell_type_richtext']
		= Array('name' => 'RichText', 
				'description' => 'Simpler than raw HTML, rich editing provides shortcut syntax for common document features such as indenting, bullets, headings and automatic hyperlinking of URLS.');

 ####################################################################
# The class_name of the default type for cells
$_BODYCOPY['TABLE_CELL']['default_type'] = 'bodycopy_table_cell_type_richtext';
   
   ####################################################################
  # this indicates to the class when it's printing the edit interface
 # whether another instance of it has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL_TYPE']['richtext']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type_RichText extends BodyCopy_Table_Cell_Type {
	
	var $html = '';  # the raw html code

	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type_RichText() {
	}#end constructor

	 #########################################
	# set the html variable
	function set_html($html='') {
		if ($this->html == $html) return '';
		$this->html = $html;
		return 'Rich Text contents updated';
	}

	 ###########################################################################
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
		$this = new BodyCopy_Table_Cell_Type_RichText();
		return true;
	}#end delete()

	 ###################################
	# paints the html
	function paint($from_backend=false, &$keyword_replacements) {
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
		retVal["text"] = var_serialise('<?=$this->js_string_escape($this->html_to_formatted_text($this->html), "'")?>');
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
		if (!$_BODYCOPY['TABLE_CELL_TYPE']['richtext']['edit_initialised']) {
			?><script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_table_cell_type_richtext.js"></script><?
			$_BODYCOPY['TABLE_CELL_TYPE']['richtext']['edit_initialised'] = true;

		}#endif

	}#end init_backend()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {

		# initilaise the backend for this cell type
		$this->init_backend();
		
		# print out the edit button
		print_bodycopy_icon("javascript: bodycopy_edit_table_cell_type_richtext('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit_content');

	}#end print_backend()

	 ###############################################
	# process the input created by print_backend()
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved; 
		global $_BODYCOPY;

		$msg = Array();

		if (isset($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['text'])) {
			if ($tmp_msg = $this->set_html($this->format_text_to_html($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['text']))) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		return $msg;

	}#end update()

	  #############################################################################################
	 # Using a special delimiting format, converts a body of text into formatted html for display#
	#############################################################################################

	function format_text_to_html ($text='') {
		$lines = explode("\n",$text);
		if (!empty($text)) {
			foreach($lines as $line) {
				
				# Get the level of indentation
				for ($indent = 0; $line[$indent] == ' '; $indent++);

				# Ordered list or not
				$ordered = (substr(trim($line),0,1) == '#');
				$bulleted = (substr(trim($line),0,1) == '.');
				$listed = ($bulleted || $ordered);

				# Adjust level of indenting if changed - and bullet types etc
				if (($moved = $indent - $previous_indent) != 0) { # Moved
					for($i = 0; $i > $moved; $i--) $new_text .= (($was_ordered) ? "</ol>\n" : "</ul>\n");
					for($i = 0; $i < $moved; $i++) $new_text .= (($ordered) ? "<ol>\n" : "<ul>\n");
				} else {
					if ($indent && $ordered && !$was_ordered) $new_text .= "</ul>\n<ol>\n";
					if ($indent && !$ordered && $was_ordered) $new_text .= "</ol>\n<ul>\n";
				}

				# Rememeber last line details
				$previous_indent  = $indent;
				$was_ordered      = $ordered;
				$was_bulleted     = $bulleted;
				$was_listed       = $listed;

				$line = trim($line); # Now trim.
			
				if (0 == 1) {
				} elseif (!$line && $previous_line) { # Sudden line break
					$new_text .= "</p>\n";

				} elseif (!$line && !$previous_line) { # Add a line break if extra blank line
					$new_text .= "<br>\n";

				} elseif (ereg("^\^([1-6])\^(.*)", $line, $regs)) { # Detect headings
					$new_text .= "<h".$regs[1].">" . $regs[2] . "</h".$regs[1].">\n";

				} elseif ($line[0] =='.') { # Detect bullets
					$new_text .='<li>' . substr($line,1) . "</li>\n";

				} elseif ($line[0] =='#') { # Detect bullets
					$new_text .='<li>' . substr($line,1) . "</li>\n";

				} elseif ($line && $previous_line) { # Line break in paragraph
					$new_text .='<br>' . $line . "\n";

				} elseif ($line && !$previous_line) { # Normal new paragraph
					$new_text .='<p>' . $line . "\n";
				}

				$previous_line = $line;
			}
		}
		for($i = 0; $i < $indent; $i++) $new_text .= "</ul>\n";
		$new_text = ereg_replace('\(c\)',  '&copy;', $new_text);
		$new_text = ereg_replace('\(r\)',  '&reg;',  $new_text);
		$new_text = ereg_replace('\(tm\)', '',      $new_text);

		return $new_text;
	}


	  #############################################################################################
	 # Using a special delimiting format, tries to convert a html into a body of formatted text  #
	#############################################################################################
	function html_to_formatted_text($html='') {

		if ($html =='') return'';

		$new_text ='';

		$html = trim($html);
		$html = str_replace("\n", '', $html);
		$html = str_replace("\r", '', $html);

		# array of the tag that we recognise
		$open_tags  = Array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'ul', 'ol', 'li');
		$close_tags = Array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'ul', 'ol', 'li');
		# put each of the open an close tags we recognise on a new line
		# (and convert them to the same case as in the array in the process)
		foreach($open_tags as $tag) {
			# because the only tags that we would have put in don't have 
			# any attributes in the tag only treat them
			$e = '<'.$tag.'>';
			$r = "\n".$e;
			$html = eregi_replace($e, $r, $html);

		}#end foreach

		foreach($close_tags as $tag) {
			$e = '</'.$tag.'>';
			$r = "\n".$e;
			$html = eregi_replace($e, $r, $html);

		}#end foreach

		# remove any extra whitepace we may have acquired
		$html = trim($html);

		$lines = explode("\n", $html);
		$indent_level = Array();
		if (!empty($lines)) {
			foreach($lines as $line) {

				$line = trim($line);

				# unordered list
				if     ($line == '<ul>') {
					$indent_level[] = '.';

				# Detect unordered list endings
				} elseif ($line == '</ul>') {
					array_pop($indent_level);

				# ordered list
				} elseif ($line == '<ol>') {
					$indent_level[] = '#';

				# Detect ordered list endings
				} elseif ($line == '</ol>') {
					array_pop($indent_level);

				# Detect bullets
				} elseif (substr($line, 0, 4) == '<li>') {

					$new_line = '';
					# if we have indentation the prefix it 
					if (count($indent_level)) {
						$new_line .= str_pad('', count($indent_level), ' ');
						$new_line .= $indent_level[count($indent_level) - 1];
					# no indenting so default to un-orderlist
					} else {
						$new_line .= '.';
					}#end if

					$new_line .= substr($line,4);
					$new_text .= $new_line."\n";

				# Detect headings
				} elseif (ereg('^<h([1-6])>', $line, $regs)) {
					$new_line = '^'.$regs[1].'^';
					$new_line .= substr($line,4);
					$new_text .= $new_line."\n";

				# Detect breaks
				} elseif (substr($line, 0, 4) == '<br>') {
					$new_line = '';
					# if we have indentation the prefix it 
					if (count($indent_level)) {
						$new_line .= str_pad('', count($indent_level), ' ');
					}
					$new_line .= substr($line,4);
					$new_text .= $new_line."\n";

				# Detect paragraphs
				} elseif (substr($line, 0, 3) == '<p>') {
					$new_line = '';
					# if we have indentation the prefix it 
					if (count($indent_level)) {
						$new_line .= str_pad('', count($indent_level), ' ');
					}
					$new_line .= substr($line,3);
					$new_text .= $new_line."\n";

				# Detect paragraph endings
				} elseif (substr($line, 0, 4) == '</p>') {
					$new_text .= "\n";
					# if for whatever reason there is text after the closing p tag 
					# give it it's own paragraph
					if (strlen($line) > 4) {
						$new_text .= substr($line, 4)."\n\n";
					}

				# else don't know what to do with this line, 
				# check we didn't remove anything from it then, just append
				} else {

					# Make sure this is not a close that we should remove
					$is_close_tag = false;
					foreach($close_tags as $tag) {
						$s = '</'.$tag.'>';
						if ($line == $s) {
							$is_close_tag = true;
							break;
						}#end if
					}#end foreach

					if (!$is_close_tag) {
						# if this starts with a closing tag that we have gotten rid of,
						# we need to append a close tag to the end
						foreach($close_tags as $tag) {
							$s = '<'.$tag;
							if (substr($line, 0, strlen($s)) == $s) {
								$line .= '</'.$tag.'>';
								break;
							}#dnd if
						}#end foreach
						$new_text .= $line."\n";
					}#end if not close tag

				}#end if

			}#end foreach
		}

		$new_text = str_replace('&copy;',  '(c)', $new_text);
		$new_text = str_replace('&reg;',   '(r)', $new_text);
		$new_text = str_replace('&trade;', '(tm)',$new_text);

		# remove the last new line
		$new_text = substr($new_text, 0, -1);

		return $new_text;

	}#end html_to_formatted_text()


}#end Class BodyCopy_Table_Cell_Type_RichText

?>