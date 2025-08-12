<?  ##############################################
   ### MySource ------------------------------###
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
## Desc: A class that will look after individual cells within tables of the body copy
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell.inc,v $
## $Revision: 2.29.2.2 $
## $Author: tbarrett $
## $Date: 2004/05/18 00:43:04 $
#######################################################################

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types'] = Array();
 ####################################################################
# The class_name of the default type for cells
$_BODYCOPY['TABLE_CELL']['default_type'] = '';

#---------------------------------------------------------------------#
include_once(dirname(__FILE__).'/bodycopy_table_cell_type.inc' );
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_rawhtml.inc' );
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_richtext.inc');
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_wysiwyg.inc' );
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_file.inc' );
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_iframe.inc' );
include_once(dirname(__FILE__).'/bodycopy_table_cell_type_nest_content.inc' );
#---------------------------------------------------------------------#

   ####################################################################
  # this indicates to the cell when it's printing the edit interface
 # whether another cell has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell extends Object {

	var $attributes = Array();  # an array of attributes for the TD tag
	var $type;                  # a BodyCopy_Table_Cell_Type object, that this cell refers to 

	 ##############################
	# Constructor, num_cols for 
	function BodyCopy_Table_Cell($cell_type='') {
		global $_BODYCOPY;
		$this->set_type($cell_type);

	}#end constructor

	 #########################################
	# set an attribute for the table tag
	function set_attribute($name='', $value='') {
		$name = strtolower($name);
		if ($name == 'showif_conds' && ($this->attributes['showif'] == '' || $this->attributes['showif'] == 'Do Not Show')) $value='';
		if ($this->attributes[$name] == $value) return '';
		$this->attributes[$name] = $value;
		return $name.' changed to \''.$value.'\'';
	}

	 #########################################
	# sets the type of this cell
	function set_type($name='') {
		global $_BODYCOPY;

		# if there is something wrong with the new class name 
		if (!class_exists($name) || !in_array($name, array_keys($_BODYCOPY['TABLE_CELL']['types']))) {

			# if we already have a type don't change it
			if ($this->type) {
				$this->_set_error('Class \''.$name.'\' unknown, failed changing the cell type', __FILE__, __LINE__);
				return false;

			# else we don't have a type so better set one, 
			} else {
				# attempt to use the default
				$name = $_BODYCOPY['TABLE_CELL']['default_type'];
				if (!class_exists($name) || !in_array($name, array_keys($_BODYCOPY['TABLE_CELL']['types']))) {
					# otherwise use the first in the array
					$name = '';
					foreach ($_BODYCOPY['TABLE_CELL']['types'] as $class_name => $data) {
						if (class_exists($class_name)) {
							$name = $class_name;
							break;
						}
					}

					# what the hell is going on ???, die screaming
					if (!$name) {
						$this->_set_error('Unable to define a cell type, no valid types available', __FILE__, __LINE__);
						unset($this);
						return false;
					}#end if
				}#end if
			}#end if

		}#end if

		# if we got this far the name must be OK
	
		$new_type = new $name();

		if (!$new_type) {
			$this->_set_error('Unable to create an instance of \''.$name.'\' for the cell type', __FILE__, __LINE__);
			return false;
		}#end if

		$new_type_name = $_BODYCOPY['TABLE_CELL']['types'][get_class($new_type)]['name'];
		# if there is an old type, see if we can convert any of its data, then delete it
		if ($this->type) {
			$old_type_name = $_BODYCOPY['TABLE_CELL']['types'][get_class($this->type)]['name'];
			$new_type->convert($this->type);
			$this->type->delete();
		}
		unset($this->type);
		$this->type = $new_type;

		return 'Cell Type changed '.(($old_type_name) ? 'from \''.$old_type_name.'\' ' : '').'to \''.$new_type_name.'\'';

	}#end set_type()

	 #################
	# Delete this cell
	function delete() {

		unset($this->attributes);

		if ($this->type) $this->type->delete();
		unset($this->type);

		$this = new BodyCopy_Table_Cell();
		return true;

	}#end delete()

	 ########################
	# paints the table cell 
	function paint($from_backend=false, &$keyword_replacements) {
		
		if (!$this->attributes['rawhtml']) {
			echo '<td';
			if (!empty($this->attributes)) {
				foreach($this->attributes as $name => $val) {
					if ($val == '') continue;
					if ($name == 'showif') continue;
					if ($name == 'type') continue;
					if (($name == 'bgcolor') && $val) {
						$val = "#" . $val;
					}
					if (strtolower($name) == 'nowrap') {
						if ($val) echo ' nowrap';
					} else {
						echo ' '.$name.'="'.str_replace('"', '&quot;', $val).'"';
					}
				}#end foreach
			}
		}

		# if we are printing the backend and this cell has no content 
		# put diagonal lined background
		if ($from_backend && $this->empty_cell() && !$this->attributes['rawhtml']) {
			global $_BODYCOPY;
			echo ' background="'.$_BODYCOPY['file_prefix'].'images/no_content.gif">';
			?><img src="images/blank.gif" width="1" height="1" border="0"><?

		} else {
			if (!$this->attributes['rawhtml']) echo '>';
			if ($this->type) {
				$condition = trim($this->attributes['showif']);
				if ($condition == 'Do Not Show' && !$this->attributes['rawhtml']) echo '&nbsp;';
				if (!$from_backend && $condition) {
					# check if we have a conditional statement and, if so, only
					# print if this condition evaluates to TRUE or if the condition is
					# has the AllowFalse property - only print if FALSE
					$allowfalse = false;
					if (substr($condition,-9) == '_is_false') {
						$condition = substr($condition,0,-9);
						$allowfalse = true;
					}
					global $XTRAS_PATH;
					include_once($XTRAS_PATH.'/conditions/'.$condition.'/'.$condition.'.inc');
					# if the class doesn't exist then die
					if (class_exists($condition)) {
						$cond = new $condition($this);
						$result = $cond->evaluate($this->attributes['showif_conds']);
						if (((!$result && !$allowfalse) || ($result && $allowfalse)) && !$this->attributes['rawhtml']) {
							# conditional test failed
							echo '&nbsp;';
						} else {
							$this->type->paint($from_backend, $keyword_replacements);
						}
					}
				} else {
					$this->type->paint($from_backend, $keyword_replacements);
				}
			}#end if
		}
		if (!$this->attributes['rawhtml']) echo '</td>';

	}#end paint()


	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		if ($this->type) {
			$this->type->remap_link_ids($map);
		}#end if
	}


	 #####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		if ($this->type) {
			return $this->type->uses_file($fileid);
		}#end if
		return false;
	}


	 ###########################################
	# how many columns does this cell span ??
	function colspan() {
		return ((int) $this->attributes['colspan'] > 0) ? (int) $this->attributes['colspan'] : 1;
	}

	 ###########################################
	# and how many rows does this cell span ??
	function rowspan() {
		return ((int) $this->attributes['rowspan'] > 0) ? (int) $this->attributes['rowspan'] : 1;
	}

	 #########################################################################
	# returns true if there is nothing to print (ie no content) for the cell
	function empty_cell() {
		if ($this->type) return $this->type->empty_cell();
		else             return true;
	}#end empty_cell()


	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		$this->attributes['type'] = get_class($this->type)
		?> 
									case <?=$cellid?> :
										retVal["attributes"] = '<?=var_serialise($this->attributes, true)?>';
										retVal["type"] = new Object();
										<?
										if ($this->type) {
											$this->type->print_backend_js_data($this_bodycopy_name, $tableid, $rowid, $cellid);
										}#end if

										?>
									break;
		<?
		unset($this->attributes['type']);
	}#end print_backend_js_data()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		global $_BODYCOPY;

		$attribute_list = '';
		$colspaning = 0;
		if (!empty($this->attributes)) {
			foreach($this->attributes as $name => $val) {
				if ($val == '') continue;
				if (strtolower($name) == 'nowrap') {
					if ($val) $attribute_list .= ' nowrap';
				} else {
					if (strtolower($name) == 'colspan') {
						$colspaning = $val;
					}
					$attribute_list .= ' '.$name.'="'.str_replace(array('"', "\r", "\n"), array('&quot;', '\r', '\n'), $val).'"'; 
				}
			}#end foreach
		}
		$attribute_list .= '<br />Current Type : '.$_BODYCOPY['TABLE_CELL']['types'][get_class($this->type)]['name'];

		# print out the edit buttons
		?> 
		<td class="bodycopy-edit" <?=($colspaning > 0) ? 'colspan="'.$colspaning.'"' : '';?> id="<?=$this_bodycopy_name.'_cell_'.$tableid.'_'.$rowid.'_'.$cellid?>" align="right">
		<?
			print_bodycopy_icon("javascript: bodycopy_edit_table_cell_properties('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit Cell '.($cellid + 1).'\'s Properties', (($attribute_list) ? 'Current Properties :<br>'.$attribute_list : ''), 'edit_cell_properties');
			if ($this->type) {
				$this->type->print_backend($this_bodycopy_name, $tableid, $rowid, $cellid);
			}#end if
		?> 
		</td> 
		<?

	}#end print_backend()

	 ###############################################
	# process the input created by print_backend()
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved;
		global $_BODYCOPY;
		$msg = Array();

		# update the type
		if ($this->type) {
			if ($tmp_msg = $this->type->update($this_bodycopy_name,$tableid,$rowid,$cellid)) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}#end if

		if (!empty($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['attributes'])) {

			foreach($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['attributes'] as $name => $value) {
				if ($name == 'type') {
					# changing the cell type
					if ($value != get_class($this->type) && $tmp_msg = $this->set_type($value)) {
						$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
						$msg = array_merge($msg, $tmp_msg);
					}
					continue;
				}
				if ($tmp_msg = $this->set_attribute($name, $value)) {
					$tmp_msg = 'Table '.($tableid+1).', row '.($rowid+1).', cell '.($cellid+1).": $tmp_msg";
					$msg = array_merge($msg, $tmp_msg);
				}
			}#end foreach

		}#end if
		
		return $msg;

	}#end update()

	 #################################################
	# return all the keywords that are in the bodycopy
	# in the form "%keyword%"
	function get_keywords(&$keywords) {
		if ($this->type) {
			$this->type->get_keywords($keywords);
		}#end if
	}

	 ################################################
	# gets all the anchors from the bodycopy
	function get_anchors(&$anchors) {
		if ($this->type) {
			$this->type->get_anchors($anchors);
		}#end if
	}

}#end Class BodyCopy_Table_Cell

?>
