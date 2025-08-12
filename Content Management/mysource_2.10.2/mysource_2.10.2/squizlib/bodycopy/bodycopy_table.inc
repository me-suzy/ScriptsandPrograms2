<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## Desc: A class that handles tables within the body copy
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table.inc,v $
## $Revision: 2.30.2.1 $
## $Author: tbarrett $
## $Date: 2004/05/18 00:43:04 $
#######################################################################
include_once(dirname(__FILE__).'/bodycopy_table_row.inc');
#---------------------------------------------------------------------#


/** 
* A class that handles user content editing.
* The table contains an array of row (BodyCopy_Table_Row) objects
* which in turn contain an array of cell (BodyCopy_Table_Cell) objects.
*
* @access public
* @package Bodycopy
*/
class BodyCopy_Table extends Object {

	var $attributes = Array();  # an array of attributes for the table tag
	var $rows       = Array();  # an array of table rows (BodyCopy_Table_Row)
	var $default_cell_type = ''; # the default cell type to use when creating new cells
	

	 ##############################
	# Constructor
	function BodyCopy_Table($num_rows=0, $num_cols=0, $default_cell_type='', $attributes=Array()) {

		# just in case :P
		$num_rows = intval($num_rows);
		$num_cols = intval($num_cols);
		if (!$num_rows) $num_rows = 1;
		if (!$num_cols) $num_cols = 1;

		$this->default_cell_type = $default_cell_type;

		for($i = 0; $i < $num_rows; $i++) {
			$this->rows[$i] = new BodyCopy_Table_Row($num_cols, $this->default_cell_type);
		}#end for

		foreach($attributes as $name => $value) {
			$this->set_attribute($name, $value);
		}#end foreach

	}#end constructor

	 #########################################
	# set an attribute for the table tag
	function set_attribute($name='', $value='') {
		$name = strtolower($name);
		if ($name == 'showif_conds' && ($this->attributes['showif'] == '' || $this->attributes['showif'] == 'Do Not Show')) $value='';
		if ($this->attributes[$name] == $value) return '';
		if ($name == 'change_type') return '';
		$this->attributes[$name] = $value;
		return $name." changed to '$value'";
	}

	 ##############################
	# delete this row
	function delete() {

		unset($this->attributes);

		# run through the rows and delete() them
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$this->rows[$i]->delete();
		}#end for
		unset($this->rows);

		$this = new BodyCopy_Table();
		return true;

	}#end delete()
	 
	 #########################################################
	# insert a new table row before or after the passed rowid
	function insert_row($rowid=0, $before=true) {

		$rowid = intval($rowid);

		# if the row is greater than the number of rows we have set it to the last on
		if ($rowid > count($this->rows) - 1) $rowid = count($this->rows) - 1;
		if ($rowid < 0) $rowid = 0;
		$compareid = $rowid;

		# for the moment we will just use the number of columns that the one we are inserting
		# before/after has
		$num_cols = $this->rows[$compareid]->num_cols(false);

		# if they want to insert after the passed rowid what 
		# they really mean is to insert before the next rowid
		if (!$before) $rowid++;

		# move all the rows up one position
		$row_count = count($this->rows);
		for($i = $row_count; $i > $rowid; $i--) {
			$this->rows[$i] = $this->rows[$i - 1];
		}#end for

		if ($rowid <= $compareid) $compareid++;

		# now add the row
		$this->rows[$rowid] = new BodyCopy_Table_Row($num_cols, $this->default_cell_type);

		# now that the cells exist, set their properties to
		# the properties of the cells above/below
		$cell_count = count($this->rows[$compareid]->cells);
		for($i = 0; $i < $cell_count; $i++) {
			$attribs = $this->rows[$compareid]->cells[$i]->attributes;
			if (!empty($attribs)) {
				foreach ($attribs as $name => $val) {
					$this->rows[$rowid]->cells[$i]->set_attribute($name,$val);
				}
			}
			$this->rows[$rowid]->cells[$i]->set_type(get_class($this->rows[$compareid]->cells[$i]->type));
        }#end for

		return 'Row inserted '.(($before) ? 'before' : 'after').' row '.(($before) ? $rowid + 1 : $rowid);

	}#end insert_row()

	 #########################################################
	# delete a the passed table row from the array
	function delete_row($rowid=0) {

		# make sure it exists
		$row_count = count($this->rows);
		if ($this->rows[$rowid] && $row_count > 1) {

			# do any clean up
			$this->rows[$rowid]->delete();

			# move all the rows down one position
			for($i = $rowid; $i < $row_count - 1; $i++) {
				$this->rows[$i] = $this->rows[$i + 1];
			}#end for

			# remove the last element
			array_pop($this->rows);

			return 'Row '.($rowid + 1).' has been deleted';

		} elseif ($row_count == 1) {
		
			return 'Row '.($rowid + 1).' cannot be deleted as it is the only row left';

		}#end if

		return '';

	}#end delete_row()

	 #########################################################
	# re-orders the table rows, takes an array of the structure (new_place => old_place)
	function reorder_rows($row_order=array()) {
		if (empty($row_order)) return;
		# if there ain't no changes, die
		$changes = false;
		foreach($row_order as $new_place => $old_place) $changes |= ($new_place != $old_place);
		if (!$changes) return '';

		# if there is a conflict in the number of rows, die
		if (count($this->rows) != count(array_unique($row_order))) return '';

		$new_array = Array();
		$row_count = count($row_order);
		for($i = 0; $i < $row_count; $i++) {
			$new_array[$i] = $this->rows[$row_order[$i]];
		}
		# now assign the new ordered array
		$this->rows = $new_array;

		return 'Table Row\'s have been reordered';

	}#end reorder_rows()

	 ###################################################################
	# insert a new column in all rows, before or after the passed colid
	function insert_col($colid=0, $before=true) {

		$msg = Array();

		# we don't know how to do, just send it to every row to handle
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			if ($tmp_msg = $this->rows[$i]->insert_cell($colid, $before, $this->default_cell_type)) {
				$msg[] = $tmp_msg.' on Row '.($i + 1);
			}#end if
		}#end for

		return implode("\n", $msg);

	}#end insert_col()


 	 ###################################################################
	# deletes a column from all rows, before or after the passed colid
	function delete_col($colid=0) {

		$msg = Array();

		# we don't know how to do, just send it to every row to handle
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			if ($tmp_msg = $this->rows[$i]->delete_cell($colid)) {
				$msg[] = $tmp_msg.' on Row '.($i + 1);
			}#end if
		}#end for

		return implode("\n", $msg);

	}#end delete_col()

 	 ###################################################################################
	# re-orders the table rows, takes an array of the structure (new_place => old_place)
	function reorder_cols($col_order=array()) {

		$msg = Array();

		# we don't know how to do, just send it to every row to handle
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			if ($tmp_msg = $this->rows[$i]->reorder_cells($col_order)) {
				$msg[] = $tmp_msg.' on Row '.($i + 1);
			}#end if
		}#end for

		return implode("\n", $msg);

	}#end reorder_cols()

	 ########################
	# paints the table 
	function paint(&$keyword_replacements) {
		# check if we have a conditional statement and, if so, only
		# print if this condition evaluates to TRUE or if the condition is
		# has the AllowFalse property - only print if FALSE
		$condition = trim($this->attributes['showif']);
		if ($condition == 'Do Not Show') return;
		if ($condition) {
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
				if ((!$result && !$allowfalse) || ($result && $allowfalse)) {
					# conditional test failed
					return;
				}
			}
		}

		if ($this->rows) {
			if (!$this->attributes['rawhtml']) {
				$align_val = '';
				if ($this->attributes['align']) {
					$align_val = $this->attributes['align'];
					echo '<div align="'.$align_val.'">';
				}
				echo '<table';
				if (!empty($this->attributes)) {
					foreach($this->attributes as $name => $val) {
						if ($val == '') continue;
						if ($name == 'align') continue;
						if ($name == 'showif') continue;
						if ($name == 'rawhtml') continue;
						if (($name == 'bgcolor') && $val) {
							$val = "#" . $val;
						}
						echo ' '.$name.'="'.str_replace('"', '&quot;', $val).'"';
					}#end foreach
				}
				echo '>';
			}

			$row_count = count($this->rows);
			for($i = 0; $i < $row_count; $i++) {
				$this->rows[$i]->set_attribute('rawhtml', $this->attributes['rawhtml']);
				$this->rows[$i]->paint($keyword_replacements);
			}#end for

			if (!$this->attributes['rawhtml']) {
				echo '</table>';
				if ($align_val != '') { echo '</div>'; }
			}
		}

	}#end paint()


	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$this->rows[$i]->remap_link_ids($map);
		}#end for
	}


	#####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			if ($this->rows[$i]->uses_file($fileid)) return true;
		}#end for
		return false;
	}


	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0) {

		?> 
					case <?=$tableid?> :

						switch (rowid) {
							<?
							$row_count = count($this->rows);
							for($i = 0; $i < $row_count; $i++) {
								$this->rows[$i]->print_backend_js_data($this_bodycopy_name, $tableid, $i);
							}#end for
							?>

							default :
								retVal["attributes"] = '<?=var_serialise($this->attributes, true)?>';
								retVal["num_cols"]   = <?=(int) $this->max_cols(false)?>;
								retVal["num_rows"]   = <?=(int) count($this->rows)?>;

						}// end switch rowid

					break;
		<?
	
	}#end print_backend_js_data()


	/**
	* Prints the backend interface for this table
	*
	* @param string		$this_bodycopy_name A unique name (to the template) for this bodycopy
	* @param integer	$tableid			The ID of the table we are printing
	* @access public
	* @returns void
	*/
	function print_backend($this_bodycopy_name='', $tableid=0) {
		global $_BODYCOPY;

		$attribute_list = '';
		$popup_attribute_list = '';
		$bgimage = '';
		if (!empty($this->attributes)) {
			foreach($this->attributes as $name => $val) {
				if ($val == '') continue;
				if (($name == 'bgcolor') && $val) {
					$val = "#" . $val;
				}
				if ($name == 'background') {
					$web = &get_web_system();
					$file = &$web->get_file(substr($val, 5));
					$bgimage = $file->get_href();
					$popup_attribute_list .= ' '.$name.'="'.str_replace('"', '&quot;', $file->filename).'"';
					continue;
				}
				$popup_attribute_list .= ' '.$name.'="'.str_replace('"', '&quot;', $val).'"';
				# if this is the width and the value isn't a percentage, 
				# then add 20 to it for the icons in the left column
				if ($name == 'width' && !strstr($val, '%')) $val = (int) $val + 20;
				$attribute_list .= ' '.$name.'="'.str_replace('"', '&quot;', $val).'"';
			}#end foreach
		}

		$max_cols = $this->max_cols();
		$colspans_in_row = false;

		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$cell_count = count($this->rows[$i]->cells);
			for($x = 0; $x < $cell_count; $x++) {
				if ($this->rows[$i]->cells[$x]->colspan() > 1) {
					$colspans_in_row = true;
					break;
				}
			}
			if ($colspans_in_row) break;
		}

		?> 
		<table<?=$attribute_list?> background="<?=$bgimage?>">
			<tr> 
				<td class="bodycopy-edit" align="center" background="" id="<?=$this_bodycopy_name.'_table_'.$tableid?>" width="4%"><?
					print_bodycopy_icon("javascript: bodycopy_edit_table_properties('$this_bodycopy_name', $tableid);", 'Edit Table '.($tableid + 1).'\'s Properties', (($popup_attribute_list) ? 'Current Properties :<br>'.$popup_attribute_list : ''), 'edit_table_properties');
				?></td>
		<?
			for($i = 0; $i < $max_cols; $i++) {
			?>
				<td class="bodycopy-edit" align="center" background="" valign="middle">
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td align="left">
							<?
							print_bodycopy_icon("javascript: bodycopy_insert_table_col('$this_bodycopy_name', $tableid, $i, true);", 'Insert New Column on Left', '', 'insert_column_left', '', '7', '20');		
							?></td><?
					
							?><td align="center"><?
							print_bodycopy_icon("javascript: bodycopy_delete_table_col('$this_bodycopy_name', $tableid, $i);", 'Delete Column '.($i + 1).' ?', '', 		'delete');
							if ($i == 0) {
								if ($max_cols > 1 && (!$colspans_in_row || count($this->rows) == 1)) {
									print_bodycopy_icon("javascript: bodycopy_edit_table_col_order('$this_bodycopy_name', $tableid);", 'Reorder Table '.($tableid + 1).'\'s Columns ?', '', 'reorder_cols');
								}#end if
							}
							?></td>
							<td align="right"><?
							print_bodycopy_icon("javascript: bodycopy_insert_table_col('$this_bodycopy_name', $tableid, $i, false);", 'Insert New Column on Right', '', 'insert_column_right', '', '7', '20');
							?></td>
						</tr>
					</table>
				</td> 
			<?
			}
		?>
			</tr>
		<?

		$num_rows = count($this->rows);
		for($i = 0; $i < $num_rows; $i++) {
			$this->rows[$i]->print_backend($this_bodycopy_name, $tableid, $i, $num_rows);
		}#end for

		?>
		</table>
		<?

	}#end print_backend()


	 ##########################################################
	# gets the maximum number of columns out of all the rows
	function max_cols($add_colspans=true) {

		$max = 0;
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$num = $this->rows[$i]->num_cols($add_colspans);
			if ($max < $num) $max = $num; 
		}#end for

		return $max;

	}#end max_cols()

	 ###############################################
	# process the input created by print_backend()
	function update($this_bodycopy_name='bodycopy', $tableid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved; 
		global $_BODYCOPY;

		$msg = Array();

		if (!empty($bodycopy_saved[$this_bodycopy_name][$tableid]['attributes'])) {

			foreach($bodycopy_saved[$this_bodycopy_name][$tableid]['attributes'] as $name => $value) {
				if ($tmp_msg = $this->set_attribute($name, $value)) {
					$tmp_msg = 'Table '.($tableid+1).": $tmp_msg";
					$msg = array_merge($msg, $tmp_msg);
				}
			}#end foreach

		}#end if

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['attributes']['change_type']) != '') {
			$row_count = count($this->rows);
			for($r = 0; $r < $row_count; $r++) {
				$cell_count = count($this->rows[$r]->cells);
				for($c = 0; $c < $cell_count; $c++) {
					if ($tmp_msg = $this->rows[$r]->cells[$c]->set_type($bodycopy_saved[$this_bodycopy_name][$tableid]['attributes']['change_type'])) $msg = array_merge($msg, $tmp_msg);
				}
			}
		}


		 ############################################################################
		# only update if we are the table that submitted the form
		if (($bodycopy_data['tableid'] == $tableid && $this_bodycopy_name == $bodycopy_name) || !empty($bodycopy_saved[$this_bodycopy_name][$tableid])) {

			###########################
			# now update the rows
			$row_count = count($this->rows);
			for($i = 0; $i < $row_count; $i++) {
				if ($tmp_msg = $this->rows[$i]->update($this_bodycopy_name, $tableid, $i)) {
					$msg = array_merge($msg, $tmp_msg);
				}
			}#end for

			if ($bodycopy_data['tableid'] == $tableid && $bodycopy_name == $this_bodycopy_name) {
				switch($bodycopy_action) {
					
					case 'insert table row' :
						$msg[] = $this->insert_row($bodycopy_data['rowid'], $bodycopy_data['before']);
					break;

					case 'delete table row' :
						$msg[] = $this->delete_row($bodycopy_data['rowid']);
					break;

					case 'edit table row order' :
						$msg[] = $this->reorder_rows($bodycopy_data['row_order']);
					break;

					case 'insert table column' :
						$msg[] = $this->insert_col($bodycopy_data['colid'], $bodycopy_data['before']);
					break;

					case 'delete table column' :
						$msg[] = $this->delete_col($bodycopy_data['colid']);
					break;

					case 'edit table col order' :
						$msg[] = $this->reorder_cols($bodycopy_data['col_order']);
					break;

				}#end switch
			}

		}#end if

		return $msg;

	}#end update()

	 #################################################
	# return all the keywords that are in the bodycopy
	# in the form "%keyword%"
	function get_keywords(&$keywords) {
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$this->rows[$i]->get_keywords($keywords);
		}#end for
	}

	 ################################################
	# gets all the anchors from the bodycopy
	function get_anchors(&$anchors) {
		$row_count = count($this->rows);
		for($i = 0; $i < $row_count; $i++) {
			$this->rows[$i]->get_anchors($anchors);
		}#end for
	}


}#end Class BodyCopy_Table
?>